<?php

namespace App\Http\Controllers;

use App\Models\AmiFinding;
use App\Models\AuditorAssignment;
use App\Models\AuditSesi;
use App\Models\BulanMutuActivity;
use App\Models\BulanMutuConfig;
use App\Models\BulanMutuStatus;
use App\Models\EvidenceDocument;
use App\Models\LaporanAmi;
use App\Models\LedEntry;
use App\Models\LedSubmission;
use App\Models\Standard;
use App\Models\Unit;
use App\Providers\AppServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LaporanAmiController extends Controller
{
    /**
     * Memastikan hanya LPMA dan Super Admin yang dapat mengakses.
     */
    protected function checkRole(): void
    {
        $user = auth()->user();
        if (! $user || ! in_array($user->role?->kode, ['super_admin', 'lpma'])) {
            abort(403, 'Akses ditolak. Menu ini hanya dapat diakses oleh LPMA dan Super Administrator.');
        }
    }

    /**
     * Halaman pengaturan dan isian naratif Laporan AMI.
     */
    public function index(Request $request)
    {
        $this->checkRole();

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $defaultYear = $activeConfig ? (int) $activeConfig->tahun : (int) date('Y');

        // Cari tahun-tahun yang tersedia
        $findingYears = AmiFinding::selectRaw('YEAR(tanggal) as yr')
            ->whereNotNull('tanggal')
            ->distinct()
            ->pluck('yr')
            ->toArray();

        $availableYears = array_unique(array_merge([2023, 2024, 2025, 2026, $defaultYear], $findingYears));
        rsort($availableYears);

        $tahun = (int) $request->get('tahun', in_array(2023, $availableYears) ? 2023 : $availableYears[0]);

        if (! Schema::hasTable('laporan_ami')) {
            try {
                AppServiceProvider::syncSchema();
            } catch (\Throwable $e) {
                // fallback
            }
        }

        $laporan = Schema::hasTable('laporan_ami') ? LaporanAmi::where('tahun', $tahun)->first() : null;
        if (! $laporan) {
            $laporan = new LaporanAmi(LaporanAmi::getDefaultData($tahun));
        }

        $units = Unit::orderBy('id')->get();

        // Ambil jadwal Bulan Mutu tahun ini jika ada
        $bulanMutuActivities = BulanMutuActivity::orderBy('index_kegiatan')->get();

        return view('laporan_ami.index', compact('laporan', 'tahun', 'availableYears', 'units', 'bulanMutuActivities'));
    }

    /**
     * Simpan isian naratif Laporan AMI.
     */
    public function update(Request $request)
    {
        $this->checkRole();

        $tahun = (int) $request->input('tahun');

        if (! Schema::hasTable('laporan_ami')) {
            try {
                AppServiceProvider::syncSchema();
            } catch (\Throwable $e) {
                // fallback
            }
        }

        $validated = $request->validate([
            'nomor_dokumen' => 'nullable|string|max:255',
            'tanggal_terbit' => 'nullable|date',
            'versi' => 'nullable|string|max:50',
            'kata_pengantar' => 'nullable|string',
            'dasar_hukum' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'ruang_lingkup' => 'nullable|string',
            'metode' => 'nullable|string',
            'kesimpulan' => 'nullable|string',
            'rekomendasi_pimpinan' => 'nullable|string',
            'penandatangan_lpma_nama' => 'nullable|string|max:255',
            'penandatangan_lpma_nidn' => 'nullable|string|max:50',
            'penandatangan_lpma_jabatan' => 'nullable|string|max:255',
            'penandatangan_rektor_nama' => 'nullable|string|max:255',
            'penandatangan_rektor_nidn' => 'nullable|string|max:50',
            'penandatangan_rektor_jabatan' => 'nullable|string|max:255',
            'selected_unit_ids' => 'nullable|array',
            'lampiran_options' => 'nullable|array',
            'jadwal_kegiatan' => 'nullable|array',
            'jadwal_tanggal' => 'nullable|array',
            'jadwal_pic' => 'nullable|array',
        ]);

        // Rekonstruksi jadwal_ami dari baris input
        $jadwalAmi = [];
        if (! empty($validated['jadwal_kegiatan'])) {
            foreach ($validated['jadwal_kegiatan'] as $i => $keg) {
                if (! empty($keg)) {
                    $jadwalAmi[] = [
                        'kegiatan' => $keg,
                        'tanggal' => $validated['jadwal_tanggal'][$i] ?? '-',
                        'penanggung_jawab' => $validated['jadwal_pic'][$i] ?? 'LPMA',
                    ];
                }
            }
        }

        $laporan = LaporanAmi::updateOrCreate(
            ['tahun' => $tahun],
            [
                'nomor_dokumen' => $validated['nomor_dokumen'] ?? null,
                'tanggal_terbit' => $validated['tanggal_terbit'] ?? null,
                'versi' => $validated['versi'] ?? '1.0',
                'kata_pengantar' => $validated['kata_pengantar'] ?? null,
                'dasar_hukum' => $validated['dasar_hukum'] ?? null,
                'tujuan' => $validated['tujuan'] ?? null,
                'ruang_lingkup' => $validated['ruang_lingkup'] ?? null,
                'metode' => $validated['metode'] ?? null,
                'jadwal_ami' => ! empty($jadwalAmi) ? $jadwalAmi : (LaporanAmi::getDefaultData($tahun)['jadwal_ami']),
                'kesimpulan' => $validated['kesimpulan'] ?? null,
                'rekomendasi_pimpinan' => $validated['rekomendasi_pimpinan'] ?? null,
                'penandatangan_lpma_nama' => $validated['penandatangan_lpma_nama'] ?? null,
                'penandatangan_lpma_nidn' => $validated['penandatangan_lpma_nidn'] ?? null,
                'penandatangan_lpma_jabatan' => $validated['penandatangan_lpma_jabatan'] ?? 'Kepala LPMA',
                'penandatangan_rektor_nama' => $validated['penandatangan_rektor_nama'] ?? null,
                'penandatangan_rektor_nidn' => $validated['penandatangan_rektor_nidn'] ?? null,
                'penandatangan_rektor_jabatan' => $validated['penandatangan_rektor_jabatan'] ?? 'Rektor Universitas Sumatera Selatan',
                'selected_unit_ids' => $validated['selected_unit_ids'] ?? ['all'],
                'lampiran_options' => $validated['lampiran_options'] ?? ['form4', 'form5', 'standar'],
            ]
        );

        return redirect()->route('laporan_ami.index', ['tahun' => $tahun])
            ->with('success', 'Isian naratif dan konfigurasi Laporan AMI Tahun '.$tahun.' berhasil disimpan.');
    }

    /**
     * Impor jadwal kegiatan dari modul Bulan Mutu tahun bersangkutan.
     */
    public function importJadwal(Request $request)
    {
        $this->checkRole();

        $tahun = (int) $request->input('tahun');

        if (! Schema::hasTable('laporan_ami')) {
            try {
                AppServiceProvider::syncSchema();
            } catch (\Throwable $e) {
                // fallback
            }
        }
        $activities = BulanMutuActivity::orderBy('index_kegiatan')->get();
        $statuses = BulanMutuStatus::where('tahun', $tahun)->get()->keyBy('kegiatan_id');

        $importedJadwal = [];
        foreach ($activities as $act) {
            $status = $statuses->get($act->id);
            $tgl = $status && $status->tanggal_pelaksanaan ? $status->tanggal_pelaksanaan : 'Bulan Mutu '.$tahun;

            $importedJadwal[] = [
                'kegiatan' => $act->nama,
                'tanggal' => $tgl,
                'penanggung_jawab' => $act->pic ?: 'LPMA USS',
            ];
        }

        $laporan = LaporanAmi::firstOrNew(['tahun' => $tahun], LaporanAmi::getDefaultData($tahun));
        $laporan->jadwal_ami = $importedJadwal;
        $laporan->save();

        return redirect()->route('laporan_ami.index', ['tahun' => $tahun])
            ->with('success', 'Jadwal kegiatan berhasil ditarik dari modul Bulan Mutu Tahun '.$tahun.'.');
    }

    /**
     * Mengumpulkan dan mengolah seluruh data untuk Buku Laporan AMI.
     */
    public function getLaporanData(int $tahun, array $unitIds = [], array $lampiran = []): array
    {
        $laporan = Schema::hasTable('laporan_ami') ? LaporanAmi::where('tahun', $tahun)->first() : null;
        if (! $laporan) {
            $laporan = new LaporanAmi(LaporanAmi::getDefaultData($tahun));
        }

        // Tentukan unit-unit yang masuk cakupan
        $allUnits = Unit::orderBy('id')->get();
        $unitIdsClean = array_values(array_filter(array_map('strval', $unitIds), fn ($v) => $v !== '' && $v !== null));

        $isAllUnits = empty($unitIdsClean)
            || in_array('all', $unitIdsClean, true)
            || count($unitIdsClean) >= $allUnits->count();

        if ($isAllUnits) {
            $targetUnits = $allUnits;
            $judulCakupan = 'UNIVERSITAS SUMATERA SELATAN';
            $judulDokumenLengkap = 'Buku Laporan Hasil Audit Mutu Internal (AMI) Universitas Sumatera Selatan';
            $subjudulLaporan = 'Potret Komprehensif Siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP) Standar SPMI dan Hasil Audit Mutu Internal di Lingkungan Universitas Sumatera Selatan';
            $unitScopeLabel = 'Universitas Sumatera Selatan';
        } else {
            $targetUnits = $allUnits->whereIn('id', array_map('intval', $unitIdsClean));
            if ($targetUnits->isEmpty()) {
                $targetUnits = $allUnits;
                $isAllUnits = true;
                $judulCakupan = 'UNIVERSITAS SUMATERA SELATAN';
                $judulDokumenLengkap = 'Buku Laporan Hasil Audit Mutu Internal (AMI) Universitas Sumatera Selatan';
                $subjudulLaporan = 'Potret Komprehensif Siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP) Standar SPMI dan Hasil Audit Mutu Internal di Lingkungan Universitas Sumatera Selatan';
                $unitScopeLabel = 'Universitas Sumatera Selatan';
            } elseif ($targetUnits->count() === 1) {
                $singleUnit = $targetUnits->first();
                $judulCakupan = mb_strtoupper($singleUnit->nama);
                $judulDokumenLengkap = 'Buku Laporan Hasil Audit Mutu Internal (AMI) '.$singleUnit->nama;
                $subjudulLaporan = 'Potret Komprehensif Siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP) Standar SPMI dan Hasil Audit Mutu Internal pada '.$singleUnit->nama.' Universitas Sumatera Selatan';
                $unitScopeLabel = $singleUnit->nama;
            } else {
                $unitNames = $targetUnits->pluck('nama')->implode(', ');
                $judulCakupan = mb_strtoupper($unitNames);
                $judulDokumenLengkap = 'Buku Laporan Hasil Audit Mutu Internal (AMI) '.$unitNames;
                $subjudulLaporan = 'Potret Komprehensif Siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP) Standar SPMI dan Hasil Audit Mutu Internal pada Unit Kerja Terpilih di Lingkungan Universitas Sumatera Selatan';
                $unitScopeLabel = $unitNames;
            }
        }

        $targetUnitIds = $targetUnits->pluck('id')->toArray();

        // ==========================================
        // BAB II: Pelaksanaan AMI
        // ==========================================
        // 1. Tim Auditor per Unit
        $assignments = AuditorAssignment::with(['auditor', 'unit'])
            ->where('tahun', $tahun)
            ->whereIn('unit_id', $targetUnitIds)
            ->get();

        $auditorTeams = [];
        foreach ($targetUnits as $u) {
            $unitAssigns = $assignments->where('unit_id', $u->id);
            $ketua = $unitAssigns->firstWhere('peran', 'Auditor Ketua')?->auditor
                ?? $unitAssigns->first()?->auditor;
            $anggota = $unitAssigns->filter(function ($a) use ($ketua) {
                return ! $ketua || $a->auditor_id != $ketua->id;
            })->map->auditor->filter();

            $auditorTeams[$u->id] = [
                'unit' => $u,
                'ketua' => $ketua,
                'anggota' => $anggota,
            ];
        }

        // 2. Status LED per Unit
        $ledSubmissions = LedSubmission::where('tahun', $tahun)
            ->whereIn('unit_id', $targetUnitIds)
            ->get()
            ->keyBy('unit_id');

        $ledEntries = LedEntry::where('tahun', $tahun)
            ->whereIn('unit_id', $targetUnitIds)
            ->get();

        $ledStatuses = [];
        foreach ($targetUnits as $u) {
            $uEntries = $ledEntries->where('unit_id', $u->id);
            $lengkap = $uEntries->where('status_pengisian', 'Lengkap')->count();
            $draft = $uEntries->where('status_pengisian', 'Draft')->count();
            $sub = $ledSubmissions->get($u->id);

            // Total standar relevan (default 50-57)
            $totalStd = $uEntries->count() > 0 ? $uEntries->count() : 50;
            $belum = max(0, $totalStd - ($lengkap + $draft));

            $ledStatuses[$u->id] = [
                'unit' => $u,
                'lengkap' => $lengkap,
                'draft' => $draft,
                'belum' => $belum,
                'total' => $totalStd,
                'submitted_at' => $sub ? $sub->submitted_at : null,
                'status' => $sub ? 'Sudah Diajukan' : ($lengkap > 0 ? 'Sedang Diisi' : 'Belum Mengisi'),
            ];
        }

        // ==========================================
        // BAB III: Hasil Audit
        // ==========================================
        $findings = AmiFinding::with('unit')
            ->whereIn('unit_id', $targetUnitIds)
            ->whereYear('tanggal', $tahun)
            ->get();

        // Kategori Temuan
        $categoriesList = ['Sesuai', 'Observasi (OB)', 'KTS Minor', 'KTS Mayor', 'Peluang Peningkatan (OFI)', 'Temuan Positif (TP)'];

        // Rekap Nasional / Universitas
        $rekapNasional = [];
        foreach ($categoriesList as $cat) {
            $rekapNasional[$cat] = $findings->where('kategori_temuan', $cat)->count();
        }
        $totalFindings = $findings->count();

        // Rekap per Unit
        $rekapPerUnit = [];
        foreach ($targetUnits as $u) {
            $uFinds = $findings->where('unit_id', $u->id);
            $counts = [];
            foreach ($categoriesList as $cat) {
                $counts[$cat] = $uFinds->where('kategori_temuan', $cat)->count();
            }
            $avgScore = $uFinds->count() > 0 ? round($uFinds->avg('skor'), 2) : 0;

            $rekapPerUnit[$u->id] = [
                'unit' => $u,
                'counts' => $counts,
                'total' => $uFinds->count(),
                'avg_score' => $avgScore,
            ];
        }

        // Skor Rata-rata per Kelompok Standar
        $standards = Standard::all()->keyBy('kode');
        $kelompokScores = [
            'Standar Pendidikan' => ['sum' => 0, 'count' => 0],
            'Standar Penelitian' => ['sum' => 0, 'count' => 0],
            'Standar Pengabdian kepada Masyarakat (PkM)' => ['sum' => 0, 'count' => 0],
            'Standar Tambahan' => ['sum' => 0, 'count' => 0],
        ];

        foreach ($findings as $f) {
            $std = $standards->get($f->standar_kode);
            $kel = $std ? $std->kelompok : 'Standar Pendidikan';
            if (str_contains($kel, 'Pendidikan')) {
                $key = 'Standar Pendidikan';
            } elseif (str_contains($kel, 'Penelitian')) {
                $key = 'Standar Penelitian';
            } elseif (str_contains($kel, 'PkM') || str_contains($kel, 'Pengabdian')) {
                $key = 'Standar Pengabdian kepada Masyarakat (PkM)';
            } else {
                $key = 'Standar Tambahan';
            }

            if ($f->skor !== null && $f->skor > 0) {
                $kelompokScores[$key]['sum'] += $f->skor;
                $kelompokScores[$key]['count'] += 1;
            }
        }

        $rekapKelompokStandar = [];
        foreach ($kelompokScores as $kName => $kData) {
            $rekapKelompokStandar[$kName] = $kData['count'] > 0
                ? round($kData['sum'] / $kData['count'], 2)
                : 3.50; // fallback nilai sehat jika belum ada penilaian kuantitatif
        }

        // 10 Standar dengan Temuan KTS Terbanyak
        $ktsFindings = $findings->filter(function ($item) {
            return in_array($item->kategori_temuan, ['KTS Mayor', 'KTS Minor', 'Observasi (OB)']);
        });

        $topKtsStandards = $ktsFindings->groupBy('standar_kode')->map(function ($items, $kode) use ($standards) {
            $std = $standards->get($kode);

            return [
                'kode' => $kode,
                'nama' => $std ? $std->nama : $kode,
                'mayor' => $items->where('kategori_temuan', 'KTS Mayor')->count(),
                'minor' => $items->where('kategori_temuan', 'KTS Minor')->count(),
                'ob' => $items->where('kategori_temuan', 'Observasi (OB)')->count(),
                'total' => $items->count(),
            ];
        })->sortByDesc('total')->take(10);

        // Daftar Temuan Non-Sesuai
        $nonSesuaiFindings = $findings->filter(function ($item) {
            return $item->kategori_temuan !== 'Sesuai';
        })->sortBy('unit_id');

        // ==========================================
        // BAB IV: Tindak Lanjut (RTL)
        // ==========================================
        $rtlStatuses = [];
        $totalRtlSelesai = 0;
        $totalRtlLewatWaktu = 0;
        $overdueList = [];

        foreach ($targetUnits as $u) {
            $uFinds = $findings->where('unit_id', $u->id);
            $selesai = $uFinds->where('status_tindak_lanjut', 'Selesai')->count();
            $proses = $uFinds->whereIn('status_tindak_lanjut', ['Proses', 'Sedang Berjalan'])->count();
            $ptk = $uFinds->where('status_tindak_lanjut', 'Proses PTK')->count();
            $belum = $uFinds->where('status_tindak_lanjut', 'Belum')->count();
            $uTotal = $uFinds->count();

            $pct = $uTotal > 0 ? round(($selesai / $uTotal) * 100, 1) : 100;
            $totalRtlSelesai += $selesai;

            // Cek lewat batas waktu
            $overdue = $uFinds->filter(function ($f) use ($tahun) {
                if ($f->status_tindak_lanjut === 'Selesai' || empty($f->batas_waktu)) {
                    return false;
                }

                return $f->batas_waktu < sprintf('%d-12-31', $tahun);
            });

            $totalRtlLewatWaktu += $overdue->count();
            foreach ($overdue as $od) {
                $overdueList[] = $od;
            }

            $rtlStatuses[$u->id] = [
                'unit' => $u,
                'selesai' => $selesai,
                'proses' => $proses,
                'ptk' => $ptk,
                'belum' => $belum,
                'total' => $uTotal,
                'persentase' => $pct,
            ];
        }

        $overallRtlPct = $totalFindings > 0 ? round(($totalRtlSelesai / $totalFindings) * 100, 1) : 100;

        // Tanggal audit riil untuk dicetak
        $auditSesi = Schema::hasTable('audit_sesi')
            ? AuditSesi::whereIn('unit_id', $targetUnitIds)->where('tahun', $tahun)->first()
            : null;
        $tanggalAuditTerpilih = $auditSesi && $auditSesi->tanggal_audit
            ? $auditSesi->tanggal_audit
            : ($findings->isNotEmpty() ? $findings->min('tanggal') : sprintf('%d-10-16', $tahun));

        // Sinkronkan jadwal pelaksanaan Tabel 2.1 dengan tanggal audit riil
        $jadwalList = (array) ($laporan->jadwal_ami ?: LaporanAmi::getDefaultData($tahun)['jadwal_ami']);
        if ($tanggalAuditTerpilih) {
            $formattedAuditDate = \Carbon\Carbon::parse($tanggalAuditTerpilih)->translatedFormat('d F Y');
            foreach ($jadwalList as &$j) {
                if (str_contains(strtolower($j['kegiatan'] ?? ''), 'visitasi') || str_contains(strtolower($j['kegiatan'] ?? ''), 'audit lapangan')) {
                    if (! $isAllUnits && $targetUnits->count() === 1) {
                        $j['tanggal'] = $formattedAuditDate;
                    } else {
                        $j['tanggal'] = sprintf('05 s.d. 25 Oktober %d', $tahun);
                    }
                }
            }
            unset($j);
            $laporan->jadwal_ami = $jadwalList;
        }

        return [
            'tahun' => $tahun,
            'laporan' => $laporan,
            'targetUnits' => $targetUnits,
            'isAllUnits' => $isAllUnits,
            'judulCakupan' => $judulCakupan,
            'judulDokumenLengkap' => $judulDokumenLengkap,
            'subjudulLaporan' => $subjudulLaporan,
            'unitScopeLabel' => $unitScopeLabel,
            'auditorTeams' => $auditorTeams,
            'ledStatuses' => $ledStatuses,
            'findings' => $findings,
            'totalFindings' => $totalFindings,
            'rekapNasional' => $rekapNasional,
            'rekapPerUnit' => $rekapPerUnit,
            'rekapKelompokStandar' => $rekapKelompokStandar,
            'topKtsStandards' => $topKtsStandards,
            'nonSesuaiFindings' => $nonSesuaiFindings,
            'rtlStatuses' => $rtlStatuses,
            'overallRtlPct' => $overallRtlPct,
            'totalRtlLewatWaktu' => $totalRtlLewatWaktu,
            'overdueList' => $overdueList,
            'standards' => $standards,
            'tanggalAuditTerpilih' => $tanggalAuditTerpilih,
            'lampiran' => $lampiran,
        ];
    }

    /**
     * Tampilan pratinjau buku laporan di browser.
     */
    public function preview(Request $request)
    {
        $this->checkRole();

        $tahun = (int) $request->get('tahun', 2023);
        $laporan = Schema::hasTable('laporan_ami') ? LaporanAmi::where('tahun', $tahun)->first() : null;
        $defaultUnits = $laporan?->selected_unit_ids ?: ['all'];
        $defaultLampiran = $laporan?->lampiran_options ?: ['form4', 'form5', 'daftar_hadir', 'standar'];

        $selectedUnitIds = (array) $request->get('unit_ids', $defaultUnits);
        $lampiran = (array) $request->get('lampiran', $defaultLampiran);

        $data = $this->getLaporanData($tahun, $selectedUnitIds, $lampiran);

        return view('laporan_ami.preview', $data);
    }

    /**
     * Cetak PDF Buku Laporan AMI (A4 portrait, margin 4-3-3-3).
     */
    public function exportPdf(Request $request)
    {
        $this->checkRole();

        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $tahun = (int) $request->get('tahun', 2023);
        $laporan = Schema::hasTable('laporan_ami') ? LaporanAmi::where('tahun', $tahun)->first() : null;
        $defaultUnits = $laporan?->selected_unit_ids ?: ['all'];
        $defaultLampiran = $laporan?->lampiran_options ?: ['form4', 'form5', 'daftar_hadir', 'standar'];

        $selectedUnitIds = (array) $request->get('unit_ids', $defaultUnits);
        $lampiran = (array) $request->get('lampiran', $defaultLampiran);

        $data = $this->getLaporanData($tahun, $selectedUnitIds, $lampiran);

        // Render PDF
        $pdf = Pdf::loadView('laporan_ami.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->getOptions()->set('isPhpEnabled', true);
        $pdf->getDomPDF()->getOptions()->set('isRemoteEnabled', true);

        $slugUnit = $data['isAllUnits'] ? 'Universitas' : str_replace([' ', '/', '\\', ':', '*'], '_', $data['unitScopeLabel']);
        $filename = sprintf('Buku_Laporan_AMI_%s_%d.pdf', $slugUnit, $tahun);

        if ($request->has('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    /**
     * Simpan PDF yang telah disahkan ke Bank Dokumen sebagai Dokumen Induk Universitas.
     */
    public function arsip(Request $request)
    {
        $this->checkRole();

        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $tahun = (int) $request->input('tahun', 2023);
        $data = $this->getLaporanData($tahun);

        $pdf = Pdf::loadView('laporan_ami.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->getOptions()->set('isPhpEnabled', true);
        $pdf->getDomPDF()->getOptions()->set('isRemoteEnabled', true);

        $directory = 'laporan_ami/'.$tahun;
        $filename = sprintf('Laporan_AMI_USS_%d.pdf', $tahun);
        $fullPath = $directory.'/'.$filename;

        // Simpan ke storage publik
        Storage::disk('public')->put($fullPath, $pdf->output());

        // Update record LaporanAmi
        if (Schema::hasTable('laporan_ami')) {
            $laporan = LaporanAmi::where('tahun', $tahun)->first();
            if ($laporan) {
                $laporan->file_path = $fullPath;
                $laporan->save();
            }
        }

        // Simpan ke Bank Dokumen (evidence_documents)
        EvidenceDocument::updateOrCreate(
            [
                'tahun' => $tahun,
                'judul' => sprintf('Buku Laporan Audit Mutu Internal (AMI) Tahun %d', $tahun),
            ],
            [
                'kategori' => 'Laporan / Monev',
                'scope' => 'global',
                'unit_id' => null,
                'file_path' => $fullPath,
                'original_filename' => $filename,
                'keterangan' => sprintf('Arsip Resmi Buku Laporan Hasil Audit Mutu Internal (AMI) Tahun %d Universitas Sumatera Selatan yang telah disahkan oleh Kepala LPMA dan Rektor.', $tahun),
                'uploaded_by_id' => auth()->id(),
            ]
        );

        return redirect()->route('laporan_ami.index', ['tahun' => $tahun])
            ->with('success', 'Buku Laporan AMI Tahun '.$tahun.' berhasil diarsipkan ke Bank Dokumen sebagai Dokumen Induk Universitas.');
    }
}
