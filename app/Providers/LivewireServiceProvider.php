<?php

namespace App\Providers;

use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Http\Livewire\Global\Quality\CreateQualitySlideOver;
use App\Http\Livewire\Global\Quality\QualitiesOverviewPage;
use App\Http\Livewire\Global\Shelter\CreateShelterSlideOver;
use App\Http\Livewire\Global\Shelter\DeleteShelterModal;
use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use App\Http\Livewire\Global\Shelter\UpdateShelterSlideOver;
use App\Http\Livewire\Shelter\Admin\AdminsOverviewPage;
use App\Http\Livewire\Shelter\Admin\CreateAdminSlideOver;
use App\Http\Livewire\Shelter\Animal\AnimalDetailPage;
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
        'global.delete-shelter-modal' => DeleteShelterModal::class,

        'global.qualities-overview-page' => QualitiesOverviewPage::class,
        'global.create-quality-slide-over' => CreateQualitySlideOver::class,

        'shelter.animals-overview-page' => AnimalsOverviewPage::class,
        'shelter.create-animal-slide-over' => CreateAnimalSlideOver::class,
        'shelter.update-animal-slide-over' => UpdateAnimalSlideOver::class,
        'shelter.delete-animal-modal' => DeleteAnimalModal::class,
        'shelter.animal-detail-page' => AnimalDetailPage::class,

        'shelter.detail-page' => ShelterDetailPage::class,

        'shelter.admins-overview-page' => AdminsOverviewPage::class,
        'shelter.create-admin-slide-over' => CreateAdminSlideOver::class,
    ];

    public function boot() : void
    {
        foreach ($this->components as $alias => $component) {
            Livewire::component($alias, $component);
        }
    }
}
