<div class="flex w-1/4 min-w-[400px] flex-col justify-between border border-blue-base bg-blue-lightest 4xl:w-1/5">
    <div class="flex items-center justify-between px-4 py-8">
        <div class="flex items-center">
            <span class="mr-2">
                <x-dynamic-component component='icon.clock' class="h-6 w-6 text-blue-base" />
            </span>
            <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.shelter_detail_page_opening_periods_label') }}</h2>
        </div>

        @if ($shelter->openingPeriods->isNotEmpty())
            @can('update', [App\Models\OpeningPeriod::class, $shelter])
                <x-button leading-icon="pencil" variant="secondary" wire:click.prevent="$dispatch('slide-over.open', {component: 'shelter.update-opening-periods-slide-over', arguments: {'shelterId': {{ $shelter->id }}}})">
                    {{ __('web.shelter_detail_opening_periods_update_button') }}
                </x-button>
            @endcan
        @else
            @can('create', [App\Models\OpeningPeriod::class, $shelter])
                <x-button leading-icon="plus" variant="secondary" wire:click.prevent="$dispatch('slide-over.open', {component: 'shelter.create-opening-periods-slide-over', arguments: {'shelterId': {{ $shelter->id }}}})">
                    {{ __('web.shelter_detail_opening_periods_create_button') }}
                </x-button>
            @endcan
        @endif
    </div>

    <div class="flex h-full flex-col justify-end">
        @if ($shelter->openingPeriods->isNotEmpty())
            <div class="mt-4">
                @foreach ($openingPeriods as $index => $openingPeriod)
                    <div class="{{ $index % 2 == 0 ? 'bg-blue-lighter' : '' }} flex justify-between p-4">
                        <div>
                            <p class="text-base leading-5">{{ __('web.shelter_detail_page_opening_periods_day_' . $openingPeriod->day) }}</p>
                        </div>

                        <div>
                            @if ($openingPeriod->open && $openingPeriod->close)
                                <p class="text-base leading-5">{{ $openingPeriod->open->format('H:i') }} - {{ $openingPeriod->close->format('H:i') }}</p>
                            @else
                                <p class="text-base leading-5">{{ __('web.shelter_detail_page_opening_periods_closed') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-empty-state :title="__('web.shelter_detail_opening_periods_empty_state_title')" :description="__('web.shelter_detail_opening_periods_empty_state_description')" />
        @endif
    </div>
</div>
