<?php

namespace Tests\Policies\AdminDashboard\Shelter;

use App\Enums\Permission;
use App\Enums\Role;
use App\Events\ServingAdminDashboard;
use App\Models\Shelter;
use App\Models\User;
use Tests\TestCase;

class DeleteShelterPolicyTest extends TestCase
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

        $this->assertGate('delete', $this->shelter)->isAllowed();
    }

    /** @test */
    public function it_denies_if_shelter_is_deleted()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->shelter->delete();

        $this->assertGate('delete', $this->shelter)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.shelter.delete.deleted'))
            ->withCode('deleted');
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_permissions()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('delete', $this->shelter)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.shelter.delete.no_permission'))
            ->withCode('no_permission');
    }
}
