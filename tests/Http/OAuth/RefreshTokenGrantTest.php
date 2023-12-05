<?php

namespace Tests\Http\OAuth;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Tests\TestCase;

class RefreshTokenGrantTest extends TestCase
{
    public Client $client;

    public string $refreshToken;

    public function setUp() : void
    {
        parent::setUp();

        $this->client = Client::factory()->asPasswordClient()->create();
        $this->user = User::factory()->create(['password' => $password = Str::random()]);

        $this->refreshToken = $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $this->user->email,
            'password' => $password,
        ])->assertSuccessful()->json('refresh_token');
    }

    /** @test */
    public function it_returns_new_access_and_refresh_token()
    {
        $this
            ->postJson('/oauth/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'refresh_token' => $this->refreshToken,
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ]);
    }
}
