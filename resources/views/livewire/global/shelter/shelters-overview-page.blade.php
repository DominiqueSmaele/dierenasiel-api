<div class="flex h-full flex-col">
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.shelters_overview_page_title') }}</h2>

        <x-button leading-icon="plus" wire:click="$dispatch('slide-over.open', {component: 'global.create-shelter-slide-over'}) ">
            {{ __('web.shelters_overview_page_create_button') }}
        </x-button>
    </div>

    @if ($shelters->isNotEmpty())
        <div class="mt-12 grid grid-cols-4 gap-4">
            @foreach ($shelters as $shelter)
                @php
                    $image = $shelter?->getMedia('image')->first();
                @endphp

                <a wire:key="shelter-{{ $shelter->id }}" href="{{ route('shelter.home', $shelter->id) }}" class="relative flex flex-col items-center justify-center border border-blue-base bg-white p-4 shadow-light hover:border-blue-light">
                    <x-button class="absolute right-1 top-1" variant="tertiary" wire:click.prevent="$dispatch('slide-over.open', {component: 'global.update-shelter-slide-over', arguments: {'shelterId': {{ $shelter->id }}}})">
                        <x-icon.pencil class="h-4 w-4" />
                    </x-button>

                    <div class="flex flex-col items-center justify-center">
                        @if ($image)
                            <img class="mt-3 h-32" src="{{ $image->getAvailableFullUrl(['small', 'medium']) }}" />
                        @else
                            <img class="mt-3 h-32" src="{{ asset('storage/images/shelter/logo-placeholder.png') }}" />
                        @endif

                        <p class="mt-3 font-highlight-sans text-xl font-semibold leading-5">{{ $shelter->name }}</p>
                    </div>

                </a>
            @endforeach
        </div>

        {{ $shelters->links('pagination.links', ['translationKey' => 'web.shelters_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.shelters_overview_page_empty_state_title')" :description="__('web.shelters_overview_page_empty_state_description')" />
    @endif
</div>
