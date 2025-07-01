<?php

namespace Database\Factories;

use App\Models\Entrega;
use App\Models\Transportadora;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntregaFactory extends Factory
{
    protected $model = Entrega::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'transportadora_id' => Transportadora::factory(),
            'volumes' => $this->faker->numberBetween(1, 5),
            'remetente_nome' => $this->faker->company(),
            'destinatario_nome' => $this->faker->name(),
            'destinatario_cpf' => $this->faker->numerify('###########'),
            'destinatario_endereco' => $this->faker->streetAddress(),
            'destinatario_cep' => $this->faker->numerify('########'),
            'destinatario_estado' => $this->faker->state(),
        ];
    }
} 