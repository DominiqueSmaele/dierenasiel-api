@props([
    'timeZone' => 'Europe/Brussels',
])

<div class="flex h-full flex-col">
    <div>
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.volunteers_overview_page_title') }}</h2>

        {{ $paginatedCalendar->links('pagination.calendar', ['translationKey' => 'web.calendar_pagination_info']) }}
    </div>

    <div class="mt-4 grid w-full grid-cols-1 gap-y-8 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-7">
        @foreach ($paginatedCalendar as $day)
            <div>
                @php
                    $date = \Carbon\Carbon::parse($day['date'])->locale(app()->getLocale());
                    $datePastNow = $date->gte(now($timeZone)->startOfDay());
                @endphp

                <div class="{{ $date->isSameDay(now($timeZone)) ? 'bg-blue-light' : 'bg-blue-base' }} -ml-px -mt-px border border-gray-base p-4 text-white">
                    <div class="flex flex-col">
                        <p class="">{{ ucfirst($date->minDayName) }}</p>
                        <p class="text-4xl font-bold">{{ $date->format('d') }}</p>
                    </div>
                </div>

                @foreach ($day['timeslots'] as $timeslot)
                    @php
                        $volunteer = $timeslot?->volunteer;
                    @endphp

                    <div
                        @if ($datePastNow) wire:click="$dispatch('slide-over.open', {component: 'shelter.update-timeslot-slide-over', arguments: {'timeslotId': {{ $timeslot->id }}}})" @endif
                        @class([
                            '-ml-px -mt-px flex flex-col border border-gray-base bg-blue-lightest px-4 py-4',
                            'cursor-pointer' => $datePastNow,
                        ])>

                        <div class="mb-1 flex w-full items-center justify-between">
                            <p class="text-sm">{{ $timeslot->start_time->format('H:i') }} - {{ $timeslot->end_time->format('H:i') }}</p>

                            @if ($date->startOfDay()->lt(now($timeZone)->startOfDay()))
                                <p class="rounded-full bg-gray-base px-2 py-0.5 text-sm font-bold text-white">{{ __('web.timeslot_closed_label') }}</p>
                            @elseif ($volunteer)
                                <p class="rounded-full bg-red-base px-2 py-0.5 text-sm font-bold text-white">{{ __('web.timeslot_filled_label') }}</p>
                            @else
                                <p class="rounded-full bg-green-base px-2 py-0.5 text-sm font-bold text-white">{{ __('web.timeslot_open_label') }}</p>
                            @endif
                        </div>

                        @if ($volunteer)
                            <p class="text-sm font-bold text-blue-base">{{ $volunteer->user->firstname }} {{ $volunteer->user->lastname }}</p>
                        @else
                            <p class="text-sm font-bold text-blue-base">-</p>
                        @endif
                    </div>
                @endforeach

                @if ($datePastNow)
                    <div class="-ml-px -mt-px flex h-[50px] items-center justify-between border border-gray-base bg-blue-lighter px-3 py-6">
                        <p>{{ __('web.volunteers_overview_page_create_timeslot') }}</p>

                        <x-button variant="tertiary" color="orange" wire:click="$dispatch('slide-over.open', {component: 'shelter.create-timeslot-slide-over', arguments: {'shelterId': {{ $shelter->id }}, 'dateString': '{{ $date->format('Y-m-d') }}' }}) ">
                            <x-icon.plus class="h-5 w-5" />
                        </x-button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

</div>
