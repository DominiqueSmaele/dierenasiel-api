<?php

namespace App\Http\Livewire\Shelter\Animal\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;

trait ValidatesAnimalQualities
{
    public Collection $animalQualities;

    public function mountValidatesAnimalQualities() : void
    {
        $this->animal->qualities()->syncWithoutDetaching(
            $this->animal->type->qualities->sortBy('id')->values()->pluck('id')->toArray()
        );

        $this->animalQualities = $this->animal->qualities->sortBy('name')->pluck('pivot');
    }

    public function bootedValidatesAnimalQualities() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }
            });
        });
    }

    protected function rules() : array
    {
        return [
            'animalQualities.*.value' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
