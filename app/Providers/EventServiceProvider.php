<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot() : void
    {
        $this->registerObservers();
    }

    protected function registerObservers() : void
    {
        User::observe(UserObserver::class);
    }
}
