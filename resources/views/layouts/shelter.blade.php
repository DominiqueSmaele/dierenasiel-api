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
                <div class="flex h-10 justify-between px-2.5">
                    <a href="{{ route('shelter.animals-overview', $shelter->id) }}" x-show="!isCollapsed" class="font-highlight-sans text-2xl font-semibold leading-normal text-white">{{ $shelter->name }}</a>

                    <div @click="isCollapsed = !isCollapsed"
                        :class="{
                            'flex text-white cursor-pointer': true,
                            'transform -scale-x-100': !isCollapsed
                        }">
                        <x-icon.bars-from-left class="w-7" />
                    </div>
                </div>
                <div class="mt-20 flex flex-col gap-2">
                    @can('viewAny', [App\Models\Animal::class, $shelter])
                        <x-navbar-link href="{{ route('shelter.animals-overview', $shelter->id) }}" wire:navigate icon="animal" :active="request()->routeIs('shelter.animals-overview') || request()->routeIs('shelter.animal-detail')">
                            <p x-show="!isCollapsed">{{ __('web.shelter_navigation_animals_link') }}</p>
                        </x-navbar-link>
                    @endcan

                    @can('viewAny', [App\Models\Timeslot::class, $shelter])
                        <x-navbar-link href="{{ route('shelter.volunteers-overview', $shelter->id) }}" wire:navigate icon="calendar" :active="request()->routeIs('shelter.volunteers-overview')">
                            <p x-show="!isCollapsed">{{ __('web.shelter_navigation_volunteers_link') }}</p>
                        </x-navbar-link>
                    @endcan

                    @can('view', [App\Models\Shelter::class, $shelter])
                        <x-navbar-link href="{{ route('shelter.detail', $shelter->id) }}" wire:navigate icon="information" :active="request()->routeIs('shelter.detail')">
                            <p x-show="!isCollapsed">{{ __('web.shelter_navigation_information_link') }}</p>
                        </x-navbar-link>
                    @endcan

                    @can('viewAnyAdmin', [App\Models\User::class, $shelter])
                        <x-navbar-link class="mt-10" href="{{ route('shelter.admins-overview', $shelter->id) }}" wire:navigate icon="user" :active="request()->routeIs('shelter.admins-overview')">
                            <p x-show="!isCollapsed">{{ __('web.shelter_navigation_admins_link') }}</p>
                        </x-navbar-link>
                    @endcan
                </div>
            </div>

            <div>
                <a href="{{ route('profile.show', ['shelterId' => $shelter->id]) }}" class="group flex h-12 items-center gap-4 px-1">
                    <p class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-gray-light text-lg font-semibold uppercase text-blue-base group-hover:bg-blue-lightest">
                        {{ (auth()->user()->firstname[0] ?? '') . (auth()->user()->lastname[0] ?? '') }}
                    </p>
                    <div x-show="!isCollapsed">
                        <p class="truncate font-highlight-sans text-xl leading-6 text-gray-light group-hover:text-white">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
                        <p class="leading-2 text-sm text-gray-light group-hover:text-blue-lightest">{{ ucfirst((auth()->user()->getShelterRole() ?? auth()->user()->getRole())->getTranslation()) }}</p>
                    </div>
                </a>

                <div class="mt-8 flex flex-col gap-4">
                    @can('viewAny', App\Models\Shelter::class)
                        <x-button href="{{ route('global.home') }}" variant="secondary" color="gray" leading-icon="arrow-left" class="flex h-10 w-full justify-between !gap-x-0 truncate">
                            <p x-show="!isCollapsed">{{ __('web.shelter_navigation_global_link') }}</p>
                        </x-button>
                    @endcan
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
