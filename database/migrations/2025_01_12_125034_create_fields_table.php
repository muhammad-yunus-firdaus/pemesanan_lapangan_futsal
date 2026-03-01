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
    Schema::create('fields', function (Blueprint $table) {
        $table->id();
        $table->string('name');  // Kolom 'name' untuk nama lapangan
        $table->text('description'); // Kolom deskripsi
        $table->decimal('price_per_hour', 8, 2); // Kolom harga per jam
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
};
