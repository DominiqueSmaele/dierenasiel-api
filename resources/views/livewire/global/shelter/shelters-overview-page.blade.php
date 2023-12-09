<div class="flex h-full flex-col">
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.shelters_overview_page_title') }}</h2>

        <x-button leading-icon="plus" wire:click="$emit('slide-over.open', 'global.create-shelter-slide-over')">
            {{ __('web.shelters_overview_page_create_button') }}
        </x-button>
    </div>

    @if ($shelters->isNotEmpty())
        <div class="mt-12 flex flex-col gap-4">
            @foreach ($shelters as $shelter)
                <a wire:key="shelter-{{ $shelter->id }}" href="{{ route('shelter.home', $shelter->id) }}" class="flex items-center justify-between border border-blue-base bg-white p-4 shadow-light hover:border-blue-light">
                    <div>
                        <p class="font-highlight-sans text-lg font-semibold leading-5">{{ $shelter->name }}</p>
                        <p class="text-gray-dark">{{ $shelter->email }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{ $shelters->links('pagination.links', ['translationKey' => 'web.shelters_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.shelters_overview_page_empty_state_title')" :description="__('web.shelters_overview_page_empty_state_description')" />
    @endif
</div>
