<?php

namespace App\Providers;

use App\Enums\Permission;
use App\Enums\ShelterPermission;
use App\Events\ServingAdminDashboard;
use App\Models\Animal;
use App\Models\OpeningPeriod;
use App\Models\Quality;
use App\Models\Shelter;
use App\Models\User;
use App\Policies\AdminDashboard\AnimalPolicy;
use App\Policies\AdminDashboard\OpeningPeriodPolicy;
use App\Policies\AdminDashboard\QualityPolicy;
use App\Policies\AdminDashboard\ShelterPolicy;
use App\Policies\AdminDashboard\UserPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminDashboardServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Shelter::class => ShelterPolicy::class,
        Animal::class => AnimalPolicy::class,
        Quality::class => QualityPolicy::class,
        OpeningPeriod::class => OpeningPeriodPolicy::class,
    ];

    public function boot() : void
    {
        $this->registerGate();
        $this->registerPolicies();
    }

    protected function registerGate() : void
    {
        Gate::define('viewAdminDashboard', function (User $user) {
            return $user->hasPermission(Permission::manageAllShelters)
            || $user->hasPermission(ShelterPermission::manageShelter, $user->shelter_id);
        });
    }

    protected function registerPolicies() : void
    {
        Event::listen(function (ServingAdminDashboard $event) {
            foreach ($this->policies as $key => $value) {
                Gate::policy($key, $value);
            }
        });
    }
}
