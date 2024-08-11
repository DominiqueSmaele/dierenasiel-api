<?php

namespace Tests\Http\Web\Shelter\Timeslot;

use App\Http\Livewire\Shelter\Timeslot\DeleteTimeslotModal;
use App\Models\Timeslot;
use App\Policies\AdminDashboard\TimeslotPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class DeleteTimeslotModalTest extends TestCase
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
        Livewire::test(DeleteTimeslotModal::class, [$this->timeslot->id])
            ->call('delete')
            ->assertDispatched('timeslotDeleted')
            ->assertDispatched('modal.close');

        $this->assertNull(Timeslot::find($this->timeslot->id));
    }

    /** @test */
    public function it_returns_success_response_if_delete_timeslot_allowed_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldAllow('delete', $this->timeslot);

        Livewire::test(DeleteTimeslotModal::class, [$this->timeslot->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_delete_timeslot_denied_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldDeny('delete', $this->timeslot);

        Livewire::test(DeleteTimeslotModal::class, [$this->timeslot->id])->assertForbidden();
    }
}
