<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateCurrentUserPasswordRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'password' => ['required', 'string', 'max:255', Password::min(8)->mixedCase()->numbers()->uncompromised()],
            'repeat_password' => ['required', 'string', 'same:password', 'max:255'],
        ];
    }
}
