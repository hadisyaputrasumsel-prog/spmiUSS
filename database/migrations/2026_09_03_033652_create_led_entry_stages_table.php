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
        Schema::create('led_entry_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('led_entry_id')->constrained('led_entries')->cascadeOnDelete();
            $table->enum('tahap', ['P1', 'P2', 'P3', 'P4', 'P5']);
            $table->date('tanggal')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->text('uraian')->nullable();
            $table->text('catatan')->nullable();
            $table->json('bukti')->nullable();
            $table->unique(['led_entry_id', 'tahap']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('led_entry_stages');
    }
};

