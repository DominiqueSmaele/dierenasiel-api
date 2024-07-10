@props([
    'error' => null,
    'noCalendar' => false,
    'enableTime' => false,
    'monthOnly' => false,
    'timeZone' => 'Etc/UTC',
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
        noCalendar: @js($noCalendar),
        enableTime: @js($enableTime),
        locale: locale = document.querySelector('html').getAttribute('lang') ?? 'en',
        altInput: true,
        altFormat: {{ $noCalendar && $enableTime ? json_encode('HH:mm') : json_encode('DD-MM-YYYY H:i:s') }},
        dateFormat: {{ $dateFormat = $enableTime ? json_encode('YYYY-MM-DD\\\\THH:mm:ssZ') : json_encode('YYYY-MM-DD') }},
        defaultDate: value,
        time_24hr: true,
        {{ $monthOnly ? 'maxDate: new Date(),' : '' }}
        wrap: true,
        parseDate(dateString, format) {
            let timezonedDate = new moment.tz(dateString, format, @js($timeZone));
    
            return new Date(
                timezonedDate.year(),
                timezonedDate.month(),
                timezonedDate.date(),
                timezonedDate.hour(),
                timezonedDate.minute(),
                timezonedDate.second()
            );
        },
        formatDate(date, format) {
            return moment.tz([
                date.getFullYear(),
                date.getMonth(),
                date.getDate(),
                date.getHours(),
                date.getMinutes(),
                date.getSeconds()
            ], @js($timeZone)).locale(locale).format(format);
        },
        onChange: (dates, dateStr) => {
            dates = dates.map(date => picker.formatDate(date, {{ $enableTime ? json_encode('YYYY-MM-DD\\\\THH:mm:ssZ') : json_encode('YYYY-MM-DD') }}));
            value = dates[0] ?? null;
        },
        plugins: [
            @if($monthOnly)
            new monthSelectPlugin({ shorthand: true, dateFormat: {{ json_encode('YYYY-MM-DD') }}, altFormat: 'MMMM YYYY' })
            @endif
        ],
    });
    $watch('value', () => picker.setDate(value))"
    x-modelable="value"
    wire:key="{{ $key }}"
    wire:ignore>

    <div class="pointer-events-none absolute inset-y-0 left-0 m-1 p-2">
        @if ($noCalendar && $enableTime)
            <x-icon.clock class="h-5 w-5 text-gray-dark" />
        @else
            <x-icon.calendar class="h-5 w-5 text-gray-dark" />
        @endif
    </div>

    <input :id="$id('input')" data-input type="text" {{ $attributes->only(['placeholder'])->class(['pl-11 w-full border bg-blue-lightest p-3 text-black placeholder:text-gray-dark placeholder:font-normal caret-black text-sm font-bold font-sans leading-5 focus:ring-0', 'border-blue-lightest focus:border-blue-lightest' => !$error, 'border-red-base focus:border-red-base' => $error]) }}>

    <button type="button" class="absolute inset-y-0 right-0 m-1 p-2" title="clear" data-clear x-show="Array.isArray(value) ? value.filter(Boolean).length > 0 : value !== null">
        <x-icon.x-mark class="h-4 w-4 text-gray-dark hover:text-gray-base" />
    </button>
</div>
