<?php

namespace Tests\Models;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_adds_default_global_role_on_creation()
    {
        $user = User::factory()->create();

        $this->assertTrue($user->hasRole(Role::user->value));
    }

    /** @test */
    public function it_adds_locale_on_creation()
    {
        $user = User::factory()->create(['locale' => null]);

        $this->assertNotNull($user->locale);
    }

    /** @test */
    public function it_does_not_overwrite_locale_if_already_exists()
    {
        $user = User::factory()->create(['locale' => $locale = $this->faker->locale()]);

        $this->assertSame($locale, $user->locale);
    }

    /** @test */
    public function it_revokes_access_and_refresh_tokens_if_user_is_being_soft_deleted()
    {
        $user = User::factory()->create();

        $accessToken = Token::create([
            'id' => Str::random(),
            'user_id' => $user->id,
            'client_id' => Client::factory()->create()->id,
            'scopes' => [],
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        $refreshToken = RefreshToken::create([
            'id' => Str::random(),
            'access_token_id' => $accessToken->id,
            'revoked' => false,
            'expires_at' => now()->addMonth(),
        ]);

        $otherAccessToken = Token::create([
            'id' => Str::random(),
            'user_id' => User::factory()->create()->id,
            'client_id' => Client::factory()->create()->id,
            'scopes' => [],
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);
        $otherRefreshToken = RefreshToken::create([
            'id' => Str::random(),
            'access_token_id' => $otherAccessToken->id,
            'revoked' => false,
            'expires_at' => now()->addMonth(),
        ]);

        $user->delete();

        $this->assertTrue(Token::find($accessToken->id)->revoked);
        $this->assertTrue(RefreshToken::find($refreshToken->id)->revoked);

        $this->assertFalse(Token::find($otherAccessToken->id)->revoked);
        $this->assertFalse(RefreshToken::find($otherRefreshToken->id)->revoked);
    }
}
