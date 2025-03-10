<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hammock;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $weekAgo = now()->subWeek()->toDateString();
        $monthAgo = now()->subMonth()->toDateString();

        return response()->json([
            'totals' => [
                'today' => [
                    'reservations' => Booking::whereDate('date', $today)->count(),
                    'available' => Hammock::where('status', 'available')->count(),
                    'users' => User::whereDate('created_at', $today)->count(),
                ],
                'week' => [
                    'reservations' => Booking::whereBetween('date', [$weekAgo, $today])->count(),
                    'available' => Hammock::where('status', 'available')->count(),
                    'users' => User::whereBetween('created_at', [$weekAgo, now()])->count(),
                ],
                'month' => [
                    'reservations' => Booking::whereBetween('date', [$monthAgo, $today])->count(),
                    'available' => Hammock::where('status', 'available')->count(),
                    'users' => User::whereBetween('created_at', [$monthAgo, now()])->count(),
                ],
            ],
            'recentReservations' => Booking::latest()->take(5)->get(['user_id', 'date', 'time_slot'])
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
