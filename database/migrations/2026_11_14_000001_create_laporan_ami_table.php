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
        if (! Schema::hasTable('laporan_ami')) {
            Schema::create('laporan_ami', function (Blueprint $table) {
                $table->id();
                $table->year('tahun')->unique();
                $table->string('nomor_dokumen')->nullable();
                $table->date('tanggal_terbit')->nullable();
                $table->string('versi')->default('1.0');
                $table->longText('kata_pengantar')->nullable();
                $table->text('dasar_hukum')->nullable();
                $table->text('tujuan')->nullable();
                $table->text('ruang_lingkup')->nullable();
                $table->text('metode')->nullable();
                $table->json('jadwal_ami')->nullable();
                $table->text('kesimpulan')->nullable();
                $table->text('rekomendasi_pimpinan')->nullable();
                $table->string('penandatangan_lpma_nama')->nullable();
                $table->string('penandatangan_lpma_nidn')->nullable();
                $table->string('penandatangan_lpma_jabatan')->default('Kepala LPMA');
                $table->string('penandatangan_rektor_nama')->nullable();
                $table->string('penandatangan_rektor_nidn')->nullable();
                $table->string('penandatangan_rektor_jabatan')->default('Rektor Universitas Sumatera Selatan');
                $table->json('lampiran_options')->nullable();
                $table->json('selected_unit_ids')->nullable();
                $table->string('file_path')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_ami');
    }
};
