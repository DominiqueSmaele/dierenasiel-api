<?php

namespace Tests\Http\Web\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public User $user;

    public string $token;

    public string $password;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = Password::broker()->createToken($this->user);
        $this->password = Str::random();
    }

    /** @test */
    public function it_shows_reset_password_view()
    {
        $this->get("/reset-password/{$this->token}?email={$this->user->email}")
            ->assertSuccessful()
            ->assertViewIs('auth.reset-password');
    }

    /** @test */
    public function it_resets_password()
    {
        $this->post('/reset-password', [
            'token' => $this->token,
            'email' => $this->user->email,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ])->assertSessionHasNoErrors()->assertSessionHas('status', __('passwords.reset'));

        $dbUser = User::find($this->user->id);

        $this->assertTrue(Hash::check($this->password, $dbUser->password));
    }
}
