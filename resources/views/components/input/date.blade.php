@props([
    'error' => null,
])

@php
    // Necessary because of livewire/alpine bug https://github.com/livewire/livewire/discussions/4790
    $key = $attributes->wire('model')?->value() ?? $attributes->first('x-model');
@endphp

<div
    {{ $attributes->whereDoesntStartWith('wire:model')->class(['relative']) }}
    x-data="{
        value: @if ($attributes->wire('model')->value()) @entangle($attributes->first('x-model')) @else {{ $attributes->first('x-model') }} @endif
    }"
    x-init="let picker = flatpickr($el, {
        locale: locale = document.querySelector('html').getAttribute('lang') ?? 'en',
        altInput: true,
        defaultDate: value,
        maxDate: 'today',
        wrap: true,
        onChange: (dates, dateStr) => {
            dates = dates.map(date => picker.formatDate(date, {{ json_encode('Y-m-d') }}));
            value = dates[0] ?? null;
        },
        plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: {{ json_encode('Y-m-d') }}, altFormat: 'F Y' })],
    });
    $watch('value', () => picker.setDate(value))"
    x-modelable="value"
    wire:key="{{ $key }}"
    wire:ignore>

    <div class="pointer-events-none absolute inset-y-0 left-0 m-1 p-2">
        <x-icon.calendar class="h-5 w-5 text-gray-dark" />
    </div>

    <input :id="$id('input')" data-input type="text" {{ $attributes->only(['placeholder'])->class(['pl-11 w-full border bg-blue-lightest p-3 text-black placeholder:text-gray-dark placeholder:font-normal caret-black text-sm font-bold font-sans leading-5 focus:ring-0', 'border-blue-lightest focus:border-blue-lightest' => !$error, 'border-red-base focus:border-red-base' => $error]) }}>

    <button type="button" class="absolute inset-y-0 right-0 m-1 p-2" title="clear" data-clear x-show="Array.isArray(value) ? value.filter(Boolean).length > 0 : value !== null">
        <x-icon.x-mark class="h-4 w-4 text-gray-dark hover:text-gray-base" />
    </button>
</div>
