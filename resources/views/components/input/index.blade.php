@aware(['error'])
@props([
    'error' => null,
])

@php
    // Necessary because of livewire/alpine bug https://github.com/livewire/livewire/discussions/4790
    $key = $attributes->wire('model')?->value() ?? $attributes->first('x-model');
@endphp

<div {{ $attributes->only(['class', 'style'])->class(['relative']) }}>
    <input
        :id="$id('input')"
        wire:key="{{ $key }}"
        {{ $attributes->except(['class', 'style'])->merge(['type' => 'text'])->class(['w-full border bg-blue-lightest rounded-2xl p-3 text-black placeholder:text-gray-dark placeholder:font-normal caret-black text-sm font-bold font-sans leading-5 focus:ring-0 [&::-webkit-date-and-time-value]:text-left [&::-webkit-calendar-picker-indicator]:hidden [&[readonly]]:bg-blue-lightest/50 [&[readonly]]:text-gray-dark', 'border-blue-lightest focus:border-blue-lightest' => !$error, 'border-red-base focus:border-red-base' => $error]) }} />
</div>
