
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
    Schema::create('ticket_access', function (Blueprint $table) {
    $table->id();
    $table->boolean('active')->default(false); 
    $table->unsignedBigInteger('user_id');
    $table->timestamps();
});

/**
 * Reverse the migrations.
 */
}

public function down()
{
    Schema::table('ticket_access', function (Blueprint $table) {
        $table->dropColumn('active'); // Menghapus kolom active jika di-rollback
    });
}
};