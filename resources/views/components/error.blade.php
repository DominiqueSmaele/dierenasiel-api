<div {{ $attributes->class(['flex gap-1.5 text-sm font-semibold leading-5 text-red-base']) }}>
    <x-icon.alert class="mt-0.5 block h-4 w-4 text-red-base" />
    <p>{{ $slot }}</p>
</div>
