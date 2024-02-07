<div class="flex h-full flex-col">
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.animals_overview_page_title') }}</h2>

        @can('create', [App\Models\Animal::class, $shelter])
            <x-button leading-icon="plus" wire:click="$dispatch('slide-over.open', {component: 'shelter.create-animal-slide-over', arguments: {'shelterId': {{ $shelter->id }}}}) ">
                {{ __('web.animals_overview_page_create_button') }}
            </x-button>
        @endcan
    </div>

    @if ($animals->isNotEmpty())
        <div class="mt-12 grid grid-cols-3 gap-5 2xl:grid-cols-4 4xl:grid-cols-5">
            @foreach ($animals as $animal)
                @php
                    $image = $animal?->getMedia('image')->first();
                @endphp

                <a wire:key="animal-{{ $animal->id }}" href="{{ route('shelter.animal-detail', $animal->id) }}" wire:navigate class="relative box-border flex flex-col border border-blue-light bg-white">
                    @can('update', $animal)
                        <x-button class="absolute right-0 top-0 h-10 border-0" variant="primary" wire:click.prevent="$dispatch('slide-over.open', {component: 'shelter.update-animal-slide-over', arguments: {'animalId': {{ $animal->id }}}})">
                            <x-icon.pencil class="h-4 w-4" />
                        </x-button>
                    @endcan

                    @if ($image)
                        <img class="h-96 object-cover" src="{{ $image->getAvailableFullUrl(['small', 'medium']) }}" />
                    @endif

                    <div class="flex flex-col break-words p-4">
                        <p class="mt-1 font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ ucfirst($animal->name) }}</p>
                        <p class="mt-2 font-highlight-sans text-lg font-semibold leading-5">{{ $animal->race ? ucfirst($animal->race) : '-' }}</p>
                        <div class="flex gap-1">
                            <p class="mt-3 font-highlight-sans text-base leading-5">{{ __('web.animals_overview_page_sex_' . $animal->sex) }}</p>
                            @if ($animal->years || $animal->months)
                                <p class="mt-3 font-highlight-sans text-base leading-5">- {{ $animal->years ?? '0' }} {{ __('web.animals_overview_page_years') }} {{ $animal->months ?? '0' }} {{ __('web.animals_overview_page_months') }}</p>
                            @endif
                        </div>

                        <p class="mb-3 mt-3 font-highlight-sans text-base leading-5">
                            <span class="line-clamp-3">{!! nl2br(e($animal->description)) !!}</span>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        {{ $animals->links('pagination.links', ['translationKey' => 'web.animals_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.animals_overview_page_empty_state_title')" :description="__('web.animals_overview_page_empty_state_description')" />
    @endif
</div>
