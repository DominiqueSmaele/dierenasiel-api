<div class="flex flex-1 flex-col gap-4">
    @foreach ($this->animalQualities as $key => $animalQuality)
        @php
            $animalQualityValue = $animalQuality['pivot']['value'] !== null ? (int) $animalQuality['pivot']['value'] : null;
        @endphp

        <x-input.group :error="$errors->first('animalQualities.' . $key . '.pivot.value')" class="mb-5">
            <x-label>{{ ucfirst($animalQuality['name']) }}</x-label>

            <div x-data="{ animalQualityValue: @entangle('animalQualities.' . $key . '.pivot.value') }" x-init='animalQualityValue = {{ $animalQualityValue ?? 'null' }}'>
                <x-select x-model="animalQualityValue" @change="if ($event.target.value === '') animalQualityValue = null" class="mt-1">
                    <x-select.option value="">{{ __('web.animal_qualities_fieldset_unknown') }}</x-select.option>
                    <x-select.option value="1">{{ __('web.animal_qualities_fieldset_true') }}</x-select.option>
                    <x-select.option value="0">{{ __('web.animal_qualities_fieldset_false') }}</x-select.option>
                </x-select>
            </div>
        </x-input.group>
    @endforeach
</div>
