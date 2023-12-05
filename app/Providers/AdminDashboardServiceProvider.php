<?php

namespace App\Providers;

use App\Enums\Permission;
use App\Events\ServingAdminDashboard;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminDashboardServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot() : void
    {
        $this->registerGate();
        $this->registerPolicies();
    }

    protected function registerGate() : void
    {
        Gate::define('viewAdminDashboard', function (User $user) {
            return $user->hasPermission(Permission::viewAdminDashboard);
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
