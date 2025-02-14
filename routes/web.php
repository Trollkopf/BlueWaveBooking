<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
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

    // API DASHBOARD
    Route::get('/api/admin/dashboard', [DashboardController::class, 'index']);
});


// HAMACAS
Route::middleware(['auth'])->get('/admin/hammocks', function () {
    return Inertia::render('Admin/Hammocks');
})->name('admin.hammocks');

// API HAMACAS
Route::get('/api/hammock-spaces', [HammockSpaceController::class, 'index']);
Route::put('/api/hammock-spaces/{id}', [HammockSpaceController::class, 'update']);
Route::delete('/api/hammock-spaces/{id}', [HammockSpaceController::class, 'destroy']);
Route::post('/api/hammock-spaces', [HammockSpaceController::class, 'store']);

// BOOKINGS
Route::middleware(['auth'])->get('/admin/bookings', function () {
    return Inertia::render('Admin/Bookings');
})->name('admin.bookings');
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

// USERS
Route::middleware(['auth'])->get('/admin/users', function () {
    return Inertia::render('Admin/Users');
})->name('admin.users');
Route::delete('/bookings/{id}', [RegisteredUserController::class, 'destroy']);

// API USERS
Route::get('/api/users/', [RegisteredUserController::class, 'index']); //
Route::get('/api/users/{id}/reservations', [RegisteredUserController::class, 'reservations']); // Historial de reservas
Route::delete('/api/users/{id}', [RegisteredUserController::class, 'destroy']); // Eliminar usuario

// AUTH
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
