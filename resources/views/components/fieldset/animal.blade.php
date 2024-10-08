@props(['animal', 'image', 'withoutImage'])

@php
    $types = \App\Models\Type::all()->sortBy('id')->values();
@endphp

<div class="flex flex-1 flex-col gap-4">
    <x-input.group :error="$errors->first('image')">
        <div class="mb-1 flex items-center justify-between gap-4">
            <x-label>{{ __('web.animal_fieldset_image_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

            <div class="flex items-center justify-end gap-2 text-gray-dark">
                @if ($animal->exists && (!$withoutImage || $image !== null))
                    <x-button type="button" @click="document.querySelector('#animal-image').click()" variant="tertiary" class="!p-0">{{ __('web.animal_fieldset_image_change_button') }}</x-button>
                    <p>/</p>
                @endif
                @if (($animal->exists && !$withoutImage) || $image !== null)
                    <x-button type="button" @click="$wire.set('image', null); $wire.set('withoutImage', true);" variant="tertiary" class="!p-0">{{ __('web.animal_fieldset_image_remove_button') }}</x-button>
                @endif
            </div>
        </div>
        <div x-data="{ dragging: false }" :class="dragging ? 'bg-blue-light/10' : 'bg-blue-lightest'" class="relative flex h-96 flex-none cursor-pointer flex-col items-center justify-center gap-2 self-stretch hover:bg-blue-light/10">
            <input
                type="file"
                accept="image/jpg,image/jpeg,image/png,image/webp"
                wire:model="image"
                @dragover="dragging = true"
                @dragleave="dragging = false"
                @drop="dragging = false"
                id="animal-image"
                class="cursor-point absolute inset-0 z-50 h-full w-full text-transparent opacity-0"
                @if (!$animal->exists) required @endif
                autofocus />

            @if (($animal->exists && !$withoutImage) || ($image && is_object($image)))
                <div class="h-full w-full bg-cover bg-center" style="background-image: url('{{ $image && is_object($image) ? $image?->temporaryUrl() : $animal->image?->getAvailableFullUrl(['small', 'medium']) }}')">
                </div>
            @else
                <x-icon.loading wire:loading wire:target="image" class="h-4 w-4 animate-spin text-gray-base" />
                <x-icon.image wire:loading.remove wire:target="image" class="h-6 w-6 text-gray-base" />
                <p class="text-sm text-gray-dark">{{ __('web.animal_fieldset_image_button') }}</p>
            @endif
        </div>
    </x-input.group>

    <x-input.group :error="$errors->first('animal.name')" class="mt-1">
        <x-label>{{ __('web.animal_fieldset_name_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input class="mt-1" wire:model="animal.name" :placeholder="__('web.animal_fieldset_name_placeholder')" required />
    </x-input.group>

    <x-input.group :error="$errors->first('animal.type_id')" class="mt-1">
        <x-label>{{ __('web.animal_fieldset_animal_type_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

        <div x-data="{ typeId: @entangle('animal.type_id') }" x-init="typeId ??= {{ $types->first()?->id }}">
            <x-select x-model="typeId" class="mt-1" required>
                @foreach ($types as $type)
                    <x-select.option :value="$type->id">{{ __('web.' . strtolower($type->name)) }}</x-select.option>
                @endforeach
            </x-select>
        </div>
    </x-input.group>

    <x-input.group :error="$errors->first('animal.sex')" class="mt-1">
        <x-label>{{ __('web.animal_fieldset_sex_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

        <div x-data="{ sex: @entangle('animal.sex') }" x-init="sex ??= 'm'">
            <x-select x-model="sex" class="mt-1" required>
                <x-select.option value='m'>{{ ucfirst(__('web.animal_fieldset_sex_male')) }}</x-select.option>
                <x-select.option value='f'>{{ ucfirst(__('web.animal_fieldset_sex_female')) }}</x-select.option>
            </x-select>
        </div>
    </x-input.group>

    <x-input.group :error="$errors->first('animal.birth_date')" class="mt-1">
        <x-label>{{ __('web.animal_fieldset_birth_date_label') }}</x-label>

        <div class="mt-1" x-data="{ birthDate: @entangle('animal.birth_date') }" x-init="birthDate ?? 'null'">
            <x-input.date x-model="birthDate" :monthOnly="true" :placeholder="__('web.animal_fieldset_birth_date')" class="flex-none" />
        </div>
    </x-input.group>

    <x-input.group :error="$errors->first('animal.race')" class="mt-1">
        <x-label>{{ __('web.animal_fieldset_race_label') }}</x-label>
        <x-input class="mt-1" wire:model="animal.race" :placeholder="__('web.animal_fieldset_race_placeholder')" />
    </x-input.group>

    <x-input.group :error="$errors->first('animal.description')" class="mt-1">
        <x-label>{{ __('web.animal_fieldset_description_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input.textarea class="mt-1" rows="8" wire:model="animal.description" :placeholder="__('web.animal_fieldset_description_placeholder')" required />
    </x-input.group>
</div>
