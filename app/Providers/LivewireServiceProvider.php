<?php

namespace App\Providers;

use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Http\Livewire\Global\Quality\CreateQualitySlideOver;
use App\Http\Livewire\Global\Quality\DeleteQualityModal;
use App\Http\Livewire\Global\Quality\QualitiesOverviewPage;
use App\Http\Livewire\Global\Quality\UpdateQualitySlideOver;
use App\Http\Livewire\Global\Shelter\CreateShelterSlideOver;
use App\Http\Livewire\Global\Shelter\DeleteShelterModal;
use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use App\Http\Livewire\Global\Shelter\UpdateShelterSlideOver;
use App\Http\Livewire\Shelter\Admin\AdminsOverviewPage;
use App\Http\Livewire\Shelter\Admin\CreateAdminSlideOver;
use App\Http\Livewire\Shelter\Admin\DeleteAdminModal;
use App\Http\Livewire\Shelter\Admin\UpdateAdminSlideOver;
use App\Http\Livewire\Shelter\Animal\AnimalDetailPage;
use App\Http\Livewire\Shelter\Animal\AnimalsOverviewPage;
use App\Http\Livewire\Shelter\Animal\CreateAnimalSlideOver;
use App\Http\Livewire\Shelter\Animal\DeleteAnimalModal;
use App\Http\Livewire\Shelter\Animal\UpdateAnimalQualitiesSlideOver;
use App\Http\Livewire\Shelter\Animal\UpdateAnimalSlideOver;
use App\Http\Livewire\Shelter\OpeningPeriod\CreateOpeningPeriodSlideOver;
use App\Http\Livewire\Shelter\OpeningPeriod\OpeningPeriodsOverviewPage;
use App\Http\Livewire\Shelter\OpeningPeriod\UpdateOpeningPeriodSlideOver;
use App\Http\Livewire\Shelter\ShelterDetailPage;
use App\Http\Livewire\Shelter\Timeslot\CreateTimeslotSlideOver;
use App\Http\Livewire\Shelter\Timeslot\DeleteTimeslotModal;
use App\Http\Livewire\Shelter\Timeslot\UpdateTimeslotSlideOver;
use App\Http\Livewire\Shelter\Volunteer\TimeslotsOverviewPage;
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
        'global.update-quality-slide-over' => UpdateQualitySlideOver::class,
        'global.delete-quality-modal' => DeleteQualityModal::class,

        'shelter.animals-overview-page' => AnimalsOverviewPage::class,
        'shelter.create-animal-slide-over' => CreateAnimalSlideOver::class,
        'shelter.update-animal-qualities-slide-over' => UpdateAnimalQualitiesSlideOver::class,
        'shelter.update-animal-slide-over' => UpdateAnimalSlideOver::class,
        'shelter.delete-animal-modal' => DeleteAnimalModal::class,
        'shelter.animal-detail-page' => AnimalDetailPage::class,

        'shelter.timeslots-overview-page' => TimeslotsOverviewPage::class,
        'shelter.create-timeslot-slide-over' => CreateTimeslotSlideOver::class,
        'shelter.update-timeslot-slide-over' => UpdateTimeslotSlideOver::class,
        'shelter.delete-timeslot-modal' => DeleteTimeslotModal::class,

        'shelter.detail-page' => ShelterDetailPage::class,

        'shelter.opening-periods-overview-page' => OpeningPeriodsOverviewPage::class,
        'shelter.create-opening-periods-slide-over' => CreateOpeningPeriodSlideOver::class,
        'shelter.update-opening-periods-slide-over' => UpdateOpeningPeriodSlideOver::class,

        'shelter.admins-overview-page' => AdminsOverviewPage::class,
        'shelter.create-admin-slide-over' => CreateAdminSlideOver::class,
        'shelter.update-admin-slide-over' => UpdateAdminSlideOver::class,
        'shelter.delete-admin-modal' => DeleteAdminModal::class,
    ];

    public function boot() : void
    {
        foreach ($this->components as $alias => $component) {
            Livewire::component($alias, $component);
        }
    }
}
