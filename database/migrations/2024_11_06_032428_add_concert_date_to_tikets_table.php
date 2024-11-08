<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConcertDateToTiketsTable extends Migration
{
    public function up()
    {
        Schema::table('tikets', function (Blueprint $table) {
            // Menambahkan kolom concert_date bertipe datetime
            $table->dateTime('concert_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tikets', function (Blueprint $table) {
            // Menghapus kolom concert_date jika migrasi dibatalkan
            $table->dropColumn('concert_date');
        });
    }
}
