<?php

namespace Tests\Http\Oauth;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected User $user;

    protected string $password;

    public function setUp() : void
    {
        parent::setUp();

        $this->artisan('passport:install');

        $this->user = User::factory()->create(['password' => $this->password = Str::random()]);
    }

    /** @test */
    public function it_authenticates_user_with_provided_email_and_password()
    {
        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }

    /** @test */
    public function it_does_not_authenticate_when_invalid_credentials_are_given()
    {
        $this->postJson('/api/login', [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->password,
        ])
            ->assertStatus(401)
            ->assertJson(['message' => __('auth.failed')]);
    }

    /** @test */
    public function it_does_not_authenticate_when_email_is_invalid()
    {
        $this->postJson('/api/login', [
            'email' => Str::random(),
            'password' => $this->password,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_authenticate_when_email_is_missing()
    {
        $this->postJson('/api/login', [
            'email' => null,
            'password' => $this->password,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_authenticate_when_password_is_missing()
    {
        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => null,
        ])
            ->assertStatus(422);
    }
}
