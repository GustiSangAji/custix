<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stockins', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_tiket')->unique();
            $table->integer('jumlah');
            $table->text('deskripsi');
            $table->datetime('datetime'); // Tanggal event
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stockins');
    }
};
