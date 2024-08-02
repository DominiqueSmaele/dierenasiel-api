@aware(['error'])
@props([
    'error' => null,
    'leftAddon' => null,
    'inlineLeftAddon' => null,
    'inlineLeftIcon' => null,
])

@php
    // Necessary because of livewire/alpine bug https://github.com/livewire/livewire/discussions/4790
    $key = $attributes->wire('model')?->value() ?? $attributes->first('x-model');
@endphp

<div {{ $attributes->only(['class', 'style'])->class(['relative']) }} x-data="{ leftPadding: null, rightPadding: null, inlineLeft: {{ json_encode($inlineLeftAddon !== null) }} }">
    @if ($leftAddon || $inlineLeftAddon || $inlineLeftIcon)
        <div x-ref="leftAddon" class="pointer-events-none absolute inset-y-0 left-0 flex items-center rounded-l py-2 pl-3 pr-3 text-blue-base text-gray-dark">
            <x-dynamic-component :component="'icon.' . $inlineLeftIcon" class="h-4 w-4" />
        </div>
    @endif

    <input
        :id="$id('input')"
        wire:key="{{ $key }}"
        x-init="$nextTick(() => {
            if ($refs.leftAddon) {
                this.resizeObserver = new ResizeObserver(() => {
                    if ($refs.leftAddon) {
                        leftPadding = $refs.leftAddon.offsetWidth + (inlineLeft ? 0 : 12);
                    }
                });
                this.resizeObserver.observe($refs.leftAddon);
            }
        })"
        :style="{ 'padding-left': leftPadding ? `${leftPadding}px` : null, 'padding-right': rightPadding ? `${rightPadding}px` : null }"
        {{ $attributes->except(['class', 'style'])->merge(['type' => 'text'])->class(['w-full border bg-blue-lightest p-3 text-black placeholder:text-gray-dark placeholder:font-normal caret-black text-sm font-bold font-sans leading-5 focus:ring-0 [&::-webkit-date-and-time-value]:text-left [&::-webkit-calendar-picker-indicator]:hidden [&[readonly]]:bg-blue-lightest/50 [&[readonly]]:text-gray-dark', 'border-blue-lightest focus:border-blue-lightest' => !$error, 'border-red-base focus:border-red-base' => $error]) }} />
</div>
