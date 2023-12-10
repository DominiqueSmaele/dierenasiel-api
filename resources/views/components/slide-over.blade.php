@props(['title'])

@php
    $tag = $attributes->wire('submit')->value() ? 'form' : 'div';
@endphp

<{{ $tag }} {{ $attributes->except('wire:close')->class(['flex flex-1 flex-col bg-blue-base overflow-hidden']) }}>
    <div class="flex items-center justify-center px-4 pb-8 pt-4">
        <div class="flex-1">
            @if ($attributes->has('x-on:close'))
                <x-button type="button" variant="tertiary" color="gray" @click="{{ $attributes->get('x-on:close') }}">
                    <x-icon.arrow-left class="h-5 w-5" />
                </x-button>
            @else
                <x-button type="button" variant="tertiary" color="gray" wire:click="{{ $attributes->get('wire:close', '$dispatch(\'slide-over.close\')') }}">
                    <x-icon.arrow-left class="h-5 w-5" />
                </x-button>
            @endif
        </div>

        <h3 class="mr-auto font-highlight-sans text-2xl font-semibold leading-7 text-gray-light">{{ $title }}</h3>

        <div class="flex flex-1">
            <div class="ml-auto">
                {{ $action ?? '' }}
            </div>
        </div>
    </div>

    <div class="flex flex-1 flex-col overflow-hidden bg-white">
        <div class="flex flex-1 flex-col overflow-auto p-4 pt-10">
            {{ $slot }}
        </div>
    </div>

    </{{ $tag }}>
