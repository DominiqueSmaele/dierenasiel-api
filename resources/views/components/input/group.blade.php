@props(['error' => null])

<div x-data x-id="['input']" {{ $attributes }}>
    {{ $slot }}

    @if ($error)
        <x-error class="mt-1">{{ $error }}</x-error>
    @endif
</div>
