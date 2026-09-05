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
        Schema::create('ami_findings', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['akademik', 'nonakademik']);
            $table->string('standar_kode');
            $table->enum('tahap', ['P1', 'P2', 'P3', 'P4', 'P5']);
            $table->foreignId('unit_id')->constrained('units');
            $table->enum('kategori_temuan', ['Sesuai', 'Observasi (OB)', 'KTS Minor', 'KTS Mayor']);
            $table->text('uraian')->nullable();
            $table->text('rencana_tindakan')->nullable();
            $table->string('pic')->nullable();
            $table->date('batas_waktu')->nullable();
            $table->foreignId('auditor_id')->constrained('users');
            $table->date('tanggal');
            $table->enum('status_tindak_lanjut', ['Belum', 'Proses', 'Selesai', 'N/A'])->default('N/A');
            $table->text('catatan_tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ami_findings');
    }
};

