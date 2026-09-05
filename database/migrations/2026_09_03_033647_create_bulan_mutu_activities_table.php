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
        Schema::create('bulan_mutu_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('index_kegiatan')->unique();
            $table->string('nama');
            $table->string('tahap');
            $table->string('pic');
            $table->string('dokumen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulan_mutu_activities');
    }
};

