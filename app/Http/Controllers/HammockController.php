<?php

namespace App\Http\Controllers;

use App\Models\Hammock;
use Illuminate\Http\Request;

class HammockController extends Controller
{
    public function index()
    {
        return response()->json(Hammock::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,reserved,unavailable',
            'location' => 'nullable|json'
        ]);

        $hammock = Hammock::create($request->all());
        return response()->json($hammock, 201);
    }

    public function show(Hammock $hammock)
    {
        return response()->json($hammock);
    }

    public function update(Request $request, Hammock $hammock)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:available,reserved,unavailable',
            'location' => 'sometimes|json'
        ]);

        $hammock->update($request->all());
        return response()->json($hammock);
    }

    public function destroy(Hammock $hammock)
    {
        $hammock->delete();
        return response()->json(null, 204);
    }
}
