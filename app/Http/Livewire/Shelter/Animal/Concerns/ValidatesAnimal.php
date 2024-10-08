<?php

namespace App\Http\Livewire\Shelter\Animal\Concerns;

use App\Models\Animal;
use App\Models\Type;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait ValidatesAnimal
{
    public Animal $animal;

    public TemporaryUploadedFile | string | null $image = null;

    public bool $withoutImage = false;

    public function mountValidatesAnimal() : void
    {
        $this->animal ??= Animal::make();
        $this->withoutImage = $this->animal->image === null;
    }

    public function bootedValidatesAnimal() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if ($this->withoutImage) {
                    $this->image = null;
                }
            });
        });
    }

    public function updatedImage() : void
    {
        $this->withoutImage = $this->image === null;
    }

    protected function rules() : array
    {
        return [
            'animal.name' => [
                'required',
                'string',
                'max:255',
            ],
            'animal.type_id' => [
                'required',
                'integer',
                Rule::exists(Type::class, 'id'),
            ],
            'animal.sex' => [
                'required',
                'string',
                Rule::in(['m', 'f']),
            ],
            'animal.birth_date' => [
                'nullable',
                'date',
                'before_or_equal:' . now()->format('Y-m-d'),
            ],
            'animal.race' => [
                'nullable',
                'string',
                'max:255',
            ],
            'animal.description' => [
                'required',
                'string',
                'max:2550',
            ],
            'image' => [
                ! $this->animal->exists ? 'required' : 'nullable',
                'image',
                'max:10000',
            ],
        ];
    }
}
