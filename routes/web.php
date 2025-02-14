<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HammockSpaceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// DASHBOARD
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    Route::get('/api/admin/dashboard', [DashboardController::class, 'index']);
});


// HAMACAS
Route::middleware(['auth'])->get('/admin/hammocks', function () {
    return Inertia::render('Admin/Hammocks');
})->name('admin.hammocks');
Route::get('/api/hammock-spaces', [HammockSpaceController::class, 'index']);
Route::put('/api/hammock-spaces/{id}', [HammockSpaceController::class, 'update']);
Route::delete('/api/hammock-spaces/{id}', [HammockSpaceController::class, 'destroy']);
Route::post('/api/hammock-spaces', [HammockSpaceController::class, 'store']);

// AUTH
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
