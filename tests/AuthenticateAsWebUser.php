<?php

namespace Tests;

use App\Enums\Role;
use App\Events\ServingAdminDashboard;
use App\Models\User;

trait AuthenticateAsWebUser
{
    public User $user;

    public function setUpWebAdminUser() : void
    {
        $this->user = User::factory()->create(['shelter_id' => null]);
        $this->user->syncRoles([Role::developer]);

        $this->actingAs($this->user->fresh()->unsetRelations());

        ServingAdminDashboard::dispatch(request());
    }
}
