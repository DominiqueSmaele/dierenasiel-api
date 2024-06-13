@php
    $types = \App\Models\Type::all()->sortBy('id')->values();
@endphp

<div class="flex flex-1 flex-col gap-4">
    <x-input.group :error="$errors->first('quality.name')">
        <x-label>{{ __('web.quality_fieldset_name_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input class="mt-1" wire:model="quality.name" :placeholder="__('web.quality_fieldset_name_placeholder')" required />
    </x-input.group>

    <x-input.group :error="$errors->first('quality.type_id')" class="mt-3">
        <x-label for="quality.type_id">{{ __('web.quality_fieldset_type_id_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

        <div x-data="{ typeId: @entangle('quality.type_id') }" x-init="typeId ??= {{ $types->first()?->id }}">
            <x-select x-model="typeId" class="mt-1" required>
                @foreach ($types as $type)
                    <x-select.option :value="$type->id">{{ ucfirst($type->name) }}</x-select.option>
                @endforeach
            </x-select>
        </div>
    </x-input.group>
</div>
