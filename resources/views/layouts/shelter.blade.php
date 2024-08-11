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
    <div class="flex flex-shrink-0">
        <div class="flex w-80 flex-col justify-between px-4 py-8">
            <div>
                <a href="{{ route('shelter.home', $shelter->id) }}" class="flex items-center gap-3 px-3">
                    <x-graphic.logo-white class="-ml-1 w-8 flex-none" />
                    <h1 class="truncate font-highlight-sans text-2xl font-semibold text-white">{{ $shelter->name }}<h1>
                </a>
                <div class="mt-20 flex flex-col gap-2">
                    @can('viewAny', [App\Models\Animal::class, $shelter])
                        <x-navbar-link href="{{ route('shelter.animals-overview', $shelter->id) }}" wire:navigate icon="animal" :active="request()->routeIs('shelter.animals-overview') || request()->routeIs('shelter.animal-detail')">
                            {{ __('web.shelter_navigation_animals_link') }}
                        </x-navbar-link>
                    @endcan

                    @can('view', [App\Models\Shelter::class, $shelter])
                        <x-navbar-link href="{{ route('shelter.detail', $shelter->id) }}" wire:navigate icon="information" :active="request()->routeIs('shelter.detail')">
                            {{ __('web.shelter_navigation_information_link') }}
                        </x-navbar-link>
                    @endcan

                    @can('viewAnyAdmin', [App\Models\User::class, $shelter])
                        <x-navbar-link class="mt-10" href="{{ route('shelter.admins-overview', $shelter->id) }}" wire:navigate icon="user" :active="request()->routeIs('shelter.admins-overview')">
                            {{ __('web.shelter_navigation_admins_link') }}
                        </x-navbar-link>
                    @endcan
                </div>
            </div>

            <div>
                <a href="{{ route('profile.show', ['shelterId' => $shelter->id]) }}" class="group flex items-center gap-4">
                    <p class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-gray-light text-lg font-semibold uppercase text-blue-base group-hover:bg-blue-lightest">
                        {{ (auth()->user()->firstname[0] ?? '') . (auth()->user()->lastname[0] ?? '') }}
                    </p>
                    <div>
                        <p class="truncate font-highlight-sans text-xl leading-6 text-gray-light group-hover:text-white">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</p>
                        <p class="text-sm leading-4 text-gray-light group-hover:text-blue-lightest">{{ ucfirst((auth()->user()->getShelterRole() ?? auth()->user()->getRole())->getTranslation()) }}</p>
                    </div>
                </a>

                <div class="mt-8 flex flex-col gap-4">
                    @can('viewAny', App\Models\Shelter::class)
                        <x-button href="{{ route('global.home') }}" variant="secondary" color="gray" leading-icon="arrow-left" class="w-full">
                            {{ __('web.shelter_navigation_global_link') }}
                        </x-button>
                    @endcan
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-button variant="secondary" color="gray" leading-icon="logout" class="w-full" href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('web.global_navigation_logout_link') }}
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
