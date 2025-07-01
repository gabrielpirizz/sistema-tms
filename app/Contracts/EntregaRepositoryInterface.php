<?php

namespace App\Contracts;

use App\Models\Entrega;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;

interface EntregaRepositoryInterface
{
    public function findByCpfFromCache(string $cpf): EloquentCollection;

    public function findById(string $id): Entrega;

    public function loadEntregasFromFile(): object;

    public function loadTransportadorasFromFile(): object;

    public function persistEntregas(SupportCollection $entregas, SupportCollection $transportadoras): void;
} 