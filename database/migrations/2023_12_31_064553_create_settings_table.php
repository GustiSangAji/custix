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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('app');
            $table->text('description');
            $table->string('logo');
            $table->string('carousel1')->nullable();
            $table->string('carousel2')->nullable();
            $table->string('carousel3')->nullable();
            $table->string('carousel4')->nullable();
            $table->string('carousel5')->nullable();
            $table->string('carousel6')->nullable();
            $table->string('carousel7')->nullable();
            $table->string('carousel8')->nullable();
            $table->string('carousel9')->nullable();
            $table->string('carousel10')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
