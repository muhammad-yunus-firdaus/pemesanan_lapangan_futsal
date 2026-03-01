<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // ID booking
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Relasi ke tabel users
            $table->foreignId('field_id')->constrained()->onDelete('cascade');  // Relasi ke tabel fields
            $table->date('booking_date');  // Tanggal booking
            $table->time('booking_time');  // Waktu booking
            $table->decimal('total_price', 10, 2)->default(0);  // Total harga
            $table->string('status')->default('pending');  // Status booking (pending, confirmed, canceled)
            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};