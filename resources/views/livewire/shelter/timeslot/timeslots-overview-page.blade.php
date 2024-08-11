<div class="flex h-full flex-col">
    <div>
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.volunteers_overview_page_title') }}</h2>

        {{ $paginatedCalendar->links('pagination.calendar', ['translationKey' => 'web.calendar_pagination_info']) }}
    </div>

    <div class="mt-4 grid w-full grid-cols-5 4xl:gap-10">
        @foreach ($paginatedCalendar as $day)
            <div>
                @php
                    $date = \Carbon\Carbon::parse($day['date'])->locale(app()->getLocale());
                @endphp

                <div class="-ml-px -mt-px border border-gray-base bg-blue-base p-4 text-center text-white">
                    <div class="flex flex-col">
                        <p>{{ ucfirst($date->dayName) }}</p>
                        <p>{{ $date->format('d/m') }}</p>
                    </div>
                </div>

                @foreach ($day['timeslots'] as $timeslot)
                    <div class="-ml-px -mt-px flex h-[75px] items-center justify-between border border-gray-base bg-blue-lighter px-3 py-6">
                        @php
                            $volunteer = $timeslot?->volunteer;
                        @endphp

                        <div>
                            @if ($volunteer)
                                <p class="font-bold text-blue-base">{{ $volunteer->user->firstname }} {{ $volunteer->user->lastname }}</p>
                            @else
                                <p class="font-bold text-green-base">OPEN</p>
                            @endif

                            <p>{{ \Carbon\Carbon::parse($timeslot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($timeslot->end_time)->format('H:i') }}</p>
                        </div>

                        @if ($date->gte(now()->startOfDay()))
                            <x-button variant="tertiary" color="blue">
                                <x-icon.pencil class="h-5 w-5" />
                            </x-button>
                        @endif
                    </div>
                @endforeach

                @if ($date->gte(now()->startOfDay()))
                    <div class="-ml-px -mt-px flex h-[50px] items-center justify-between border border-gray-base bg-blue-lighter px-3 py-6">
                        <p>{{ __('web.volunteers_overview_page_create_timeslot') }}</p>

                        <x-button variant="tertiary" color="orange">
                            <x-icon.plus class="h-5 w-5" />
                        </x-button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

</div>
