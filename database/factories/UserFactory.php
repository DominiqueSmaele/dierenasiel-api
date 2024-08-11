<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Enums\ShelterRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition() : array
    {
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make(str()->password()),
            'locale' => $this->faker->locale(),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'last_active_at' => $this->faker->dateTime(),
            'shelter_id' => null,
        ];
    }

    public function assignRole(Role $role) : UserFactory
    {
        return $this->afterCreating(fn (User $user) => $user->syncRoles([$role]));
    }

    public function assignShelterRole(ShelterRole $role) : UserFactory
    {
        return $this->afterCreating(fn (User $user) => $user->syncRoles([$role], $user->shelter_id));
    }
}
