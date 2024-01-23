<?php

namespace Tests\Policies\AdminDashboard\Quality;

use App\Enums\Permission;
use App\Enums\Role;
use App\Events\ServingAdminDashboard;
use App\Models\Quality;
use App\Models\User;
use Tests\TestCase;

class CreateQualityPolicyTest extends TestCase
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

        $this->assertGate('create', Quality::class)->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_permissions()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('create', Quality::class)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.quality.create.no_permission'))
            ->withCode('no_permission');
    }
}
