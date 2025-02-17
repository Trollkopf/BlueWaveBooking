<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(Setting::firstOrNew([]));
    }


    public function show(Setting $setting)
    {
        return response()->json($setting);
    }

    public function update(Request $request)
    {
        $settings = Setting::firstOrNew([]);
        $settings->fill($request->only([
            'morning_start',
            'morning_end',
            'afternoon_start',
            'afternoon_end',
            'full_day_start',
            'full_day_end',
            'price_morning',
            'price_afternoon',
            'price_full_day',
            'closed_from',
            'closed_to'
        ]));
        $settings->save();

        return response()->json(['message' => 'Configuración guardada con éxito']);
    }
}
