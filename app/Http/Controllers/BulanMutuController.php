<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BulanMutuActivity;
use App\Models\BulanMutuStatus;

class BulanMutuController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $activities = BulanMutuActivity::orderBy('index_kegiatan', 'asc')->get();
        
        // Get statuses for this year
        $statuses = BulanMutuStatus::where('tahun', $tahun)->get()->keyBy('kegiatan_id');
        
        $availableYears = BulanMutuStatus::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
        if (!in_array(date('Y'), $availableYears)) {
            $availableYears[] = (int)date('Y');
        }
        rsort($availableYears);
        
        return view('bulan_mutu.index', compact('activities', 'statuses', 'tahun', 'availableYears'));
    }
    
    public function generate(Request $request)
    {
        $request->validate([
            'tahun_generate' => 'required|numeric'
        ]);

        $tahun = $request->input('tahun_generate');
        $userId = auth()->id();

        // Cek apakah sudah digenerate
        if (BulanMutuStatus::where('tahun', $tahun)->exists()) {
            return redirect()->back()->with('error', 'Siklus Bulan Mutu untuk tahun ' . $tahun . ' sudah pernah digenerate.');
        }

        $activities = BulanMutuActivity::all();
        foreach ($activities as $activity) {
            BulanMutuStatus::create([
                'tahun' => $tahun,
                'kegiatan_id' => $activity->id,
                'status' => 'Belum Dilaksanakan',
                'updated_by_id' => $userId
            ]);
        }

        return redirect()->route('bulan-mutu.index', ['tahun' => $tahun])->with('success', 'Siklus Bulan Mutu tahun ' . $tahun . ' berhasil digenerate.');
    }

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $tahun = $request->query('tahun', date('Y'));
        $activities = BulanMutuActivity::orderBy('index_kegiatan', 'asc')->get();
        $statuses = BulanMutuStatus::where('tahun', $tahun)->get()->keyBy('kegiatan_id');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bulan_mutu.pdf', compact('activities', 'statuses', 'tahun'));
        return $pdf->stream('Jadwal_Bulan_Mutu_'.$tahun.'.pdf');
    }

    public function pantauLed(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $kegiatan_id = $request->query('kegiatan_id'); // ID of the 'Evaluasi Diri' activity
        
        $units = \App\Models\Unit::orderBy('jenis')->orderBy('nama')->get();
        $submissions = \App\Models\LedSubmission::with('unit')->where('tahun', $tahun)->get()->keyBy('unit_id');

        // Check if there is at least one submission
        $hasSubmissions = $submissions->count() > 0;

        return view('bulan_mutu.pantau_led', compact('tahun', 'units', 'submissions', 'hasSubmissions', 'kegiatan_id'));
    }

    public function pantauAudit(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $kegiatan_id = $request->query('kegiatan_id');
        $activity = BulanMutuActivity::findOrFail($kegiatan_id);
        
        $isAkademik = str_contains(strtolower($activity->nama), 'akademik') && !str_contains(strtolower($activity->nama), 'non');
        
        if ($isAkademik) {
            $units = \App\Models\Unit::where('jenis', 'Akademik')->orderBy('nama')->get();
        } else {
            $units = \App\Models\Unit::where('jenis', '!=', 'Akademik')->orderBy('nama')->get();
        }

        $assignments = \App\Models\AuditorAssignment::with('auditor')->where('tahun', $tahun)->get()->groupBy('unit_id');
        $findings = \App\Models\AmiFinding::whereYear('tanggal', $tahun)->get()->groupBy('unit_id');

        return view('bulan_mutu.pantau_audit', compact('tahun', 'units', 'assignments', 'findings', 'kegiatan_id', 'activity'));
    }

    public function exportTemplatePdf(Request $request, $id)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $tahun = $request->query('tahun', date('Y'));
        $activity = BulanMutuActivity::findOrFail($id);
        $nama = strtolower($activity->nama);

        if (str_contains($nama, 'surat tugas tim auditor')) {
            $assignments = \App\Models\AuditorAssignment::with(['auditor', 'unit'])
                                ->where('tahun', $tahun)
                                ->get()
                                ->groupBy(function($item) {
                                    return $item->auditor ? $item->auditor->name : 'Unknown';
                                });
                                
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bulan_mutu.template_surat_tugas', compact('activity', 'tahun', 'assignments'));
            return $pdf->stream('Surat_Tugas_Auditor_'.$tahun.'.pdf');
        
        } elseif (str_contains($nama, 'evaluasi diri')) {
            $units = \App\Models\Unit::orderBy('jenis')->orderBy('nama')->get();
            $submissions = \App\Models\LedSubmission::where('tahun', $tahun)->get()->keyBy('unit_id');
            
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bulan_mutu.template_rekap_led', compact('activity', 'tahun', 'units', 'submissions'));
            return $pdf->stream('Rekapitulasi_LED_'.$tahun.'.pdf');
        
        } elseif (str_contains($nama, 'pelaksanaan audit mutu akademik internal')) {
            return redirect()->route('ami.pdf', ['tahun' => $tahun, 'jenis' => 'akademik']);
            
        } elseif (str_contains($nama, 'pelaksanaan audit mutu internal unit non-akademik')) {
            return redirect()->route('ami.pdf', ['tahun' => $tahun, 'jenis' => 'nonakademik']);
            
        } elseif (str_contains($nama, 'laporan hasil amai/emi')) {
            return redirect()->route('ami.pdf', ['tahun' => $tahun]);
        }

        // Generic Template with Daftar Hadir
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bulan_mutu.template', compact('activity', 'tahun'));
        return $pdf->stream('Template_'.$activity->index_kegiatan.'_'.$tahun.'.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $tahun = $request->input('tahun', date('Y'));
        $userId = auth()->id(); 

        $statusInput = $request->input('status');
        $existing = BulanMutuStatus::where('tahun', $tahun)->where('kegiatan_id', $id)->first();

        // VALIDATION: Force upload if marked as Terlaksana
        if (in_array($statusInput, ['Terlaksana Sesuai Rencana', 'Terlaksana - Tertunda'])) {
            if (!$request->hasFile('dokumen_bukti') && !($existing && $existing->dokumen_bukti)) {
                return redirect()->back()->with('error', 'Pembaruan gagal! Untuk menandai kegiatan sebagai "Terlaksana", Anda diwajibkan mengunggah Dokumen Bukti (Evidence) yang sudah ditandatangani.');
            }
        }

        $data = [
            'status' => $statusInput,
            'tanggal_pelaksanaan' => $request->input('tanggal_pelaksanaan'),
            'catatan' => $request->input('catatan'),
            'updated_by_id' => $userId
        ];

        if ($request->hasFile('dokumen_bukti')) {
            $file = $request->file('dokumen_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/bulan_mutu_dokumen', $filename);
            $data['dokumen_bukti'] = str_replace('public/', 'storage/', $path);
        }

        BulanMutuStatus::updateOrCreate(
            ['tahun' => $tahun, 'kegiatan_id' => $id],
            $data
        );

        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui.');
    }

    public function setActiveYear(Request $request)
    {
        $tahun = $request->input('tahun');
        
        \App\Models\BulanMutuConfig::query()->update(['is_active' => false]);
        
        \App\Models\BulanMutuConfig::updateOrCreate(
            ['tahun' => $tahun],
            ['is_active' => true, 'updated_by_id' => auth()->id()]
        );

        return redirect()->back()->with('success', 'Tahun ' . $tahun . ' sekarang ditetapkan sebagai tahun pelaksanaan aktif.');
    }
}
