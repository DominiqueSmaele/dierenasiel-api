<?php

namespace Tests;

use App\Models\User;
use Laravel\Passport\Passport;

trait AuthenticateAsOAuthUser
{
    public User $user;

    public function setUpOAuthUser() : void
    {
        $this->user = User::factory()->create();
    }

    public function actingAsOAuthUser() : self
    {
        Passport::actingAs($this->user->fresh());

        return $this;
    }
}
