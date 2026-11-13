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
        Schema::create('pka', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->integer('tahun');
            $table->string('nomor');
            $table->text('ruang_lingkup');
            $table->text('kriteria');
            $table->string('standar_kode')->nullable();
            $table->text('tentative_audit_objective')->nullable();
            $table->text('tujuan_audit')->nullable();
            $table->foreignId('disusun_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('diperiksa_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_disusun')->nullable();
            $table->date('tanggal_diperiksa')->nullable();
            $table->timestamps();
        });

        Schema::create('pka_langkah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pka_id')->constrained('pka')->cascadeOnDelete();
            $table->integer('no_urut');
            $table->text('uraian');
            $table->string('anggaran_waktu')->nullable();
            $table->string('realisasi')->nullable();
            $table->string('no_kka')->nullable();
            $table->string('disusun_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pka_langkah');
        Schema::dropIfExists('pka');
    }
};
