<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class QualityFactory extends Factory
{
    public function definition() : array
    {
        return [
            'name' => $this->faker->name(),
            'type_id' => Type::factory(),
        ];
    }
}
