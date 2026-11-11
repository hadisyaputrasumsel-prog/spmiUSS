<?php

namespace App\Providers;

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
        $flagPath = storage_path('framework/schema_synced_v3.flag');
        if (file_exists($flagPath)) {
            return;
        }

        try {
            $this->ensureSchemaIsSynced();
            @file_put_contents($flagPath, date('c'));
        } catch (\Throwable $e) {
            Log::warning('Auto schema sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Ensure missing tables and columns are created even if partial manual fixes blocked migrate.
     */
    private function ensureSchemaIsSynced(): void
    {
        if (! Schema::hasTable('migrations')) {
            Artisan::call('migrate', ['--force' => true]);
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
        } else {
            Schema::table('settings', function (Blueprint $table): void {
                if (! Schema::hasColumn('settings', 'alamat_kampus')) {
                    $table->text('alamat_kampus')->nullable();
                }
                if (! Schema::hasColumn('settings', 'email_kampus')) {
                    $table->string('email_kampus')->nullable();
                }
                if (! Schema::hasColumn('settings', 'telepon_kampus')) {
                    $table->string('telepon_kampus')->nullable();
                }
                if (! Schema::hasColumn('settings', 'website_kampus')) {
                    $table->string('website_kampus')->nullable();
                }
                if (! Schema::hasColumn('settings', 'logo_kampus')) {
                    $table->string('logo_kampus')->nullable();
                }
                if (! Schema::hasColumn('settings', 'petunjuk_ami')) {
                    $table->longText('petunjuk_ami')->nullable();
                }
                if (! Schema::hasColumn('settings', 'nama_rektor')) {
                    $table->string('nama_rektor')->nullable();
                }
                if (! Schema::hasColumn('settings', 'nuptk_rektor')) {
                    $table->string('nuptk_rektor')->nullable();
                }
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

        if (Schema::hasTable('bulan_mutu_configs') && ! Schema::hasColumn('bulan_mutu_configs', 'is_active')) {
            Schema::table('bulan_mutu_configs', function (Blueprint $table): void {
                $table->boolean('is_active')->default(false)->after('tahun');
            });
        }

        if (Schema::hasTable('standards')) {
            Schema::table('standards', function (Blueprint $table): void {
                if (! Schema::hasColumn('standards', 'periode')) {
                    $table->string('periode')->nullable();
                }
                if (! Schema::hasColumn('standards', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
                if (! Schema::hasColumn('standards', 'sasaran_unit')) {
                    $table->json('sasaran_unit')->nullable();
                }
                if (! Schema::hasColumn('standards', 'is_akademik')) {
                    $table->boolean('is_akademik')->default(true);
                }
                if (! Schema::hasColumn('standards', 'indikator')) {
                    $table->text('indikator')->nullable();
                }
                if (! Schema::hasColumn('standards', 'penanggung_jawab')) {
                    $table->string('penanggung_jawab')->nullable();
                }
                if (! Schema::hasColumn('standards', 'indikator_p1')) {
                    $table->text('indikator_p1')->nullable();
                }
                if (! Schema::hasColumn('standards', 'indikator_p2')) {
                    $table->text('indikator_p2')->nullable();
                }
                if (! Schema::hasColumn('standards', 'indikator_p3')) {
                    $table->text('indikator_p3')->nullable();
                }
                if (! Schema::hasColumn('standards', 'indikator_p4')) {
                    $table->text('indikator_p4')->nullable();
                }
                if (! Schema::hasColumn('standards', 'indikator_p5')) {
                    $table->text('indikator_p5')->nullable();
                }
                if (! Schema::hasColumn('standards', 'rubrik_penilaian')) {
                    $table->json('rubrik_penilaian')->nullable();
                }
                if (! Schema::hasColumn('standards', 'target')) {
                    $table->text('target')->nullable();
                }
                if (! Schema::hasColumn('standards', 'acuan')) {
                    $table->string('acuan')->nullable();
                }
                if (! Schema::hasColumn('standards', 'template_dokumen')) {
                    $table->string('template_dokumen')->nullable();
                }
            });
        }

        if (Schema::hasTable('ami_findings')) {
            Schema::table('ami_findings', function (Blueprint $table): void {
                if (! Schema::hasColumn('ami_findings', 'skor')) {
                    $table->tinyInteger('skor')->nullable();
                }
                if (! Schema::hasColumn('ami_findings', 'akar_penyebab')) {
                    $table->text('akar_penyebab')->nullable();
                }
                if (! Schema::hasColumn('ami_findings', 'akibat')) {
                    $table->text('akibat')->nullable();
                }
                if (! Schema::hasColumn('ami_findings', 'rekomendasi')) {
                    $table->text('rekomendasi')->nullable();
                }
                if (! Schema::hasColumn('ami_findings', 'tanggapan_auditi')) {
                    $table->string('tanggapan_auditi')->nullable();
                }
                if (! Schema::hasColumn('ami_findings', 'rencana_pencegahan')) {
                    $table->text('rencana_pencegahan')->nullable();
                }
            });
        }

        if (Schema::hasTable('units')) {
            Schema::table('units', function (Blueprint $table): void {
                if (! Schema::hasColumn('units', 'pimpinan')) {
                    $table->string('pimpinan')->nullable();
                }
                if (! Schema::hasColumn('units', 'nuptk_pimpinan')) {
                    $table->string('nuptk_pimpinan')->nullable();
                }
            });
        }

        if (Schema::hasTable('bulan_mutu_statuses') && ! Schema::hasColumn('bulan_mutu_statuses', 'dokumen_bukti')) {
            Schema::table('bulan_mutu_statuses', function (Blueprint $table): void {
                $table->string('dokumen_bukti')->nullable();
            });
        }

        if (Schema::hasTable('led_entries')) {
            Schema::table('led_entries', function (Blueprint $table): void {
                if (! Schema::hasColumn('led_entries', 'status_pengisian')) {
                    $table->string('status_pengisian')->default('draft');
                }
                if (! Schema::hasColumn('led_entries', 'capaian')) {
                    $table->text('capaian')->nullable();
                }
            });
        }

        if (Schema::hasTable('led_entry_stages') && ! Schema::hasColumn('led_entry_stages', 'data_spesifik')) {
            Schema::table('led_entry_stages', function (Blueprint $table): void {
                $table->json('data_spesifik')->nullable();
            });
        }

        if (Schema::hasTable('auditor_assignments') && ! Schema::hasColumn('auditor_assignments', 'peran')) {
            Schema::table('auditor_assignments', function (Blueprint $table): void {
                $table->string('peran')->default('Auditor Ketua');
            });
        }
    }
}
