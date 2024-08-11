@props(['shelter', 'image', 'withoutImage'])

@php
    $countries = \App\Models\Country::all()->sortBy(fn($country) => $country->getName())->values();
@endphp

<div x-data="{ activeTab: @entangle('activeTab') }" class="flex flex-col">
    <div class="flex flex-wrap gap-4">
        @foreach (['general', 'address', 'socials'] as $category)
            <x-button wire:click.prevent="$set('activeTab', '{{ $category }}')" :variant="$this->activeTab === $category ? 'primary' : 'secondary'" color="blue">{{ __('web.shelter_fieldset_' . $category . '_info') }}</x-button>
        @endforeach
    </div>

    <div x-show="activeTab === 'general'">
        <div class="mt-8 flex flex-col gap-4">
            <x-input.group :error="$errors->first('image')">
                <div class="flex items-center justify-between gap-4">
                    <x-label>{{ __('web.shelter_fieldset_image_label') }}</x-label>

                    <div class="flex items-center justify-end gap-2 text-gray-dark">
                        @if ($shelter->exists && (!$withoutImage || $image !== null))
                            <x-button type="button" @click="document.querySelector('#shelter-image').click()" variant="tertiary" class="!p-0">{{ __('web.shelter_fieldset_image_change_button') }}</x-button>
                            <p>/</p>
                        @endif
                        @if (($shelter->exists && !$withoutImage) || $image !== null)
                            <x-button type="button" @click="$wire.set('image', null); $wire.set('withoutImage', true);" variant="tertiary" class="!p-0">{{ __('web.shelter_fieldset_image_remove_button') }}</x-button>
                        @endif
                    </div>
                </div>
                <div x-data="{ dragging: false }" :class="dragging ? 'bg-blue-light/10' : 'bg-blue-lightest'" class="relative mt-1 flex h-52 flex-none cursor-pointer flex-col items-center justify-center gap-2 self-stretch hover:bg-blue-light/10">
                    <input
                        type="file"
                        accept="image/jpg,image/jpeg,image/png"
                        wire:model="image"
                        @dragover="dragging = true"
                        @dragleave="dragging = false"
                        @drop="dragging = false"
                        id="shelter-image"
                        class="cursor-point absolute inset-0 z-50 h-full w-full text-transparent opacity-0"
                        autofocus />

                    @if (($shelter->exists && !$withoutImage) || ($image && is_object($image)))
                        <div>
                            <img class="h-36" src="{{ $image && is_object($image) ? $image?->temporaryUrl() : $shelter->image?->getAvailableFullUrl(['small', 'medium']) }}" />
                        </div>
                    @else
                        <x-icon.loading wire:loading wire:target="image" class="h-4 w-4 animate-spin text-gray-base" />
                        <x-icon.image wire:loading.remove wire:target="image" class="h-6 w-6 text-gray-base" />
                        <p class="text-sm text-gray-dark">{{ __('web.shelter_fieldset_image_button') }}</p>
                    @endif
                </div>
            </x-input.group>

            <x-input.group :error="$errors->first('shelter.name')" class="mt-1">
                <x-label>{{ __('web.shelter_fieldset_name_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
                <x-input class="mt-1" wire:model="shelter.name" :placeholder="__('web.shelter_fieldset_name_placeholder')" required />
            </x-input.group>

            <x-input.group :error="$errors->first('shelter.email')" class="mt-1">
                <x-label>{{ __('web.shelter_fieldset_email_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
                <x-input class="mt-1" wire:model="shelter.email" :placeholder="__('web.shelter_fieldset_email_placeholder')" required />
            </x-input.group>

            <x-input.group :error="$errors->first('phone')" class="mt-1">
                <x-label>{{ __('web.shelter_fieldset_phone_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
                <x-input.phone class="mt-1" wire:model="phone" :placeholder="__('web.shelter_fieldset_phone_placeholder')" required />
            </x-input.group>
        </div>
    </div>

    <div x-show="activeTab === 'address'">
        <div class="mt-8 flex flex-col gap-4">
            <x-input.group :error="$errors->first('address.street')" class="basis-3/5">
                <x-label>{{ __('web.shelter_fieldset_address_street_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
                <x-input class="mt-1" wire:model="address.street" :placeholder="__('web.shelter_fieldset_address_street_placeholder')" required />
            </x-input.group>

            <div class="mt-1 flex gap-2">
                <x-input.group :error="$errors->first('address.number')" class="basis-1/2">
                    <x-label>{{ __('web.shelter_fieldset_address_number_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
                    <x-input class="mt-1" wire:model="address.number" :placeholder="__('web.shelter_fieldset_address_number_placeholder')" required />
                </x-input.group>

                <x-input.group :error="$errors->first('address.box_number')" class="basis-1/2">
                    <x-label>{{ __('web.shelter_fieldset_address_box_number_label') }}</x-label>
                    <x-input class="mt-1" wire:model="address.box_number" :placeholder="__('web.shelter_fieldset_address_box_number_placeholder')" />
                </x-input.group>
            </div>

            <div class="mt-1 flex gap-2">
                <x-input.group :error="$errors->first('address.zipcode')" class="basis-1/2">
                    <x-label>{{ __('web.shelter_fieldset_address_zipcode_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
                    <x-input class="mt-1" wire:model="address.zipcode" :placeholder="__('web.shelter_fieldset_address_zipcode_placeholder')" required />
                </x-input.group>

                <x-input.group :error="$errors->first('address.city')" class="basis-1/2">
                    <x-label>{{ __('web.shelter_fieldset_address_city_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
                    <x-input class="mt-1" wire:model="address.city" :placeholder="__('web.shelter_fieldset_address_city_placeholder')" required />
                </x-input.group>
            </div>

            <x-input.group :error="$errors->first('address.country_id')" class="mt-1">
                <x-label for="address.country_id">{{ __('web.shelter_fieldset_address_country_id_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

                <div x-data="{ countryId: @entangle('address.country_id') }" x-init="countryId ??= {{ $countries->first()?->id }}">
                    <x-select x-model="countryId" class="mt-1">
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

    <div x-show="activeTab === 'socials'">
        <div class="mt-14 mt-8 flex flex-col gap-4">
            <x-input.group :error="$errors->first('shelter.facebook')">
                <x-label>{{ __('web.shelter_fieldset_facebook_label') }}</x-label>
                <x-input class="mt-1" wire:model="shelter.facebook" :placeholder="__('web.shelter_fieldset_facebook_placeholder')" />
            </x-input.group>

            <x-input.group class="mt-1" :error="$errors->first('shelter.instagram')">
                <x-label>{{ __('web.shelter_fieldset_instagram_label') }}</x-label>
                <x-input.social-media class="mt-1" wire:model="shelter.instagram" :placeholder="__('web.shelter_fieldset_instagram_placeholder')" />
            </x-input.group>

            <x-input.group class="mt-1" :error="$errors->first('shelter.tiktok')">
                <x-label>{{ __('web.shelter_fieldset_tiktok_label') }}</x-label>
                <x-input.social-media class="mt-1" wire:model="shelter.tiktok" :placeholder="__('web.shelter_fieldset_tiktok_placeholder')" />
            </x-input.group>
        </div>
    </div>
</div>
