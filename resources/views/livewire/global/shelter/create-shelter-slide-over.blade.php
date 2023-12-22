<x-slide-over wire:submit.prevent="create" :title="__('web.create_shelter_slide_over_title')">
    <x-fieldset.shelter />

    <div class="flex flex-col gap-2">
        <x-button type="submit" class="w-full">
            {{ __('web.create_shelter_slide_over_submit_button') }}
        </x-button>
        <x-button type="button" variant="secondary" color="gray" wire:click="$dispatch('slide-over.close')" class="w-full">
            {{ __('web.create_shelter_slide_over_cancel_button') }}
        </x-button>
    </div>
</x-slide-over>
