<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetShelterAnimalsRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'q' => ['nullable', 'string'],
            'per_page' => [
                'nullable',
                'integer',
                'max:12',
            ],
        ];
    }
}
