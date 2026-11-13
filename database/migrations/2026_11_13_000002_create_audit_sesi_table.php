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
        Schema::create('audit_sesi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->integer('tahun');
            $table->string('no_kka')->nullable();
            $table->text('kriteria')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('ruang_lingkup')->nullable();
            $table->date('tanggal_audit')->nullable();
            $table->string('wakil_auditi')->nullable();
            $table->json('distribusi')->nullable();
            $table->string('penjamin_mutu')->nullable();
            $table->text('catatan_penutup')->nullable();
            $table->timestamps();

            $table->unique(['unit_id', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_sesi');
    }
};
