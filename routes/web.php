<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Http\Livewire\Global\Quality\QualitiesOverviewPage;
use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use App\Http\Livewire\Shelter\Admin\AdminsOverviewPage;
use App\Http\Livewire\Shelter\Animal\AnimalDetailPage;
use App\Http\Livewire\Shelter\Animal\AnimalsOverviewPage;
use App\Http\Livewire\Shelter\ShelterDetailPage;
use App\Http\Livewire\Shelter\Timeslot\TimeslotsOverviewPage;
use App\Http\Livewire\User\DeleteUserPage;
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
Route::get('/user/delete', DeleteUserPage::class)->name('user.delete-page');

Route::middleware(['auth', EnsureUserCanViewAdminDashboard::class, DispatchServingAdminDashboardEvent::class])->group(function () {
    Route::redirect('/settings', '/shelters')->name('global.home');
    Route::redirect('/shelter/{shelter}', '/shelter/{shelter}/animals')->name('shelter.home');

    Route::get('/shelters', SheltersOverviewPage::class)->name('global.shelters-overview');
    Route::get('/developers', DevelopersOverviewPage::class)->name('global.developers-overview');
    Route::get('/qualities', QualitiesOverviewPage::class)->name('global.qualities-overview');

    Route::get('/shelter/{shelter}/animals', AnimalsOverviewPage::class)->name('shelter.animals-overview');
    Route::get('/shelter/{shelter}/volunteers', TimeslotsOverviewPage::class)->name('shelter.volunteers-overview');
    Route::get('/shelter/{shelter}/information', ShelterDetailPage::class)->name('shelter.detail');

    Route::get('/animal/{animal}', AnimalDetailPage::class)->name('shelter.animal-detail');

    Route::get('/shelter/{shelter}/admins', AdminsOverviewPage::class)->name('shelter.admins-overview');
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle)
        ->middleware([DispatchServingAdminDashboardEvent::class]);
});
