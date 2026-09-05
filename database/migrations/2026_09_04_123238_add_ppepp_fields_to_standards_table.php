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
        Schema::table('standards', function (Blueprint $table) {
            $table->text('indikator_p1')->nullable()->after('indikator');
            $table->text('indikator_p2')->nullable()->after('indikator_p1');
            $table->text('indikator_p3')->nullable()->after('indikator_p2');
            $table->text('indikator_p4')->nullable()->after('indikator_p3');
            $table->text('indikator_p5')->nullable()->after('indikator_p4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('standards', function (Blueprint $table) {
            $table->dropColumn([
                'indikator_p1',
                'indikator_p2',
                'indikator_p3',
                'indikator_p4',
                'indikator_p5',
            ]);
        });
    }
};
