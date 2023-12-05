<x-guest-layout>
    <h1 class="text-center font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.login_title') }}</h1>

    <form method="POST" action="{{ route('login') }}" class="mt-12">
        @csrf

        <x-input.group :error="$errors->first('email')">
            <x-label for="email" class="ml-2">{{ __('web.login_email_label') }}</x-label>
            <x-input id="email" class="mt-1" type="email" name="email" :value="old('email')" :placeholder="__('web.login_email_placeholder')" required autofocus autocomplete="username" />
            @if (session('status'))
                <div class="ml-3 mt-1 text-sm font-normal leading-5 text-green-base">
                    {{ session('status') }}
                </div>
            @endif
        </x-input.group>

        <x-input.group :error="$errors->first('password')" class="mt-4">
            <x-label for="password" class="ml-2">{{ __('web.login_password_label') }}</x-label>
            <x-input id="password" class="mt-1" type="password" name="password" :placeholder="__('web.login_password_placeholder')" required autocomplete="current-password" />
        </x-input.group>

        <div class="ml-2 mt-4 block">
            <label for="remember_me" class="flex items-center">
                <x-checkbox id="remember_me" name="remember" />
                <span class="text-dark-25 ml-2 text-sm leading-5">{{ __('web.login_remember_me_label') }}</span>
            </label>
        </div>

        <x-button type="submit" variant="primary" color="blue" class="mt-8 w-full">
            {{ __('web.login_button') }}
        </x-button>

        <x-button href="{{ route('password.request') }}" variant="tertiary" class="mt-2 w-full">
            {{ __('web.login_forgot_password_button') }}
        </x-button>
    </form>
</x-guest-layout>
