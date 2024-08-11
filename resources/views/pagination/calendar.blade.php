@props(['translationKey' => null])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between py-5">
        <x-button type="button" variant="primary" color="orange" leading-icon="arrow-left" :disabled="$paginator->onFirstPage()" wire:click="previousPage('{{ $paginator->getPageName() }}')" rel="prev" aria-label="{{ __('pagination.previous') }}">
            {{ __('pagination.previous') }}
        </x-button>

        @if ($translationKey)
            <div class="hidden sm:block">
                <p class="text-sm leading-5 text-gray-dark">
                    {!! __($translationKey, [
                        'first' => "<span class=\"font-semibold\" wire:key=\"first\">" . \Carbon\Carbon::parse($paginator->first()['date'])->format('d/m/Y') . '</span>',
                        'last' => "<span class=\"font-semibold\" wire:key=\"last\">" . \Carbon\Carbon::parse($paginator->last()['date'])->format('d/m/Y') . '</span>',
                    ]) !!}
                </p>
            </div>
        @endif

        <x-button type="button" variant="primary" color="orange" trailing-icon="arrow-right" :disabled="!$paginator->hasMorePages()" wire:click="nextPage('{{ $paginator->getPageName() }}')" rel="next" aria-label="{{ __('pagination.next') }}">
            {{ __('pagination.next') }}
        </x-button>
    </nav>
@endif
