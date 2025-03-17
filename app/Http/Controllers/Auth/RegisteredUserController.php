<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect($user->role === 'admin' || $user->role === 'manager' ? route('admin.dashboard') : route('backoffice.dashboard'));
    }



    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::where('role', '!=', 'admin') // Excluir admins
            ->where('id', '!=', auth()->id()) // Excluir usuario autenticado
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(5);

        return response()->json($users);
    }


    // Obtener historial de reservas de un usuario
    public function reservations($id)
    {
        $reservations = Booking::where('user_id', $id)->get();
        return response()->json($reservations);
    }

    // Eliminar usuario y sus reservas
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->reservations()->delete(); // Borra las reservas del usuario
        $user->delete(); // Borra el usuario
        return response()->json(['message' => 'Usuario eliminado']);
    }
}
