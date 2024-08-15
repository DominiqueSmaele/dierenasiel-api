<?php

namespace App\Http\Livewire\User\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesUser
{
    public User $user;

    public function bootedValidatesUser() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $this->user = User::where('email', $this->email)->first();

                if (! Hash::check($this->password, $this->user->password)) {
                    $validator->errors()->add('email', __('auth.failed'));
                }
            });
        });
    }

    protected function rules() : array
    {
        return [
            'email' => [
                'required',
                'email:filter',
                Rule::exists(User::class, 'email'),
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
