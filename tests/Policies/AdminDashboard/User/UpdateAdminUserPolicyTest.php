<?php

namespace Tests\Policies\AdminDashboard\User;

use App\Enums\Permission;
use App\Enums\Role;
use App\Enums\ShelterPermission;
use App\Enums\ShelterRole;
use App\Events\ServingAdminDashboard;
use App\Models\Shelter;
use App\Models\User;
use Tests\TestCase;

class UpdateAdminUserPolicyTest extends TestCase
{
    public User $user;

    public User $selectedUser;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->selectedUser = User::factory()->assignShelterRole(ShelterRole::admin, Shelter::factory()->create())->create();

        $this->actingAs($this->user);

        ServingAdminDashboard::dispatch(request());
    }

    /** @test */
    public function it_allows_if_user_has_manage_all_shelters_permission_and_selected_user_has_correct_role()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->assertGate('updateAdmin', $this->selectedUser)->isAllowed();
    }

    /** @test */
    public function it_allows_if_user_has_manage_shelter_permission_for_current_shelter_and_selected_user_has_correct_role()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], $this->selectedUser->shelter);

        $this->assertGate('updateAdmin', $this->selectedUser)->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_manage_shelter_permission_for_other_shelter_and_selected_user_has_correct_role()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], Shelter::factory()->create());

        $this->assertGate('updateAdmin', $this->selectedUser)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.user.update_admin.no_permission'))
            ->withCode('no_permission');
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_permission_but_selected_user_has_correct_role()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('updateAdmin', $this->selectedUser)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.user.update_admin.no_permission'))
            ->withCode('no_permission');
    }

    /** @test */
    public function it_denies_if_user_has_correct_permission_but_selected_user_has_incorrect_role()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);
        $this->selectedUser->removeRoles([ShelterRole::admin], $this->selectedUser->shelter);

        $this->assertGate('updateAdmin', $this->selectedUser)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.user.update_admin.incorrect_role'))
            ->withCode('incorrect_role');
    }
}
