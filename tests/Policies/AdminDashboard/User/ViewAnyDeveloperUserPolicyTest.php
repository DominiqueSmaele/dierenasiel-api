<?php

namespace Tests\Policies\AdminDashboard\User;

use App\Enums\Role;
use App\Events\ServingAdminDashboard;
use App\Models\User;
use Tests\TestCase;

class ViewAnyDeveloperUserPolicyTest extends TestCase
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
    public function it_allows_if_user_has_correct_permissions_and_correct_role()
    {
        $this->user->syncRoles([Role::developer]);

        $this->assertGate('viewAnyDeveloper', User::class)->isAllowed();
    }

    /** @test */
    public function it_denies_if_user_has_incorrect_role()
    {
        $this->assertGate('viewAnyDeveloper', User::class)
            ->isDenied()
            ->withMessage(__('policies.admin_dashboard.user.view_any_developer.no_permission'))
            ->withCode('no_permission');
    }
}
