@php
    $countries = \App\Models\Country::all()
        ->sortBy(fn($country) => $country->getName())
        ->values();
@endphp

<div class="flex flex-1 flex-col gap-4">
    <x-input.group :error="$errors->first('shelter.name')">
        <x-label>{{ __('web.shelter_fieldset_name_label') }}</x-label>
        <x-input class="mt-1" wire:model="shelter.name" :placeholder="__('web.shelter_fieldset_name_placeholder')" required autofocus />
    </x-input.group>

    <x-input.group :error="$errors->first('shelter.email')">
        <x-label>{{ __('web.shelter_fieldset_email_label') }}</x-label>
        <x-input class="mt-1" wire:model="shelter.email" :placeholder="__('web.shelter_fieldset_email_placeholder')" required autofocus />
    </x-input.group>

    <x-input.group :error="$errors->first('phone')">
        <x-label>{{ __('web.shelter_fieldset_phone_label') }}</x-label>
        <x-input.phone class="mt-1" wire:model="phone" :placeholder="__('web.shelter_fieldset_phone_placeholder')" required />
    </x-input.group>

    <div class="mt-3">
        <x-input.group :error="$errors->first('address.street')" class="basis-3/5">
            <x-label>{{ __('web.shelter_fieldset_address_street_label') }}</x-label>
            <x-input class="mt-1" wire:model="address.street" :placeholder="__('web.shelter_fieldset_address_street_placeholder')" required />
        </x-input.group>

        <div class="mt-3 flex gap-2">
            <x-input.group :error="$errors->first('address.number')" class="basis-1/2">
                <x-label>{{ __('web.shelter_fieldset_address_number_label') }}</x-label>
                <x-input class="mt-1" wire:model="address.number" :placeholder="__('web.shelter_fieldset_address_number_placeholder')" required />
            </x-input.group>

            <x-input.group :error="$errors->first('address.box_number')" class="basis-1/2">
                <x-label>{{ __('web.shelter_fieldset_address_box_number_label') }}</x-label>
                <x-input class="mt-1" wire:model="address.box_number" :placeholder="__('web.shelter_fieldset_address_box_number_placeholder')" />
            </x-input.group>
        </div>

        <div class="mt-3 flex gap-2">
            <x-input.group :error="$errors->first('address.zipcode')" class="basis-1/2">
                <x-label>{{ __('web.shelter_fieldset_address_zipcode_label') }}</x-label>
                <x-input class="mt-1" wire:model="address.zipcode" :placeholder="__('web.shelter_fieldset_address_zipcode_placeholder')" required />
            </x-input.group>

            <x-input.group :error="$errors->first('address.city')" class="basis-1/2">
                <x-label>{{ __('web.shelter_fieldset_address_city_label') }}</x-label>
                <x-input class="mt-1" wire:model="address.city" :placeholder="__('web.shelter_fieldset_address_city_placeholder')" required />
            </x-input.group>
        </div>

        <x-input.group :error="$errors->first('address.country_id')" class="mt-3">
            <x-label>{{ __('web.shelter_fieldset_address_country_id_label') }}</x-label>

            <div x-data="{ countryId: @entangle('address.country_id') }" x-init="countryId ??= {{ $countries->first()?->id }}">
                <x-select x-model="countryId" class="mt-1" required>
                    @foreach ($countries as $country)
                        <x-select.option :value="$country->id">{{ $country->getName() }}</x-select.option>
                    @endforeach
                </x-select>
            </div>
        </x-input.group>

        @error('address')
            <x-error>{{ $message }}</x-error>
        @enderror
    </div>
</div>
