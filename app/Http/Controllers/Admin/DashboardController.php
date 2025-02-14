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
        return response()->json([
            'totalBookings' => Booking::count(),
            'availableHammocks' => Hammock::where('status', 'available')->count(),
            'totalUsers' => User::count(),
            'recentBookings' => Booking::latest()->take(5)->get(),
            'hammocksStatus' => Hammock::select('id', 'name', 'status')->get()
        ]);
    }
}
