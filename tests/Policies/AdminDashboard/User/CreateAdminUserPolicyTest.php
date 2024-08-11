<?php

namespace Tests\Policies\AdminDashboard\User;

use App\Enums\Permission;
use App\Enums\Role;
use App\Enums\ShelterPermission;
use App\Events\ServingAdminDashboard;
use App\Models\Shelter;
use App\Models\User;
use Tests\TestCase;

class CreateAdminUserPolicyTest extends TestCase
{
    public User $user;

    public Shelter $shelter;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        $this->shelter = Shelter::factory()->create();

        ServingAdminDashboard::dispatch(request());
    }

    /** @test */
    public function it_allows_if_user_has_manage_all_shelters_permission()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->assertGate('createAdmin', [User::class, $this->shelter])->isAllowed();
    }

    /** @test */
    public function it_allows_if_user_has_manage_shelter_permission_for_current_shelter()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], $this->shelter);

        $this->assertGate('createAdmin', [User::class, $this->shelter])->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_manage_shelter_permission_for_other_shelter()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], Shelter::factory()->create());

        $this->assertGate('createAdmin', [User::class, $this->shelter])
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.user.create_admin.no_permission'))
            ->withCode('no_permission');
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_role()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('createAdmin', [User::class, $this->shelter])
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.user.create_admin.no_permission'))
            ->withCode('no_permission');
    }
}
