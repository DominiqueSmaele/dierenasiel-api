<?php

namespace Tests\Http\CurrentUser;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class UpdateCurrentUserPasswordTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    protected string $password;

    protected string $repeatPassword;

    public function setUp() : void
    {
        parent::setUp();

        $this->password = str()->password();
        $this->repeatPassword = $this->password;
    }

    /** @test */
    public function it_updates_user_password_with_data()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/user/password', [
                'password' => $this->password,
                'repeat_password' => $this->repeatPassword,
            ])
            ->assertStatus(200);

        $dbUser = User::first();

        $this->assertTrue(Hash::check($this->password, $dbUser->password));
    }

    /** @test */
    public function it_does_not_update_user_password_if_repeat_password_not_the_same()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/user/password', [
                'password' => $this->password,
                'repeat_password' => str()->password(),
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_password_if_password_is_less_than_8_characters()
    {
        $password = str()->password(6);
        $repeatPassword = $password;

        $this->actingAsOAuthUser()
            ->patchJson('/api/user/password', [
                'password' => $password,
                'repeat_password' => $repeatPassword,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_password_if_password_is_missing_a_lowercase_letter()
    {
        $password = strtolower(str()->password());
        $repeatPassword = $password;

        $this->actingAsOAuthUser()
            ->patchJson('/api/user/password', [
                'password' => $password,
                'repeat_password' => $repeatPassword,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_password_if_password_is_missing_an_uppercase_letter()
    {
        $password = strtoupper(str()->password());
        $repeatPassword = $password;

        $this->actingAsOAuthUser()
            ->patchJson('/api/user/password', [
                'password' => $password,
                'repeat_password' => $repeatPassword,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_password_if_password_is_missing_a_number()
    {
        $password = str()->password($numbers = false);
        $repeatPassword = $password;

        $this->actingAsOAuthUser()
            ->patchJson('/api/user/password', [
                'password' => $password,
                'repeat_password' => $repeatPassword,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_update_user_password_if_password_is_compromised()
    {
        $password = 'Testing123';
        $repeatPassword = $password;

        $this->actingAsOAuthUser()
            ->patchJson('/api/user/password', [
                'password' => $password,
                'repeat_password' => $repeatPassword,
            ])
            ->assertStatus(422);
    }
}
