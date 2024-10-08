<x-slide-over wire:submit="create" :title="__('web.create_shelter_slide_over_title')">
    <x-fieldset.shelter :shelter="$shelter" :image="$image" :without-image="$withoutImage" />

    <div class="mt-10 flex flex-col gap-2">
        <x-button type="submit" class="w-full">
            {{ __('web.create_shelter_slide_over_submit_button') }}
        </x-button>
        <x-button type="button" variant="secondary" color="gray" wire:click="$dispatch('slide-over.close')" class="w-full">
            {{ __('web.create_shelter_slide_over_cancel_button') }}
        </x-button>
    </div>
</x-slide-over>
