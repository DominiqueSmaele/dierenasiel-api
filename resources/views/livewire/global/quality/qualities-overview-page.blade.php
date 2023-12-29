@php
    $types = \App\Models\Type::all()
        ->sortBy('id')
        ->values();
@endphp

<div class="flex h-full flex-col">
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.qualities_overview_page_title') }}</h2>

        <x-button leading-icon="plus" wire:click="$dispatch('slide-over.open', {component: 'global.create-quality-slide-over'}) ">
            {{ __('web.qualities_overview_page_create_button') }}
        </x-button>
    </div>

    @if (App\Models\Quality::exists() && $types->isNotEmpty())
        <div class="mt-10 flex gap-4">
            <x-button wire:click="$set('filterValue', null)" :variant="$filterValue === null ? 'primary' : 'secondary'" color="blue">
                Alle
            </x-button>

            @foreach ($types as $type)
                <x-button wire:click="$set('filterValue', {{ $type->id }})" wire:key="{{ $type->id }}" :variant="$filterValue === $type->id ? 'primary' : 'secondary'" color="blue">
                    {{ $type->name }}
                </x-button>
            @endforeach
        </div>
    @endif

    @if ($qualities->isNotEmpty())
        <div class="mt-12 grid grid-cols-4 gap-5 2xl:grid-cols-5 4xl:grid-cols-6">
            @foreach ($qualities as $quality)
                <a wire:key="quality-{{ $quality->id }}" class="flex flex-col items-center border border-blue-base bg-white p-4 shadow-light">
                    <p class="font-highlight-sans text-xl font-semibold leading-5">{{ $quality->type->name }}</p>
                    <p class="mt-3 text-gray-dark">{{ ucfirst($quality->name) }}</p>
                </a>
            @endforeach
        </div>

        {{ $qualities->links('pagination.links', ['translationKey' => 'web.qualities_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.qualities_overview_page_empty_state_title')" :description="__('web.qualities_overview_page_empty_state_description')" />
    @endif
</div>
