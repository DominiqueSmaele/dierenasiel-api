<?php

namespace App\Http\Livewire\Global\Quality\Concerns;

use App\Models\Quality;
use App\Models\Type;
use Illuminate\Validation\Rule;

trait ValidatesQuality
{
    public Quality $quality;

    public function mountValidatesQuality() : void
    {
        $this->quality ??= Quality::make();
    }

    protected function rules() : array
    {
        return [
            'quality.name' => [
                'required',
                'string',
                'max:255',
            ],
            'quality.type_id' => [
                'required',
                'integer',
                Rule::exists(Type::class, 'id'),
            ],
        ];
    }
}
