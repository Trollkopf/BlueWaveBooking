<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HammockSpace;
use App\Models\Setting;
use Illuminate\Http\Request;

class HammockSpaceController extends Controller
{
    public function indexBackoffice()
    {
        return response()->json(HammockSpace::all());
    }

    public function index(Request $request)
    {
        $selectedDate = $request->query('date'); // Obtener la fecha de la query string

        // Obtener configuración de precios y fechas de cierre
        $settings = Setting::first();

        $isClosed = $settings && $settings->closed_from && $settings->closed_to &&
            $selectedDate >= $settings->closed_from && $selectedDate <= $settings->closed_to;

        // Obtener todas las hamacas
        $hammocks = HammockSpace::all()->map(function ($hammock) use ($selectedDate) {
            // Obtener reservas de esta hamaca en la fecha seleccionada
            $reservations = Booking::where('hammock_id', $hammock->id)
                ->where('date', $selectedDate)
                ->pluck('time_slot')
                ->toArray();

            return [
                'id' => $hammock->id,
                'row' => $hammock->row,
                'col' => $hammock->col,
                'hammocks' => $hammock->hammocks,
                'reservations' => $reservations, // no los transformes
            ];

        });

        return response()->json([
            'isClosed' => $isClosed,
            'hammocks' => $hammocks,
            'prices' => [
                'morning' => $settings->price_morning ?? 0,
                'afternoon' => $settings->price_afternoon ?? 0,
                'full' => $settings->price_full_day ?? 0,
            ],
        ]);
    }



    public function update(Request $request, $id)
    {
        $space = HammockSpace::findOrFail($id);
        $space->update($request->only('hammocks'));
        return response()->json(['message' => 'Updated successfully']);
    }

    public function store(Request $request)
    {
        HammockSpace::updateOrCreate(
            ['row' => $request->row, 'col' => $request->col],
            ['hammocks' => $request->hammocks]
        );
        return response()->json(['message' => 'Saved successfully']);
    }

    public function destroy($id)
    {
        $space = HammockSpace::find($id);
        if ($space) {
            $space->delete();
            return response()->json(['message' => 'Deleted successfully']);
        }
        return response()->json(['error' => 'Not Found'], 404);
    }
}
