<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurrentUserRequest extends FormRequest
{
    public function rules() : array
    {
        return [
            'firstname' => ['sometimes', 'required', 'string', 'max:255'],
            'lastname' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email:filter',
                Rule::unique(User::class, 'email')->ignore($this->user()->id), 'max:255',
            ],
        ];
    }

    public function firstname() : ?string
    {
        return $this->firstname ?? $this->user()->firstname;
    }

    public function lastname() : ?string
    {
        return $this->lastname ?? $this->user()->lastname;
    }

    public function email() : ?string
    {
        return $this->email ?? $this->user()->email;
    }
}
