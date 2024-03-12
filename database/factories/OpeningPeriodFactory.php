<?php

namespace Database\Factories;

use App\Models\Shelter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpeningPeriodFactory extends Factory
{
    public function definition() : array
    {
        return [
            'day' => $this->faker->unique()->numberBetween(1, 7),
            'open' => $open = Carbon::parse($this->faker->time()),
            'close' => $open->copy()->addHours(3),
            'shelter_id' => Shelter::factory(),
        ];
    }
}
