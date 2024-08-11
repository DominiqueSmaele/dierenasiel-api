<?php

namespace Tests\Policies\AdminDashboard\Quality;

use App\Enums\Permission;
use App\Enums\Role;
use App\Events\ServingAdminDashboard;
use App\Models\Quality;
use App\Models\User;
use Tests\TestCase;

class DeleteQualityPolicyTest extends TestCase
{
    public User $user;

    public Quality $quality;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        $this->quality = Quality::factory()->create();

        ServingAdminDashboard::dispatch(request());
    }

    /** @test */
    public function it_allows_if_user_has_manage_all_shelters_permission()
    {
        Role::user->syncPermissions([Permission::manageAllShelters]);

        $this->assertGate('delete', $this->quality)->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_permissions()
    {
        Role::user->syncPermissions([]);

        $this->assertGate('delete', $this->quality)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.quality.delete.no_permission'))
            ->withCode('no_permission');
    }
}
