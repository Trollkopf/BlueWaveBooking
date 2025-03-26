<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{


    public function index()
    {
        $today = now()->toDateString();
        $weekAgo = now()->subWeek()->toDateString();
        $monthAgo = now()->subMonth()->toDateString();

        $getIncome = fn($start, $end) =>
            Booking::whereBetween('date', [$start, $end])
                ->where('status', 'confirmed') // Solo reservas confirmadas
                ->sum('price');

        return response()->json([
            'totals' => [
                'today' => [
                    'reservations' => Booking::whereDate('date', $today)->count(),
                    'income' => Booking::whereDate('date', $today)->where('status', 'confirmed')->sum('price'),
                    'users' => User::whereDate('created_at', $today)->count(),
                ],
                'week' => [
                    'reservations' => Booking::whereBetween('date', [$weekAgo, $today])->count(),
                    'income' => $getIncome($weekAgo, $today),
                    'users' => User::whereBetween('created_at', [$weekAgo, now()])->count(),
                ],
                'month' => [
                    'reservations' => Booking::whereBetween('date', [$monthAgo, $today])->count(),
                    'income' => $getIncome($monthAgo, $today),
                    'users' => User::whereBetween('created_at', [$monthAgo, now()])->count(),
                ],
            ],
            'recentReservations' => Booking::latest()->take(5)->get(['id', 'user_id', 'date', 'time_slot'])
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'user' => $booking->user ? $booking->user->name : 'Anónimo',
                        'date' => $booking->date,
                        'time_slot' => $booking->time_slot,
                    ];
                }),
        ]);
    }

}
