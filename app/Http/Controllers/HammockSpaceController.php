<?php

namespace App\Http\Controllers;

use App\Models\HammockSpace;
use Illuminate\Http\Request;

class HammockSpaceController extends Controller {
    public function index() {
        return response()->json(HammockSpace::all());
    }

    public function update(Request $request, $id) {
        $space = HammockSpace::findOrFail($id);
        $space->update($request->only('hammocks'));
        return response()->json(['message' => 'Updated successfully']);
    }

    public function store(Request $request) {
        HammockSpace::updateOrCreate(
            ['row' => $request->row, 'col' => $request->col],
            ['hammocks' => $request->hammocks]
        );
        return response()->json(['message' => 'Saved successfully']);
    }

    public function destroy($id) {
        $space = HammockSpace::find($id);
        if ($space) {
            $space->delete();
            return response()->json(['message' => 'Deleted successfully']);
        }
        return response()->json(['error' => 'Not Found'], 404);
    }
}
