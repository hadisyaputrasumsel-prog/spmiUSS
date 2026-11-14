<?php

namespace App\Providers;

use Database\Seeders\PetunjukAmiSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $flagPath = storage_path('framework/schema_synced_v9.flag');
        if (file_exists($flagPath)) {
            return;
        }

        try {
            self::syncSchema();
            @file_put_contents($flagPath, date('c'));
        } catch (\Throwable $e) {
            Log::warning('Auto schema sync failed: '.$e->getMessage());
        }
    }

    /**
     * Ensure missing tables and columns are created even if partial manual fixes blocked migrate.
     */
    public static function syncSchema(): void
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Throwable $e) {
            Log::warning('Auto migrate execution notice: '.$e->getMessage());
        }

        self::ensureCoreTablesExist();
        self::ensureMissingColumns();
        self::ensureColumnTypes();
        self::ensureDefaultPetunjukAmi();
        self::cleanupDuplicateFindings();
    }

    /**
     * Populate default Petunjuk AMI guidance in settings if empty.
     */
    private static function ensureDefaultPetunjukAmi(): void
    {
        if (! Schema::hasTable('settings') || ! Schema::hasColumn('settings', 'petunjuk_ami')) {
            return;
        }

        $setting = DB::table('settings')->first();
        if (! $setting) {
            DB::table('settings')->insert([
                'nama_kampus' => 'Universitas Sumatera Selatan',
                'petunjuk_ami' => PetunjukAmiSeeder::defaultContent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        if (empty($setting->petunjuk_ami)) {
            DB::table('settings')->where('id', $setting->id)->update([
                'petunjuk_ami' => PetunjukAmiSeeder::defaultContent(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Deduplicate findings per (unit_id, standar_kode, tahun) and clean up duplicate ID 51.
     */
    private static function cleanupDuplicateFindings(): void
    {
        if (! Schema::hasTable('ami_findings')) {
            return;
        }

        try {
            // Ensure tahun is populated
            if (Schema::hasColumn('ami_findings', 'tahun')) {
                DB::statement('UPDATE ami_findings SET tahun = YEAR(tanggal) WHERE tahun IS NULL AND tanggal IS NOT NULL');
            }

            // Find duplicate groups
            $duplicates = DB::table('ami_findings')
                ->select('unit_id', 'standar_kode', DB::raw('COALESCE(tahun, YEAR(tanggal)) as thn'), DB::raw('COUNT(*) as total'))
                ->groupBy('unit_id', 'standar_kode', DB::raw('COALESCE(tahun, YEAR(tanggal))'))
                ->havingRaw('COUNT(*) > 1')
                ->get();

            foreach ($duplicates as $dup) {
                $records = DB::table('ami_findings')
                    ->where('unit_id', $dup->unit_id)
                    ->where('standar_kode', $dup->standar_kode)
                    ->whereRaw('COALESCE(tahun, YEAR(tanggal)) = ?', [$dup->thn])
                    ->orderByRaw("CASE WHEN kategori_temuan != 'Sesuai' THEN 1 ELSE 2 END")
                    ->orderBy('id')
                    ->get();

                // Keep the primary record, delete subsequent duplicate records (such as duplicate ID 51)
                $deleteIds = $records->slice(1)->pluck('id')->toArray();
                if (! empty($deleteIds)) {
                    if (Schema::hasTable('ami_nilai_tahap')) {
                        DB::table('ami_nilai_tahap')->whereIn('ami_id', $deleteIds)->delete();
                    }
                    DB::table('ami_findings')->whereIn('id', $deleteIds)->delete();
                }
            }

            // Pastikan data simulasi 2023 unit 17 terinisialisasi tepat 50 temuan dan sesi audit lengkap
            if (class_exists(\Database\Seeders\AuditSesiAndPkaSimulasiSeeder::class) && Schema::hasTable('audit_sesi') && Schema::hasTable('ami_findings')) {
                $count2023 = DB::table('ami_findings')->where('unit_id', 17)->whereYear('tanggal', 2023)->count();
                $sesiExist = DB::table('audit_sesi')->where('unit_id', 17)->where('tahun', 2023)->exists();
                if (! $sesiExist || $count2023 !== 50) {
                    (new \Database\Seeders\AuditSesiAndPkaSimulasiSeeder())->run();
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Deduplication cleanup notice: '.$e->getMessage());
        }
    }

    /**
     * Create core application tables if any are missing due to out-of-sync migration state.
     */
    private static function ensureCoreTablesExist(): void
    {
        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table): void {
                $table->id();
                $table->string('kode')->unique();
                $table->string('nama');
                $table->string('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table): void {
                $table->id();
                $table->string('nama');
                $table->enum('jenis', ['Universitas', 'Fakultas', 'Program Studi', 'Non-Akademik']);
                $table->string('pimpinan')->nullable();
                $table->string('nuptk_pimpinan')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->unsignedBigInteger('role_id')->index();
                $table->unsignedBigInteger('unit_id')->nullable()->index();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('standards')) {
            Schema::create('standards', function (Blueprint $table): void {
                $table->id();
                $table->string('kode')->unique();
                $table->string('nama');
                $table->text('indikator')->nullable();
                $table->text('indikator_p1')->nullable();
                $table->text('indikator_p2')->nullable();
                $table->text('indikator_p3')->nullable();
                $table->text('indikator_p4')->nullable();
                $table->text('indikator_p5')->nullable();
                $table->text('target')->nullable();
                $table->string('acuan')->nullable();
                $table->string('template_dokumen')->nullable();
                $table->string('penanggung_jawab')->nullable();
                $table->boolean('is_akademik')->default(true);
                $table->string('kelompok');
                $table->string('periode')->nullable();
                $table->boolean('is_active')->default(true);
                $table->json('sasaran_unit')->nullable();
                $table->json('rubrik_penilaian')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('nonacademic_categories')) {
            Schema::create('nonacademic_categories', function (Blueprint $table): void {
                $table->id();
                $table->string('kode')->unique();
                $table->string('nama');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('bulan_mutu_activities')) {
            Schema::create('bulan_mutu_activities', function (Blueprint $table): void {
                $table->id();
                $table->integer('index_kegiatan')->unique();
                $table->string('nama');
                $table->string('tahap');
                $table->string('pic');
                $table->string('dokumen')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('bulan_mutu_configs')) {
            Schema::create('bulan_mutu_configs', function (Blueprint $table): void {
                $table->id();
                $table->integer('tahun')->unique();
                $table->boolean('is_active')->default(false);
                $table->string('bulan_pelaksanaan')->nullable();
                $table->unsignedBigInteger('updated_by_id')->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('bulan_mutu_unit_statuses')) {
            Schema::create('bulan_mutu_unit_statuses', function (Blueprint $table): void {
                $table->id();
                $table->integer('tahun');
                $table->unsignedBigInteger('unit_id')->index();
                $table->boolean('aktif')->default(true);
                $table->unsignedBigInteger('updated_by_id')->index();
                $table->unique(['tahun', 'unit_id']);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('bulan_mutu_statuses')) {
            Schema::create('bulan_mutu_statuses', function (Blueprint $table): void {
                $table->id();
                $table->integer('tahun');
                $table->unsignedBigInteger('kegiatan_id')->index();
                $table->string('status')->default('Belum Dilaksanakan');
                $table->date('tanggal_pelaksanaan')->nullable();
                $table->string('dokumen_bukti')->nullable();
                $table->text('catatan')->nullable();
                $table->unsignedBigInteger('updated_by_id')->index();
                $table->unique(['tahun', 'kegiatan_id']);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('led_entries')) {
            Schema::create('led_entries', function (Blueprint $table): void {
                $table->id();
                $table->integer('tahun');
                $table->enum('jenis', ['akademik', 'nonakademik']);
                $table->string('objek_kode');
                $table->unsignedBigInteger('unit_id')->index();
                $table->unsignedBigInteger('diisi_oleh_id')->index();
                $table->string('status_pengisian')->default('draft');
                $table->text('capaian')->nullable();
                $table->unique(['tahun', 'jenis', 'objek_kode', 'unit_id']);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('led_entry_stages')) {
            Schema::create('led_entry_stages', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('led_entry_id')->index();
                $table->enum('tahap', ['P1', 'P2', 'P3', 'P4', 'P5']);
                $table->string('tanggal')->nullable();
                $table->string('penanggung_jawab')->nullable();
                $table->text('uraian')->nullable();
                $table->text('catatan')->nullable();
                $table->json('bukti')->nullable();
                $table->json('data_spesifik')->nullable();
                $table->unique(['led_entry_id', 'tahap']);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ami_findings')) {
            Schema::create('ami_findings', function (Blueprint $table): void {
                $table->id();
                $table->string('jenis', 30);
                $table->string('standar_kode');
                $table->tinyInteger('skor')->nullable();
                $table->string('tahap', 20);
                $table->unsignedBigInteger('unit_id')->index();
                $table->string('kategori_temuan', 50);
                $table->text('uraian')->nullable();
                $table->text('rencana_tindakan')->nullable();
                $table->string('pic')->nullable();
                $table->date('batas_waktu')->nullable();
                $table->unsignedBigInteger('auditor_id')->index();
                $table->date('tanggal');
                $table->string('status_tindak_lanjut', 50)->default('N/A');
                $table->text('catatan_tindak_lanjut')->nullable();
                $table->text('akar_penyebab')->nullable();
                $table->text('akibat')->nullable();
                $table->text('rekomendasi')->nullable();
                $table->string('tanggapan_auditi')->nullable();
                $table->text('rencana_pencegahan')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('auditor_assignments')) {
            Schema::create('auditor_assignments', function (Blueprint $table): void {
                $table->id();
                $table->string('tahun', 4);
                $table->unsignedBigInteger('auditor_id')->index();
                $table->unsignedBigInteger('unit_id')->index();
                $table->string('peran')->default('Auditor Ketua');
                $table->unique(['tahun', 'auditor_id', 'unit_id']);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('spmi_periods')) {
            Schema::create('spmi_periods', function (Blueprint $table): void {
                $table->id();
                $table->string('nama')->unique();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });

            DB::table('spmi_periods')->insert([
                'nama' => 'Periode Utama',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table): void {
                $table->id();
                $table->string('nama_kampus')->default('Universitas Sumatera Selatan');
                $table->text('alamat_kampus')->nullable();
                $table->string('email_kampus')->nullable();
                $table->string('telepon_kampus')->nullable();
                $table->string('website_kampus')->nullable();
                $table->string('logo_kampus')->nullable();
                $table->longText('petunjuk_ami')->nullable();
                $table->string('nama_rektor')->nullable();
                $table->string('nuptk_rektor')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('led_submissions')) {
            Schema::create('led_submissions', function (Blueprint $table): void {
                $table->id();
                $table->integer('tahun');
                $table->unsignedBigInteger('unit_id');
                $table->unsignedBigInteger('submitted_by_id')->nullable();
                $table->timestamp('submitted_at')->useCurrent();
                $table->timestamps();
                $table->unique(['tahun', 'unit_id']);
            });
        }

        if (! Schema::hasTable('evidence_documents')) {
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

        if (! Schema::hasTable('audit_sesi')) {
            Schema::create('audit_sesi', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('unit_id');
                $table->integer('tahun');
                $table->string('no_kka')->nullable();
                $table->text('kriteria')->nullable();
                $table->string('lokasi')->nullable();
                $table->text('ruang_lingkup')->nullable();
                $table->date('tanggal_audit')->nullable();
                $table->string('wakil_auditi')->nullable();
                $table->json('distribusi')->nullable();
                $table->string('penjamin_mutu')->nullable();
                $table->text('catatan_penutup')->nullable();
                $table->timestamps();
                $table->unique(['unit_id', 'tahun']);
            });
        }

        if (! Schema::hasTable('pka')) {
            Schema::create('pka', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
                $table->integer('tahun');
                $table->string('nomor');
                $table->text('ruang_lingkup');
                $table->text('kriteria');
                $table->string('standar_kode')->nullable();
                $table->text('tentative_audit_objective')->nullable();
                $table->text('tujuan_audit')->nullable();
                $table->foreignId('disusun_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('diperiksa_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->date('tanggal_disusun')->nullable();
                $table->date('tanggal_diperiksa')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('pka_langkah')) {
            Schema::create('pka_langkah', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('pka_id')->constrained('pka')->cascadeOnDelete();
                $table->integer('no_urut');
                $table->text('uraian');
                $table->string('anggaran_waktu')->nullable();
                $table->string('realisasi')->nullable();
                $table->string('no_kka')->nullable();
                $table->string('disusun_oleh')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('laporan_ami')) {
            Schema::create('laporan_ami', function (Blueprint $table): void {
                $table->id();
                $table->year('tahun')->unique();
                $table->string('nomor_dokumen')->nullable();
                $table->date('tanggal_terbit')->nullable();
                $table->string('versi')->default('1.0');
                $table->longText('kata_pengantar')->nullable();
                $table->text('dasar_hukum')->nullable();
                $table->text('tujuan')->nullable();
                $table->text('ruang_lingkup')->nullable();
                $table->text('metode')->nullable();
                $table->json('jadwal_ami')->nullable();
                $table->text('kesimpulan')->nullable();
                $table->text('rekomendasi_pimpinan')->nullable();
                $table->string('penandatangan_lpma_nama')->nullable();
                $table->string('penandatangan_lpma_nidn')->nullable();
                $table->string('penandatangan_lpma_jabatan')->default('Kepala LPMA');
                $table->string('penandatangan_rektor_nama')->nullable();
                $table->string('penandatangan_rektor_nidn')->nullable();
                $table->string('penandatangan_rektor_jabatan')->default('Rektor Universitas Sumatera Selatan');
                $table->json('lampiran_options')->nullable();
                $table->json('selected_unit_ids')->nullable();
                $table->string('file_path')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ami_nilai_tahap')) {
            Schema::create('ami_nilai_tahap', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('ami_id');
                $table->string('tahap', 10);
                $table->tinyInteger('skor')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->foreign('ami_id')->references('id')->on('ami_findings')->onDelete('cascade');
                $table->unique(['ami_id', 'tahap']);
            });
        }
    }

    /**
     * Add any missing columns on existing tables.
     */
    private static function ensureMissingColumns(): void
    {
        self::ensureColumns('settings', [
            'alamat_kampus' => fn (Blueprint $table) => $table->text('alamat_kampus')->nullable(),
            'email_kampus' => fn (Blueprint $table) => $table->string('email_kampus')->nullable(),
            'telepon_kampus' => fn (Blueprint $table) => $table->string('telepon_kampus')->nullable(),
            'website_kampus' => fn (Blueprint $table) => $table->string('website_kampus')->nullable(),
            'logo_kampus' => fn (Blueprint $table) => $table->string('logo_kampus')->nullable(),
            'petunjuk_ami' => fn (Blueprint $table) => $table->longText('petunjuk_ami')->nullable(),
            'nama_rektor' => fn (Blueprint $table) => $table->string('nama_rektor')->nullable(),
            'nuptk_rektor' => fn (Blueprint $table) => $table->string('nuptk_rektor')->nullable(),
        ]);

        self::ensureColumns('bulan_mutu_configs', [
            'is_active' => fn (Blueprint $table) => $table->boolean('is_active')->default(false)->after('tahun'),
        ]);

        self::ensureColumns('standards', [
            'periode' => fn (Blueprint $table) => $table->string('periode')->nullable(),
            'is_active' => fn (Blueprint $table) => $table->boolean('is_active')->default(true),
            'sasaran_unit' => fn (Blueprint $table) => $table->json('sasaran_unit')->nullable(),
            'is_akademik' => fn (Blueprint $table) => $table->boolean('is_akademik')->default(true),
            'indikator' => fn (Blueprint $table) => $table->text('indikator')->nullable(),
            'penanggung_jawab' => fn (Blueprint $table) => $table->string('penanggung_jawab')->nullable(),
            'indikator_p1' => fn (Blueprint $table) => $table->text('indikator_p1')->nullable(),
            'indikator_p2' => fn (Blueprint $table) => $table->text('indikator_p2')->nullable(),
            'indikator_p3' => fn (Blueprint $table) => $table->text('indikator_p3')->nullable(),
            'indikator_p4' => fn (Blueprint $table) => $table->text('indikator_p4')->nullable(),
            'indikator_p5' => fn (Blueprint $table) => $table->text('indikator_p5')->nullable(),
            'rubrik_penilaian' => fn (Blueprint $table) => $table->json('rubrik_penilaian')->nullable(),
            'target' => fn (Blueprint $table) => $table->text('target')->nullable(),
            'acuan' => fn (Blueprint $table) => $table->string('acuan')->nullable(),
            'template_dokumen' => fn (Blueprint $table) => $table->string('template_dokumen')->nullable(),
        ]);

        self::ensureColumns('ami_findings', [
            'tahun' => fn (Blueprint $table) => $table->year('tahun')->nullable()->after('unit_id'),
            'status_audit' => fn (Blueprint $table) => $table->string('status_audit', 30)->default('Sudah Diaudit')->after('status_tindak_lanjut'),
            'alasan_ubah_skor' => fn (Blueprint $table) => $table->text('alasan_ubah_skor')->nullable()->after('skor'),
            'kriteria' => fn (Blueprint $table) => $table->text('kriteria')->nullable()->after('standar_kode'),
            'skor' => fn (Blueprint $table) => $table->tinyInteger('skor')->nullable(),
            'akar_penyebab' => fn (Blueprint $table) => $table->text('akar_penyebab')->nullable(),
            'akibat' => fn (Blueprint $table) => $table->text('akibat')->nullable(),
            'rekomendasi' => fn (Blueprint $table) => $table->text('rekomendasi')->nullable(),
            'tanggapan_auditi' => fn (Blueprint $table) => $table->string('tanggapan_auditi')->nullable(),
            'rencana_pencegahan' => fn (Blueprint $table) => $table->text('rencana_pencegahan')->nullable(),
            'jadwal_pencegahan' => fn (Blueprint $table) => $table->string('jadwal_pencegahan')->nullable()->after('rencana_pencegahan'),
        ]);

        self::ensureColumns('users', [
            'nama_lengkap' => fn (Blueprint $table) => $table->string('nama_lengkap')->nullable()->after('name'),
            'nidn' => fn (Blueprint $table) => $table->string('nidn', 50)->nullable()->after('email'),
            'nip' => fn (Blueprint $table) => $table->string('nip', 50)->nullable()->after('email'),
        ]);

        self::ensureColumns('units', [
            'pimpinan' => fn (Blueprint $table) => $table->string('pimpinan')->nullable(),
            'nuptk_pimpinan' => fn (Blueprint $table) => $table->string('nuptk_pimpinan')->nullable(),
            'pimpinan_nip' => fn (Blueprint $table) => $table->string('pimpinan_nip')->nullable(),
        ]);

        self::ensureColumns('bulan_mutu_statuses', [
            'dokumen_bukti' => fn (Blueprint $table) => $table->string('dokumen_bukti')->nullable(),
        ]);

        self::ensureColumns('led_entries', [
            'status_pengisian' => fn (Blueprint $table) => $table->string('status_pengisian')->default('draft'),
            'capaian' => fn (Blueprint $table) => $table->text('capaian')->nullable(),
        ]);

        self::ensureColumns('led_entry_stages', [
            'data_spesifik' => fn (Blueprint $table) => $table->json('data_spesifik')->nullable(),
        ]);

        self::ensureColumns('auditor_assignments', [
            'peran' => fn (Blueprint $table) => $table->string('peran')->default('Auditor Ketua'),
        ]);

        self::ensureColumns('audit_sesi', [
            'tanggal_audit' => fn (Blueprint $table) => $table->date('tanggal_audit')->nullable()->after('ruang_lingkup'),
            'catatan_penutup' => fn (Blueprint $table) => $table->text('catatan_penutup')->nullable()->after('penjamin_mutu'),
            'wakil_auditi' => fn (Blueprint $table) => $table->string('wakil_auditi')->nullable(),
            'distribusi' => fn (Blueprint $table) => $table->json('distribusi')->nullable(),
            'penjamin_mutu' => fn (Blueprint $table) => $table->string('penjamin_mutu')->nullable(),
            'no_kka' => fn (Blueprint $table) => $table->string('no_kka')->nullable(),
            'kriteria' => fn (Blueprint $table) => $table->text('kriteria')->nullable(),
            'lokasi' => fn (Blueprint $table) => $table->string('lokasi')->nullable(),
            'ruang_lingkup' => fn (Blueprint $table) => $table->text('ruang_lingkup')->nullable(),
        ]);
    }

    /**
     * Add any missing columns to an existing table.
     *
     * @param  array<string, callable(Blueprint): void>  $columns
     */
    private static function ensureColumns(string $table, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $missing = [];
        foreach ($columns as $column => $definition) {
            if (! Schema::hasColumn($table, $column)) {
                $missing[] = $definition;
            }
        }

        if ($missing === []) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($missing): void {
            foreach ($missing as $definition) {
                $definition($blueprint);
            }
        });
    }

    /**
     * Broaden legacy column types where free-text periods or extended statuses are allowed.
     */
    private static function ensureColumnTypes(): void
    {
        if (Schema::hasTable('led_entry_stages') && Schema::hasColumn('led_entry_stages', 'tanggal')) {
            if (Schema::getColumnType('led_entry_stages', 'tanggal') === 'date') {
                Schema::table('led_entry_stages', function (Blueprint $table): void {
                    $table->string('tanggal', 255)->nullable()->change();
                });
            }
        }

        if (Schema::hasTable('ami_findings') && Schema::hasColumn('ami_findings', 'status_tindak_lanjut')) {
            if (Schema::getColumnType('ami_findings', 'status_tindak_lanjut') === 'enum') {
                Schema::table('ami_findings', function (Blueprint $table): void {
                    $table->string('status_tindak_lanjut', 50)->default('N/A')->change();
                });
            }
        }
    }
}
