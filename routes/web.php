<?php

use App\Http\Controllers\AmiController;
use App\Http\Controllers\AuditorAssignmentController;
use App\Http\Controllers\AuditSesiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BulanMutuController;
use App\Http\Controllers\EvidenceDocumentController;
use App\Http\Controllers\LaporanAmiController;
use App\Http\Controllers\LedController;
use App\Http\Controllers\PetunjukAmiController;
use App\Http\Controllers\PkaController;
use App\Http\Controllers\RtmController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SpmiPeriodController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Models\AmiFinding;
use App\Models\AuditorAssignment;
use App\Models\BulanMutuConfig;
use App\Models\BulanMutuStatus;
use App\Models\LedEntry;
use App\Models\LedSubmission;
use App\Models\Standard;
use App\Models\Unit;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

// Public storage fallback in case symlink public/storage is missing or blocked by web server
Route::get('/storage/{path}', function (string $path) {
    $cleanPath = ltrim(str_replace('\\', '/', $path), '/');
    if ($cleanPath === '' || str_contains($cleanPath, '..')) {
        abort(404);
    }

    if (Storage::disk('public')->exists($cleanPath)) {
        return response()->file(Storage::disk('public')->path($cleanPath));
    }

    // Legacy fallback for files uploaded before explicit 'public' disk was specified
    if (
        (str_starts_with($cleanPath, 'bulan_mutu_dokumen/') || str_starts_with($cleanPath, 'led_documents/'))
        && Storage::disk('local')->exists('public/'.$cleanPath)
    ) {
        return response()->file(Storage::disk('local')->path('public/'.$cleanPath));
    }

    abort(404);
})->where('path', '.*');



// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $user = auth()->user();
        // Redirect auditee to their own unit dashboard
        if ($user && in_array($user->role->kode, ['auditee_upps', 'auditee_prodi', 'auditee_unit', 'gpm_upm'])) {
            if ($user->unit_id) {
                return redirect()->route('unit.ppepp', $user->unit_id);
            }
        }

        // Ambil konfigurasi siklus SPMI tahun aktif
        $activeConfig = null;
        try {
            if (Schema::hasTable('bulan_mutu_configs')) {
                if (Schema::hasColumn('bulan_mutu_configs', 'is_active')) {
                    $activeConfig = BulanMutuConfig::where('is_active', true)->first();
                } else {
                    $activeConfig = BulanMutuConfig::first();
                }
            }
        } catch (Throwable $e) {
            $activeConfig = null;
        }
        if (in_array(auth()->user()->role->kode, ['lpma', 'super_admin', 'auditor', 'auditor_anggota']) || auth()->user()->isAuditor()) {
            $tahun = (int) request()->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        } else {
            $tahun = $activeConfig ? $activeConfig->tahun : date('Y');
        }

        $totalStandar = Schema::hasTable('standards') ? DB::table('standards')->where('kelompok', '!=', 'Non-Akademik')->orWhereNull('kelompok')->count() : 0;
        $totalKategoriNA = Schema::hasTable('standards') ? DB::table('standards')->where('kelompok', 'Non-Akademik')->count() : 0;

        // Get P3 timeline for deadlines
        $statusP3 = null;
        $statuses = collect();
        if (Schema::hasTable('bulan_mutu_statuses') && Schema::hasTable('bulan_mutu_activities')) {
            $statusP3 = BulanMutuStatus::where('tahun', $tahun)
                ->join('bulan_mutu_activities', 'bulan_mutu_statuses.kegiatan_id', '=', 'bulan_mutu_activities.id')
                ->where('bulan_mutu_activities.tahap', 'P3')
                ->select('bulan_mutu_statuses.tanggal_pelaksanaan')
                ->first();

            $statuses = BulanMutuStatus::where('tahun', $tahun)
                ->join('bulan_mutu_activities', 'bulan_mutu_statuses.kegiatan_id', '=', 'bulan_mutu_activities.id')
                ->orderBy('bulan_mutu_activities.index_kegiatan')
                ->select('bulan_mutu_statuses.*', 'bulan_mutu_activities.tahap', 'bulan_mutu_activities.nama as kegiatan_nama', 'bulan_mutu_activities.index_kegiatan')
                ->get();
        }
        $batasWaktuLED = $statusP3 ? $statusP3->tanggal_pelaksanaan : null;

        // Tentukan tahap aktif (tahap terakhir yang statusnya belum selesai atau sedang berjalan)
        // Default ke P1 jika kosong
        $activeTahap = 'P1';
        $tahapLabels = [
            'P1' => 'PENETAPAN',
            'P2' => 'PELAKSANAAN',
            'P3' => 'EVALUASI / AMI',
            'P4' => 'PENGENDALIAN',
            'P5' => 'PENINGKATAN',
        ];

        $tahapProgress = [
            'P1' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P2' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P3' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P4' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P5' => ['status' => 'Belum dimulai', 'kegiatan' => []],
        ];

        foreach ($statuses as $st) {
            $t = $st->tahap;
            $tahapProgress[$t]['kegiatan'][] = $st;
        }

        $activeFound = false;
        foreach ($tahapProgress as $t => &$data) {
            if (count($data['kegiatan']) == 0) {
                continue;
            }

            $allDone = true;
            $anyStarted = false;
            foreach ($data['kegiatan'] as $k) {
                if (str_contains($k->status, 'Terlaksana')) {
                    $anyStarted = true;
                } else {
                    $allDone = false;
                }
            }

            if ($allDone) {
                $data['status'] = 'Selesai';
            } elseif ($anyStarted) {
                $data['status'] = 'Berjalan';
                if (! $activeFound) {
                    $activeTahap = $t;
                    $activeFound = true;
                }
            } else {
                $data['status'] = 'Belum dimulai';
                if (! $activeFound && $t != 'P1') { // If none started, P1 is active by default. If we reach here and it's not active, maybe previous is done and this is next.
                    // Check if previous was done
                    $prev = 'P'.(intval(substr($t, 1)) - 1);
                    if (isset($tahapProgress[$prev]) && $tahapProgress[$prev]['status'] == 'Selesai') {
                        $activeTahap = $t;
                        $activeFound = true;
                    }
                }
            }
        }

        // Final fallback if all done
        if (! $activeFound && $tahapProgress['P5']['status'] == 'Selesai') {
            $activeTahap = 'P5';
        }

        // Ambil unit yang memerlukan perhatian (contoh: ada temuan RTL belum selesai)
        $unitsAttention = collect();
        if (Schema::hasTable('units') && Schema::hasTable('ami_findings')) {
            $attentionFilter = function ($q) use ($tahun): void {
                $q->whereYear('tanggal', $tahun)->where('status_tindak_lanjut', '!=', 'Selesai');
            };
            $unitsAttention = Unit::whereHas('amiFindings', $attentionFilter)
                ->withCount(['amiFindings' => $attentionFilter])
                ->take(5)
                ->get();
        }

        // Matriks Penilaian Standar Mutu (untuk Auditor dan LPMA)
        $matriksPenilaian = [];
        if (Schema::hasTable('standards') && (auth()->user()->isAuditor() || in_array(auth()->user()->role->kode, ['lpma']))) {
            $matriksPenilaian = Standard::orderBy('kelompok')->get()->groupBy('kelompok');
        }

        return view('dashboard', compact('totalStandar', 'totalKategoriNA', 'tahun', 'tahapProgress', 'activeTahap', 'tahapLabels', 'unitsAttention', 'matriksPenilaian', 'batasWaktuLED'));
    })->name('dashboard');

    // Petunjuk AMI Route (accessible by everyone in auth, update restricted in view)
    Route::get('/petunjuk-ami', [PetunjukAmiController::class, 'index'])->name('petunjuk-ami.index');
    Route::put('/petunjuk-ami', [PetunjukAmiController::class, 'update'])->name('petunjuk-ami.update');

    // Bank Dokumen & Pustaka Evidence Unit (3-tier isolation enforced in controller)
    Route::get('/bank-dokumen', [EvidenceDocumentController::class, 'index'])->name('evidence-documents.index');
    Route::post('/bank-dokumen', [EvidenceDocumentController::class, 'store'])->name('evidence-documents.store');
    Route::get('/bank-dokumen/{evidenceDocument}/download', [EvidenceDocumentController::class, 'download'])->name('evidence-documents.download');
    Route::delete('/bank-dokumen/{evidenceDocument}', [EvidenceDocumentController::class, 'destroy'])->name('evidence-documents.destroy');

    // Drill down unit PPEPP
    Route::get('/unit/{id}/ppepp', function ($id) {
        $unit = Unit::findOrFail($id);
        $tahun = (int) request()->query('tahun', BulanMutuConfig::activeYear());
        $availableYears = BulanMutuConfig::availableYears($tahun);

        $findings = AmiFinding::where('unit_id', $id)->whereYear('tanggal', $tahun)->get();
        $leds = LedEntry::where('unit_id', $id)->where('tahun', $tahun)->get();
        $hasAuditor = AuditorAssignment::where('unit_id', $id)->where('tahun', $tahun)->exists();

        $hasSubmitted = LedSubmission::where('unit_id', $id)->where('tahun', $tahun)->exists();
        $hasLeds = $leds->count() > 0;
        $hasFindings = $findings->count() > 0;

        $allRtlStarted = $hasFindings && $findings->every(function ($f) {
            return $f->status_tindak_lanjut != 'Belum' && $f->status_tindak_lanjut != '';
        });

        $allRtlFinished = $hasFindings && $findings->every(function ($f) {
            return $f->status_tindak_lanjut == 'Selesai';
        });

        $microTimeline = [
            'ed' => $hasSubmitted ? 'done' : 'active',
            'verifikasi_evidence' => $hasSubmitted ? 'done' : 'pending',
            'desk_evaluation' => ($hasSubmitted && $hasAuditor) ? ($hasFindings ? 'done' : 'active') : 'pending',
            'visitasi' => $hasFindings ? 'done' : (($hasSubmitted && $hasAuditor) ? 'active' : 'pending'),
            'closing' => $hasFindings ? 'done' : 'pending',
            'rtl' => $allRtlStarted ? 'done' : ($hasFindings ? 'active' : 'pending'),
            'verifikasi_rtl' => $allRtlFinished ? 'done' : ($allRtlStarted ? 'active' : 'pending'),
        ];

        return view('unit.ppepp', compact('unit', 'tahun', 'availableYears', 'findings', 'leds', 'microTimeline'));
    })->name('unit.ppepp');

    // Auditees, GPM/UPM, Auditor, LPMA, and Super Admin can access LED
    Route::middleware('role:auditee_upps,auditee_prodi,auditee_unit,gpm_upm,super_admin,auditor,auditor_anggota,lpma')->group(function () {
        Route::get('/led', [LedController::class, 'index'])->name('led.index');
        Route::post('/led/submit', [LedController::class, 'submit'])->name('led.submit');
        Route::get('/led/{kode}/audit', [LedController::class, 'showAudit'])->name('led.audit');
        Route::put('/led/{kode}/audit', [LedController::class, 'updateAudit'])->name('led.audit.update');
        Route::get('/led/{kode}/edit', [LedController::class, 'edit'])->name('led.edit');
        Route::get('/led/{kode}/pdf', [LedController::class, 'exportPdf'])->name('led.pdf');
        Route::get('/led-full/{unit_id}/pdf', [LedController::class, 'exportFullPdf'])->name('led.pdf_full');
        Route::put('/led/{kode}', [LedController::class, 'update'])->name('led.update');

        // RTL Route for Auditee
        Route::put('/ami/{id}/rtl', [AmiController::class, 'updateRtl'])->name('ami.update-rtl');
        Route::get('/ami/rtl/pdf', [AmiController::class, 'exportRtlPdf'])->name('ami.rtl.pdf');
    });

    // Auditor, LPMA, Pimpinan and Super Admin can access AMI & PKA
    Route::middleware('role:auditor,auditor_anggota,lpma,pimpinan,super_admin')->group(function () {
        Route::get('/ami', [AmiController::class, 'index'])->name('ami.index');
        Route::get('/ami/pdf', [AmiController::class, 'exportPdf'])->name('ami.pdf');
        Route::get('/ami/kka-pdf', [AmiController::class, 'exportKkaPdf'])->name('ami.kka.pdf');
        Route::get('/ami/checklist-pdf', [AmiController::class, 'exportChecklistPdf'])->name('ami.checklist.pdf');
        Route::get('/ami/{id}/pka-pdf', [AmiController::class, 'exportPkaPdf'])->name('ami.pka.pdf');
        Route::post('/ami/import', [AmiController::class, 'import'])->name('ami.import');
        Route::get('/ami/create', [AmiController::class, 'create'])->name('ami.create');
        Route::post('/ami', [AmiController::class, 'store'])->name('ami.store');
        Route::get('/ami/{id}/edit', [AmiController::class, 'edit'])->name('ami.edit');
        Route::put('/ami/{id}', [AmiController::class, 'update'])->name('ami.update');
        Route::delete('/ami/{id}', [AmiController::class, 'destroy'])->name('ami.destroy');

        // Data Sesi Audit
        Route::get('/ami/audit-sesi', [AuditSesiController::class, 'edit'])->name('audit-sesi.edit');
        Route::post('/ami/audit-sesi', [AuditSesiController::class, 'update'])->name('audit-sesi.update');

        // Program Kerja Audit (PKA)
        Route::get('/pka', [PkaController::class, 'index'])->name('pka.index');
        Route::get('/pka/create', [PkaController::class, 'create'])->name('pka.create');
        Route::post('/pka', [PkaController::class, 'store'])->name('pka.store');
        Route::get('/pka/{id}/edit', [PkaController::class, 'edit'])->name('pka.edit');
        Route::put('/pka/{id}', [PkaController::class, 'update'])->name('pka.update');
        Route::delete('/pka/{id}', [PkaController::class, 'destroy'])->name('pka.destroy');
        Route::get('/pka/{id}/pdf', [PkaController::class, 'exportPdf'])->name('pka.export-pdf');
        Route::get('/pka/unit/{unit_id}/pdf', [PkaController::class, 'exportUnitPdf'])->name('pka.export-unit-pdf');
    });

    // Auditor, assigned auditors, LPMA, and Super Admin can verify RTL
    Route::middleware('role:auditor,auditor_anggota,lpma,super_admin')->group(function () {
        Route::put('/ami/{id}/verify-rtl', [AmiController::class, 'verifyRtl'])->name('ami.verify-rtl');
        Route::post('/unit/{id}/verify-all-rtl', [AmiController::class, 'verifyAllUnitRtl'])->name('unit.verify-all-rtl');
    });

    // LPMA and Super Admin can update Bulan Mutu, others can only view
    Route::get('/bulan-mutu', [BulanMutuController::class, 'index'])->name('bulan-mutu.index');
    Route::get('/bulan-mutu/pdf', [BulanMutuController::class, 'exportPdf'])->name('bulan-mutu.pdf');
    Route::middleware('role:lpma,super_admin')->group(function () {
        Route::post('/bulan-mutu/generate', [BulanMutuController::class, 'generate'])->name('bulan-mutu.generate');
        Route::get('/bulan-mutu/pantau-led', [BulanMutuController::class, 'pantauLed'])->name('bulan-mutu.pantau-led');
        Route::get('/bulan-mutu/pantau-audit', [BulanMutuController::class, 'pantauAudit'])->name('bulan-mutu.pantau-audit');
        Route::put('/bulan-mutu/{id}/status', [BulanMutuController::class, 'updateStatus'])->name('bulan-mutu.status');
        Route::post('/bulan-mutu/set-active', [BulanMutuController::class, 'setActiveYear'])->name('bulan-mutu.set-active');
        Route::get('/bulan-mutu/{id}/template', [BulanMutuController::class, 'exportTemplatePdf'])->name('bulan-mutu.template');
    });

    // LPMA and Super Admin routes for system setup
    Route::middleware('role:lpma,super_admin')->group(function () {
        // Buku Laporan AMI
        Route::get('/laporan-ami', [LaporanAmiController::class, 'index'])->name('laporan_ami.index');
        Route::post('/laporan-ami', [LaporanAmiController::class, 'update'])->name('laporan_ami.update');
        Route::post('/laporan-ami/import-jadwal', [LaporanAmiController::class, 'importJadwal'])->name('laporan_ami.import-jadwal');
        Route::get('/laporan-ami/preview', [LaporanAmiController::class, 'preview'])->name('laporan_ami.preview');
        Route::get('/laporan-ami/pdf', [LaporanAmiController::class, 'exportPdf'])->name('laporan_ami.pdf');
        Route::post('/laporan-ami/arsip', [LaporanAmiController::class, 'arsip'])->name('laporan_ami.arsip');

        Route::get('/rtm/cetak-berita-acara', [RtmController::class, 'generatePdf'])->name('rtm.cetak');
        Route::get('standar-mutu/buku-pdf', [StandardController::class, 'bukuPdf'])->name('standar-mutu.buku-pdf');
        Route::resource('standar-mutu', StandardController::class)->except(['show']);
        Route::post('spmi-period', [SpmiPeriodController::class, 'store'])->name('spmi-period.store');
        Route::put('spmi-period/{id}', [SpmiPeriodController::class, 'update'])->name('spmi-period.update');
        Route::post('spmi-period/{id}/activate', [SpmiPeriodController::class, 'activate'])->name('spmi-period.activate');
        Route::resource('manajemen-auditee', UnitController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

        Route::get('/penugasan-auditor', [AuditorAssignmentController::class, 'index'])->name('penugasan-auditor.index');
        Route::post('/penugasan-auditor', [AuditorAssignmentController::class, 'store'])->name('penugasan-auditor.store');
        Route::put('/penugasan-auditor/{id}', [AuditorAssignmentController::class, 'update'])->name('penugasan-auditor.update');
        Route::delete('/penugasan-auditor/{id}', [AuditorAssignmentController::class, 'destroy'])->name('penugasan-auditor.destroy');
    });

    // Super Admin only routes
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('akun', UserController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
    });
});
