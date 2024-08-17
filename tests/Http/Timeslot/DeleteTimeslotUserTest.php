<?php

namespace Tests\Http\Timeslot;

use App\Models\Timeslot;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class DeleteTimeslotUserTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    public Timeslot $timeslot;

    public function setUp() : void
    {
        parent::setUp();

        $this->timeslot = Timeslot::factory()->for($this->user)->create(['date' => now()->addDay()]);
    }

    /** @test */
    public function it_deletes_user_from_timeslot()
    {
        $this->actingAsOAuthUser()
            ->deleteJson('/api/timeslot/user', [
                'id' => $this->timeslot->id,
            ])
            ->assertStatus(204);

        $dbTimeslot = Timeslot::find($this->timeslot->id);

        $this->assertNull($dbTimeslot->user);
    }

    /** @test */
    public function it_does_not_delete_user_from_timeslot_if_user_associated_with_timeslot_does_not_equal_auth_user()
    {
        $timeslot = Timeslot::factory()->create(['date' => now()->addDay()]);

        $this->actingAsOAuthUser()
            ->deleteJson('/api/timeslot/user', [
                'id' => $timeslot->id,
            ])
            ->assertStatus(422)
            ->assertSeeText(__('api.unprocessable'));
    }

    /** @test */
    public function it_does_not_delete_user_from_timeslot_if_timeslot_is_in_the_past_or_today()
    {
        $timeslot = Timeslot::factory()->for($this->user)->create(['date' => now()->startOfDay()]);

        $this->actingAsOAuthUser()
            ->deleteJson('/api/timeslot/user', [
                'id' => $timeslot->id,
            ])
            ->assertStatus(422)
            ->assertSeeText(__('api.unprocessable'));
    }
}
