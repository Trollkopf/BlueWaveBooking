<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->time('morning_start')->default('08:00');
            $table->time('morning_end')->default('12:00');
            $table->time('afternoon_start')->default('12:00');
            $table->time('afternoon_end')->default('18:00');
            $table->time('full_day_start')->default('08:00');
            $table->time('full_day_end')->default('18:00');
            $table->decimal('price_morning', 8, 2)->default(10);
            $table->decimal('price_afternoon', 8, 2)->default(15);
            $table->decimal('price_full_day', 8, 2)->default(25);
            $table->date('closed_from')->nullable();
            $table->date('closed_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
