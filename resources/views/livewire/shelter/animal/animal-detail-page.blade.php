@php
    $qualities = $animal->type->qualities->sortBy('name')->values();
@endphp

<div>
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.animal_detail_page_title', ['name' => $animal->name]) }}</h2>

        <div class="flex gap-4">
            @can('viewAny', [App\Models\Animal::class, $shelter])
                <x-button leading-icon="arrow-left" color="blue" href="{{ route('shelter.animals-overview', $shelter->id) }}" wire:navigate>
                    {{ __('web.animal_detail_page_back_button') }}
                </x-button>
            @endcan

            @can('update', $animal)
                <x-button leading-icon="pencil" wire:click="$dispatch('slide-over.open', {component: 'shelter.update-animal-slide-over', arguments: {'animalId': {{ $animal->id }}}})">
                    {{ __('web.animal_detail_page_edit_button') }}
                </x-button>
            @endcan
        </div>
    </div>

    <div class="mb-10 mt-10 flex w-full flex-col gap-10">
        @php
            $image = $animal?->getMedia('image')->first();
        @endphp

        <div class="flex w-full gap-10 4xl:w-3/4 5xl:w-1/2">
            <div class="w-4/6 4xl:w-3/6">
                @if ($image)
                    <img class="h-full min-h-[700px] w-full object-cover" src="{{ $image->getAvailableFullUrl(['small', 'medium']) }}" />
                @endif
            </div>

            <div class="w-full">
                <div>
                    <p class="mb-5 border-b pb-4 font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ __('web.animal_detail_page_info_title') }}</p>
                    <div class="grid grid-cols-2 place-content-center bg-blue-lighter p-4">
                        <p class="text-base leading-5">{{ __('web.animal_detail_page_name') }}</p>
                        <p class="text-base leading-5">{{ ucfirst($animal->name) }}</p>
                    </div>
                    <div class="grid grid-cols-2 place-content-center bg-blue-lightest p-4">
                        <p class="text-base leading-5">{{ __('web.animal_detail_page_sex') }}</p>
                        <p class="text-base leading-5">{{ __('web.animals_overview_page_sex_' . $animal->sex) }}</p>
                    </div>
                    <div class="grid grid-cols-2 place-content-center bg-blue-lighter p-4">
                        <p class="text-base leading-5">{{ __('web.animal_detail_page_age') }}</p>
                        @if ($animal->birth_date)
                            @php
                                $age = now()->diff($animal->birth_date);
                            @endphp
                            <p class="text-base leading-5">{{ $age->y }} {{ __('web.animal_detail_page_years') }} {{ $age->m }} {{ __('web.animal_detail_page_months') }}</p>
                        @else
                            <p class="text-base leading-5">{{ __('web.animal_detail_page_age_unknown') }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 place-content-center bg-blue-lightest p-4">
                        <p class="text-base leading-5">{{ __('web.animal_detail_page_race') }}</p>
                        <p class="text-base leading-5">{{ $animal->race ? ucfirst($animal->race) : '-' }}</p>
                    </div>

                </div>

                <div>
                    <p class="mb-5 mt-10 border-b pb-4 font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ __('web.animal_detail_page_description_title') }}</p>
                    <div>
                        <p class="bg-blue-lighter p-4 text-base leading-5">{!! nl2br(e($animal->description)) !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full 4xl:w-3/4 5xl:w-1/2">
            <div class="mb-5 mt-10 flex items-center justify-between border-b pb-5">
                <p class="font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ __('web.animal_detail_page_qualities_title') }}</p>

                <x-button leading-icon="pencil" wire:click="$dispatch('slide-over.open', {component: 'shelter.update-animal-qualities-slide-over', arguments: {'animalId': {{ $animal->id }}}}) ">
                    {{ __('web.animal_detail_page_qualities_update_button') }}
                </x-button>
            </div>

            <div class="columns-2">
                @foreach ($qualities as $index => $quality)
                    <div class="{{ $index % 2 == 0 ? 'bg-blue-lighter' : 'bg-blue-lightest' }} flex justify-between p-4">
                        <p class="col-start-1 text-base leading-5">{{ ucfirst($quality->name) }}</p>

                        @if ($animal->qualities)
                            @php
                                $qualityValue = $animal->qualities->find($quality->id)?->pivot->value;
                            @endphp

                            @if ($qualityValue !== null)
                                <span>
                                    <x-dynamic-component :component="$qualityValue ? 'icon.check' : 'icon.x-mark'" class="{{ $qualityValue ? 'text-green-base' : 'text-red-base' }} h-5 w-5" />
                                </span>
                            @else
                                <p class="leading-5">{{ __('web.animal_detail_page_quality_unknown') }}</p>
                            @endif
                        @else
                            <p class="leading-5">{{ __('web.animal_detail_page_quality_unknown') }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
