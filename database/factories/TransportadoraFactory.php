<?php

namespace Database\Factories;

use App\Models\Transportadora;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportadoraFactory extends Factory
{
    protected $model = Transportadora::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'cnpj' => $this->faker->numerify('##############'),
            'fantasia' => $this->faker->company(),
        ];
    }
} 