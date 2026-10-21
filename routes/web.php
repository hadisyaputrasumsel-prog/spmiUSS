<?php

use App\Http\Controllers\AmiController;
use App\Http\Controllers\AuditorAssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BulanMutuController;
use App\Http\Controllers\LedController;
use App\Http\Controllers\PetunjukAmiController;
use App\Http\Controllers\RtmController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\SpmiPeriodController;
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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

// Migration helper route
Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        return response('<pre style="background:#1e1e1e;color:#4af626;padding:20px;border-radius:8px;font-family:monospace;">' . 
            "=== AUTO MIGRATE SUCCESS ===\n\n" . 
            htmlspecialchars($output ?: "Nothing to migrate (Database is up to date).\n") . 
            "\n<a href='/' style='color:#fff;text-decoration:underline;'>&larr; Kembali ke Dashboard</a>" . 
            '</pre>');
    } catch (\Throwable $e) {
        return response('<pre style="background:#1e1e1e;color:#ff5555;padding:20px;border-radius:8px;font-family:monospace;">' . 
            "=== MIGRATE ERROR ===\n\n" . 
            htmlspecialchars($e->getMessage()) . 
            "\n\nTrace:\n" . htmlspecialchars($e->getTraceAsString()) . 
            '</pre>', 500);
    }
});

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
        } catch (\Throwable $e) {
            $activeConfig = null;
        }
        if (in_array(auth()->user()->role->kode, ['lpma', 'super_admin'])) {
            $tahun = request()->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        } else {
            $tahun = $activeConfig ? $activeConfig->tahun : date('Y');
        }

        $totalStandar = DB::table('standards')->where('kelompok', '!=', 'Non-Akademik')->orWhereNull('kelompok')->count();
        $totalKategoriNA = DB::table('standards')->where('kelompok', 'Non-Akademik')->count();

        // Get P3 timeline for deadlines
        $statusP3 = BulanMutuStatus::where('tahun', $tahun)
            ->join('bulan_mutu_activities', 'bulan_mutu_statuses.kegiatan_id', '=', 'bulan_mutu_activities.id')
            ->where('bulan_mutu_activities.tahap', 'P3')
            ->select('bulan_mutu_statuses.tanggal_pelaksanaan')
            ->first();
        $batasWaktuLED = $statusP3 ? $statusP3->tanggal_pelaksanaan : null;

        // Ambil status kegiatan
        $statuses = BulanMutuStatus::where('tahun', $tahun)
            ->join('bulan_mutu_activities', 'bulan_mutu_statuses.kegiatan_id', '=', 'bulan_mutu_activities.id')
            ->orderBy('bulan_mutu_activities.index_kegiatan')
            ->select('bulan_mutu_statuses.*', 'bulan_mutu_activities.tahap', 'bulan_mutu_activities.nama as kegiatan_nama', 'bulan_mutu_activities.index_kegiatan')
            ->get();

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
        $unitsAttention = Unit::withCount(['amiFindings' => function ($q) use ($tahun) {
            $q->whereYear('tanggal', $tahun)->where('status_tindak_lanjut', '!=', 'Selesai');
        }])->having('ami_findings_count', '>', 0)
            ->take(5)->get();

        // Matriks Penilaian Standar Mutu (untuk Auditor dan LPMA)
        $matriksPenilaian = [];
        if (auth()->user()->isAuditor() || in_array(auth()->user()->role->kode, ['lpma'])) {
            $matriksPenilaian = Standard::orderBy('kelompok')->get()->groupBy('kelompok');
        }

        return view('dashboard', compact('totalStandar', 'totalKategoriNA', 'tahun', 'tahapProgress', 'activeTahap', 'tahapLabels', 'unitsAttention', 'matriksPenilaian', 'batasWaktuLED'));
    })->name('dashboard');

    // Petunjuk AMI Route (accessible by everyone in auth, update restricted in view)
    Route::get('/petunjuk-ami', [PetunjukAmiController::class, 'index'])->name('petunjuk-ami.index');
    Route::put('/petunjuk-ami', [PetunjukAmiController::class, 'update'])->name('petunjuk-ami.update');

    // Drill down unit PPEPP
    Route::get('/unit/{id}/ppepp', function ($id) {
        $unit = Unit::findOrFail($id);

        $activeConfig = null;
        try {
            if (Schema::hasTable('bulan_mutu_configs')) {
                if (Schema::hasColumn('bulan_mutu_configs', 'is_active')) {
                    $activeConfig = BulanMutuConfig::where('is_active', true)->first();
                } else {
                    $activeConfig = BulanMutuConfig::first();
                }
            }
        } catch (\Throwable $e) {
            $activeConfig = null;
        }
        $tahun = request()->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

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

        return view('unit.ppepp', compact('unit', 'tahun', 'findings', 'leds', 'microTimeline'));
    })->name('unit.ppepp');

    // Auditees, GPM/UPM, Auditor, LPMA, and Super Admin can access LED
    Route::middleware('role:auditee_upps,auditee_prodi,auditee_unit,gpm_upm,super_admin,auditor,auditor_anggota,lpma')->group(function () {
        Route::get('/led', [LedController::class, 'index'])->name('led.index');
        Route::post('/led/submit', [LedController::class, 'submit'])->name('led.submit');
        Route::get('/led/{kode}/edit', [LedController::class, 'edit'])->name('led.edit');
        Route::get('/led/{kode}/pdf', [LedController::class, 'exportPdf'])->name('led.pdf');
        Route::get('/led-full/{unit_id}/pdf', [LedController::class, 'exportFullPdf'])->name('led.pdf_full');
        Route::put('/led/{kode}', [LedController::class, 'update'])->name('led.update');

        // RTL Route for Auditee
        Route::put('/ami/{id}/rtl', [AmiController::class, 'updateRtl'])->name('ami.update-rtl');
        Route::get('/ami/rtl/pdf', [AmiController::class, 'exportRtlPdf'])->name('ami.rtl.pdf');
    });

    // Auditor, LPMA, Pimpinan and Super Admin can access AMI
    Route::middleware('role:auditor,auditor_anggota,lpma,pimpinan,super_admin')->group(function () {
        Route::get('/ami', [AmiController::class, 'index'])->name('ami.index');
        Route::get('/ami/pdf', [AmiController::class, 'exportPdf'])->name('ami.pdf');
        Route::get('/ami/checklist-pdf', [AmiController::class, 'exportChecklistPdf'])->name('ami.checklist.pdf');
        Route::get('/ami/{id}/pka-pdf', [AmiController::class, 'exportPkaPdf'])->name('ami.pka.pdf');
        Route::post('/ami/import', [AmiController::class, 'import'])->name('ami.import');
        Route::get('/ami/create', [AmiController::class, 'create'])->name('ami.create');
        Route::post('/ami', [AmiController::class, 'store'])->name('ami.store');
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
        Route::get('/rtm/cetak-berita-acara', [RtmController::class, 'generatePdf'])->name('rtm.cetak');
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
