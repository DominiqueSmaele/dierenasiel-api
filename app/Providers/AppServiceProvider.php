<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        $this->registerMacros();
        $this->configureEloquent();
    }

    public function register() : void
    {
        $this->app->singleton(Generator::class, function () {
            return tap(Factory::create(), fn ($faker) => $faker->addProvider(new CustomProvider($faker)));
        });

        $this->app->bind(Generator::class . ':' . config('app.faker_locale'), Generator::class);
    }

    protected function registerMacros() : void
    {
        Request::macro('locale', function () {
            $header = $this->header('Accept-Language');
            $firstLocale = head(explode(',', $header));

            return head(explode('_', str_replace('-', '_', $firstLocale)));
        });
    }

    protected function configureEloquent() : void
    {
        Model::shouldBeStrict(true);

        if (! $this->app->environment('local')) {
            Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                // When the environment is set to 'testing', then lazy loading is not really an
                // issue, so we won't do anything with those violations.
                if ($this->app->environment('testing')) {
                    return;
                }

                Log::warning(new LazyLoadingViolationException($model, $relation));
            });
        }
    }
}
