@props([
    'translationKey' => null,
    'timeZone' => 'Europe/Brussels',
])

@if ($paginator->hasPages())
    <nav class="mb-3 mt-12 flex items-center justify-start">
        <div class="flex items-center self-stretch">
            <x-button class="flex self-stretch" type="button" variant="primary" color="orange" :disabled="$paginator->onFirstPage()" wire:click="previousPage('{{ $paginator->getPageName() }}')" rel="prev" aria-label="{{ __('pagination.previous') }}">
                <x-icon.arrow-left class="h-4 w-4" />
            </x-button>

            <x-button class="ml-2 flex self-stretch" type="button" variant="primary" color="orange" :disabled="!$paginator->hasMorePages()" wire:click="nextPage('{{ $paginator->getPageName() }}')" rel="next" aria-label="{{ __('pagination.next') }}">
                <x-icon.arrow-right class="h-4 w-4" />
            </x-button>

            @if ($translationKey)
                <div class="ml-3 hidden sm:block">
                    <p class="font-highlight-sans text-2xl">
                        @php
                            $firstDate = \Carbon\Carbon::parse($paginator->first()['date'])->locale(app()->getLocale());
                            $lastDate = \Carbon\Carbon::parse($paginator->last()['date'])->locale(app()->getLocale());
                        @endphp

                        {!! __($translationKey, [
                            'first' => "<span class=\"font-semibold\" wire:key=\"first\">" . $firstDate->format('d') . ' ' . ucfirst($firstDate->monthName) . '</span>',
                            'last' => "<span class=\"font-semibold\" wire:key=\"last\">" . $lastDate->format('d') . ' ' . ucfirst($lastDate->monthName) . '</span>',
                        ]) !!}
                    </p>
                </div>

                <div class="ml-3 flex items-center rounded-full bg-blue-base px-3 py-1 text-sm text-white">
                    <p>{{ __('web.volunteers_overview_page_week_label', ['week' => $firstDate->format('W')]) }}</p>
                </div>
            @endif
        </div>
    </nav>
@endif
