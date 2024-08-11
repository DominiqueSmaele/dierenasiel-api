<div>
    <div class="flex items-center justify-between">
        <h2 class="font-highlight-sans text-2xl font-semibold leading-7">{{ __('web.shelter_detail_page_title', ['name' => $shelter->name]) }}</h2>

        @can('update', $shelter)
            <x-button leading-icon="pencil" wire:click.prevent="$dispatch('slide-over.open', {component: 'global.update-shelter-slide-over', arguments: {'shelterId': {{ $shelter->id }}}})">
                {{ __('web.shelter_detail_page_edit_button') }}
            </x-button>
        @endcan
    </div>

    <div class="mt-12 flex gap-8">
        <div class="flex w-1/5 flex-col items-center justify-between border border-blue-base bg-blue-lightest pt-8 4xl:w-1/6">
            <div class="flex w-full flex-col items-center pb-8">
                @php
                    $image = $shelter?->getMedia('image')->first();
                @endphp

                @if ($image)
                    <img class="h-36" src="{{ $image->getAvailableFullUrl(['small', 'medium']) }}" />
                @else
                    <img class="h-36" src="{{ asset('storage/images/shelter/logo-placeholder.png') }}" />
                @endif

                <h2 class="mt-5 text-center font-highlight-sans text-3xl font-semibold leading-7">{{ $shelter->name }}</h2>
            </div>

            <div class="w-full">
                <div class="flex items-center gap-2 bg-blue-lighter p-4">
                    <span class="ml-4 mr-2">
                        <x-dynamic-component component='icon.email' class="h-5 w-5 text-blue-base" />
                    </span>
                    <p class="text-sm font-medium leading-6">{{ $shelter->email }}</p>
                </div>

                <div class="flex items-center gap-2 p-4">
                    <span class="ml-4 mr-2">
                        <x-dynamic-component component='icon.phone' class="h-5 w-5 text-blue-base" />
                    </span>
                    <p class="text-sm font-medium leading-6">{{ $shelter->phone->formatInternational() }}</p>
                </div>

                <div class="flex gap-2 bg-blue-lighter p-4">
                    <span class="ml-4 mr-2">
                        <x-dynamic-component component='icon.address' class="h-5 w-5 text-blue-base" />
                    </span>
                    <p class="text-sm font-medium">
                        <span class="block">{{ $shelter->address->street }} {{ $shelter->address->number }}{{ $shelter->address->box_number }}</span>
                        <span class="block">{{ $shelter->address->zipcode }} {{ $shelter->address->city }},</span>
                        <span class="block">{{ $shelter->address->country->getName() }}</span>
                    </p>
                </div>

                @if ($shelter->facebook || $shelter->instagram || $shelter->tiktok)
                    <div class="flex justify-center gap-3 py-4">
                        @foreach (['facebook', 'instagram', 'tiktok'] as $platform)
                            @if ($shelter->$platform)
                                @switch($platform)
                                    @case('instagram')
                                        @php $link = "https://www.instagram.com/{$shelter->$platform}" @endphp
                                    @break

                                    @case('tiktok')
                                        @php $link = "https://www.tiktok.com/@{$shelter->$platform}" @endphp
                                    @break

                                    @default
                                        @php $link = $shelter->$platform @endphp
                                @endswitch

                                <a href="{{ $link }}" target="_blank">
                                    <span>
                                        <x-dynamic-component component="{{ 'icon.' . $platform }}" class="h-5 w-5" />
                                    </span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <livewire:shelter.opening-periods-overview-page :shelter='$shelter' />
    </div>
</div>
