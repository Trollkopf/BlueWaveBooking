<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hammocks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre o número de la hamaca
            $table->decimal('price', 8, 2); // Precio por día
            $table->enum('status', ['available', 'reserved', 'unavailable'])->default('available');
            $table->json('location')->nullable(); // Coordenadas o información de la ubicación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hammocks');
    }
};
