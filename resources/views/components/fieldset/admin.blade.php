<div class="flex flex-1 flex-col gap-4">
    <div class="flex gap-4">
        <x-input.group :error="$errors->first('user.firstname')" class="basis-1/2">
            <x-label>{{ __('web.admin_fieldset_firstname_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
            <x-input class="mt-1" wire:model="user.firstname" :placeholder="__('web.admin_fieldset_firstname_placeholder')" required autofocus />
        </x-input.group>

        <x-input.group :error="$errors->first('user.lastname')" class="basis-1/2">
            <x-label>{{ __('web.admin_fieldset_lastname_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
            <x-input class="mt-1" wire:model="user.lastname" :placeholder="__('web.admin_fieldset_lastname_placeholder')" required />
        </x-input.group>
    </div>

    <x-input.group class="mt-1" :error="$errors->first('user.email')">
        <x-label>{{ __('web.admin_fieldset_email_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input class="mt-1" wire:model="user.email" :placeholder="__('web.admin_fieldset_email_placeholder')" required />
    </x-input.group>

    <x-input.group class="mt-1" :error="$errors->first('password')">
        <x-label>{{ $this->user->id ? __('web.admin_fieldset_new_password_label') : __('web.admin_fieldset_password_label') }} @if (!$this->user->id)
                <span class="text-red-base">{{ __('web.required_label') }}</span>
            @endif
        </x-label>
        <x-input class="mt-1" type="password" wire:model="password" :placeholder="$this->user->id ? __('web.admin_fieldset_new_password_placeholder') : __('web.admin_fieldset_password_placeholder')" />

        <div class="mt-3">
            <ul class="list-disc pl-5 text-sm text-black">
                <li>
                    <p>{{ __('web.create_admin_slide_over_password_min_8') }}</p>
                </li>
                <li>
                    <p>{{ __('web.create_admin_slide_over_password_number') }}</p>
                </li>
                <li>
                    <p>{{ __('web.create_admin_slide_over_password_uppercase') }}</p>
                </li>
                <li>
                    <p>{{ __('web.create_admin_slide_over_password_lowercase') }}</p>
                </li>
            </ul>
        </div>
    </x-input.group>

    <x-input.group class="mt-1" :error="$errors->first('passwordRepeat')">
        <x-label>{{ $this->user->id ? __('web.admin_fieldset_new_password_repeat_label') : __('web.admin_fieldset_password_repeat_label') }} @if (!$this->user->id)
                <span class="text-red-base">{{ __('web.required_label') }}</span>
            @endif
        </x-label>
        <x-input class="mt-1" type="password" wire:model="passwordRepeat" :placeholder="$this->user->id ? __('web.admin_fieldset_new_password_repeat_placeholder') : __('web.admin_fieldset_password_repeat_placeholder')" />
    </x-input.group>
</div>
