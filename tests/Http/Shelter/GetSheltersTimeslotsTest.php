<?php

namespace Tests\Http\Shelter;

use App\Models\Timeslot;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class GetSheltersTimeslotsTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    /** @test */
    public function it_returns_shelters_timeslots()
    {
        $timeslots = Timeslot::factory()->count(4)->create(['user_id' => null]);

        $this->actingAsOAuthUser()
            ->getJson('/api/shelters/timeslots')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'facebook',
                        'instagram',
                        'tiktok',
                        'image',
                        'timeslots',
                    ],
                ],
            ])->assertJsonArray('data.*.id', $timeslots->pluck('shelter_id'));
    }
}
