<?php

namespace App\Http\Livewire\Shelter\Timeslot;

use App\Models\Shelter;
use App\Models\Timeslot;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TimeslotsOverviewPage extends Component
{
    use AuthorizesRequests, WithPagination;

    public Shelter $shelter;

    public Collection $calendar;

    protected $calendarService;

    protected $listeners = [
        'timeslotCreated' => '$refresh',
        'timeslotUpdated' => '$refresh',
        'timeslotDeleted' => '$refresh',
        'timeslotVolunteerDeleted' => '$refresh',
    ];

    public function __construct()
    {
        $this->calendarService = app(CalendarService::class);
    }

    public function mount() : void
    {
        $this->calendar = $this->calendarService->generateCalendar();

        $this->setPage($this->calendarService->defaultPage);
    }

    public function booted() : void
    {
        $this->authorize('viewAny', [Timeslot::class, $this->shelter]);
    }

    public function resetPage() : void
    {
        $this->reset('page');
    }

    public function getTimeslots() : Collection
    {
        return Timeslot::query()
            ->where('shelter_id', $this->shelter->id)
            ->whereBetween('date', [Carbon::parse($this->calendar->first()), Carbon::parse($this->calendar->last())])
            ->with('user')
            ->orderBy('date')
            ->orderBy('start_time')
            ->orderBy('id')
            ->get()
            ->groupBy(function ($timeslot) {
                return Carbon::parse($timeslot->date)->format('Y-m-d');
            });
    }

    public function render() : View
    {
        $timeslots = $this->getTimeslots();
        $paginatedCalendar = $this->calendarService->paginateCalendar($this->calendar, $timeslots);

        return view('livewire.shelter.timeslot.timeslots-overview-page', [
            'timeslots' => $timeslots, // Not used in view, for testing purposes...
            'paginatedCalendar' => $paginatedCalendar,
        ])
            ->layout('layouts.shelter', ['shelter' => $this->shelter]);
    }
}
