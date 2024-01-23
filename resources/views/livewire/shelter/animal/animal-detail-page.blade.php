@php
    $qualities = $animal->type->qualities->sortBy('name')->values();
@endphp

<div>
    <div class="mt-1 flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.animal_detail_page_title', ['name' => $animal->name]) }}</h2>

        @can('update', $animal)
            <x-button leading-icon="pencil" wire:click="$dispatch('slide-over.open', {component: 'shelter.update-animal-slide-over', arguments: {'animalId': {{ $animal->id }}}})">
                {{ __('web.animal_detail_page_edit_button') }}
            </x-button>
        @endcan
    </div>

    <div class="mt-10 flex gap-10">
        @php
            $image = $animal?->getMedia('image')->first();
        @endphp

        <div class="w-2/6 4xl:w-1/4">
            @if ($image)
                <img class="h-full object-cover" src="{{ $image->getAvailableFullUrl(['small', 'medium']) }}" />
            @endif
        </div>

        <div class="w-1/2 4xl:w-2/5">
            <div>
                <p class="mb-5 border-b pb-4 font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ __('web.animal_detail_page_info_title') }}</p>
                <div class="grid grid-cols-2 place-content-center bg-gray-lighter p-4">
                    <p class="text-base font-bold leading-5">{{ __('web.animal_detail_page_name') }}</p>
                    <p class="text-base leading-5">{{ ucfirst($animal->name) }}</p>
                </div>
                <div class="grid grid-cols-2 place-content-center p-4">
                    <p class="text-base font-bold leading-5">{{ __('web.animal_detail_page_sex') }}</p>
                    <p class="text-base leading-5">{{ __('web.animals_overview_page_sex_' . $animal->sex) }}</p>
                </div>
                <div class="grid grid-cols-2 place-content-center bg-gray-lighter p-4">
                    <p class="text-base font-bold leading-5">{{ __('web.animal_detail_page_age') }}</p>
                    @if (isset($animal->years) || isset($animal->months))
                        <p class="text-base leading-5">{{ $animal->years ?? '0' }} {{ __('web.animal_detail_page_years') }} {{ $animal->months ?? '0' }} {{ __('web.animal_detail_page_months') }}</p>
                    @else
                        <p class="text-base leading-5">{{ __('web.animal_detail_page_age_unknown') }}</p>
                    @endif

                </div>
                <div class="grid grid-cols-2 place-content-center p-4">
                    <p class="text-base font-bold leading-5">{{ __('web.animal_detail_page_race') }}</p>
                    <p class="text-base leading-5">{{ $animal->race ? ucfirst($animal->race) : '-' }}</p>
                </div>

            </div>

            <div>
                <p class="mb-5 mt-10 border-b pb-4 font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ __('web.animal_detail_page_description_title') }}</p>
                <div>
                    <p class="bg-gray-lighter p-4 text-base leading-5">{!! nl2br(e($animal->description)) !!}</p>
                </div>
            </div>

            <div>
                <p class="mb-5 mt-10 border-b pb-4 font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ __('web.animal_detail_page_qualities_title') }}</p>

                @foreach ($qualities as $index => $quality)
                    <div class="{{ $index % 2 == 0 ? 'bg-gray-lighter' : '' }} grid grid-cols-2 place-content-center p-4">
                        <p class="text-base font-bold leading-5">{{ ucfirst($quality->name) }}</p>

                        @if ($animal->qualities)
                            <p class="text-base leading-5">{{ __('web.animal_detail_page_quality_' . ($animal->qualities->find($quality->id)->pivot->value ?? 'unknown')) }}</p>
                        @else
                            <p class="text-base leading-5">{{ __('web.animal_detail_page_quality_unknown') }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
