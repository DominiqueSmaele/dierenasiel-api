<?php

namespace Database\Factories;

use App\Models\Shelter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeslotFactory extends Factory
{
    public function definition() : array
    {
        return [
            'date' => $this->faker->dateTimeBetween(now(), now()->addWeeks(2))->format('Y-m-d'),
            'start_time' => $start = $this->faker->time($max = '22:59:59'),
            'end_time' => Carbon::createFromFormat('H:i:s', $start)->addHour(),
            'shelter_id' => Shelter::factory(),
        ];
    }
}
