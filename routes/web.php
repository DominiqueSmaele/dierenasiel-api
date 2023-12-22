<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use App\Http\Livewire\Shelter\Animal\AnimalsOverviewPage;
use App\Http\Middleware\DispatchServingAdminDashboardEvent;
use App\Http\Middleware\EnsureUserCanViewAdminDashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth', EnsureUserCanViewAdminDashboard::class, DispatchServingAdminDashboardEvent::class])->group(function () {
    Route::redirect('/settings', '/shelters')->name('global.home');
    Route::redirect('/shelter/{shelter}', '/shelter/{shelter}/animals')->name('shelter.home');

    Route::get('/shelters', SheltersOverviewPage::class)->name('global.shelters-overview');
    Route::get('/developers', DevelopersOverviewPage::class)->name('global.developers-overview');

    Route::get('/shelter/{shelter}/animals', AnimalsOverviewPage::class)->name('shelter.animals-overview');
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle)
        ->middleware([DispatchServingAdminDashboardEvent::class]);
});
