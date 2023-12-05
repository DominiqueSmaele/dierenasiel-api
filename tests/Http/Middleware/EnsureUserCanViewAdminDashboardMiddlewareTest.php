<?php

namespace Tests\Http\Middleware;

use App\Http\Middleware\EnsureUserCanViewAdminDashboard;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EnsureUserCanViewAdminDashboardMiddlewareTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        Route::get('ensure-user-can-view-admin-dashboard', fn () => 'ok')->middleware(EnsureUserCanViewAdminDashboard::class);
    }

    /** @test */
    public function it_does_nothing_if_user_can_view_admin_dashboard()
    {
        Gate::shouldReceive('forUser')
            ->with($user = User::factory()->create())
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('check')
            ->with('viewAdminDashboard', [])
            ->once()
            ->andReturnTrue();

        $this->actingAs($user)
            ->get('ensure-user-can-view-admin-dashboard')
            ->assertSuccessful()
            ->assertSee('ok');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_logs_out_and_redirects_to_login_if_user_cannot_view_admin_dashboard()
    {
        Gate::shouldReceive('forUser')
            ->with($user = User::factory()->create())
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('check')
            ->with('viewAdminDashboard', [])
            ->once()
            ->andReturnFalse();

        $this->actingAs($user)
            ->get('ensure-user-can-view-admin-dashboard')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
