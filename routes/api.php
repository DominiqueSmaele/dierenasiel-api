<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ShelterAnimalController;
use App\Http\Controllers\ShelterController;
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

Route::post('login', [LoginController::class, 'authenticate']);

Route::middleware('auth:api')->group(function () {
    Route::get('user/current', [CurrentUserController::class, 'show']);

    Route::get('shelters', [ShelterController::class, 'index']);

    Route::get('shelter/{shelter}/animals', [ShelterAnimalController::class, 'index']);

    Route::get('animals', [AnimalController::class, 'index']);

    Route::post('logout', [LogoutController::class, 'store']);
});
