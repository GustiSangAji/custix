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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID user
            $table->foreignId('ticket_id')->constrained('tikets')->onDelete('cascade'); // ID tiket (pastikan nama tabel sesuai)
            $table->integer('jumlah_pemesanan'); // Jumlah tiket yang dipesan
            $table->integer('total_harga'); // Total harga tiket
            $table->enum('status', ['Unpaid', 'Paid']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
