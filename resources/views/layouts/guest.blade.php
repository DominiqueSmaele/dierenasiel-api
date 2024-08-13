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
    'font-sans antialiased text-black',
    'debug-screens' => app()->isLocal(),
])>
    <div class="flex min-h-screen flex-col content-start items-center justify-center">
        <div class="w-full max-w-md">
            <a href="{{ route('login') }}" class="flex justify-center">
                <x-graphic.logo class="h-28" />
            </a>

            <div class="mt-4 w-full overflow-hidden px-6 py-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
