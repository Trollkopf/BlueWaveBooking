<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BackofficeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HammockSpaceController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Página de inicio
Route::get('/', fn() => Inertia::render('Welcome/Welcome'));

Route::middleware('auth:sanctum')->get('/api/user', fn(Request $request) => response()->json($request->user()));

// API Hamacas Pública
Route::get('/api/hammock-spaces', [HammockSpaceController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [RegisteredUserController::class, 'getUser']);
    Route::put('/user/update', [RegisteredUserController::class, 'update']);
    Route::get('/my-reservations', [BookingController::class, 'userReservations']);
});

// Middleware para proteger rutas
Route::middleware(['auth'])->group(function () {
    Route::get('/backoffice', fn() => Inertia::render('Backoffice/Dashboard'))->name('backoffice.dashboard');
    Route::get('/backoffice', [BackofficeController::class, 'dashboard'])->name('backoffice.dashboard');
    Route::get('/backoffice/profile', [BackofficeController::class, 'profile'])->name('backoffice.profile');
    Route::get('/backoffice/reservations', [BackofficeController::class, 'reservations'])->name('backoffice.reservations');
    Route::put('/api/user/update', [RegisteredUserController::class, 'update' ])->name('user.update');
    Route::get('/api/my-reservations', [BackofficeController::class, 'myReservations' ])->name('user.reservations');

    Route::middleware([RoleMiddleware::class . ':admin,manager'])->group(function () {
        Route::get('/dashboard', fn() => Inertia::render('Admin/Dashboard'))->name('admin.dashboard');
        Route::get('/api/admin/dashboard', [DashboardController::class, 'index']);
    });

    // Gestión de hamacas
    Route::get('/admin/hammocks', fn() => Inertia::render('Admin/Hammocks'))->name('admin.hammocks');
    Route::prefix('/api/hammock-spaces')->group(function () {
        Route::get('/backoffice', [HammockSpaceController::class, 'indexBackoffice']);
        Route::post('/', [HammockSpaceController::class, 'store']);
        Route::put('/{id}', [HammockSpaceController::class, 'update']);
        Route::delete('/{id}', [HammockSpaceController::class, 'destroy']);
    });

    // Rutas protegidas para administración de gerentes
    Route::get('/admin/managers', fn() => Inertia::render('Admin/Managers'))->name('admin.managers');

    // API para obtener la lista de usuarios con paginación y búsqueda
    Route::get('/api/users', [ManagerController::class, 'index']);

    // API para cambiar el rol de usuario a gerente o viceversa
    Route::put('/api/users/{id}/role', [ManagerController::class, 'updateRole']);

    // Gestión de reservas
    Route::get('/admin/bookings', fn() => Inertia::render('Admin/Bookings'))->name('admin.bookings');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    Route::get('/bookings/today', [BookingController::class, 'today']);
    Route::get('/bookings/upcoming', [BookingController::class, 'upcoming']);
    Route::get('/bookings', [BookingController::class, 'searchByDate']);

    // Configuración
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('/admin/settings', fn() => Inertia::render('Admin/Settings'))->name('admin.settings');

        Route::prefix('/api/settings')->group(function () {
            Route::get('/', [SettingController::class, 'index']); // Obtener configuración
            Route::put('/', [SettingController::class, 'update']); // Guardar configuración
        });
    });

    // Gestión de usuarios
    Route::get('/admin/users', fn() => Inertia::render('Admin/Users'))->name('admin.users');
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

// Rutas reservas
Route::middleware(['auth'])->group(function () {
    Route::get('/api/bookings', [BookingController::class, 'index']);
    Route::get('/api/bookings/{id}', [BookingController::class, 'show']);
    Route::put('/api/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/api/bookings/{id}', [BookingController::class, 'destroy']);
});

// Permitir que usuarios no autenticados hagan reservas
Route::post('/api/bookings', [BookingController::class, 'store']);
require __DIR__ . '/auth.php';
