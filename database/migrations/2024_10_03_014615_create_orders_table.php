<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 16);
            $table->integer('jumlah');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['1', '2', '3'])->comment('1=menunggu pembayaran, 2=sudah dibayar, 3=kadaluarsa');
            $table->string('snap_token', 36)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
