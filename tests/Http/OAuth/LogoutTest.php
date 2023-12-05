<?php

namespace Tests\Http\OAuth;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Token;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    protected User $user;

    protected Client $client;

    public function setUp() : void
    {
        parent::setUp();

        $this->client = Client::factory()->asPasswordClient()->create();
        $this->user = User::factory()->create(['password' => $password = Str::random()]);

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $this->user->email,
            'password' => $password,
        ]);

        $this->withHeader('Authorization', "Bearer {$response->json('access_token')}");
    }

    /** @test */
    public function it_revokes_access_token()
    {
        $this->postJson('/api/logout')
            ->assertStatus(204);

        $dbToken = Token::where('user_id', $this->user->id)->first();

        $this->assertTrue($dbToken->revoked);
    }
}
