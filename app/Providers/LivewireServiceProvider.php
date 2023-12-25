<?php

namespace App\Providers;

use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Http\Livewire\Global\Shelter\CreateShelterSlideOver;
use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use App\Http\Livewire\Global\Shelter\UpdateShelterSlideOver;
use App\Http\Livewire\Shelter\Animal\AnimalsOverviewPage;
use App\Http\Livewire\Shelter\Animal\CreateAnimalSlideOver;
use App\Http\Livewire\Shelter\Animal\DeleteAnimalModal;
use App\Http\Livewire\Shelter\Animal\UpdateAnimalSlideOver;
use App\Http\Livewire\Shelter\ShelterDetailPage;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    public array $components = [
        'global.developers-overview-page' => DevelopersOverviewPage::class,

        'global.shelters-overview-page' => SheltersOverviewPage::class,
        'global.create-shelter-slide-over' => CreateShelterSlideOver::class,
        'global.update-shelter-slide-over' => UpdateShelterSlideOver::class,

        'shelter.animals-overview-page' => AnimalsOverviewPage::class,
        'shelter.create-animal-slide-over' => CreateAnimalSlideOver::class,
        'shelter.update-animal-slide-over' => UpdateAnimalSlideOver::class,
        'shelter.delete-animal-modal' => DeleteAnimalModal::class,

        'shelter.detail-page' => ShelterDetailPage::class,
    ];

    public function boot() : void
    {
        foreach ($this->components as $alias => $component) {
            Livewire::component($alias, $component);
        }
    }
}
