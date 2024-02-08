<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Shelter;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class AnimalFactory extends Factory
{
    public function definition() : array
    {
        return [
            'name' => $this->faker->name(),
            'sex' => $this->faker->randomElement(['mannelijk', 'vrouwelijk']),
            'birth_date' => $this->faker->dateTime(),
            'race' => $this->faker->word(),
            'description' => $this->faker->text(),
            'type_id' => Type::factory(),
            'shelter_id' => Shelter::factory(),
        ];
    }

    public function withImage() : self
    {
        return $this->afterCreating(function (Animal $animal) {
            $animal->addMedia(UploadedFile::fake()->image('image.png'))->toMediaCollection('image');
        });
    }
}
