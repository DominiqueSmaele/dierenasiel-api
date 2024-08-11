<?php

namespace App\Http\Requests;

use App\Models\Values\Point;
use Illuminate\Foundation\Http\FormRequest;

class GetSheltersRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'latitude' => ['required_with:longitude|between:-90,90'],
            'longitude' => ['required_with:latitude|between:-180,180'],
            'q' => ['nullable', 'string'],
            'per_page' => [
                'nullable',
                'integer',
                'max:12',
            ],
        ];
    }

    public function location() : ?Point
    {
        return $this->latitude && $this->longitude ? new Point($this->latitude, $this->longitude, 4326) : null;
    }
}
