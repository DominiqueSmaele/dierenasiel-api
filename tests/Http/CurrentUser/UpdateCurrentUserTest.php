<?php

namespace Tests\Http\CurrentUser;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class UpdateCurrentUserTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    protected string $firstname;

    protected string $lastname;

    protected string $email;

    public function setUp() : void
    {
        parent::setUp();

        $this->firstname = $this->faker->firstName();
        $this->lastname = $this->faker->lastName();
        $this->email = $this->faker->unique()->safeEmail();
    }

    /** @test */
    public function it_updates_user_with_data()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/user', [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
            ])
            ->assertStatus(200);

        $dbUser = User::first();

        $this->assertSame($this->firstname, $dbUser->firstname);
        $this->assertSame($this->lastname, $dbUser->lastname);
        $this->assertSame($this->email, $dbUser->email);
    }

    /** @test */
    public function it_does_not_update_user_if_email_is_not_unique()
    {
        $user = User::factory()->create();

        $this->actingAsOAuthUser()
            ->patchJson('/api/user', [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $user->email,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_if_email_is_invalid()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/user', [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => Str::random(),
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_if_firstname_is_missing()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/user', [
                'firstname' => null,
                'lastname' => $this->lastname,
                'email' => $this->email,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_if_lastname_is_missing()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/user', [
                'firstname' => $this->firstname,
                'lastname' => null,
                'email' => $this->email,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_if_email_is_missing()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/user', [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => null,
            ])
            ->assertStatus(422);
    }
}
