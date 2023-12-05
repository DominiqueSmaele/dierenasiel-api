@props(['error' => null])

<div x-data x-id="['input']" {{ $attributes }}>
    {{ $slot }}

    @if ($error)
        <x-error class="ml-3 mt-1">{{ $error }}</x-error>
    @endif
</div>
