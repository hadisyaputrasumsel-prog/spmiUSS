<?php

namespace App\Http\Controllers;

use App\Models\AmiFinding;
use App\Models\AuditorAssignment;
use App\Models\AuditSesi;
use App\Models\BulanMutuActivity;
use App\Models\BulanMutuConfig;
use App\Models\BulanMutuStatus;
use App\Models\LaporanAmi;
use App\Models\LedSubmission;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BulanMutuController extends Controller
{
    public function index(Request $request)
    {
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $activities = BulanMutuActivity::orderBy('index_kegiatan', 'asc')->get();

        // Get statuses for this year
        $statuses = BulanMutuStatus::where('tahun', $tahun)->get()->keyBy('kegiatan_id');

        $availableYears = BulanMutuStatus::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
        if (! in_array(date('Y'), $availableYears)) {
            $availableYears[] = (int) date('Y');
        }
        rsort($availableYears);

        return view('bulan_mutu.index', compact('activities', 'statuses', 'tahun', 'availableYears'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tahun_generate' => 'required|numeric',
        ]);

        $tahun = $request->input('tahun_generate');
        $userId = auth()->id();

        // Cek apakah sudah digenerate
        if (BulanMutuStatus::where('tahun', $tahun)->exists()) {
            return redirect()->back()->with('error', 'Siklus Bulan Mutu untuk tahun '.$tahun.' sudah pernah digenerate.');
        }

        $activities = BulanMutuActivity::all();
        foreach ($activities as $activity) {
            BulanMutuStatus::create([
                'tahun' => $tahun,
                'kegiatan_id' => $activity->id,
                'status' => 'Belum Dilaksanakan',
                'updated_by_id' => $userId,
            ]);
        }

        return redirect()->route('bulan-mutu.index', ['tahun' => $tahun])->with('success', 'Siklus Bulan Mutu tahun '.$tahun.' berhasil digenerate.');
    }

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $activities = BulanMutuActivity::orderBy('index_kegiatan', 'asc')->get();
        $statuses = BulanMutuStatus::where('tahun', $tahun)->get()->keyBy('kegiatan_id');

        $pdf = Pdf::loadView('bulan_mutu.pdf', compact('activities', 'statuses', 'tahun'));

        return $pdf->stream('Jadwal_Bulan_Mutu_'.$tahun.'.pdf');
    }

    public function pantauLed(Request $request)
    {
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $kegiatan_id = $request->query('kegiatan_id'); // ID of the 'Evaluasi Diri' activity

        $units = Unit::orderBy('jenis')->orderBy('nama')->get();
        $submissions = LedSubmission::with('unit')->where('tahun', $tahun)->get()->keyBy('unit_id');

        // Check if there is at least one submission
        $hasSubmissions = $submissions->count() > 0;

        return view('bulan_mutu.pantau_led', compact('tahun', 'units', 'submissions', 'hasSubmissions', 'kegiatan_id'));
    }

    public function pantauAudit(Request $request)
    {
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $kegiatan_id = $request->query('kegiatan_id');
        $activity = BulanMutuActivity::findOrFail($kegiatan_id);

        $isAkademik = str_contains(strtolower($activity->nama), 'akademik') && ! str_contains(strtolower($activity->nama), 'non');

        if ($isAkademik) {
            $units = Unit::where('jenis', 'Akademik')->orderBy('nama')->get();
        } else {
            $units = Unit::where('jenis', '!=', 'Akademik')->orderBy('nama')->get();
        }

        $assignments = AuditorAssignment::with('auditor')->where('tahun', $tahun)->get()->groupBy('unit_id');
        $findings = AmiFinding::whereYear('tanggal', $tahun)->get()->groupBy('unit_id');

        return view('bulan_mutu.pantau_audit', compact('tahun', 'units', 'assignments', 'findings', 'kegiatan_id', 'activity'));
    }

    public function exportTemplatePdf(Request $request, $id)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $activity = BulanMutuActivity::findOrFail($id);
        $nama = strtolower($activity->nama);

        if (str_contains($nama, 'surat tugas tim auditor') || str_contains($nama, 'pelaksanaan audit mutu')) {
            $status = BulanMutuStatus::where('tahun', $tahun)->where('kegiatan_id', $activity->id)->first();

            $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $bulanRomawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

            if ($status && $status->tanggal_pelaksanaan) {
                $time = strtotime($status->tanggal_pelaksanaan);
                $m = (int) date('n', $time);
                $y = (int) date('Y', $time);
                $rom = $bulanRomawi[$m] ?? 'VIII';
                $nomorSurat = sprintf('024/ST-AMI/LPMA/USS/%s/%d', $rom, $y);
                $tanggalSurat = date('j', $time).' '.$bulanIndo[$m].' '.$y;
                $tanggalAudit = date('j', $time).' s.d. '.date('j', strtotime('+20 days', $time)).' '.$bulanIndo[$m].' '.$y;
            } else {
                $nomorSurat = sprintf('.../ST-AMI/LPMA/USS/VIII/%d', $tahun);
                $tanggalSurat = '01 Agustus '.$tahun;
                $tanggalAudit = '01 s.d. 25 Agustus '.$tahun;
            }

            $laporan = LaporanAmi::where('tahun', $tahun)->first() ?? LaporanAmi::first();
            $kepalaLpmaNama = $laporan?->penandatangan_lpma_nama ?? 'Dr. H. Ahmad Fauzi, M.Pd';
            $kepalaLpmaJabatan = $laporan?->penandatangan_lpma_jabatan ?? 'Kepala Lembaga Penjaminan Mutu Akademik (LPMA)';
            $rektorNama = $laporan?->penandatangan_rektor_nama ?? 'Prof. Dr. Ir. H. M. Syahril, M.S.';
            $rektorJabatan = $laporan?->penandatangan_rektor_jabatan ?? 'Rektor Universitas Sumatera Selatan';

            $prodiUnits = Unit::where('jenis', 'Program Studi')->orderBy('nama')->get();
            $fakultasUnits = Unit::where('jenis', 'Fakultas')->orderBy('nama')->get();
            $nonAkademikUnits = Unit::whereNotIn('jenis', ['Program Studi', 'Fakultas', 'Universitas'])->orderBy('nama')->get();
            $units = Unit::where('jenis', '!=', 'Universitas')->orderBy('jenis')->orderBy('nama')->get();

            $assignments = AuditorAssignment::with(['auditor', 'unit'])
                ->where('tahun', $tahun)
                ->get();

            $unitAssignments = [];
            foreach ($units as $u) {
                $ketua = $assignments->where('unit_id', $u->id)->where('peran', 'Auditor Ketua')->first()?->auditor?->name;
                $anggota = $assignments->where('unit_id', $u->id)->where('peran', 'Auditor Anggota')->first()?->auditor?->name;

                if (! $ketua || ! $anggota) {
                    $sesi = AuditSesi::where('unit_id', $u->id)->where('tahun', $tahun)->first();
                    if ($sesi) {
                        $ketua = $ketua ?: $sesi->auditor_ketua_nama;
                        $anggota = $anggota ?: $sesi->auditor_anggota_nama;
                    }
                }

                $unitAssignments[$u->id] = [
                    'ketua' => $ketua ?: 'Tim Auditor LPMA',
                    'anggota' => $anggota ?: '-',
                ];
            }

            $selectedUnitsRaw = $request->query('unit_ids', []);
            $selectedUnitIds = is_array($selectedUnitsRaw) ? $selectedUnitsRaw : explode(',', (string) $selectedUnitsRaw);
            $selectedUnitIds = array_filter(array_map('intval', $selectedUnitIds));

            $pdf = Pdf::loadView('bulan_mutu.template_surat_tugas', compact(
                'activity',
                'tahun',
                'status',
                'nomorSurat',
                'tanggalSurat',
                'tanggalAudit',
                'kepalaLpmaNama',
                'kepalaLpmaJabatan',
                'rektorNama',
                'rektorJabatan',
                'prodiUnits',
                'fakultasUnits',
                'nonAkademikUnits',
                'units',
                'unitAssignments',
                'selectedUnitIds'
            ));

            return $pdf->stream('Surat_Tugas_Auditor_'.$tahun.'.pdf');

        } elseif (str_contains($nama, 'evaluasi diri')) {
            $status = BulanMutuStatus::where('tahun', $tahun)->where('kegiatan_id', $activity->id)->first();

            $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $bulanRomawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

            if ($status && $status->tanggal_pelaksanaan) {
                $time = strtotime($status->tanggal_pelaksanaan);
                $m = (int) date('n', $time);
                $y = (int) date('Y', $time);
                $rom = $bulanRomawi[$m] ?? 'VII';
                $nomorSurat = sprintf('038/SE-LED/LPMA/USS/%s/%d', $rom, $y);
                $tanggalSurat = date('j', $time).' '.$bulanIndo[$m].' '.$y;
                $tanggalMulai = $tanggalSurat;
                $selesaiTime = strtotime('+16 days', $time);
                $tanggalSelesai = date('j', $selesaiTime).' '.$bulanIndo[(int) date('n', $selesaiTime)].' '.$y;
            } else {
                $nomorSurat = sprintf('.../SE-LED/LPMA/USS/VII/%d', $tahun);
                $tanggalSurat = '05 Juli '.$tahun;
                $tanggalMulai = '07 Juli '.$tahun;
                $tanggalSelesai = '28 Juli '.$tahun;
            }

            $laporan = LaporanAmi::where('tahun', $tahun)->first() ?? LaporanAmi::first();
            $kepalaLpmaNama = $laporan?->penandatangan_lpma_nama ?? 'Dr. H. Ahmad Fauzi, M.Pd';
            $kepalaLpmaJabatan = $laporan?->penandatangan_lpma_jabatan ?? 'Kepala Lembaga Penjaminan Mutu Akademik (LPMA)';

            $prodiUnits = Unit::where('jenis', 'Program Studi')->orderBy('nama')->get();
            $fakultasUnits = Unit::where('jenis', 'Fakultas')->orderBy('nama')->get();
            $nonAkademikUnits = Unit::whereNotIn('jenis', ['Program Studi', 'Fakultas', 'Universitas'])->orderBy('nama')->get();
            $units = Unit::where('jenis', '!=', 'Universitas')->orderBy('jenis')->orderBy('nama')->get();

            $selectedUnitsRaw = $request->query('unit_ids', []);
            $selectedUnitIds = is_array($selectedUnitsRaw) ? $selectedUnitsRaw : explode(',', (string) $selectedUnitsRaw);
            $selectedUnitIds = array_filter(array_map('intval', $selectedUnitIds));

            $pdf = Pdf::loadView('bulan_mutu.template_surat_edaran_led', compact(
                'activity',
                'tahun',
                'status',
                'nomorSurat',
                'tanggalSurat',
                'tanggalMulai',
                'tanggalSelesai',
                'kepalaLpmaNama',
                'kepalaLpmaJabatan',
                'prodiUnits',
                'fakultasUnits',
                'nonAkademikUnits',
                'units',
                'selectedUnitIds'
            ));

            return $pdf->stream('Surat_Edaran_Pengisian_LED_'.$tahun.'.pdf');

        } elseif (str_contains($nama, 'laporan hasil amai/emi')) {
            return redirect()->route('ami.pdf', ['tahun' => $tahun]);
        }

        // Generic Template with Daftar Hadir
        $pdf = Pdf::loadView('bulan_mutu.template', compact('activity', 'tahun'));

        return $pdf->stream('Template_'.$activity->index_kegiatan.'_'.$tahun.'.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        BulanMutuActivity::findOrFail($id);

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $defaultTahun = $activeConfig ? $activeConfig->tahun : date('Y');

        $validated = $request->validate([
            'tahun' => ['nullable', 'integer'],
            'status' => ['required', 'in:Belum Dilaksanakan,Terlaksana Sesuai Rencana,Terlaksana - Tertunda,Dibatalkan'],
            'tanggal_pelaksanaan' => ['nullable', 'date'],
            'dokumen_bukti' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg', 'max:5120'],
            'catatan' => ['nullable', 'string'],
        ]);

        $tahun = (int) ($validated['tahun'] ?? $defaultTahun);
        $userId = auth()->id();

        $statusInput = $validated['status'];
        $existing = BulanMutuStatus::where('tahun', $tahun)->where('kegiatan_id', $id)->first();

        // VALIDATION: Force upload if marked as Terlaksana
        if (in_array($statusInput, ['Terlaksana Sesuai Rencana', 'Terlaksana - Tertunda'], true)) {
            if (! $request->hasFile('dokumen_bukti') && ! ($existing && $existing->dokumen_bukti)) {
                return redirect()
                    ->route('bulan-mutu.index', ['tahun' => $tahun])
                    ->with('error', 'Pembaruan gagal! Untuk menandai kegiatan sebagai "Terlaksana", Anda diwajibkan mengunggah Dokumen Bukti (Evidence) yang sudah ditandatangani.');
            }
        }

        $data = [
            'status' => $statusInput,
            'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'updated_by_id' => $userId,
        ];

        if ($request->hasFile('dokumen_bukti')) {
            $path = $request->file('dokumen_bukti')->store('bulan_mutu_dokumen', 'public');
            $data['dokumen_bukti'] = 'storage/'.$path;
        }

        BulanMutuStatus::updateOrCreate(
            ['tahun' => $tahun, 'kegiatan_id' => $id],
            $data
        );

        return redirect()
            ->route('bulan-mutu.index', ['tahun' => $tahun])
            ->with('success', 'Status kegiatan berhasil diperbarui.');
    }

    public function setActiveYear(Request $request)
    {
        $tahun = $request->input('tahun');

        BulanMutuConfig::query()->update(['is_active' => false]);

        BulanMutuConfig::updateOrCreate(
            ['tahun' => $tahun],
            ['is_active' => true, 'updated_by_id' => auth()->id()]
        );

        return redirect()->back()->with('success', 'Tahun '.$tahun.' sekarang ditetapkan sebagai tahun pelaksanaan aktif.');
    }
}
