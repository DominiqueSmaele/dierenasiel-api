<?php

use App\Http\Controllers\LogoutController;
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

Route::middleware('auth:api')->group(function () {
    Route::get('shelters', [ShelterController::class, 'index']);

    Route::post('logout', [LogoutController::class, 'store']);
});
