<?php

namespace Tests\Http\Web;

use App\Models\User;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public User $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_redirects_to_login_if_no_auth_user()
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    /** @test */
    public function it_redirects_to_login_if_auth_user_has_no_view_admin_dashboard_permission()
    {
        $this->user->syncPermissions([]);

        $this->actingAs($this->user->fresh())->get('/')->assertRedirect(route('login'));
    }
}
