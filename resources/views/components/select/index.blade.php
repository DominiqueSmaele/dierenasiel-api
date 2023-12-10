@php
    // Necessary because of livewire/alpine bug https://github.com/livewire/livewire/discussions/4790
    $key = $attributes->wire('model')?->value() ?? $attributes->first('x-model');
@endphp

<select
    :id="$id('input')"
    wire:key="{{ $key }}"
    {{ $attributes->class(['w-full flex items-center border-none bg-blue-lightest p-3 font-sans text-sm font-bold leading-5 text-black focus:ring-0']) }}>

    {{ $slot }}
</select>
