<?php

namespace Tests\Http\Web\Auth;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public User $user;

    public string $password;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => Hash::make($this->password = Str::random())]);
        $this->user->syncRoles([Role::developer]);
    }

    /** @test */
    public function it_shows_login_view()
    {
        $this->get('/login')->assertSuccessful()->assertViewIs('auth.login');
    }

    /** @test */
    public function it_logs_in_if_password_is_correct()
    {
        $this->post('/login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function it_does_not_log_in_if_password_is_incorrect()
    {
        $this->post('/login', [
            'email' => $this->user->email,
            'password' => Str::random(),
        ])->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    /** @test */
    public function it_logs_in_if_user_can_view_admin_dashboard()
    {
        Gate::shouldReceive('forUser')
            ->withArgs(fn ($user) => $user->is($this->user))
            ->atLeast()->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('check')
            ->with('viewAdminDashboard', [])
            ->atLeast()->once()
            ->andReturnTrue();

        $this->post('/login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function it_does_not_log_in_if_user_cannot_view_admin_dashboard()
    {
        Gate::shouldReceive('forUser')
            ->withArgs(fn ($user) => $user->is($this->user))
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('check')
            ->with('viewAdminDashboard', [])
            ->once()
            ->andReturnFalse();

        $this->post('/login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ])->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }
}
