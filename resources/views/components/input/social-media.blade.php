@aware(['error'])
@props([
    'error' => null,
])

@php
    // Necessary because of livewire/alpine bug https://github.com/livewire/livewire/discussions/4790
    $key = $attributes->wire('model')?->value() ?? $attributes->first('x-model');
@endphp

<div {{ $attributes->only(['class', 'style'])->class(['relative']) }}>
    <div x-ref="leftAddon" class="absolute inset-y-px left-px flex items-center border-none bg-blue-lightest py-2 pl-3 pr-3 font-sans text-sm font-bold leading-5 text-black focus:ring-0">
        <p>@</p>
    </div>

    <input
        :id="$id('input')"
        wire:key="{{ $key }}"
        x-data="{ leftPadding: null }"
        x-init="if ($refs.leftAddon) new ResizeObserver(() => leftPadding = $refs.leftAddon.offsetWidth + 12).observe($refs.leftAddon);"
        :style="{ 'padding-left': leftPadding ? `${leftPadding}px` : null }"
        {{ $attributes->except(['class', 'style'])->merge(['type' => 'text'])->class(['w-full border bg-blue-lightest p-3 text-black placeholder:text-gray-dark placeholder:font-normal caret-black text-sm font-bold font-sans leading-5 focus:ring-0 [&::-webkit-date-and-time-value]:text-left [&::-webkit-calendar-picker-indicator]:hidden [&[readonly]]:bg-blue-lightest/50 [&[readonly]]:text-gray-dark', 'border-blue-lightest focus:border-blue-lightest' => !$error, 'border-red-base focus:border-red-base' => $error]) }} />
</div>
