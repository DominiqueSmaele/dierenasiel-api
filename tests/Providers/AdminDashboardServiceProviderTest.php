<?php

namespace Tests\Providers;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AdminDashboardServiceProviderTest extends TestCase
{
    /** @test */
    public function it_grants_access_if_has_permission_to_dashboard()
    {
        Role::user->syncPermissions([Permission::viewAdminDashboard]);

        $user = User::factory()->create();
        $user->syncRoles([Role::user]);

        $this->assertTrue(Gate::forUser($user)->allows('viewAdminDashboard'));
    }
}
