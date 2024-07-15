<?php

namespace Tests\Http\Web\Shelter\Volunteer;

use App\Http\Livewire\Shelter\Timeslot\TimeslotsOverviewPage;
use App\Models\Shelter;
use App\Models\Timeslot;
use App\Policies\AdminDashboard\TimeslotPolicy;
use App\Services\CalendarService;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class TimeslotsOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public CalendarService $calendarService;

    public int $page;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->calendarService = app(CalendarService::class);

        $this->calendarService->generateCalendar();

        $this->page = $this->calendarService->defaultPage;
    }

    /** @test */
    public function it_is_accessible_on_volunteers_route()
    {
        $this->get("shelter/{$this->shelter->id}/volunteers?page={$this->page}")
            ->assertStatus(200)
            ->assertSeeLivewire(TimeslotsOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_timeslots_of_shelter()
    {
        $otherTimeslots = Timeslot::factory()->count(3)->create();
        $timeSlots = Timeslot::factory()->for($this->shelter)->count(2)->create();

        Livewire::withQueryParams(['page' => $this->page])
            ->test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('timeslots', function ($items) use ($timeSlots) {
                $this->assertEqualsArray($timeSlots->pluck('id'), $items->flatten()->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_timeslots_by_date_and_start_time_and_id()
    {
        $secondTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now(), 'start_time' => now()->addHour()]);
        $thirdTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now()->addDay()]);
        $fifthTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now()->addWeek()]);
        $firstTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now(), 'start_time' => now()]);
        $fourthTimeslot = Timeslot::factory()->for($this->shelter)->create(['date' => now()->addDay()]);

        Livewire::withQueryParams(['page' => $this->page])
            ->test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('timeslots', function ($items) use ($firstTimeslot, $secondTimeslot, $thirdTimeslot, $fourthTimeslot, $fifthTimeslot) {
                $flattenedItems = $items->flatten();

                $this->assertSame($firstTimeslot->id, $flattenedItems[0]->id);
                $this->assertSame($secondTimeslot->id, $flattenedItems[1]->id);
                $this->assertSame($thirdTimeslot->id, $flattenedItems[2]->id);
                $this->assertSame($fourthTimeslot->id, $flattenedItems[3]->id);
                $this->assertSame($fifthTimeslot->id, $flattenedItems[4]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_any_timeslot_allowed_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldAllow('viewAny', $this->shelter);

        Livewire::withQueryParams(['page' => $this->page])
            ->test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_any_timeslot_denied_by_policy()
    {
        $this->partialMockPolicy(TimeslotPolicy::class)->forUser($this->user)->shouldDeny('viewAny', $this->shelter);

        Livewire::withQueryParams(['page' => $this->page])
            ->test(TimeslotsOverviewPage::class, ['shelter' => $this->shelter])->assertForbidden();
    }
}
