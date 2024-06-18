@php
    $volunteers = \App\Models\Volunteer::with('user')->get()->sortBy('users.firstname')->values();
@endphp

<div class="flex flex-col gap-4">
    <x-input.group class="mt-1" :error="$errors->first('timeslot.date')">
        <x-label>{{ __('web.timeslot_fieldset_date_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input class="mt-1" wire:model="timeslot.date" :placeholder="__('web.timeslot_fieldset_date_placeholder')" required readonly />
    </x-input.group>

    <div class="mt-1 flex items-center justify-between gap-4">
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

    <x-input.group class="mt-1" :error="$errors->first('timeslot.volunteer_id')">
        <x-label>{{ __('web.timeslot_fieldset_volunteer_id_label') }}</x-label>

        <div class="mt-1" x-data="{ volunteerId: @entangle('timeslot.volunteer_id') }" x-init="volunteerId ??= null">
            <x-select x-model="volunteerId" @change="if ($event.target.value === '') volunteerId = null">
                <x-select.option value="">{{ __('web.timeslot_fieldset_no_volunteer_option') }}</x-select.option>
                @foreach ($volunteers as $volunteer)
                    <x-select.option :value="$volunteer->id">{{ ucfirst($volunteer->user->firstname) }} {{ ucfirst($volunteer->user->lastname) }}</x-select.option>
                @endforeach
            </x-select>
        </div>
    </x-input.group>
</div>
