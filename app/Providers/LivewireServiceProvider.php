<?php

namespace App\Providers;

use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Http\Livewire\Global\Shelter\CreateShelterSlideOver;
use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    public array $components = [
        'global.developers-overview-page' => DevelopersOverviewPage::class,

        'global.shelters-overview-page' => SheltersOverviewPage::class,
        'global.create-shelter-slide-over' => CreateShelterSlideOver::class,
    ];

    public function boot() : void
    {
        foreach ($this->components as $alias => $component) {
            Livewire::component($alias, $component);
        }
    }
}
