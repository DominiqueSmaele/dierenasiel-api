<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CalendarService
{
    public int $perPage = 7;

    public int $defaultPage;

    public string $timeZone = 'Europe/Brussels';

    public function generateCalendar() : Collection
    {
        $now = Carbon::now($this->timeZone);
        $startOfCalendar = $now->copy()->firstOfMonth()->startOfWeek(Carbon::MONDAY)->subWeeks(2);
        $endOfCalendar = $now->copy()->lastOfMonth()->endOfWeek(Carbon::SUNDAY)->addWeeks(2);

        $dates = CarbonPeriod::create($startOfCalendar, $endOfCalendar);

        $calendar = collect();

        foreach ($dates as $date) {
            $calendar->push($date->format('Y-m-d'));
        }

        $this->findDefaultPage($calendar);

        return $calendar;
    }

    public function findDefaultPage(Collection $calendar) : void
    {
        $currentDateIndex = $calendar->search(Carbon::now($this->timeZone)->format('Y-m-d'));
        $this->defaultPage = $currentDateIndex !== false ? intdiv($currentDateIndex, $this->perPage) + 1 : 1;
    }

    public function paginateCalendar(Collection $calendar, Collection $timeslots) : LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $items = $calendar->forPage($currentPage, $this->perPage);

        $paginatedCalendar = new LengthAwarePaginator($items, $calendar->count(), $this->perPage, $currentPage);

        $this->transformCalendar($paginatedCalendar, $timeslots);

        return $paginatedCalendar;
    }

    public function transformCalendar(LengthAwarePaginator $calendar, Collection $timeslots) : void
    {
        $calendar->getCollection()->transform(function ($date) use ($timeslots) {
            return [
                'date' => $date,
                'timeslots' => $timeslots->get($date, collect()),
            ];
        });
    }
}
