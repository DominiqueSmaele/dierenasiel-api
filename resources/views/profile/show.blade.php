<x-dynamic-component :component="request()->query('companyId') ? 'company-layout' : 'global-layout'" :company="request()->query('companyId') ? \App\Models\Company::find(request()->query('companyId')) : null">
    <h1 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.profile_title') }}</h1>

    <div class="flex flex-col gap-10 divide-y divide-gray-light">
        <div class="mt-10 flex flex-col gap-4">
            <div>
                <x-label>{{ __('web.profile_firstname_label') }}</x-label>
                <p class="text-sm font-medium leading-6">{{ auth()->user()->firstname }}</p>
            </div>

            <div>
                <x-label>{{ __('web.profile_lastname_label') }}</x-label>
                <p class="text-sm font-medium leading-6">{{ auth()->user()->lastname }}</p>
            </div>

            <div>
                <x-label>{{ __('web.profile_email_label') }}</x-label>
                <p class="text-sm font-medium leading-6">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="pt-10">
            <livewire:profile.two-factor-authentication-form />
        </div>
    </div>
</x-dynamic-component>
