<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ami_findings', function (Blueprint $table) {
            $table->text('akar_penyebab')->nullable();
            $table->text('akibat')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->string('tanggapan_auditi')->nullable(); // Setuju / Tidak Setuju
            $table->text('rencana_pencegahan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ami_findings', function (Blueprint $table) {
            $table->dropColumn(['akar_penyebab', 'akibat', 'rekomendasi', 'tanggapan_auditi', 'rencana_pencegahan']);
        });
    }
};
