<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
     /**
     * Listar usuarios con paginación y búsqueda opcional
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $users = User::where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->paginate(10);

        return response()->json($users);
    }

    /**
     * Actualizar el rol del usuario (gerente o usuario normal)
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,manager',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return response()->json(['message' => 'Rol actualizado con éxito']);
    }
}


