<?php

namespace Database\Factories;

use App\Models\Rastreamento;
use App\Models\Entrega;
use Illuminate\Database\Eloquent\Factories\Factory;

class RastreamentoFactory extends Factory
{
    protected $model = Rastreamento::class;

    public function definition(): array
    {
        $messages = [
            'ENTREGA CRIADA',
            'EM TRÂNSITO',
            'CHEGOU À FILIAL DA CIDADE',
            'SAIU PARA ENTREGA',
            'ENTREGA REALIZADA'
        ];

        return [
            'entrega_id' => Entrega::factory(),
            'message' => $this->faker->randomElement($messages),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
} 