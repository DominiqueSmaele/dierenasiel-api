<?php

namespace Tests\Policies\AdminDashboard\Animal;

use App\Enums\Permission;
use App\Enums\Role;
use App\Enums\ShelterPermission;
use App\Events\ServingAdminDashboard;
use App\Models\Animal;
use App\Models\Shelter;
use App\Models\User;
use Tests\TestCase;

class DeleteAnimalPolicyTest extends TestCase
{
    public User $user;

    public Animal $animal;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        $this->animal = Animal::factory()->create();

        ServingAdminDashboard::dispatch(request());
    }

    /** @test */
    public function it_allows_if_user_has_manage_all_shelters_permission()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->assertGate('delete', $this->animal)->isAllowed();
    }

    /** @test */
    public function it_allows_if_user_has_manage_shelter_permission_for_current_shelter()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], $this->animal->shelter);

        $this->assertGate('delete', $this->animal)->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_manage_shelter_permission_for_other_shelter()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], Shelter::factory()->create());

        $this->assertGate('delete', $this->animal)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.animal.delete.no_permission'))
            ->withCode('no_permission');
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_permissions()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('delete', $this->animal)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.animal.delete.no_permission'))
            ->withCode('no_permission');
    }

    /** @test */
    public function it_denies_if_animal_is_deleted()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->animal->delete();

        $this->assertGate('delete', $this->animal)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.animal.delete.deleted'))
            ->withCode('deleted');
    }

    /** @test */
    public function it_denies_if_animal_shelter_is_deleted()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->animal->shelter->delete();

        $this->assertGate('delete', $this->animal)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.animal.delete.shelter_deleted'))
            ->withCode('shelter_deleted');
    }
}
