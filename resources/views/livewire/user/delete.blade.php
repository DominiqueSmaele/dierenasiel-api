<div>
    <h1 class="text-center font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.user_delete_title') }}</h1>

    @if (session('status'))
        <div class="mt-12 bg-green-light p-4 text-center font-bold text-white">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="delete" class="mt-12">
        @csrf

        <x-input.group :error="$errors->first('email')">
            <x-label>{{ __('web.user_delete_email_label') }}</x-label>
            <x-input wire:model='email' class="mt-1" type="email" :placeholder="__('web.user_delete_email_placeholder')" required autofocus />
        </x-input.group>

        <x-input.group :error="$errors->first('password')" class="mt-4">
            <x-label>{{ __('web.user_delete_password_label') }}</x-label>
            <x-input wire:model='password' class="mt-1" type="password" :placeholder="__('web.user_delete_password_placeholder')" required />
        </x-input.group>

        <x-button type="submit" variant="primary" class="mt-8 w-full bg-red-base hover:bg-red-light active:bg-red-base">
            {{ __('web.user_delete_button') }}
        </x-button>
    </form>
</div>
