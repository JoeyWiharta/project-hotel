<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->nullable(); // Set booking_id to nullable
            $table->string('user_name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('roomnumber');
            $table->string('type');
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('days');
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('order_status')->default('Booked'); // Tambahkan kolom order_status
            $table->timestamps();

            // Hapus constraint foreign key lama dan tambahkan constraint baru dengan onDelete('set null')
            $table->foreign('booking_id')->references('id')->on('bookinglist')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_lists');
    }
};
