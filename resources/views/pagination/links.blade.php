@props(['translationKey' => null])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between py-3.5">
        <x-button type="button" variant="tertiary" leading-icon="arrow-left" :disabled="$paginator->onFirstPage()" wire:click.prefetch="previousPage('{{ $paginator->getPageName() }}')" rel="prev" aria-label="{{ __('pagination.previous') }}">
            {{ __('pagination.previous') }}
        </x-button>

        @if ($translationKey)
            <div class="hidden sm:block">
                <p class="text-sm leading-5 text-gray-dark">
                    {!! __($translationKey, [
                        'first' => "<span class=\"font-semibold\" wire:key=\"first\">{$paginator->firstItem()}</span>",
                        'last' => "<span class=\"font-semibold\" wire:key=\"last\">{$paginator->lastItem()}</span>",
                        'total' => "<span class=\"font-semibold\" wire:key=\"total\">{$paginator->total()}</span>",
                    ]) !!}
                </p>
            </div>
        @endif

        <x-button type="button" variant="tertiary" trailing-icon="arrow-right" :disabled="!$paginator->hasMorePages()" wire:click.prefetch="nextPage('{{ $paginator->getPageName() }}')" rel="next" aria-label="{{ __('pagination.next') }}">
            {{ __('pagination.next') }}
        </x-button>
    </nav>
@endif
