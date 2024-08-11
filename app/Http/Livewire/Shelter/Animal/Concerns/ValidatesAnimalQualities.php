<?php

namespace App\Http\Livewire\Shelter\Animal\Concerns;

use Illuminate\Validation\Validator;

trait ValidatesAnimalQualities
{
    public array $animalQualities = [];

    public function mountValidatesAnimalQualities() : void
    {
        $this->animal->qualities()->syncWithoutDetaching(
            $this->animal->type->qualities->sortBy('id')->values()->pluck('id')->toArray()
        );

        $this->animalQualities = $this->animal->qualities->sortBy('name')->toArray();
    }

    public function bootedValidatesAnimalQualities() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
            });
        });
    }

    protected function rules() : array
    {
        return [
            'animalQualities.*.pivot.value' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
