<?php

namespace Tests;

use App\Enums\ShelterRole;
use App\Events\ServingAdminDashboard;
use App\Models\User;

class AuthenticateAsWebShelterUser
{
    public User $user;

    public function setUpWebShelterUser() : void
    {
        $this->user = User::factory()->create();
        $this->user->syncRoles([ShelterRole::admin], $this->user->shelter);

        $this->actingAs($this->user->fresh()->unsetRelations());

        ServingAdminDashboard::dispatch(request());
    }
}
