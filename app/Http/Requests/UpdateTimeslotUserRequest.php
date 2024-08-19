<?php

namespace App\Http\Requests;

use App\Models\Timeslot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTimeslotUserRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'id' => ['required', 'integer', Rule::exists(Timeslot::class, 'id')],
        ];
    }
}
