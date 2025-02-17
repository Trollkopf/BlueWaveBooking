<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HammockSpaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Página de inicio
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Middleware para proteger rutas de administrador
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
    Route::get('/api/admin/dashboard', [DashboardController::class, 'index']);

    // Gestión de hamacas
    Route::get('/admin/hammocks', function () {
        return Inertia::render('Admin/Hammocks');
    })->name('admin.hammocks');
    Route::prefix('/api/hammock-spaces')->group(function () {
        Route::get('/backoffice', [HammockSpaceController::class, 'indexBackoffice']);
        Route::get('/', [HammockSpaceController::class, 'index']);
        Route::post('/', [HammockSpaceController::class, 'store']);
        Route::put('/{id}', [HammockSpaceController::class, 'update']);
        Route::delete('/{id}', [HammockSpaceController::class, 'destroy']);
    });

    // Gestión de reservas
    Route::get('/admin/bookings', function () {
        return Inertia::render('Admin/Bookings');
    })->name('admin.bookings');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

    // Configuración
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('/admin/settings', function () {
            return Inertia::render('Admin/Settings');
        })->name('admin.settings');

        Route::prefix('/api/settings')->group(function () {
            Route::get('/', [SettingController::class, 'index']); // Obtener configuración
            Route::put('/', [SettingController::class, 'update']); // Guardar configuración
        });
    });

    // Gestión de usuarios
    Route::get('/admin/users', function () {
        return Inertia::render('Admin/Users');
    })->name('admin.users');
    Route::prefix('/api/users')->group(function () {
        Route::get('/', [RegisteredUserController::class, 'index']);
        Route::get('/{id}/reservations', [RegisteredUserController::class, 'reservations']);
        Route::delete('/{id}', [RegisteredUserController::class, 'destroy']);
    });
});

// Rutas de perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
