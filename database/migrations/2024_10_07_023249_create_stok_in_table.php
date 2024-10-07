<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokInTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stok_in', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tikets')->onDelete('cascade'); // Foreign key ke tabel 'tikets'
            $table->string('description'); // Kolom deskripsi
            $table->timestamp('added_at'); // Kolom tanggal penambahan stok
            $table->integer('amount'); // Kolom jumlah stok yang ditambahkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_in');
    }
}
