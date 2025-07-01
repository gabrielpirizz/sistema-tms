<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Entrega;
use App\Models\Transportadora;
use App\Models\Rastreamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class EntregaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_exibe_pagina_inicial()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect(route('entregas.index'));
    }

    public function test_exibe_formulario_de_busca()
    {
        $response = $this->get(route('entregas.index'));

        $response->assertStatus(200);
        $response->assertSee('Rastrear Entrega');
        $response->assertSee('Digite o CPF');
    }

    public function test_busca_entregas_por_cpf_valido()
    {
        $transportadora = Transportadora::factory()->create();
        $entrega = Entrega::factory()->create([
            'destinatario_cpf' => '12345678901',
            'transportadora_id' => $transportadora->id,
            'destinatario_nome' => 'João Silva'
        ]);

        $response = $this->post(route('entregas.buscar'), [
            'cpf' => '12345678901'
        ]);

        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertSee($transportadora->fantasia);
    }

    public function test_retorna_erro_para_cpf_invalido()
    {
        $response = $this->post(route('entregas.buscar'), [
            'cpf' => '123'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('cpf');
    }

    public function test_retorna_erro_para_cpf_vazio()
    {
        $response = $this->post(route('entregas.buscar'), [
            'cpf' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('cpf');
    }

    public function test_exibe_detalhes_da_entrega()
    {
        $transportadora = Transportadora::factory()->create([
            'fantasia' => 'Transportadora Teste',
            'cnpj' => '12345678901234'
        ]);
        
        $entrega = Entrega::factory()->create([
            'transportadora_id' => $transportadora->id,
            'destinatario_nome' => 'Maria Silva',
            'volumes' => 3
        ]);

        $rastreamento = Rastreamento::factory()->create([
            'entrega_id' => $entrega->id,
            'message' => 'ENTREGA REALIZADA'
        ]);

        $response = $this->get(route('entregas.detalhar', $entrega->id));

        $response->assertStatus(200);
        $response->assertSee('Maria Silva');
        $response->assertSee('Transportadora Teste');
        $response->assertSee('12345678901234');
        $response->assertSee('3');
        $response->assertSee('ENTREGA REALIZADA');
    }

    public function test_retorna_erro_para_entrega_inexistente()
    {
        $response = $this->get(route('entregas.detalhar', 'id-inexistente'));

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }
    
    public function test_aceita_cpf_com_formatacao()
    {
        $transportadora = Transportadora::factory()->create();
        $entrega = Entrega::factory()->create([
            'destinatario_cpf' => '12345678901',
            'transportadora_id' => $transportadora->id
        ]);

        $response = $this->post(route('entregas.buscar'), [
            'cpf' => '123.456.789-01'
        ]);

        $response->assertStatus(200);
        $response->assertSee($entrega->destinatario_nome);
    }
} 