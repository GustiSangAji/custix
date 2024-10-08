<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stockins', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_tiket');
            $table->integer('jumlah');
            $table->text('deskripsi');
            $table->datetime('datetime'); // Tanggal event
            $table->timestamps();
        });

        // Menambahkan trigger menggunakan raw SQL
        DB::unprepared('
            CREATE TRIGGER trigger_tambah
            AFTER INSERT ON stockins
            FOR EACH ROW
            BEGIN
                UPDATE tikets SET quantity = quantity + NEW.jumlah
                WHERE kode_tiket = NEW.kode_tiket;
            END
        ');
    }

    public function down(): void
    {
        // Drop trigger sebelum menghapus tabel
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_tambah');

        Schema::dropIfExists('stockins');
    }
};

