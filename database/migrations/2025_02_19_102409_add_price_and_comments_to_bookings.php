<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(0)->after('status'); // Precio de la reserva
            $table->text('comments')->nullable()->after('price'); // Comentarios opcionales
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['price', 'comments']);
        });
    }
};
