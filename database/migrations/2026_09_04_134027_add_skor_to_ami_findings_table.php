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
        Schema::table('ami_findings', function (Blueprint $table) {
            $table->tinyInteger('skor')->nullable()->after('standar_kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ami_findings', function (Blueprint $table) {
            //
        });
    }
};
