<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanTable extends Migration
{
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id(); // Auto increment primary key
            $table->string('email'); // Email pengguna
            $table->string('nama_tiket'); // Nama tiket
            $table->integer('jumlah'); // Jumlah tiket
            $table->decimal('harga', 15, 2); // Harga tiket
            $table->timestamp('tanggal_pembelian')->nullable(); // Tanggal pembelian
            $table->enum('status', ['paid', 'unpaid']); // Status pembayaran
            $table->timestamps(); // Created at dan Updated at
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan');
    }
}
