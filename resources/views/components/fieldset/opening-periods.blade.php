<div class="flex flex-col gap-4">
    @foreach ($this->openingPeriods as $key => $openingPeriod)
        <div class="mb-5">
            <x-label>{{ __('web.opening_periods_fieldset_day_' . $openingPeriod->day . '_label') }}</x-label>

            <div class="mt-2 flex items-center justify-between gap-4">
                <x-input.group :error="$errors->first('openingPeriods.' . $key . '.open')">
                    <div x-data="{ open: @entangle('openingPeriods.' . $key . '.open') }" x-init='open = @json($openingPeriod->open ?? null)'>
                        <x-input.date x-model="open" data-utc :noCalendar="true" :enableTime="true" :placeholder="__('web.opening_periods_fieldset_placeholder')" class="flex-none" />
                    </div>
                </x-input.group>

                <span>-</span>

                <x-input.group :error="$errors->first('openingPeriods.' . $key . '.close')">
                    <div x-data="{ close: @entangle('openingPeriods.' . $key . '.close') }" x-init='close = @json($openingPeriod->close ?? null)'>
                        <x-input.date x-model="close" :noCalendar="true" :enableTime="true" :placeholder="__('web.opening_periods_fieldset_placeholder')" class="flex-none" />
                    </div>
                </x-input.group>
            </div>
        </div>
    @endforeach
</div>
