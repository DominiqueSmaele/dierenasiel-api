<div class="divide-y divide-gray-light">
    <div class="flex flex-col gap-4">
        <x-input.group class="mt-1" :error="$errors->first('timeslot.date')">
            <x-label>{{ __('web.timeslot_fieldset_date_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
            <x-input class="mt-1" wire:model="timeslot.date" :placeholder="__('web.timeslot_fieldset_date_placeholder')" required readonly />
        </x-input.group>

        <div class="mt-1 flex items-center justify-between gap-4">
            <x-input.group class="flex w-[45%] flex-col self-stretch" :error="$errors->first('timeslot.start_time')">
                <x-label>{{ __('web.timeslot_fieldset_start_time_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

                <div class="mt-1" x-data="{ start_time: @entangle('timeslot.start_time') }" x-init="start_time ?? 'null'">
                    <x-input.date x-model="start_time" :noCalendar="true" :enableTime="true" :placeholder="__('web.timeslot_fieldset_start_time')" class="flex-none" />
                </div>
            </x-input.group>

            <div class="mt-9 flex self-stretch">-</div>

            <x-input.group class="flex w-[45%] flex-col self-stretch" :error="$errors->first('timeslot.end_time')">
                <x-label>{{ __('web.timeslot_fieldset_end_time_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>

                <div class="mt-1" x-data="{ end_time: @entangle('timeslot.end_time') }" x-init="end_time ?? 'null'">
                    <x-input.date x-model="end_time" :noCalendar="true" :enableTime="true" :placeholder="__('web.timeslot_fieldset_end_time')" class="flex-none" />
                </div>
            </x-input.group>
        </div>
    </div>

    @if ($this->timeslot->user)
        @php
            $name = "{$this->timeslot->user->firstname} {$this->timeslot->user->lastname}";
        @endphp

        <div class="mt-8">
            <x-input.group class="mt-6" :error="$errors->first('timeslot.user_id')">
                <div class="flex items-center justify-between">
                    <x-label>{{ __('web.timeslot_fieldset_volunteer_name_label') }}</x-label>

                    <x-button type="button" variant="tertiary" color="gray" wire:click="$dispatch('modal.open', {component: 'shelter.delete-timeslot-volunteer-modal', arguments: {'timeslotId': {{ $this->timeslot->id }}}})">
                        <x-icon.trash class="h-4 w-4" />
                    </x-button>
                </div>

                <x-input class="mt-1" :value="$name" placeholder="-" readonly />
            </x-input.group>

            <x-input.group class="mt-6" :error="$errors->first('timeslot.userr_id')">
                <x-label>{{ __('web.timeslot_fieldset_volunteer_email_label') }}</x-label>

                <x-input class="mt-1" :value="$this->timeslot->user->email" placeholder="-" readonly />
            </x-input.group>
        </div>
    @endif
</div>
