<div class="flex flex-1 flex-col gap-4">
    @foreach ($this->animalQualities as $key => $animalQuality)
        <x-input.group :error="$errors->first('animalQualities.' . $key . '.value')" class="mb-5">
            <x-label>{{ ucfirst($animalQuality->quality->name) }}</x-label>

            <div x-data="{ animalQualityValue: @entangle('animalQualities.' . $key . '.value') }" x-init='animalQualityValue = {{ $animalQuality->value ?? 'null' }}'>
                <x-select x-model="animalQualityValue" @change="if ($event.target.value === '') animalQualityValue = null" class="mt-1">
                    <x-select.option value="">{{ __('web.animal_qualities_fieldset_unknown') }}</x-select.option>
                    <x-select.option value="1">{{ __('web.animal_qualities_fieldset_true') }}</x-select.option>
                    <x-select.option value="0">{{ __('web.animal_qualities_fieldset_false') }}</x-select.option>
                </x-select>
            </div>
        </x-input.group>
    @endforeach
</div>
