<x-modal
    wire:submit="delete"
    :title="__('web.delete_timeslot_volunteer_modal_title')"
    :description="__('web.delete_timeslot_volunteer_modal_description', ['firstname' => $timeslot->user->firstname, 'lastname' => $timeslot->user->lastname])"
    class="pb-3">

    <div class="mt-10 flex flex-col gap-1">
        <x-button type="submit" class="w-full">
            {{ __('web.delete_timeslot_volunteer_modal_submit_button') }}
        </x-button>
        <x-button type="button" variant="tertiary" color="gray" wire:click="$dispatch('modal.close')" class="w-full">
            {{ __('web.delete_timeslot_volunteer_modal_cancel_button') }}
        </x-button>
    </div>
</x-modal>
