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
            $table->id(); // Kolom ID
            $table->uuid('uuid')->unique(); // Menambahkan kolom UUID
            $table->string('name'); // Kolom Nama
            $table->string('place'); // Kolom Tempat
            $table->datetime('datetime'); // Kolom Waktu
            $table->enum('status', ['available', 'unavailable']); // Kolom Status
            $table->integer('quantity'); // Kolom Jumlah
            $table->decimal('price', 10, 2); // Kolom Harga
            $table->string('image')->nullable(); // Kolom Gambar (nullable)
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
