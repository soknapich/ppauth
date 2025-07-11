<?php

use App\Http\Controllers\API\AuthenticationController;
use Illuminate\Support\Facades\Route;

// --------------- Register and Login ----------------//
Route::post('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('login', [AuthenticationController::class, 'login'])->name('login');

Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
    // ------------------ Get Data ----------------------//
    Route::middleware(['auth:api'])->group(function () {
        Route::get('get-user', [AuthenticationController::class, 'userInfo']);
        Route::post('logout', [AuthenticationController::class, 'logOut']);
        Route::get('findUser', [AuthenticationController::class, 'findUser']);
    });
});
