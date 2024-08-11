<?php

namespace Tests\Policies\AdminDashboard\Shelter;

use App\Enums\Permission;
use App\Enums\Role;
use App\Events\ServingAdminDashboard;
use App\Models\Shelter;
use App\Models\User;
use Tests\TestCase;

class ViewAnyShelterPolicyTest extends TestCase
{
    public User $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        ServingAdminDashboard::dispatch(request());
    }

    /** @test */
    public function it_allows_if_user_has_correct_permissions()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->assertGate('viewAny', Shelter::class)->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_permissions()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('viewAny', Shelter::class)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.shelter.view_any.no_permission'))
            ->withCode('no_permission');
    }
}
