<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BackofficeController extends Controller
{
    /**
     * Dashboard del usuario.
     */
    public function dashboard(): Response
    {
        $user = Auth::user();

        return Inertia::render('Backoffice/Dashboard', [
            'user' => $user
        ]);
    }

    /**
     * Vista del perfil del usuario.
     */
    public function profile(): Response
    {
        return Inertia::render('Backoffice/Profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Vista de reservas del usuario.
     */
    public function reservations(): Response
    {
        $reservations = Booking::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Backoffice/Reservations', [
            'reservations' => $reservations
        ]);
    }

    public function myReservations()
    {
        $reservations = Booking::where('user_id', Auth::id())
            ->with(['hammock', 'user']) // Relaciona hamaca y usuario
            ->latest()
            ->paginate(10);

         return response()->json(
            $reservations
        );

    }
}
