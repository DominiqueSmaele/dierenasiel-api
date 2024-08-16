<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetSheltersRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'per_page' => [
                'nullable',
                'integer',
                'max:12',
            ],
        ];
    }
}
