<div>
    <div class="flex items-start justify-between">
        <div class="flex gap-2">
            @php
                $image = $shelter?->getMedia('image')->first();
            @endphp

            @if ($image)
                <img class="mt-3 h-32 4xl:h-48" src="{{ $image->getAvailableFullUrl(['small', 'medium']) }}" />
            @else
                <img class="mt-3 h-32" src="{{ asset('storage/images/shelter/logo-placeholder.png') }}" />
            @endif
        </div>

        @can('update', $shelter)
            <x-button leading-icon="pencil" wire:click.prevent="$dispatch('slide-over.open', {component: 'global.update-shelter-slide-over', arguments: {'shelterId': {{ $shelter->id }}}})">
                {{ __('web.shelter_detail_page_edit_button') }}
            </x-button>
        @endcan
    </div>

    <div class="mt-10">
        <h2 class="font-highlight-sans text-4xl font-semibold leading-7">{{ $shelter->name }}</h2>
    </div>

    <div class="flex">
        <div class="mt-10 w-1/2 flex-col gap-20">
            <div class="flex gap-20">
                <div>
                    <div class="flex">
                        <x-label class="bg-blue-base p-1.5 text-white">{{ __('web.shelter_detail_page_email_label') }}</x-label>
                    </div>
                    <p class="mt-1 text-sm font-medium leading-6">{{ $shelter->email }}</p>
                </div>

                <div>
                    <div class="flex">
                        <x-label class="bg-blue-base p-1.5 text-white">{{ __('web.shelter_detail_page_phone_label') }}</x-label>
                    </div>

                    <p class="mt-1 text-sm font-medium leading-6">{{ $shelter->phone }}</p>
                </div>
            </div>

            <div class="mt-5 flex flex-col gap-6">
                <div>
                    <div class="flex">
                        <x-label class="bg-blue-base p-1.5 text-white">{{ __('web.shelter_detail_page_address_label') }}</x-label>
                    </div>

                    <p class="mt-1 text-sm font-medium leading-6">
                        <span class="block">{{ $shelter->address->street }} {{ $shelter->address->number }}{{ $shelter->address->box_number }}</span>
                        <span class="block">{{ $shelter->address->zipcode }} {{ $shelter->address->city }},</span>
                        <span class="block">{{ $shelter->address->country->getName() }}</span>
                    </p>
                </div>
            </div>

            <div class="mt-3 flex flex-col gap-6">
                <div>
                    <div class="mt-3 flex gap-3">
                        @foreach (['facebook', 'instagram', 'tiktok'] as $platform)
                            @if ($shelter->$platform)
                                @php
                                    switch ($platform) {
                                        case 'instagram':
                                            $link = "https://www.instagram.com/{$shelter->$platform}";
                                            break;
                                        case 'tiktok':
                                            $link = "https://www.tiktok.com/@{$shelter->$platform}";
                                            break;
                                        default:
                                            $link = $shelter->$platform;
                                    }
                                @endphp

                                <x-button class="!p-1.5" leading-icon="{{ $platform }}" variant="secondary" href="{{ $link }}" target="_blank">
                                    {{ __('web.shelter_detail_page_social_media_' . $platform) }}
                                </x-button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
