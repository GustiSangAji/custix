<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // ID Unik untuk tiket
            $table->string('name'); // Nama event
            $table->string('place'); // Lokasi event
            $table->datetime('datetime'); // Tanggal event
            $table->enum('status', ['available', 'unavailable'])->default('available'); // Status tiket
            $table->integer('quantity'); // Stok tiket yang tersedia
            $table->decimal('price', 10, 2); // Harga tiket
            $table->string('image')->nullable(); // Gambar tiket
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tikets');
    }
}
