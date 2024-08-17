<?php

namespace Tests\Http\Timeslot;

use App\Models\Timeslot;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class GetTimeslotUsersTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    /** @test */
    public function it_returns_user_timeslots_starting_from_today()
    {
        $timeslots = Timeslot::factory()->for($this->user)->count(2)->create();
        $pastTimeslots = Timeslot::factory()->for($this->user)->count(2)->create(['date' => now()->subDay()]);
        $otherTimeslots = Timeslot::factory()->count(3)->create();

        $this->actingAsOAuthUser()
            ->getJson('/api/user/timeslots')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'date',
                        'start_time',
                        'end_time',
                        'shelter',
                    ],
                ],
            ])->assertJsonArray('data.*.id', $timeslots->pluck('id'));
    }
}
