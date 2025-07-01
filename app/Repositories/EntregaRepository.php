<?php

namespace App\Repositories;

use App\Contracts\EntregaRepositoryInterface;
use App\Exceptions\DataSourceException;
use App\Models\Entrega;
use App\Models\Transportadora;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntregaRepository implements EntregaRepositoryInterface
{
    public function findByCpfFromCache(string $cpf): EloquentCollection
    {
        return Entrega::where('destinatario_cpf', $cpf)
                     ->with('transportadora', 'rastreamentos')
                     ->get();
    }

    public function findById(string $id): Entrega
    {
        try {
            return Entrega::with('transportadora', 'rastreamentos')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new DataSourceException('Entrega não encontrada.', 404, $e);
        }
    }

    public function loadEntregasFromFile(): object
    {
        return $this->loadJsonData('private/API_LISTAGEM_ENTREGAS.json');
    }

    public function loadTransportadorasFromFile(): object
    {
        return $this->loadJsonData('private/API_LISTAGEM_TRANSPORTADORAS.json');
    }

    public function persistEntregas(SupportCollection $entregas, SupportCollection $transportadoras): void
    {
        DB::transaction(function () use ($entregas, $transportadoras) {
            foreach ($entregas as $dadosEntrega) {
                if (!isset($transportadoras[$dadosEntrega->_id_transportadora])) {
                    continue;
                }

                $dadosTransportadora = $transportadoras[$dadosEntrega->_id_transportadora];
                
                $this->saveTransportadora($dadosTransportadora);
                $entrega = $this->saveEntrega($dadosEntrega, $dadosTransportadora);
                $this->saveRastreamentos($entrega, $dadosEntrega);
            }
        });
    }

    private function loadJsonData(string $path): object
    {
        if (!Storage::disk('local')->exists($path)) {
            throw new DataSourceException("Arquivo não encontrado em: {$path}");
        }

        $json = Storage::disk('local')->get($path);
        $data = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DataSourceException("JSON inválido: " . json_last_error_msg());
        }

        if (!isset($data->data)) {
            throw new DataSourceException("Estrutura de dados inválida no arquivo JSON.");
        }

        return $data;
    }

    private function saveTransportadora($dadosTransportadora): void
    {
        Transportadora::firstOrCreate(
            ['id' => $dadosTransportadora->_id],
            [
                'cnpj' => $dadosTransportadora->_cnpj, 
                'fantasia' => $dadosTransportadora->_fantasia
            ]
        );
    }

    private function saveEntrega($dadosEntrega, $dadosTransportadora): Entrega
    {
        return Entrega::create([
            'id' => $dadosEntrega->_id,
            'transportadora_id' => $dadosTransportadora->_id,
            'volumes' => $dadosEntrega->_volumes,
            'remetente_nome' => $dadosEntrega->_remetente->_nome ?? null,
            'destinatario_nome' => $dadosEntrega->_destinatario->_nome ?? null,
            'destinatario_cpf' => $dadosEntrega->_destinatario->_cpf ?? null,
            'destinatario_endereco' => $dadosEntrega->_destinatario->_endereco ?? null,
            'destinatario_cep' => $dadosEntrega->_destinatario->_cep ?? null,
            'destinatario_estado' => $dadosEntrega->_destinatario->_estado ?? null,
        ]);
    }

    private function saveRastreamentos(Entrega $entrega, $dadosEntrega): void
    {
        if (empty($dadosEntrega->_rastreamento)) {
            return;
        }

        foreach ($dadosEntrega->_rastreamento as $rastreamento) {
            $entrega->rastreamentos()->create([
                'message' => $rastreamento->message,
                'date' => $rastreamento->date,
            ]);
        }
    }
} 