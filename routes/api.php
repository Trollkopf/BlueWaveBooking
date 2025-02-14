<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HammockController;
use App\Http\Controllers\HammockSpaceController;
use App\Http\Controllers\SettingController;

Route::apiResource('hammocks', HammockController::class);
Route::apiResource('settings', SettingController::class);
Route::apiResource('bookings', BookingController::class);
Route::get('/hammock-spaces', [HammockSpaceController::class, 'index']);
Route::put('/hammock-spaces/{id}', [HammockSpaceController::class, 'update']);
Route::post('/hammock-spaces', [HammockSpaceController::class, 'store']);
