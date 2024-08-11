<?php

namespace App\Providers;

use App\Http\Middleware\EnforceJson;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot() : void
    {
        $this->configurePassport();
    }

    protected function configurePassport() : void
    {
        Route::prefix(config('passport.path', 'oauth'))
            ->post('/token', [AccessTokenController::class, 'issueToken'])
            ->name('passport.token')
            ->middleware(['throttle:300,1', EnforceJson::class]);

        Passport::tokensExpireIn(now()->addMinutes(config('passport.access_token_expire_minutes')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('passport.refresh_token_expire_minutes')));
    }
}
