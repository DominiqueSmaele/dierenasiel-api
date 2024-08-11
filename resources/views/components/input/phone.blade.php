@aware(['error'])
@props(['defaultCountryCode' => 'BE'])

@php
    $defaultCountryCallingCode = collect(\libphonenumber\CountryCodeToRegionCodeMap::COUNTRY_CODE_TO_REGION_CODE_MAP)
        ->filter(fn($countryCodes) => collect($countryCodes)->contains($defaultCountryCode))
        ->keys()
        ->first();

    // Necessary because of livewire/alpine bug https://github.com/livewire/livewire/discussions/4790
    $key = $attributes->wire('model')?->value() ?? $attributes->first('x-model');
@endphp

<div
    x-data="{
        value: @if ($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else null @endif,
        countryCallingCode: null,
        nationalNumber: null,
    }"
    x-init="const phone = parsePhoneNumber(value ?? '');
    countryCallingCode = phone?.countryCallingCode ?? @js($defaultCountryCallingCode);
    nationalNumber = phone?.nationalNumber ?? null;
    
    $watch('value', () => {
        if (value === null) {
            nationalNumber = null;
        }
    
        const phone = parsePhoneNumber(value ?? '');
    
        if (!phone) {
            return;
        }
    
        countryCallingCode = phone.countryCallingCode;
        nationalNumber = phone.nationalNumber;
    });
    $watch('countryCallingCode', () => value = countryCallingCode && nationalNumber ? '+' + (countryCallingCode ?? '') + (nationalNumber ?? '') : null);
    $watch('nationalNumber', () => value = countryCallingCode && nationalNumber ? '+' + (countryCallingCode ?? '') + (nationalNumber ?? '') : null);"
    x-modelable="value"
    {{ $attributes->only(['class', 'style', 'x-model'])->class(['relative']) }}>

    <div x-model="countryCallingCode" x-ref="leftAddon" class="absolute inset-y-px left-px flex items-center border-none bg-blue-lightest py-2 pl-3 pr-3 font-sans text-sm font-bold leading-5 text-black focus:ring-0">
        <p value="{{ $defaultCountryCallingCode }}">+{{ $defaultCountryCallingCode }} ({{ $defaultCountryCode }})</p>
    </div>

    <input
        :id="$id('input')"
        wire:key="{{ $key }}"
        x-data="{ leftPadding: null }"
        x-init="if ($refs.leftAddon) new ResizeObserver(() => leftPadding = $refs.leftAddon.offsetWidth + 12).observe($refs.leftAddon);"
        :style="{ 'padding-left': leftPadding ? `${leftPadding}px` : null }"
        x-model="nationalNumber"
        type="tel"
        {{ $attributes->except(['class', 'style'])->whereDoesntStartWith('wire:model')->class(['w-full border bg-blue-lightest p-3 text-black placeholder:text-gray-dark placeholder:font-normal caret-black text-sm font-bold font-sans leading-5 focus:ring-0', 'border-blue-lightest focus:border-blue-lightest' => !$error, 'border-red-base focus:border-red-base' => $error]) }} />
</div>
