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
        if (Schema::hasTable('led_entry_stages') && Schema::hasColumn('led_entry_stages', 'tanggal')) {
            Schema::table('led_entry_stages', function (Blueprint $table): void {
                $table->string('tanggal', 255)->nullable()->change();
            });
        }

        if (Schema::hasTable('ami_findings') && Schema::hasColumn('ami_findings', 'status_tindak_lanjut')) {
            Schema::table('ami_findings', function (Blueprint $table): void {
                $table->string('status_tindak_lanjut', 50)->default('N/A')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep broadened string columns on rollback to avoid truncating free-text P2 periods or RTL statuses.
    }
};
