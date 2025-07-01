<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\EntregaService;
use App\Contracts\EntregaRepositoryInterface;
use App\Models\Entrega;
use App\Models\Transportadora;
use App\Exceptions\DataSourceException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Mockery;

class EntregaServiceTest extends TestCase
{
    private EntregaService $service;
    /** @var EntregaRepositoryInterface&\Mockery\MockInterface */
    private $mockRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockRepository = Mockery::mock(EntregaRepositoryInterface::class);
        $this->service = new EntregaService($this->mockRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_busca_entregas_no_cache()
    {
        $transportadora = Transportadora::factory()->make([
            'id' => 'test-transportadora-id',
            'cnpj' => '12345678000199',
            'fantasia' => 'Transportadora Teste'
        ]);
        
        $entregas = Entrega::factory()->count(2)->make([
            'destinatario_cpf' => '12345678901',
            'transportadora_id' => $transportadora->id
        ]);
        $collection = new EloquentCollection($entregas);

        $this->mockRepository->shouldReceive('findByCpfFromCache')
            ->once()
            ->with('12345678901')
            ->andReturn($collection);

        $resultado = $this->service->buscarEntregasPorCpf('12345678901');

        $this->assertCount(2, $resultado);
        $this->assertEquals('12345678901', $resultado->first()->destinatario_cpf);
    }

    public function test_retorna_vazio_para_cpf_inexistente()
    {
        $emptyCollection = new EloquentCollection();

        $this->mockRepository->shouldReceive('findByCpfFromCache')
            ->twice()
            ->with('12345678902')
            ->andReturn($emptyCollection);

        $this->mockRepository->shouldReceive('loadEntregasFromFile')
            ->once()
            ->andReturn((object)['data' => []]);

        $this->mockRepository->shouldReceive('loadTransportadorasFromFile')
            ->once()
            ->andReturn((object)['data' => []]);

        $resultado = $this->service->buscarEntregasPorCpf('12345678902');

        $this->assertCount(0, $resultado);
    }

    public function test_encontra_entrega_por_id()
    {
        $transportadora = Transportadora::factory()->make([
            'id' => 'test-transportadora-id',
            'cnpj' => '12345678000199',
            'fantasia' => 'Transportadora Teste'
        ]);
        
        $entrega = Entrega::factory()->make([
            'id' => 'test-entrega-id',
            'transportadora_id' => $transportadora->id
        ]);
        $entrega->setRelation('transportadora', $transportadora);

        $this->mockRepository->shouldReceive('findById')
            ->once()
            ->with($entrega->id)
            ->andReturn($entrega);

        $resultado = $this->service->buscarEntregaPorId($entrega->id);

        $this->assertEquals($entrega->id, $resultado->id);
        $this->assertNotNull($resultado->transportadora);
    }

    public function test_lanca_excecao_para_entrega_inexistente()
    {
        $this->mockRepository->shouldReceive('findById')
            ->once()
            ->with('id-inexistente')
            ->andThrow(new DataSourceException('Entrega não encontrada.', 404));

        $this->expectException(DataSourceException::class);
        $this->expectExceptionMessage('Entrega não encontrada.');
        
        $this->service->buscarEntregaPorId('id-inexistente');
    }
} 