<?php

namespace Tests\Http\CurrentUser;

use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class ShowCurrentUserTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    /** @test */
    public function it_returns_the_authenticated_user_with_firstname_lastname_and_email()
    {
        $this->actingAsOAuthUser()
            ->getJson('/api/user/current')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'firstname',
                    'lastname',
                    'email',
                ],
            ])
            ->assertJsonPath('data.id', $this->user->id);
    }
}
