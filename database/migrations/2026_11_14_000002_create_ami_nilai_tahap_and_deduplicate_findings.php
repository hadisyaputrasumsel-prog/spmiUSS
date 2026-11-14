<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom tahun, status_audit, dan alasan_ubah_skor pada ami_findings
        Schema::table('ami_findings', function (Blueprint $table) {
            if (! Schema::hasColumn('ami_findings', 'tahun')) {
                $table->year('tahun')->nullable()->after('unit_id');
            }
            if (! Schema::hasColumn('ami_findings', 'status_audit')) {
                $table->string('status_audit', 30)->default('Sudah Diaudit')->after('status_tindak_lanjut');
            }
            if (! Schema::hasColumn('ami_findings', 'alasan_ubah_skor')) {
                $table->text('alasan_ubah_skor')->nullable()->after('skor');
            }
            if (! Schema::hasColumn('ami_findings', 'kriteria')) {
                $table->text('kriteria')->nullable()->after('standar_kode');
            }
        });

        // 2. Buat tabel ami_nilai_tahap
        Schema::create('ami_nilai_tahap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ami_id');
            $table->string('tahap', 10); // P1, P2, P3, P4, P5
            $table->tinyInteger('skor')->nullable(); // 1, 2, 3, 4
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('ami_id')->references('id')->on('ami_findings')->onDelete('cascade');
            $table->unique(['ami_id', 'tahap']);
        });

        // 3. Populate kolom tahun dari tanggal (atau created_at)
        DB::statement('UPDATE ami_findings SET tahun = YEAR(tanggal) WHERE tanggal IS NOT NULL');
        DB::statement('UPDATE ami_findings SET tahun = YEAR(created_at) WHERE tahun IS NULL AND created_at IS NOT NULL');
        DB::statement('UPDATE ami_findings SET tahun = 2023 WHERE tahun IS NULL');

        // 4. Bersihkan data duplikat per (unit_id, tahun, standar_kode) dan migrasikan nilai tahap
        $duplicates = DB::table('ami_findings')
            ->select('unit_id', 'tahun', 'standar_kode', DB::raw('COUNT(*) as total'))
            ->groupBy('unit_id', 'tahun', 'standar_kode')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            $records = DB::table('ami_findings')
                ->where('unit_id', $dup->unit_id)
                ->where('tahun', $dup->tahun)
                ->where('standar_kode', $dup->standar_kode)
                ->orderByRaw("CASE WHEN kategori_temuan != 'Sesuai' THEN 1 ELSE 2 END")
                ->orderByDesc('id')
                ->get();

            $primary = $records->first();
            $others = $records->slice(1);

            // Simpan nilai tahap dari record-record yang ada
            foreach ($records as $rec) {
                if ($rec->tahap && in_array($rec->tahap, ['P1', 'P2', 'P3', 'P4', 'P5'])) {
                    DB::table('ami_nilai_tahap')->updateOrInsert(
                        ['ami_id' => $primary->id, 'tahap' => $rec->tahap],
                        [
                            'skor' => $rec->skor,
                            'catatan' => $rec->uraian,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            // Hapus duplikat lainnya
            $deleteIds = $others->pluck('id')->toArray();
            if (! empty($deleteIds)) {
                DB::table('ami_findings')->whereIn('id', $deleteIds)->delete();
            }
        }

        // 5. Untuk record tunggal, inisialisasi ami_nilai_tahap jika ada tahap P1..P5
        $singleRecords = DB::table('ami_findings')->get();
        foreach ($singleRecords as $rec) {
            if ($rec->tahap && in_array($rec->tahap, ['P1', 'P2', 'P3', 'P4', 'P5'])) {
                DB::table('ami_nilai_tahap')->updateOrInsert(
                    ['ami_id' => $rec->id, 'tahap' => $rec->tahap],
                    [
                        'skor' => $rec->skor,
                        'catatan' => $rec->uraian,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // 6. Tambahkan unique index pada ami_findings untuk (unit_id, tahun, standar_kode)
        Schema::table('ami_findings', function (Blueprint $table) {
            $table->unique(['unit_id', 'tahun', 'standar_kode'], 'ami_findings_unit_tahun_standar_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ami_findings', function (Blueprint $table) {
            $table->dropUnique('ami_findings_unit_tahun_standar_unique');
            $table->dropColumn(['tahun', 'status_audit', 'alasan_ubah_skor', 'kriteria']);
        });

        Schema::dropIfExists('ami_nilai_tahap');
    }
};
