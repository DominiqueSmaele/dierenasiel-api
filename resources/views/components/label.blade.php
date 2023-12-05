@props(['value'])

<label :for="$id('input')" {{ $attributes->merge(['class' => 'text-blue-base block text-sm font-semibold leading-5']) }}>
    {{ $value ?? $slot }}
</label>
