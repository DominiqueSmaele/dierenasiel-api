<div class="flex flex-col gap-4">
    <x-input.group class="mt-1" :error="$errors->first('timeslot.date')">
        <x-label>{{ __('web.timeslot_fieldset_date_label') }} </x-label>
        <x-input class="mt-1" wire:model="timeslot.date" :placeholder="__('web.timeslot_fieldset_date_placeholder')" required readonly />
    </x-input.group>

    <div class="mt-2 flex items-center justify-between gap-4">
        <x-input.group :error="$errors->first('timeslot.start_time')" class="mt-1">
            <x-label>{{ __('web.timeslot_fieldset_start_time_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

            <div class="mt-1" x-data="{ start_time: @entangle('timeslot.start_time') }" x-init="start_time ?? 'null'">
                <x-input.date x-model="start_time" :noCalendar="true" :enableTime="true" :placeholder="__('web.timeslot_fieldset_start_time')" class="flex-none" />
            </div>
        </x-input.group>

        <span class="mt-6">-</span>

        <x-input.group :error="$errors->first('timeslot.end_time')" class="mt-1">
            <x-label>{{ __('web.timeslot_fieldset_end_time_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

            <div class="mt-1" x-data="{ end_time: @entangle('timeslot.end_time') }" x-init="end_time ?? 'null'">
                <x-input.date x-model="end_time" :noCalendar="true" :enableTime="true" :placeholder="__('web.timeslot_fieldset_end_time')" class="flex-none" />
            </div>
        </x-input.group>
    </div>
</div>
