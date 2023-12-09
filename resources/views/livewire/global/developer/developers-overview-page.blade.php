<div class="flex h-full flex-col">
    <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.developers_overview_page_title') }}</h2>

    @if ($developers->isNotEmpty())
        <div class="mt-12 flex flex-col gap-4">
            @foreach ($developers as $developer)
                <div wire:key="developer-{{ $developer->id }}" class="border border-blue-base bg-white p-4 shadow-light">
                    <p class="font-highlight-sans text-lg font-semibold leading-5">{{ $developer->lastname }} {{ $developer->firstname }}</p>
                    <p class="text-gray-dark">{{ $developer->email }}</p>
                </div>
            @endforeach
        </div>

        {{ $developers->links('pagination.links', ['translationKey' => 'web.developers_pagination_info']) }}
    @else
        <x-empty-state :title="__('web.developers_overview_page_empty_state_title')" :description="__('web.developers_overview_page_empty_state_description')" />
    @endif
</div>
