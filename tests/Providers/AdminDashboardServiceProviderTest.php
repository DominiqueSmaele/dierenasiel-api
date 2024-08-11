<?php

namespace Tests\Providers;

use App\Enums\Permission;
use App\Enums\Role;
use App\Enums\ShelterPermission;
use App\Enums\ShelterRole;
use App\Events\ServingAdminDashboard;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AdminDashboardServiceProviderTest extends TestCase
{
    /** @test */
    public function it_grants_access_if_has_permission_to_manage_all_shelters()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $user = User::factory()->create();
        $user->syncRoles([Role::user]);

        $this->assertTrue(Gate::forUser($user)->allows('viewAdminDashboard'));
    }

    /** @test */
    public function it_grants_access_if_belongs_to_shelter_and_has_permission_to_manage_shelter()
    {
        Role::user->syncPermissions([]);
        ShelterRole::admin->syncPermissions([ShelterPermission::manageShelter]);

        $user = User::factory()->create();
        $user->syncRoles([ShelterRole::admin], $user->shelter);

        $this->assertTrue(Gate::forUser($user)->allows('viewAdminDashboard'));
    }

    /** @test */
    public function it_does_not_grant_access_if_does_not_have_permission_to_dashboard()
    {
        $user = User::factory()->create();
        $user->syncRoles([Role::user]);

        $this->assertFalse(Gate::forUser($user)->allows('viewAdminDashboard'));
    }

    /** @test */
    public function it_registers_admin_dashboard_policies()
    {
        ServingAdminDashboard::dispatch(request());

        if (! is_dir(app_path('Policies/AdminDashboard'))) {
            $this->markTestSkipped('No admin dashboard policies exist.');
        }

        collect(app(Filesystem::class)->files(app_path('Policies/AdminDashboard')))
            ->map(fn ($file) => str_replace('.php', '', $file->getFilename()))
            ->each(fn ($classname) => $this->assertContains("App\Policies\AdminDashboard\\{$classname}", Gate::policies()));
    }
}
