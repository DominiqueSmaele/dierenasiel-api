<?php

namespace Tests\Http\Web\Shelter\Volunteer;

use App\Http\Livewire\Shelter\Timeslot\TimeslotsOverviewPage;
use App\Models\Shelter;
use App\Models\Timeslot;
use App\Policies\AdminDashboard\TimeslotPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class TimeslotsOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();
    }

    /** @test */
    public function it_is_accessible_on_volunteers_route()
    {
        $this->get("shelter/{$this->shelter->id}/volunteers")
            ->assertStatus(200)
            ->assertSeeLivewire(TimeslotsOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_timeslots_of_shelter()
    {
        $otherTimeslots = Timeslot::factory()->count(3)->create();
        $timeSlots = Timeslot::factory()->for($this->shelter)->count(2)->create();

        Livewire::test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('timeslots', function ($items) use ($timeSlots) {
                $this->assertEqualsArray($timeSlots->pluck('id'), $items->flatten()->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_timeslots_by_date_and_id()
    {
        $secondTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now()->addDay()]);
        $fourthTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now()->addWeek()]);
        $firstTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now()]);
        $thirdTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now()->addDay()]);

        Livewire::test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('timeslots', function ($items) use ($firstTimeslot, $secondTimeslot, $thirdTimeslot, $fourthTimeslot) {
                $flattenedItems = $items->flatten();

                $this->assertSame($firstTimeslot->id, $flattenedItems[0]->id);
                $this->assertSame($secondTimeslot->id, $flattenedItems[1]->id);
                $this->assertSame($thirdTimeslot->id, $flattenedItems[2]->id);
                $this->assertSame($fourthTimeslot->id, $flattenedItems[3]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_any_timeslot_allowed_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldAllow('viewAny', $this->shelter);

        Livewire::test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_any_timeslot_denied_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldDeny('viewAny', $this->shelter);

        Livewire::test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])->assertForbidden();
    }
}
