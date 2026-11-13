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
            if (! Schema::hasColumn('ami_findings', 'jadwal_pencegahan')) {
                $table->string('jadwal_pencegahan')->nullable()->after('rencana_pencegahan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ami_findings', function (Blueprint $table) {
            if (Schema::hasColumn('ami_findings', 'jadwal_pencegahan')) {
                $table->dropColumn('jadwal_pencegahan');
            }
        });
    }
};
