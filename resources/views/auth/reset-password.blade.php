<x-guest-layout>

    <h1 class="text-center font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.reset_password_title') }}</h1>

    <p class="mt-8 leading-5">{{ __('web.reset_password_description') }}</p>

    <form method="POST" action="{{ route('password.update') }}" class="mt-8">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-input.group :error="$errors->first('email')">
            <x-label for="email" class="ml-3">{{ __('web.reset_password_email_label') }}</x-label>
            <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" :placeholder="__('web.reset_password_email_placeholder')" required autofocus autocomplete="username" />
        </x-input.group>

        <x-input.group :error="$errors->first('password')" class="mt-4">
            <x-label for="password" class="ml-3">{{ __('web.reset_password_password_label') }}</x-label>
            <x-input id="password" class="mt-1 block w-full" type="password" name="password" :placeholder="__('web.reset_password_password_placeholder')" required autocomplete="new-password" />
        </x-input.group>

        <x-input.group :error="$errors->first('password_confirmation')" class="mt-4">
            <x-label for="password_confirmation" class="ml-3">{{ __('web.reset_password_confirm_password_label') }}</x-label>
            <x-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" :placeholder="__('web.reset_password_confirm_password_placeholder')" required autocomplete="new-password" />
        </x-input.group>

        <x-button type="submit" class="mt-8 w-full">
            {{ __('web.reset_password_submit_button') }}
        </x-button>
    </form>
</x-guest-layout>
