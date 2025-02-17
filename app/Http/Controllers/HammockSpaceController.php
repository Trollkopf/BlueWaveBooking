<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HammockSpace;
use App\Models\Setting;
use Illuminate\Http\Request;

class HammockSpaceController extends Controller
{
    public function indexBackoffice() {
        return response()->json(HammockSpace::all());
    }

    public function index(Request $request)
    {
        $selectedDate = $request->query('date'); // Obtener la fecha de la query string

        // Verificar si el servicio está cerrado en esta fecha
        $settings = Setting::first();
        $isClosed = $settings && $settings->closed_from && $settings->closed_to &&
            $selectedDate >= $settings->closed_from && $selectedDate <= $settings->closed_to;

        // Obtener todas las hamacas
        $hammocks = HammockSpace::all()->map(function ($hammock) use ($selectedDate) {
            // Obtener reservas de esta hamaca en la fecha seleccionada
            $reservations = Booking::where('hammock_id', $hammock->id)
                ->where('date', $selectedDate)
                ->pluck('type')
                ->toArray();

            // Retornar la hamaca con su disponibilidad
            return [
                'id' => $hammock->id,
                'row' => $hammock->row,
                'col' => $hammock->col,
                'hammocks' => $hammock->hammocks,
                'reservations' => $reservations, // Tipos de reservas en esa fecha (morning, afternoon, full)
            ];
        });

        return response()->json([
            'isClosed' => $isClosed,
            'hammocks' => $hammocks,
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
