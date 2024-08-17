<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\ShelterAnimalController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\ShelterTimeslotController;
use App\Http\Controllers\TimeslotUserController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [RegisterUserController::class, 'register']);
Route::post('login', [LoginUserController::class, 'authenticate']);

Route::middleware('auth:api')->group(function () {
    Route::get('user/current', [CurrentUserController::class, 'show']);
    Route::patch('user', [CurrentUserController::class, 'update']);
    Route::patch('user/password', [CurrentUserController::class, 'updatePassword']);

    Route::get('user/timeslots', [TimeslotUserController::class, 'index']);

    Route::get('animals', [AnimalController::class, 'index']);

    Route::get('shelters', [ShelterController::class, 'index']);
    Route::get('shelters/timeslots', [ShelterTimeslotController::class, 'index']);
    Route::get('shelter/{shelter}/animals', [ShelterAnimalController::class, 'index']);

    Route::get('types', [TypeController::class, 'index']);

    Route::delete('timeslot/user', [TimeslotUserController::class, 'delete']);

    Route::post('logout', [LogoutController::class, 'store']);
});
