<?php

namespace Tests\Http\Web\Shelter\Timeslot;

use App\Http\Livewire\Shelter\Timeslot\CreateTimeslotSlideOver;
use App\Models\Shelter;
use App\Models\Timeslot;
use App\Models\User;
use App\Policies\AdminDashboard\TimeslotPolicy;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class CreateTimeslotSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public Carbon $date;

    public Carbon $startTime;

    public Carbon $endTime;

    public User $volunteer;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->date = Carbon::parse($this->faker->dateTimeBetween(now(), now()->addWeeks(2)));
        $this->startTime = Carbon::parse($this->faker->time($max = '22:59:59'));
        $this->endTime = $this->startTime->copy()->addHour();
        $this->volunteer = User::factory()->create();
    }

    /** @test */
    public function it_creates_timeslot()
    {
        Livewire::test(CreateTimeslotSlideOver::class, [$this->shelter->id, $this->date->format('Y-m-d')])
            ->set('timeslot.start_time', $this->startTime)
            ->set('timeslot.end_time', $this->endTime)
            ->set('timeslot.user_id', $this->volunteer->id)
            ->call('create')
            ->assertHasNoErrors()
            ->assertDispatched('timeslotCreated')
            ->assertDispatched('slide-over.close');

        $dbTimeslot = Timeslot::first();

        $this->assertNotNull($dbTimeslot);
        $this->assertSameMinute($this->date->startOfDay(), Carbon::parse($dbTimeslot->date));
        $this->assertSameMinute($this->startTime, $dbTimeslot->start_time);
        $this->assertSameMinute($this->endTime, $dbTimeslot->end_time);
        $this->assertSame($this->volunteer->id, $dbTimeslot->user->id);
    }

    /** @test */
    public function it_throws_validation_error_if_required_data_is_missing()
    {
        Livewire::test(CreateTimeslotSlideOver::class, [$this->shelter->id, $this->date->format('Y-m-d')])
            ->set('timeslot.start_time', null)
            ->set('timeslot.end_time', null)
            ->set('timeslot.user_id', null)
            ->call('create')
            ->assertHasErrors([
                'timeslot.start_time',
                'timeslot.end_time',
            ])
            ->assertHasNoErrors([
                'timeslot.user_id',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_create_timeslot_allowed_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldAllow('create', $this->shelter);

        Livewire::test(CreateTimeslotSlideOver::class, [$this->shelter->id, $this->date->format('Y-m-d')])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_create_timeslot_denied_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldDeny('create', $this->shelter);

        Livewire::test(CreateTimeslotSlideOver::class, [$this->shelter->id, $this->date->format('Y-m-d')])->assertForbidden();
    }
}
