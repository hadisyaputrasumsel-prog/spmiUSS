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
        Schema::create('led_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->enum('jenis', ['akademik', 'nonakademik']);
            $table->string('objek_kode');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('diisi_oleh_id')->constrained('users');
            $table->unique(['tahun', 'jenis', 'objek_kode', 'unit_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('led_entries');
    }
};

