@props(['title', 'description'])

<div {{ $attributes->class(['flex flex-1 flex-col items-center justify-center gap-8']) }}>
    <x-graphic.empty class="w-24" />
    <div class="w-80 text-center">
        <h3 class="font-highlight-sans text-2xl font-semibold leading-7">{{ $title }}</h3>
        <p class="mt-2 text-center text-gray-dark">{{ $description }}</p>
    </div>
</div>
