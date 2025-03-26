<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\HammockSpace;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener los precios desde la configuración
        $settings = Setting::first();
        if (!$settings) {
            $this->command->error('No hay configuración de precios en la tabla settings.');
            return;
        }

        $timeSlots = ['morning', 'afternoon', 'full'];
        $priceMap = [
            'morning' => $settings->price_morning ?? 0,
            'afternoon' => $settings->price_afternoon ?? 0,
            'full' => $settings->price_full_day ?? 0,
        ];

        // Asegurarse de que haya usuarios y hamacas
        $users = User::all();
        $hammocks = HammockSpace::all();

        if ($users->isEmpty() || $hammocks->isEmpty()) {
            $this->command->warn('Debes tener usuarios y hamacas antes de ejecutar este seeder.');
            return;
        }

        // Crear 30 reservas aleatorias
        for ($i = 0; $i < 30; $i++) {
            $slot = $timeSlots[array_rand($timeSlots)];
            Booking::create([
                'user_id' => $users->random()->id,
                'hammock_id' => $hammocks->random()->id,
                'date' => now()->addDays(rand(0, 15))->toDateString(),
                'time_slot' => $slot,
                'status' => 'confirmed',
                'price' => $priceMap[$slot],
                'comments' => fake()->boolean(50) ? fake()->sentence() : null,
            ]);
        }
    }
}
