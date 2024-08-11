<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShelterFactory extends Factory
{
    public function definition() : array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->e164PhoneNumber(),
            'facebook' => $this->faker->domainName(),
            'instagram' => $this->faker->domainName(),
            'tiktok' => $this->faker->domainName(),
            'address_id' => Address::factory(),
        ];
    }
}
