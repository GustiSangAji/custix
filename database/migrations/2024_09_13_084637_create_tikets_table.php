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
            $table->bigIncrements('id'); // Primary key
            $table->uuid('uuid'); // UUID sebagai identifier unik
            $table->string('id_tiket'); // UUID sebagai identifier unik
            $table->string('name'); // Nama tiket
            $table->string('place'); // Tempat acara
            $table->datetime('datetime'); // Waktu acara
            $table->enum('status', ['available', 'unavailable']); // Status tiket
            $table->integer('quantity'); // Jumlah tiket yang tersedia
            $table->decimal('price', 10, 2); // Harga tiket
            $table->string('image')->nullable(); // Gambar tiket (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
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
