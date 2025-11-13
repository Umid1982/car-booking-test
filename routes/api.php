<?php

use App\Http\Controllers\Api\CarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/cars/available', [CarController::class, 'available']);
});
