<?php

namespace App\Http\Livewire\Shelter\Admin\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesAdmin
{
    public User $user;

    public ?string $password;

    public ?string $passwordRepeat;

    public function mountValidatesAdmin() : void
    {
        $this->user ??= User::make();
    }

    public function bootedValidatesAdmin() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if (empty($this->password)) {
                    return;
                }

                $this->user->password = Hash::make($this->password);
            });
        });
    }

    protected function rules() : array
    {
        return [
            'user.firstname' => [
                'required',
                'string',
                'max:255',
            ],
            'user.lastname' => [
                'required',
                'string',
                'max:255',
            ],
            'user.email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email')->ignore($this->user),
                'max:255',
            ],
            'password' => [
                $this->user->id ? 'nullable' : 'required',
                'string',
                'min:8',
                'max:255',
                'regex:/[0-9]/',
            ],
            'passwordRepeat' => [
                $this->user->id ? 'nullable' : 'required',
                'string',
                'min:8',
                'max:255',
                'regex:/[0-9]/',
                'same:password',
            ],
        ];
    }
}
