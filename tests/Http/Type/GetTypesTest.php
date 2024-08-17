<?php

namespace Tests\Http\Type;

use App\Models\Type;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class GetTypesTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    /** @test */
    public function it_returns_types()
    {
        $types = Type::factory()->count(4)->create();

        $this->actingAsOAuthUser()
            ->getJson('/api/types')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ],
                ],
            ])->assertJsonArray('data.*.id', $types->pluck('id'));
    }
}
