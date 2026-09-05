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
        Schema::create('bulan_mutu_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->foreignId('kegiatan_id')->constrained('bulan_mutu_activities');
            $table->enum('status', ['Belum Dilaksanakan', 'Terlaksana Sesuai Rencana', 'Terlaksana - Tertunda', 'Dibatalkan'])->default('Belum Dilaksanakan');
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by_id')->constrained('users');
            $table->unique(['tahun', 'kegiatan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulan_mutu_statuses');
    }
};

