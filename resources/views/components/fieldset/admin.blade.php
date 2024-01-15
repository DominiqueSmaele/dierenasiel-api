<div class="flex flex-1 flex-col gap-4">
    <div class="flex gap-2">
        <x-input.group :error="$errors->first('user.firstname')" class="basis-1/2">
            <x-label>{{ __('web.admin_fieldset_firstname_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
            <x-input class="mt-1" wire:model="user.firstname" :placeholder="__('web.admin_fieldset_firstname_placeholder')" autofocus />
        </x-input.group>

        <x-input.group :error="$errors->first('user.lastname')" class="basis-1/2">
            <x-label>{{ __('web.admin_fieldset_lastname_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
            <x-input class="mt-1" wire:model="user.lastname" :placeholder="__('web.admin_fieldset_lastname_placeholder')" />
        </x-input.group>
    </div>

    <x-input.group class="mt-5" :error="$errors->first('user.email')">
        <x-label>{{ __('web.admin_fieldset_email_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input class="mt-1" wire:model="user.email" :placeholder="__('web.admin_fieldset_email_placeholder')" />
    </x-input.group>

    <x-input.group class="mt-5" :error="$errors->first('password')">
        <x-label>{{ __('web.admin_fieldset_password_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input class="mt-1" type="password" wire:model="password" :placeholder="__('web.admin_fieldset_password_placeholder')" />
    </x-input.group>

    <x-input.group class="mt-5" :error="$errors->first('password_repeat')">
        <x-label>{{ __('web.admin_fieldset_password_repeat_label') }} <span class="text-red-base">{{ __('web.required_label') }}</span></x-label>
        <x-input class="mt-1" type="password" wire:model="password_repeat" :placeholder="__('web.admin_fieldset_password_repeat_placeholder')" />
    </x-input.group>
</div>
