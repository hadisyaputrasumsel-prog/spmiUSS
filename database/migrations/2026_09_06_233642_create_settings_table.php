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
            $table->string('nama_kampus')->default('Universitas Sumatera Selatan');
            $table->text('alamat_kampus')->nullable();
            $table->string('email_kampus')->nullable();
            $table->string('telepon_kampus')->nullable();
            $table->string('website_kampus')->nullable();
            $table->string('logo_kampus')->nullable();
            $table->string('nama_rektor')->nullable();
            $table->string('nuptk_rektor')->nullable();
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
