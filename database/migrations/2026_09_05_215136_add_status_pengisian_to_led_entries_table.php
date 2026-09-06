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
        Schema::table('led_entries', function (Blueprint $table) {
            $table->enum('status_pengisian', ['draft', 'lengkap'])->default('draft')->after('diisi_oleh_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('led_entries', function (Blueprint $table) {
            $table->dropColumn('status_pengisian');
        });
    }
};
