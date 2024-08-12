@php
    $types = \App\Models\Type::all()->sortBy('id')->values();
@endphp

<div class="flex h-full flex-col">
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.qualities_overview_page_title') }}</h2>

        <x-button leading-icon="plus" wire:click="$dispatch('slide-over.open', {component: 'global.create-quality-slide-over'}) ">
            {{ __('web.qualities_overview_page_create_button') }}
        </x-button>
    </div>

    @if ($types->isNotEmpty())
        <div class="mt-10 flex flex-wrap gap-4">
            <x-button wire:click="resetFilter" :variant="$filterValue === null ? 'primary' : 'secondary'" color="blue">
                {{ __('web.qualities_overview_page_all_types_label') }}
            </x-button>

            @foreach ($types as $type)
                <x-button wire:click="$set('filterValue', {{ $type->id }})" wire:key="{{ $type->id }}" :variant="$filterValue === $type->id ? 'primary' : 'secondary'" color="blue">
                    {{ __('web.' . strtolower($type->name)) }}
                </x-button>
            @endforeach
        </div>
    @endif

    @if ($qualities->isNotEmpty())
        <div class="mt-12 grid auto-rows-fr grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 4xl:grid-cols-6 5xl:grid-cols-10">
            @foreach ($qualities as $quality)
                <a wire:key="quality-{{ $quality->id }}" wire:click="$dispatch('slide-over.open', {component: 'global.update-quality-slide-over', arguments: {'qualityId': {{ $quality->id }}}})" variant="secondary" class="flex cursor-pointer flex-col items-center break-words border border-blue-base bg-white p-4 text-center shadow-light hover:bg-blue-base hover:text-white">
                    <p class="mb-3 font-highlight-sans text-xl font-semibold leading-5">{{ __('web.' . strtolower($quality->type->name)) }}</p>
                    <div class="flex h-full items-center p-1">
                        <p class="text-gray-dark">{{ ucfirst($quality->name) }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{ $qualities->links('pagination.links', ['translationKey' => 'web.qualities_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.qualities_overview_page_empty_state_title')" :description="__('web.qualities_overview_page_empty_state_description')" />
    @endif
</div>
