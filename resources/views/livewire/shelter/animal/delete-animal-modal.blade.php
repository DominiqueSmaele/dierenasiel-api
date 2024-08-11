<x-modal
    wire:submit="delete"
    :title="__('web.delete_animal_modal_title', ['name' => $animal->name])"
    :description="__('web.delete_animal_modal_description', ['name' => $animal->name])"
    class="pb-3">

    <div class="mt-10 flex flex-col gap-1">
        <x-button type="submit" class="w-full">
            {{ __('web.delete_animal_modal_submit_button') }}
        </x-button>
        <x-button type="button" variant="tertiary" color="gray" wire:click="$dispatch('modal.close')" class="w-full">
            {{ __('web.delete_animal_modal_cancel_button') }}
        </x-button>
    </div>
</x-modal>
