<?php

namespace Tests\Http\Web\Shelter\Timeslot;

use App\Http\Livewire\Shelter\Timeslot\UpdateTimeslotSlideOver;
use App\Models\Timeslot;
use App\Policies\AdminDashboard\TimeslotPolicy;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class UpdateTimeslotSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Timeslot $timeslot;

    public Carbon $startTime;

    public Carbon $endTime;

    public function setUp() : void
    {
        parent::setUp();

        $this->timeslot = Timeslot::factory()->create();

        $this->startTime = Carbon::parse($this->faker->time($max = '22:59:59'));
        $this->endTime = $this->startTime->copy()->addHour();
    }

    /** @test */
    public function it_updates_timeslot()
    {
        Livewire::test(UpdateTimeslotSlideOver::class, [$this->timeslot->id])
            ->set('timeslot.start_time', $this->startTime)
            ->set('timeslot.end_time', $this->endTime)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('timeslotUpdated')
            ->assertDispatched('slide-over.close');

        $dbTimeslot = Timeslot::first();

        $this->assertNotNull($dbTimeslot);
        $this->assertSameMinute($this->startTime, $dbTimeslot->start_time);
        $this->assertSameMinute($this->endTime, $dbTimeslot->end_time);
    }

    /** @test */
    public function it_throws_validation_error_if_required_data_is_missing()
    {
        Livewire::test(UpdateTimeslotSlideOver::class, [$this->timeslot->id])
            ->set('timeslot.start_time', null)
            ->set('timeslot.end_time', null)
            ->call('update')
            ->assertHasErrors([
                'timeslot.start_time',
                'timeslot.end_time',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_update_timeslot_allowed_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldAllow('update', $this->timeslot);

        Livewire::test(UpdateTimeslotSlideOver::class, [$this->timeslot->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_update_timeslot_denied_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldDeny('update', $this->timeslot);

        Livewire::test(UpdateTimeslotSlideOver::class, [$this->timeslot->id])->assertForbidden();
    }
}
