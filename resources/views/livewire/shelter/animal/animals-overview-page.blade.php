<div class="flex h-full flex-col">
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.animals_overview_page_title') }}</h2>

        <x-button leading-icon="plus" wire:click="$dispatch('slide-over.open', {component: 'global.create-animal-slide-over'}) ">
            {{ __('web.animals_overview_page_create_button') }}
        </x-button>
    </div>

    @if ($animals->isNotEmpty())
        <div class="mt-12 grid grid-cols-4 gap-5">
            @foreach ($animals as $animal)
                @php
                    $image = $animal?->getMedia('image')->first();
                @endphp

                <a wire:key="animal-{{ $animal->id }}" href="" class="relative flex flex-col border border-blue-light bg-white">
                    <x-button class="absolute right-1 top-1" variant="tertiary" wire:click.prevent="$dispatch('slide-over.open', {component: 'global.update-animal-slide-over', arguments: {'animalId': {{ $animal->id }}}})">
                        <x-icon.pencil class="h-4 w-4" />
                    </x-button>

                    @if ($image)
                        <img class="h-84" src="{{ $image->getAvailableFullUrl(['medium']) }}" />
                    @endif

                    <div class="flex flex-col p-4">
                        <p class="mt-1 font-highlight-sans text-2xl font-semibold leading-5 text-blue-base">{{ $animal->name }}</p>
                        <p class="mt-2 font-highlight-sans text-lg font-semibold leading-5">{{ $animal?->race }}</p>
                        <p class="mt-3 font-highlight-sans text-base leading-5">{{ $animal->type->name }} - {{ $animal->years }} {{ __('web.animals_overview_page_years') }} {{ $animal->months }} {{ __('web.animals_overview_page_months') }}</p>
                        <p class="mb-3 mt-3 font-highlight-sans text-base leading-5">{{ $animal->description }}</p>
                    </div>

                </a>
            @endforeach
        </div>

        {{ $animals->links('pagination.links', ['translationKey' => 'web.shelters_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.animals_overview_page_empty_state_title')" :description="__('web.animals_overview_page_empty_state_description')" />
    @endif
</div>
