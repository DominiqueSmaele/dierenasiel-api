<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Values\Point;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition() : array
    {
        return [
            'coordinates' => new Point($this->faker->latitude(), $this->faker->longitude(), 4326),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->randomNumber(),
            'box_number' => $this->faker->randomNumber(),
            'zipcode' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country_id' => Country::factory(),
        ];
    }
}
