<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HammockController;
use App\Http\Controllers\SettingController;

Route::apiResource('hammocks', HammockController::class);
Route::apiResource('settings', SettingController::class);
Route::apiResource('bookings', BookingController::class);
