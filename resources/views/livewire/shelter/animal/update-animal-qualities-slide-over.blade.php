<x-slide-over wire:submit="update" :title="__('web.update_animal_qualities_slide_over_title')">
    <x-fieldset.animal-qualities />

    <div class="mt-10 flex flex-col gap-2">
        <x-button type="submit" class="w-full">
            {{ __('web.update_animal_qualities_slide_over_submit_button') }}
        </x-button>
        <x-button type="button" variant="secondary" color="gray" wire:click="$dispatch('slide-over.close')" class="w-full">
            {{ __('web.update_animal_qualities_slide_over_cancel_button') }}
        </x-button>
    </div>
</x-slide-over>
