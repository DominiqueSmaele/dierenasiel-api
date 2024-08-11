@props(['title', 'description'])

@php
    $tag = $attributes->wire('submit')->value() !== null ? 'form' : 'div';
@endphp

<{{ $tag }} {{ $attributes->class(['p-6']) }}>
    <h3 class="text-center font-highlight-sans text-2xl font-semibold leading-7">{{ $title }}</h3>

    <p class="mt-4 text-center text-gray-dark">{!! $description !!}</p>

    <div>
        {{ $slot }}
    </div>
    </{{ $tag }}>
