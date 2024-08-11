<x-modal
    wire:submit="delete"
    :title="__('web.delete_quality_modal_title')"
    :description="__('web.delete_quality_modal_description')"
    class="pb-3">

    <div class="mt-10 flex flex-col gap-1">
        <x-button type="submit" class="w-full">
            {{ __('web.delete_quality_modal_submit_button') }}
        </x-button>
        <x-button type="button" variant="tertiary" color="gray" wire:click="$dispatch('modal.close')" class="w-full">
            {{ __('web.delete_quality_modal_cancel_button') }}
        </x-button>
    </div>
</x-modal>
