<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CalendarService
{
    public function generateCalendar() : Collection
    {
        $now = Carbon::now();
        $startOfCalendar = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $now->copy()->lastOfMonth()->endOfWeek(Carbon::SUNDAY)->addWeeks(2);

        $dates = CarbonPeriod::create($startOfCalendar, $endOfCalendar);

        $calendar = collect();

        foreach ($dates as $date) {
            $calendar->push($date->format('Y-m-d'));
        }

        return $calendar;
    }

    public function paginateCalendar(Collection $calendar, Collection $timeslots) : LengthAwarePaginator
    {
        $perPage = 5;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $items = $calendar->forPage($currentPage, $perPage);

        $paginatedCalendar = new LengthAwarePaginator($items, $calendar->count(), $perPage, $currentPage);

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
