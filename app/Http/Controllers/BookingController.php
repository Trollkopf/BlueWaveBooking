<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\HammockSpace;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Listar reservas con paginación.
     */
    public function index(Request $request)
    {
        $reservations = Booking::with(['user', 'hammock'])
            ->latest()
            ->paginate(10);

        return response()->json($reservations);
    }

    /**
     * Crear una nueva reserva.
     */
    public function store(Request $request)
    {

        // return ($request);
        // Validar la solicitud
        $this->validateBookingRequest($request);

        // Obtener usuario o crear uno temporal
        $user = $this->getOrCreateUser($request);

        // Verificar disponibilidad de la hamaca
        $this->checkHammockAvailability($request);

        // Obtener el precio de la reserva
        $price = $this->getPrice($request->time_slot);

        // Determinar estado de la reserva
        $status = $this->determineBookingStatus($user);

        // Crear la reserva
        $booking = Booking::create([
            'user_id' => $user->id,
            'hammock_id' => $request->hammock_id,
            'date' => $request->date,
            'time_slot' => $request->type,
            'comment' => $request->comment ?? null,
            'status' => $status,
            'type'=> $request->type,
            'price' => $request->price,
            'name' =>$request->name,
            'email' =>$request->email,
            'phone'=>$request->phone

        ]);

        return response()->json([
            'message' => 'Reserva creada con éxito',
            'booking' => $booking,
            'needs_payment' => $user->role !== 'manager',
        ], 201);
    }

    /**
     * Mostrar una reserva específica.
     */
    public function show(Booking $booking)
    {
        return response()->json($booking);
    }

    /**
     * Actualizar una reserva existente.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'date' => 'sometimes|date',
            'time_slot' => 'sometimes|in:morning,afternoon,full',
            'status' => 'sometimes|in:pending,confirmed,cancelled',
            'comment' => 'nullable|string|max:500',
        ]);

        $booking->update($request->all());
        return response()->json($booking);
    }

    /**
     * Eliminar una reserva.
     */
    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return response()->json(['message' => 'Reserva eliminada']);
    }

    /**
     * Validar la solicitud de reserva.
     */
    private function validateBookingRequest(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'hammock_id' => 'required|exists:hammock_spaces,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|in:morning,afternoon,full',
            'name' => $user ? 'nullable' : 'required|string|min:3',
            'email' => $user ? 'nullable' : 'required|email',
            'phone' => $user ? 'nullable' : 'required|string|min:9',
            'comment' => 'nullable|string|max:500',
        ]);
    }

    /**
     * Obtener usuario autenticado o crear uno temporal si no está autenticado.
     */
    private function getOrCreateUser(Request $request)
    {
        if ($user = Auth::user()) {
            return $user;
        }

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt(uniqid()), // Generar contraseña aleatoria
            'role' => 'guest',
        ]);
    }

    /**
     * Verificar si la hamaca ya está reservada en la fecha y franja horaria seleccionada.
     */
    private function checkHammockAvailability(Request $request)
    {
        $exists = Booking::where('hammock_id', $request->hammock_id)
            ->where('date', $request->date)
            ->where('time_slot', $request->time_slot)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'error' => 'Esta hamaca ya está reservada en este horario.',
            ]);
        }
    }

    /**
     * Obtener el precio de la reserva según la franja horaria.
     */
    private function getPrice(string $timeSlot)
    {
        $settings = Setting::first();

        return match ($timeSlot) {
            'morning' => $settings->price_morning ?? 0,
            'afternoon' => $settings->price_afternoon ?? 0,
            'full' => $settings->price_full ?? 0,
            default => 0
        };
    }

    /**
     * Determinar el estado de la reserva según el tipo de usuario.
     */
    private function determineBookingStatus(User $user)
    {
        return $user->role === 'manager' ? 'confirmed' : 'pending';
    }
}
