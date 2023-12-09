@props(['icon', 'active' => false])

<a {{ $attributes->class(['flex items-center gap-4 px-3 py-2.5 font-highlight-sans text-xl leading-6 hover:bg-blue-lightest/10 hover:text-white', 'bg-blue-lightest/10 text-white' => $active, 'text-gray-light' => !$active]) }}>
    <x-dynamic-component :component="'icon.' . $icon" class="h-6 w-6 flex-none" />
    <span>{{ $slot }}</span>
</a>
