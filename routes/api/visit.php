<?php

use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

Route::controller(VisitController::class)->group(function () {
    Route::get('/', 'index');
});