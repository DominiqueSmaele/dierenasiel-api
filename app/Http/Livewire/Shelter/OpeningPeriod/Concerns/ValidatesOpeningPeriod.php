<?php

namespace App\Http\Livewire\Shelter\OpeningPeriod\Concerns;

use App\Models\OpeningPeriod;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;

trait ValidatesOpeningPeriod
{
    public Collection $openingPeriods;

    public function mountValidatesOpeningPeriod() : void
    {
        if ($this->shelter->openingPeriods->isEmpty()) {
            $this->makeOpeningPeriods();

            return;
        }

        $this->openingPeriods = $this->shelter->openingPeriods->sortBy('day');
    }

    public function bootedValidatesOpeningPeriod() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }
            });
        });
    }

    protected function makeOpeningPeriods() : void
    {
        $this->openingPeriods = collect();

        for ($i = 0; $i <= 6; $i++) {
            $this->openingPeriods->push(OpeningPeriod::make(['day' => $i + 1]));
        }
    }

    protected function rules() : array
    {
        return [
            'openingPeriods.*.day' => [
                'required',
                'integer',
                'between:1,7',
                'distinct',
            ],
            'openingPeriods.*.open' => [
                'nullable',
                'date',
                'required_with:openingPeriods.*.close',
            ],
            'openingPeriods.*.close' => [
                'nullable',
                'date',
                'required_with:openingPeriods.*.open',
                'after:openingPeriods.*.open',
            ],
        ];
    }
}
