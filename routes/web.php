<?php

use App\Http\Controllers\AmiController;
use App\Http\Controllers\AuditorAssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BulanMutuController;
use App\Http\Controllers\LedController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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
        $activeConfig = \App\Models\BulanMutuConfig::where('is_active', true)->first();
        $tahun = $activeConfig ? $activeConfig->tahun : date('Y');
        
        $totalStandar = DB::table('standards')->where('kelompok', '!=', 'Non-Akademik')->orWhereNull('kelompok')->count();
        $totalKategoriNA = DB::table('standards')->where('kelompok', 'Non-Akademik')->count();

        // Ambil konfigurasi siklus SPMI tahun ini
        $bulanMutuConfig = $activeConfig ?? \App\Models\BulanMutuConfig::where('tahun', $tahun)->first();
        
        // Ambil status kegiatan
        $statuses = \App\Models\BulanMutuStatus::where('tahun', $tahun)
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
            'P5' => 'PENINGKATAN'
        ];

        $tahapProgress = [
            'P1' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P2' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P3' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P4' => ['status' => 'Belum dimulai', 'kegiatan' => []],
            'P5' => ['status' => 'Belum dimulai', 'kegiatan' => []],
        ];

        foreach($statuses as $st) {
            $t = $st->tahap;
            $tahapProgress[$t]['kegiatan'][] = $st;
        }

        $activeFound = false;
        foreach($tahapProgress as $t => &$data) {
            if(count($data['kegiatan']) == 0) continue;
            
            $allDone = true;
            $anyStarted = false;
            foreach($data['kegiatan'] as $k) {
                if(str_contains($k->status, 'Terlaksana')) {
                    $anyStarted = true;
                } else {
                    $allDone = false;
                }
            }

            if($allDone) {
                $data['status'] = 'Selesai';
            } elseif($anyStarted) {
                $data['status'] = 'Berjalan';
                if(!$activeFound) {
                    $activeTahap = $t;
                    $activeFound = true;
                }
            } else {
                $data['status'] = 'Belum dimulai';
                if(!$activeFound && $t != 'P1') { // If none started, P1 is active by default. If we reach here and it's not active, maybe previous is done and this is next.
                    // Check if previous was done
                    $prev = 'P' . (intval(substr($t, 1)) - 1);
                    if(isset($tahapProgress[$prev]) && $tahapProgress[$prev]['status'] == 'Selesai') {
                        $activeTahap = $t;
                        $activeFound = true;
                    }
                }
            }
        }
        
        // Final fallback if all done
        if(!$activeFound && $tahapProgress['P5']['status'] == 'Selesai') {
            $activeTahap = 'P5';
        }

        // Ambil unit yang memerlukan perhatian (contoh: ada temuan RTL belum selesai)
        $unitsAttention = \App\Models\Unit::withCount(['amiFindings' => function($q) use ($tahun) {
            $q->where('status_tindak_lanjut', '!=', 'Selesai'); // assume incomplete
        }])->having('ami_findings_count', '>', 0)
          ->take(5)->get();

        // Matriks Penilaian Standar Mutu (untuk Auditor dan LPMA)
        $matriksPenilaian = [];
        if (in_array(auth()->user()->role->kode, ['auditor', 'lpma', 'super_admin'])) {
            $matriksPenilaian = \App\Models\Standard::orderBy('kelompok')->get()->groupBy('kelompok');
        }

        return view('dashboard', compact('totalStandar', 'totalKategoriNA', 'tahun', 'tahapProgress', 'activeTahap', 'tahapLabels', 'unitsAttention', 'matriksPenilaian'));
    })->name('dashboard');

    // Drill down unit PPEPP
    Route::get('/unit/{id}/ppepp', function($id) {
        $unit = \App\Models\Unit::findOrFail($id);
        
        $activeConfig = \App\Models\BulanMutuConfig::where('is_active', true)->first();
        $tahun = request()->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        
        $findings = \App\Models\AmiFinding::where('unit_id', $id)->get();
        $leds = \App\Models\LedEntry::where('unit_id', $id)->where('tahun', $tahun)->get();
        $hasAuditor = \App\Models\AuditorAssignment::where('unit_id', $id)->where('tahun', $tahun)->exists();
        
        // Kalkulasi Micro-Timeline 7-Langkah
        $hasLeds = $leds->count() > 0;
        $hasFindings = $findings->count() > 0;
        
        $allRtlStarted = $hasFindings && $findings->every(function($f) {
            return $f->status_tindak_lanjut != 'Belum' && $f->status_tindak_lanjut != '';
        });
        
        $allRtlFinished = $hasFindings && $findings->every(function($f) {
            return $f->status_tindak_lanjut == 'Selesai';
        });

        $microTimeline = [
            'ed' => $hasLeds ? 'done' : 'active',
            'verifikasi_evidence' => $hasLeds ? 'done' : 'pending',
            'desk_evaluation' => $hasAuditor ? 'done' : ($hasLeds ? 'active' : 'pending'),
            'visitasi' => $hasFindings ? 'done' : ($hasAuditor ? 'active' : 'pending'),
            'closing' => $hasFindings ? 'done' : 'pending',
            'rtl' => $allRtlStarted ? 'done' : ($hasFindings ? 'active' : 'pending'),
            'verifikasi_rtl' => $allRtlFinished ? 'done' : ($allRtlStarted ? 'active' : 'pending')
        ];

        return view('unit.ppepp', compact('unit', 'tahun', 'findings', 'leds', 'microTimeline'));
    })->name('unit.ppepp');

    // Auditees, GPM/UPM, Auditor, LPMA, and Super Admin can access LED
    Route::middleware('role:auditee_upps,auditee_prodi,auditee_unit,gpm_upm,super_admin,auditor,lpma')->group(function () {
        Route::get('/led', [LedController::class, 'index'])->name('led.index');
        Route::get('/led/{kode}/edit', [LedController::class, 'edit'])->name('led.edit');
        Route::get('/led/{kode}/pdf', [LedController::class, 'exportPdf'])->name('led.pdf');
        Route::put('/led/{kode}', [LedController::class, 'update'])->name('led.update');
        
        // RTL Route for Auditee
        Route::put('/ami/{id}/rtl', [AmiController::class, 'updateRtl'])->name('ami.update-rtl');
    });

    // Auditor, LPMA, Pimpinan and Super Admin can access AMI
    Route::middleware('role:auditor,lpma,pimpinan,super_admin')->group(function () {
        Route::get('/ami', [AmiController::class, 'index'])->name('ami.index');
        Route::post('/ami/import', [AmiController::class, 'import'])->name('ami.import');
        Route::get('/ami/create', [AmiController::class, 'create'])->name('ami.create');
        Route::post('/ami', [AmiController::class, 'store'])->name('ami.store');
    });

    // LPMA and Super Admin can update Bulan Mutu, others can only view
    Route::get('/bulan-mutu', [BulanMutuController::class, 'index'])->name('bulan-mutu.index');
    Route::get('/bulan-mutu/pdf', [BulanMutuController::class, 'exportPdf'])->name('bulan-mutu.pdf');
    Route::middleware('role:lpma,super_admin')->group(function () {
        Route::put('/bulan-mutu/{id}/status', [BulanMutuController::class, 'updateStatus'])->name('bulan-mutu.status');
        Route::post('/bulan-mutu/set-active', [BulanMutuController::class, 'setActiveYear'])->name('bulan-mutu.set-active');
    });

    // LPMA and Super Admin routes for system setup
    Route::middleware('role:lpma,super_admin')->group(function () {
        Route::get('/rtm/cetak-berita-acara', [\App\Http\Controllers\RtmController::class, 'generatePdf'])->name('rtm.cetak');
        Route::resource('standar-mutu', StandardController::class)->except(['show']);
        Route::resource('manajemen-auditee', UnitController::class)->only(['index', 'store', 'destroy']);
        
        Route::get('/penugasan-auditor', [AuditorAssignmentController::class, 'index'])->name('penugasan-auditor.index');
        Route::post('/penugasan-auditor', [AuditorAssignmentController::class, 'store'])->name('penugasan-auditor.store');
        Route::delete('/penugasan-auditor/{id}', [AuditorAssignmentController::class, 'destroy'])->name('penugasan-auditor.destroy');
    });

    // Super Admin only routes
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('akun', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});
