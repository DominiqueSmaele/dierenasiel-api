<?php

namespace Tests\Policies\AdminDashboard\Timeslot;

use App\Enums\Permission;
use App\Enums\Role;
use App\Enums\ShelterPermission;
use App\Events\ServingAdminDashboard;
use App\Models\Shelter;
use App\Models\Timeslot;
use App\Models\User;
use Tests\TestCase;

class DeleteTimeslotVolunteerPolicyTest extends TestCase
{
    public User $user;

    public Timeslot $timeslot;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        $this->timeslot = Timeslot::factory()->create();

        ServingAdminDashboard::dispatch(request());
    }

    /** @test */
    public function it_allows_if_user_has_manage_all_shelters_permission()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->assertGate('deleteVolunteer', $this->timeslot)->isAllowed();
    }

    /** @test */
    public function it_allows_if_user_has_manage_shelter_permission_for_current_shelter()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], $this->timeslot->shelter);

        $this->assertGate('deleteVolunteer', $this->timeslot)->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_manage_shelter_permission_for_other_shelter()
    {
        Role::user->syncPermissions([]);
        $this->user->syncPermissions([ShelterPermission::manageShelter], Shelter::factory()->create());

        $this->assertGate('deleteVolunteer', $this->timeslot)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.timeslot.delete_volunteer.no_permission'))
            ->withCode('no_permission');
    }

    /** @test */
    public function it_denies_if_shelter_is_deleted()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->timeslot->shelter->delete();

        $this->assertGate('deleteVolunteer', $this->timeslot)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.timeslot.delete_volunteer.shelter_deleted'))
            ->withCode('shelter_deleted');
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_permissions()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('deleteVolunteer', $this->timeslot)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.timeslot.delete_volunteer.no_permission'))
            ->withCode('no_permission');
    }
}
