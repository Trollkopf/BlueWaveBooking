<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(Setting::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required|string'
        ]);

        $setting = Setting::create($request->all());
        return response()->json($setting, 201);
    }

    public function show(Setting $setting)
    {
        return response()->json($setting);
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'key' => 'sometimes|string|unique:settings,key,' . $setting->id,
            'value' => 'sometimes|string'
        ]);

        $setting->update($request->all());
        return response()->json($setting);
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        return response()->json(null, 204);
    }
}
