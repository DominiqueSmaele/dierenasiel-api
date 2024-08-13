<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body @class([
    'flex font-sans antialiased h-screen overflow-hidden bg-blue-base',
    'debug-screens' => app()->isLocal(),
])>
    <div x-data="{ isCollapsed: localStorage.getItem('isCollapsed') === 'true' }" class="flex flex-shrink-0" x-init="$watch('isCollapsed', value => localStorage.setItem('isCollapsed', value))">
        <div :class="isCollapsed ? 'w-20' : 'w-80'" class="navigation flex flex-col justify-between px-4 py-8">
            <div>
                <div class="flex h-10 items-center justify-between px-3">
                    <a href="{{ route('home') }}" x-show="!isCollapsed" class="truncate font-highlight-sans text-2xl font-semibold text-white">{{ __('web.app_name') }}</a>

                    <div @click="isCollapsed = !isCollapsed"
                        :class="{
                            'flex text-white cursor-pointer': true,
                            'transform -scale-x-100': !isCollapsed
                        }">
                        <x-icon.bars-from-left class="w-7" />
                    </div>
                </div>

                <div class="mt-20 flex flex-col gap-2">
                    @can('viewAny', App\Models\Shelter::class)
                        <x-navbar-link href="{{ route('global.shelters-overview') }}" wire:navigate icon="building-offices" :active="request()->routeIs('global.shelters-overview')">
                            <p x-show="!isCollapsed">{{ __('web.global_navigation_shelters_link') }}</p>
                        </x-navbar-link>
                    @endcan
                    @can('viewAny', App\Models\Quality::class)
                        <x-navbar-link href="{{ route('global.qualities-overview') }}" wire:navigate icon="folder" :active="request()->routeIs('global.qualities-overview')">
                            <p x-show="!isCollapsed">{{ __('web.global_navigation_qualities_link') }}</p>
                        </x-navbar-link>
                    @endcan
                    @can('viewAnyDeveloper', App\Models\User::class)
                        <x-navbar-link class="mt-10" href="{{ route('global.developers-overview') }}" wire:navigate icon="command-line" :active="request()->routeIs('global.developers-overview')">
                            <p x-show="!isCollapsed">{{ __('web.global_navigation_developers_link') }}</p>
                        </x-navbar-link>
                    @endcan
                </div>
            </div>

            <div>
                <a href="{{ route('profile.show') }}" class="group flex h-12 items-center gap-4 px-1">
                    <p class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-gray-light text-lg font-semibold uppercase text-blue-base group-hover:bg-blue-lightest">
                        {{ (auth()->user()->firstname[0] ?? '') . (auth()->user()->lastname[0] ?? '') }}
                    </p>
                    <div x-show="!isCollapsed">
                        <p class="truncate font-highlight-sans text-xl leading-6 text-gray-light group-hover:text-white">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
                        <p class="leading-2 text-sm text-gray-light group-hover:text-blue-lightest">{{ ucfirst((auth()->user()->getShelterRole() ?? auth()->user()->getRole())->getTranslation()) }}</p>
                    </div>
                </a>

                <div class="mt-8 flex flex-col gap-4">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-button variant="secondary" color="gray" leading-icon="logout" class="flex h-10 w-full justify-between !gap-x-0" href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            <p x-show="!isCollapsed">{{ __('web.global_navigation_logout_link') }}</p>
                        </x-button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <main class="flex flex-1 flex-col overflow-auto border-t border-gray-light bg-white px-14 pt-8">
        <div class="mb-8 flex flex-1 flex-col">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </main>

    @livewire('slide-over-pro')
    @livewire('modal-pro')
</body>

</html>
