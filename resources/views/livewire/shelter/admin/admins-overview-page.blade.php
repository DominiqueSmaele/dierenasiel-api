<div class="flex h-full flex-col">
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.admins_overview_page_title') }}</h2>

        <x-button leading-icon="plus" wire:click="$dispatch('slide-over.open', {component: 'shelter.create-admin-slide-over', arguments: {'shelterId': {{ $shelter->id }}}}) ">
            {{ __('web.admins_overview_page_create_button') }}
        </x-button>
    </div>

    @if ($admins->isNotEmpty())
        <div class="mt-12 flex flex-col gap-4">
            @foreach ($admins as $admin)
                <div wire:key="admin-{{ $admin->id }}" class="border border-blue-base bg-white p-4 shadow-light">
                    <p class="font-highlight-sans text-lg font-semibold leading-5">{{ $admin->lastname }} {{ $admin->firstname }}</p>
                    <p class="text-gray-dark">{{ $admin->email }}</p>
                </div>
            @endforeach
        </div>

        {{ $admins->links('pagination.links', ['translationKey' => 'web.admins_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.admins_overview_page_empty_state_title')" :description="__('web.admins_overview_page_empty_state_description')" />
    @endif
</div>
