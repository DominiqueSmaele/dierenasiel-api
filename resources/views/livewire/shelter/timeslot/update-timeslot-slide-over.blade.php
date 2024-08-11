<x-slide-over wire:submit="update" :title="__('web.update_timeslot_slide_over_title')">
    <x-slot name="action">
        <x-button type="button" variant="tertiary" color="gray" wire:click="$dispatch('modal.open', {component: 'shelter.delete-timeslot-modal', arguments: {'timeslotId': {{ $timeslot->id }}}})">
            <x-icon.trash class="h-5 w-5" />
        </x-button>
    </x-slot>

    <x-fieldset.timeslot :animal="$timeslot" />

    <div class="mt-10 flex flex-col gap-2">
        <x-button type="submit" class="w-full">
            {{ __('web.update_timeslot_slide_over_submit_button') }}
        </x-button>
        <x-button type="button" variant="secondary" color="gray" wire:click="$dispatch('slide-over.close')" class="w-full">
            {{ __('web.update_timeslot_slide_over_cancel_button') }}
        </x-button>
    </div>
</x-slide-over>
