@props(['variant' => 'primary', 'color' => null, 'size' => 'medium', 'leadingIcon' => null, 'trailingIcon' => null])

@php
    $color ??= match ($variant) {
        'primary' => 'orange',
        'secondary' => 'blue',
        'tertiary' => 'gray',
    };

    $tag = $attributes->has('href') ? 'a' : 'button';

    $baseClasses = 'flex items-center justify-center gap-x-2 font-semibold leading-5 text-lg leading-5 focus:outline-none';

    $variantClasses = match ($variant) {
        'primary' => ' font-semibold border border-current rounded-full disabled:bg-gray-light disabled:hover:bg-gray-light',
        'secondary' => 'bg-transparent border rounded-full disabled:border-gray-light disabled:hover:border-gray-light',
        'tertiary' => 'disabled:text-gray-light disabled:hover:text-gray-light',
    };

    $colorClasses = match ("{$variant}-{$color}") {
        'primary-orange' => 'bg-orange-base hover:bg-orange-light active:bg-orange-dark text-white focus-visible:bg-orange-light',
        'primary-blue' => 'bg-blue-base hover:bg-blue-light active:bg-blue-dark text-white focus-visible:bg-blue-light',
        'primary-gray' => 'bg-gray-dark hover:bg-gray-base active:bg-gray-dark text-white focus-visible:bg-gray-base',
        'secondary-orange' => 'text-orange-base border-orange-base hover:text-orange-light hover:border-orange-light active:text-orange-dark active:border-orange-dark focus-visible:text-orange-light focus-visible:border-orange-light',
        'secondary-blue' => 'text-blue-base border-blue-base hover:text-blue-light hover:border-blue-light active:text-blue-dark active:border-blue-dark focus-visible:text-blue-light focus-visible:border-blue-light',
        'secondary-gray' => 'text-gray-dark border-gray-dark hover:text-gray-base hover:border-gray-base active:text-gray-dark active:border-gray-dark focus-visible:text-gray-base focus-visible:border-gray-base',
        'tertiary-orange' => 'text-orange-base hover:text-orange-light active:text-orange-dark focus-visible:text-orange-light',
        'tertiary-blue' => 'text-blue-base hover:text-blue-light active:text-blue-dark focus-visible:text-blue-light',
        'tertiary-gray' => 'text-gray-dark hover:text-gray-base active:text-gray-dark focus-visible:text-gray-base',
    };

    $sizeClasses = match ($size) {
        'medium' => 'px-3 py-2.5 text-sm',
        'small' => 'py-1.5 px-3 text-xs',
    };

    $iconSizeClasses = match ($size) {
        'medium' => 'h-4 w-4',
        'small' => 'h-3 w-3',
    };
@endphp

<{{ $tag }} {{ $attributes->class([$baseClasses, $variantClasses, $colorClasses, $sizeClasses ?? '']) }}>
    @if ($leadingIcon || $trailingIcon)
        <span class="flex-1">
            @if ($leadingIcon instanceof \Illuminate\View\ComponentSlot)
                {{ $leadingIcon }}
            @elseif ($leadingIcon)
                <x-dynamic-component :component="'icon.' . $leadingIcon" :class="$iconSizeClasses" />
            @endif
        </span>
    @endif

    <span @class(['mr-auto' => $leadingIcon || $trailingIcon])>{{ $slot }}</span>

    @if ($leadingIcon || $trailingIcon)
        <span class="flex-1">
            @if ($trailingIcon instanceof \Illuminate\View\ComponentSlot)
                <span class="ml-auto">{{ $trailingIcon }}</span>
            @elseif ($trailingIcon)
                <x-dynamic-component :component="'icon.' . $trailingIcon" :class='"{$iconSizeClasses} ml-auto"' />
            @endif
        </span>
    @endif
    </{{ $tag }}>
