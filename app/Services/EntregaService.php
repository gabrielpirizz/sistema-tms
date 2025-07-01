<?php

namespace App\Services;

use App\Contracts\EntregaRepositoryInterface;
use App\Exceptions\DataSourceException;
use App\Models\Entrega;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;
use Throwable;

class EntregaService
{
    public function __construct(
        private EntregaRepositoryInterface $entregaRepository
    ) {}

    public function buscarEntregasPorCpf(string $cpf): EloquentCollection
    {
        $entregas = $this->entregaRepository->findByCpfFromCache($cpf);
        
        if ($entregas->isNotEmpty()) {
            return $entregas;
        }

        try {
            $this->loadExternalData($cpf);
        } catch (Throwable $e) {
            Log::error('Erro ao carregar dados externos', ['cpf' => $cpf, 'erro' => $e->getMessage()]);
            throw new DataSourceException('Falha ao acessar dados de entregas.', 0, $e);
        }

        return $this->entregaRepository->findByCpfFromCache($cpf);
    }

    public function buscarEntregaPorId(string $id): Entrega
    {
        return $this->entregaRepository->findById($id);
    }

    private function loadExternalData(string $cpf): void
    {
        $dadosEntrega = $this->entregaRepository->loadEntregasFromFile();
        $transportadoras = $this->entregaRepository->loadTransportadorasFromFile();

        $filtered = $this->filterByCpf($dadosEntrega, $cpf);

        if ($filtered->isEmpty()) {
            return;
        }

        $transportadorasMap = collect($transportadoras->data)->keyBy('_id');
        
        $this->entregaRepository->persistEntregas($filtered, $transportadorasMap);
    }

    private function filterByCpf(object $data, string $cpf): \Illuminate\Support\Collection
    {
        return collect($data->data)->filter(
            fn($entrega) => isset($entrega->_destinatario->_cpf) && 
                           $entrega->_destinatario->_cpf === $cpf
        );
    }
}