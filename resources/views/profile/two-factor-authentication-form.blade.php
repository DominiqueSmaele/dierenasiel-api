<div class="max-w-2xl">
    <h1 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.two_factor_authentication_form_title') }}</h1>

    <div class="mt-8">
        <h3 class="text-md font-semibold">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('web.two_factor_authentication_form_finish_enabling_subtitle') }}
                @else
                    {{ __('web.two_factor_authentication_form_enabled_subtitle') }}
                @endif
            @else
                {{ __('web.two_factor_authentication_form_not_enabled_subtitle') }}
            @endif
        </h3>

        <p class="mt-3 text-sm">
            {{ __('web.two_factor_authentication_form_description') }}
        </p>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 text-sm font-semibold">
                    <p class="font-semibold">
                        @if ($showingConfirmation)
                            {{ __('web.two_factor_authentication_form_finish_enabling_instructions') }}
                        @else
                            {{ __('web.two_factor_authentication_form_enabled_instructions') }}
                        @endif
                    </p>
                </div>

                <div class="mt-4">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="mt-4 max-w-xl text-sm">
                    <p class="font-semibold">
                        {{ __('web.two_factor_authentication_form_setup_key_label') }}: {{ decrypt($this->user->two_factor_secret) }}
                    </p>
                </div>

                @if ($showingConfirmation)
                    <x-input.group :error="$errors->first('code')" class="mt-4 w-1/2">
                        <x-label>{{ __('web.two_factor_authentication_form_code_label') }}</x-label>
                        <x-input wire:model="code" wire:keydown.enter="confirmTwoFactorAuthentication" class="mt-1 block" inputmode="numeric" autofocus autocomplete="one-time-code" />
                    </x-input.group>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 max-w-xl text-sm">
                    <p class="font-semibold">
                        {{ __('web.two_factor_authentication_form_recovery_codes_note') }}
                    </p>
                </div>

                <div class="font-mono mt-4 grid max-w-xl gap-1 rounded-lg bg-gray-light px-4 py-4 text-sm">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-8 flex items-center gap-2">
            @if (!$this->enabled)
                <x-button type="button" wire:click="enableTwoFactorAuthentication" wire:loading.attr="disabled">
                    {{ __('web.two_factor_authentication_form_enable_button') }}
                </x-button>
            @else
                @if ($showingRecoveryCodes)
                    <x-button type="button" wire:click="regenerateRecoveryCodes">
                        {{ __('web.two_factor_authentication_form_regenerate_recovery_codes_button') }}
                    </x-button>
                @elseif ($showingConfirmation)
                    <x-button type="button" wire:click="confirmTwoFactorAuthentication" wire:loading.attr="disabled">
                        {{ __('web.two_factor_authentication_form_confirm_button') }}
                    </x-button>
                @else
                    <x-button type="button" wire:click="showRecoveryCodes" wire:loading.attr="disabled">
                        {{ __('web.two_factor_authentication_form_show_recovery_codes_button') }}
                    </x-button>
                @endif

                @if ($showingConfirmation)
                    <x-button type="button" variant="secondary" wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                        {{ __('web.two_factor_authentication_form_cancel_button') }}
                    </x-button>
                @else
                    <x-button type="button" variant="secondary" color="blue" wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                        {{ __('web.two_factor_authentication_form_disable_button') }}
                    </x-button>
                @endif

            @endif
        </div>
    </div>
</div>
