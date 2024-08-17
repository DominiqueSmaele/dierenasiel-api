<?php

namespace Tests\Http\Web\Shelter\Timeslot;

use App\Http\Livewire\Shelter\Timeslot\DeleteTimeslotVolunteerModal;
use App\Models\Timeslot;
use App\Policies\AdminDashboard\TimeslotPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class DeleteTimeslotVolunteerModalTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Timeslot $timeslot;

    public function setUp() : void
    {
        parent::setUp();

        $this->timeslot = Timeslot::factory()->create();
    }

    /** @test */
    public function it_deletes_timeslot()
    {
        Livewire::test(DeleteTimeslotVolunteerModal::class, [$this->timeslot->id])
            ->call('delete')
            ->assertDispatched('timeslotVolunteerDeleted')
            ->assertDispatched('modal.close');

        $dbTimeslot = Timeslot::find($this->timeslot->id);

        $this->assertNull($dbTimeslot->user_id);
    }

    /** @test */
    public function it_returns_success_response_if_delete_timeslot_allowed_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldAllow('deleteVolunteer', $this->timeslot);

        Livewire::test(DeleteTimeslotVolunteerModal::class, [$this->timeslot->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_delete_timeslot_denied_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldDeny('deleteVolunteer', $this->timeslot);

        Livewire::test(DeleteTimeslotVolunteerModal::class, [$this->timeslot->id])->assertForbidden();
    }
}
