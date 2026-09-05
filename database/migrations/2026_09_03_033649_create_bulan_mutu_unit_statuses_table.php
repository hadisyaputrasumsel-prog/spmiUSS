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
        Schema::create('bulan_mutu_unit_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->foreignId('unit_id')->constrained('units');
            $table->boolean('aktif')->default(true);
            $table->foreignId('updated_by_id')->constrained('users');
            $table->unique(['tahun', 'unit_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulan_mutu_unit_statuses');
    }
};

