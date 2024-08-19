<?php

namespace Tests\Http\Timeslot;

use App\Models\Timeslot;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class UpdateTimeslotUsersTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    public Timeslot $timeslot;

    public function setUp() : void
    {
        parent::setUp();

        $this->timeslot = Timeslot::factory()->create(['date' => now()->addDay(), 'user_id' => null]);
    }

    /** @test */
    public function it_updates_user_from_timeslot()
    {
        $this->actingAsOAuthUser()
            ->patchJson('/api/timeslot/user', [
                'id' => $this->timeslot->id,
            ])
            ->assertStatus(200);

        $dbTimeslot = Timeslot::find($this->timeslot->id);

        $this->assertSame($this->user->id, $dbTimeslot->user->id);
    }
}
