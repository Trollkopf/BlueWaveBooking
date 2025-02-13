<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(Booking::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'hammock_id' => 'required|exists:hammocks,id',
            'date' => 'required|date',
            'time_slot' => 'required|in:morning,afternoon,full_day',
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $booking = Booking::create($request->all());
        return response()->json($booking, 201);
    }

    public function show(Booking $booking)
    {
        return response()->json($booking);
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'hammock_id' => 'sometimes|exists:hammocks,id',
            'date' => 'sometimes|date',
            'time_slot' => 'sometimes|in:morning,afternoon,full_day',
            'status' => 'sometimes|in:pending,confirmed,cancelled'
        ]);

        $booking->update($request->all());
        return response()->json($booking);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }
}
