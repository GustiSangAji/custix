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
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade'); // Menghubungkan ke pesanan di tabel carts
            $table->string('ticket_number')->unique(); // Nomor tiket unik
            $table->enum('status', ['Unused', 'Used'])->default('Unused'); // Status tiap tiket
            $table->text('qr_code')->nullable(); // QR Code untuk setiap tiket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_details');
    }
};
