<?php

namespace Tests\Http\Web\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public User $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_shows_forgot_password_view()
    {
        $this->get('/forgot-password')->assertSuccessful()->assertViewIs('auth.forgot-password');
    }

    /** @test */
    public function it_sends_notification_to_reset_password()
    {
        $this->post('/forgot-password', ['email' => $this->user->email])
            ->assertSessionHas('status', __('passwords.sent'));

        Notification::assertSentTo($this->user, ResetPassword::class);
    }
}
