<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('led_submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('submitted_by_id')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->unique(['tahun', 'unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('led_submissions');
    }
};
