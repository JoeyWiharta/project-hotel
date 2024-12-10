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
        Schema::create('rooms', function (Blueprint $table) {
            $table->string('roomnumber')->primary(); // Primary key roomnumber
            $table->enum('type', ['Single Room', 'Double Room', 'Deluxe Room', 'Standard Room']);
            $table->integer('price_per_night'); //Harga Room dengan Rp.
            $table->string('description')->nullable();
            $table->enum('status', ['Available', 'Unavailable'])->default('Available');
            $table->integer('adult_capacity'); // Kapasitas untuk orang dewasa
            $table->integer('child_capacity'); // Kapasitas untuk orang dewasa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
