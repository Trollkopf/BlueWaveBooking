<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hammock_spaces', function (Blueprint $table) {
            $table->id();
            $table->integer('row'); // Número de fila
            $table->integer('col'); // Número de columna
            $table->integer('hammocks')->default(2); // 0 = Desactivado, 1 = 1 hamaca, 2 = 2 hamacas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hammock_spaces');
    }
};
