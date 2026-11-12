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
        if (Schema::hasTable('evidence_documents')) {
            return;
        }

        Schema::create('evidence_documents', function (Blueprint $table): void {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 100)->default('Lainnya');
            $table->string('scope', 20)->default('unit')->index();
            $table->unsignedBigInteger('unit_id')->nullable()->index();
            $table->integer('tahun')->nullable()->index();
            $table->string('standar_kode')->nullable()->index();
            $table->string('tahap', 10)->nullable()->index();
            $table->boolean('auto_attach')->default(false);
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->text('external_url')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('uploaded_by_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence_documents');
    }
};
