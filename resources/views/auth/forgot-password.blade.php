<x-guest-layout>
    <h1 class="text-center font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.forgot_password_title') }}</h1>

    <p class="mt-8 text-center leading-5">{{ __('web.forgot_password_description') }}</p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-8">
        @csrf

        <x-input.group :error="$errors->first('email')">
            <x-label for="email">{{ __('web.forgot_password_email_label') }}</x-label>
            <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" :placeholder="__('web.forgot_password_email_placeholder')" required autofocus autocomplete="username" />
            @if (session('status'))
                <div class="ml-3 mt-1 text-sm font-normal leading-5 text-green-base">
                    {{ session('status') }}
                </div>
            @endif
        </x-input.group>

        <x-button type="submit" color="blue" class="mt-8 w-full">
            {{ __('web.forgot_password_submit_button') }}
        </x-button>

        <x-button href="{{ route('login') }}" variant="tertiary" class="mt-2 w-full">
            {{ __('web.forgot_password_back_button') }}
        </x-button>
    </form>
</x-guest-layout>
