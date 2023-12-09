<?php

namespace App\Providers;

use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    public array $components = [
        'global.shelters-overview-page' => SheltersOverviewPage::class,
        'global.developers-overview-page' => DevelopersOverviewPage::class,
    ];

    public function boot() : void
    {
        foreach ($this->components as $alias => $component) {
            Livewire::component($alias, $component);
        }
    }
}
