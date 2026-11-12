<?php

namespace App\Http\Controllers;

use App\Imports\AmiImport;
use App\Models\AmiFinding;
use App\Models\AuditorAssignment;
use App\Models\BulanMutuConfig;
use App\Models\EvidenceDocument;
use App\Models\LedEntry;
use App\Models\LedEntryStage;
use App\Models\Standard;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class AmiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = AmiFinding::with('unit')->orderBy('created_at', 'desc');

        $tahun = (int) $request->query('tahun', BulanMutuConfig::activeYear());
        $availableYears = BulanMutuConfig::availableYears($tahun);
        $query->whereYear('tanggal', $tahun);

        $unit_id = $request->query('unit_id', 'all');
        if ($unit_id != 'all') {
            $query->where('unit_id', $unit_id);
        }

        $kategori = $request->query('kategori', 'all');
        if ($kategori != 'all') {
            $query->where('kategori_temuan', $kategori);
        }

        if ($user->role->kode === 'auditor') {
            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
                ->pluck('unit_id');
            $query->whereIn('unit_id', $assignedUnitIds);
        }

        $findings = $query->get();

        return view('ami.index', compact('findings', 'tahun', 'availableYears'));
    }

    public function create()
    {
        $user = auth()->user();
        $unitsQuery = Unit::query();

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $activeConfig ? $activeConfig->tahun : date('Y');

        if ($user->role->kode === 'auditor') {
            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
                ->pluck('unit_id');
            $unitsQuery->whereIn('id', $assignedUnitIds);
        }

        $units = $unitsQuery->get();
        $standards = Standard::activePeriod()->get();

        $ledEntry = null;
        $stageData = [];
        $sharedDocumentsMap = collect();
        $autoAttachedByStage = ['P1' => collect(), 'P2' => collect(), 'P3' => collect(), 'P4' => collect(), 'P5' => collect()];

        if (request('unit_id') && request('standar_kode')) {
            $ledEntry = LedEntry::where('unit_id', request('unit_id'))
                ->where('objek_kode', request('standar_kode'))
                ->where('tahun', $tahun)
                ->first();

            if ($ledEntry) {
                $stages = LedEntryStage::where('led_entry_id', $ledEntry->id)->get();
                foreach ($stages as $stage) {
                    $stageData[$stage->tahap] = $stage;
                }

                if (Schema::hasTable('evidence_documents')) {
                    $docIds = $stages->flatMap(fn (LedEntryStage $stage): array => $stage->decodedBukti()['shared_document_ids'])
                        ->unique()
                        ->values()
                        ->all();

                    if ($docIds !== []) {
                        $sharedDocumentsMap = EvidenceDocument::accessibleByUnit((int) $ledEntry->unit_id)
                            ->whereIn('id', $docIds)
                            ->get()
                            ->keyBy('id');
                    }
                }
            }

            if (Schema::hasTable('evidence_documents')) {
                $autoAttachedByStage = EvidenceDocument::autoAttachedByStage((string) request('standar_kode'), (int) $tahun);
            }
        }

        return view('ami.create', compact('units', 'standards', 'ledEntry', 'stageData', 'sharedDocumentsMap', 'autoAttachedByStage'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $activeConfig ? $activeConfig->tahun : date('Y');

        if ($user->role->kode === 'auditor') {
            $isAssigned = AuditorAssignment::where('auditor_id', $user->id)
                ->where('unit_id', $request->unit_id)
                ->where('tahun', $tahun)
                ->exists();

            if (! $isAssigned) {
                return redirect()->back()->withErrors(['error' => 'Anda tidak ditugaskan untuk mengaudit unit ini pada periode berjalan.'])->withInput();
            }
        }

        // Validation could be added here
        $data = $request->except('_token');

        $data['auditor_id'] = $user->id;

        AmiFinding::create($data);

        return redirect()->route('ami.index')->with('success', 'Temuan audit (AMI) berhasil dicatat.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new AmiImport, $request->file('file'));

            return redirect()->route('ami.index')->with('success', 'Data Instrumen AMI berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('ami.index')->withErrors(['error' => 'Gagal mengimpor data: '.$e->getMessage()]);
        }
    }

    public function updateRtl(Request $request, $id)
    {
        $finding = AmiFinding::findOrFail($id);

        $request->validate([
            'akar_penyebab' => 'nullable|string',
            'akibat' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
            'tanggapan_auditi' => 'nullable|string',
            'rencana_tindakan' => 'required|string',
            'rencana_pencegahan' => 'nullable|string',
            'pic' => 'required|string|max:255',
            'batas_waktu' => 'required|date',
        ]);

        $finding->akar_penyebab = $request->akar_penyebab;
        $finding->akibat = $request->akibat;
        $finding->rekomendasi = $request->rekomendasi;
        $finding->tanggapan_auditi = $request->tanggapan_auditi;
        $finding->rencana_tindakan = $request->rencana_tindakan;
        $finding->rencana_pencegahan = $request->rencana_pencegahan;
        $finding->pic = $request->pic;
        $finding->batas_waktu = $request->batas_waktu;
        if (in_array($finding->status_tindak_lanjut, ['Belum', 'N/A', ''], true) || ! $finding->status_tindak_lanjut) {
            $finding->status_tindak_lanjut = 'Sedang Berjalan';
        }
        $finding->save();

        return redirect()->back()->with('success', 'Rencana Tindak Lanjut (RTL) berhasil disimpan.');
    }

    public function verifyRtl(Request $request, $id)
    {
        $finding = AmiFinding::findOrFail($id);
        $findingYear = (int) date('Y', strtotime((string) $finding->tanggal));

        if (! $this->canVerifyUnitRtl((int) $finding->unit_id, $findingYear)) {
            abort(403, 'Anda tidak memiliki wewenang untuk memverifikasi RTL unit ini.');
        }

        $validated = $request->validate([
            'status_tindak_lanjut' => ['required', 'in:Belum,Sedang Berjalan,Proses,Proses PTK,Selesai,N/A'],
            'catatan_tindak_lanjut' => ['nullable', 'string'],
        ]);

        $finding->status_tindak_lanjut = $validated['status_tindak_lanjut'];
        if (array_key_exists('catatan_tindak_lanjut', $validated)) {
            $finding->catatan_tindak_lanjut = $validated['catatan_tindak_lanjut'];
        }
        $finding->save();

        return redirect()->back()->with('success', 'Verifikasi RTL berhasil diperbarui.');
    }

    public function verifyAllUnitRtl(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $validated = $request->validate([
            'tahun' => ['required', 'integer'],
            'catatan_tindak_lanjut' => ['nullable', 'string'],
        ]);

        $tahun = (int) $validated['tahun'];
        if (! $this->canVerifyUnitRtl((int) $unit->id, $tahun)) {
            abort(403, 'Anda tidak memiliki wewenang untuk memverifikasi RTL unit ini.');
        }

        $query = AmiFinding::where('unit_id', $unit->id)
            ->whereYear('tanggal', $tahun)
            ->where('status_tindak_lanjut', '!=', 'Selesai');

        $updatePayload = ['status_tindak_lanjut' => 'Selesai'];
        if (! empty($validated['catatan_tindak_lanjut'])) {
            $updatePayload['catatan_tindak_lanjut'] = $validated['catatan_tindak_lanjut'];
        }

        $updatedCount = $query->update($updatePayload);

        return redirect()
            ->route('unit.ppepp', ['id' => $unit->id, 'tahun' => $tahun])
            ->with('success', "Verifikasi RTL selesai: {$updatedCount} temuan unit {$unit->nama} tahun {$tahun} ditandai Selesai.");
    }

    private function canVerifyUnitRtl(int $unitId, int $tahun): bool
    {
        $user = auth()->user();
        $roleKode = $user->role->kode ?? '';

        if (in_array($roleKode, ['super_admin', 'lpma'], true)) {
            return true;
        }

        if (AuditorAssignment::where('auditor_id', $user->id)
            ->where('unit_id', $unitId)
            ->where('tahun', (string) $tahun)
            ->exists()) {
            return true;
        }

        return in_array($roleKode, ['auditor', 'auditor_anggota'], true)
            && ! AuditorAssignment::where('tahun', (string) $tahun)->exists();
    }

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing

        $unit_id = $request->query('unit_id', 'all');
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $query = AmiFinding::with('unit')->whereYear('tanggal', $tahun);

        if ($unit_id && $unit_id != 'all') {
            $query->where('unit_id', $unit_id);
            $unit = Unit::find($unit_id);
        } else {
            $unit = null;
        }

        if ($request->has('jenis')) {
            $jenis = $request->query('jenis');
            $query->whereHas('unit', function ($q) use ($jenis) {
                if ($jenis == 'akademik') {
                    $q->where('jenis', '!=', 'Non-Akademik');
                } else {
                    $q->where('jenis', 'Non-Akademik');
                }
            });
        }

        $findings = $query->orderBy('created_at', 'desc')->get();

        $auditors = collect();
        if ($unit) {
            $assignments = AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }

        $pdf = Pdf::loadView('ami.pdf', compact('findings', 'tahun', 'unit', 'auditors'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Laporan_AMI';
        if ($unit) {
            $filename .= '_'.str_replace(' ', '_', $unit->nama);
        }
        $filename .= '_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }

    public function exportRtlPdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing

        $unit_id = $request->query('unit_id', 'all');
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        if ($unit_id == 'all' && auth()->user()->role->kode != 'auditor' && auth()->user()->role->kode != 'lpma' && auth()->user()->role->kode != 'super_admin' && auth()->user()->role->kode != 'pimpinan') {
            $unit_id = auth()->user()->unit_id;
        }

        $query = AmiFinding::with('unit')->whereYear('tanggal', $tahun);
        if ($unit_id && $unit_id != 'all') {
            $query->where('unit_id', $unit_id);
            $unit = Unit::find($unit_id);
        } else {
            $unit = null;
        }

        // Only include findings that have RTL
        $query->whereNotNull('rencana_tindakan')->where('rencana_tindakan', '!=', '');

        $findings = $query->orderBy('created_at', 'desc')->get();

        $auditors = collect();
        if ($unit) {
            $assignments = AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }

        $pdf = Pdf::loadView('ami.rtl_pdf', compact('findings', 'tahun', 'unit', 'auditors'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Laporan_Tindak_Lanjut';
        if ($unit) {
            $filename .= '_'.str_replace(' ', '_', $unit->nama);
        }
        $filename .= '_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }

    public function exportChecklistPdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing

        $unit_id = $request->query('unit_id', 'all');
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $query = AmiFinding::with('unit')->whereYear('tanggal', $tahun);

        $unit = null;
        if ($unit_id && $unit_id != 'all') {
            $query->where('unit_id', $unit_id);
            $unit = Unit::find($unit_id);
        }

        if ($request->has('jenis')) {
            $jenis = $request->query('jenis');
            $query->whereHas('unit', function ($q) use ($jenis) {
                if ($jenis == 'akademik') {
                    $q->where('jenis', '!=', 'Non-Akademik');
                } else {
                    $q->where('jenis', 'Non-Akademik');
                }
            });
        }

        $findings = $query->orderBy('created_at', 'desc')->get();

        $auditors = collect();
        if ($unit) {
            $assignments = AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }

        // Fetch standards applicable to this unit for the checklist
        if (! $unit || stripos($unit->nama, 'LPMA') !== false) {
            $standards = Standard::activePeriod()->get();
        } else {
            $jenisUnit = $unit->jenis ?? '';
            $stQuery = Standard::activePeriod()->where(function ($q) use ($jenisUnit) {
                $q->whereNull('sasaran_unit')
                    ->orWhereJsonContains('sasaran_unit', $jenisUnit);
            });
            if (stripos($unit->nama, 'LPPM') !== false) {
                $stQuery->orWhere('kelompok', 'LIKE', '%Penelitian%')
                    ->orWhere('kelompok', 'LIKE', '%Pengabdian%');
            }
            $standards = $stQuery->get();
        }

        // Generate radar chart image via QuickChart
        $chartLabels = [];
        $chartData = [];
        $tahapanEnum = ['P1', 'P2', 'P3', 'P4', 'P5'];

        foreach ($standards as $index => $std) {
            $stdScores = [];
            foreach ($tahapanEnum as $t) {
                if ($t === 'P1') {
                    $stdScores[] = 4;
                } else {
                    if ($unit) {
                        $f = $findings->where('standar_kode', $std->kode)->where('tahap', $t)->whereNotNull('skor')->sortByDesc('created_at')->first();
                        if ($f) {
                            $stdScores[] = $f->skor;
                        }
                    } else {
                        $fs = $findings->where('standar_kode', $std->kode)->where('tahap', $t)->whereNotNull('skor');
                        if ($fs->count() > 0) {
                            $stdScores[] = $fs->avg('skor');
                        }
                    }
                }
            }
            $stdAvg = count($stdScores) > 0 ? array_sum($stdScores) / count($stdScores) : 0;
            $chartLabels[] = 'S'.($index + 1); // e.g. S1, S2
            $chartData[] = round($stdAvg, 2);
        }

        $chartUrl = '';
        if (count($chartLabels) > 0) {
            $qcData = [
                'type' => 'radar',
                'data' => [
                    'labels' => $chartLabels,
                    'datasets' => [
                        [
                            'label' => 'Rerata Pencapaian',
                            'data' => $chartData,
                            'fill' => true,
                            'backgroundColor' => 'rgba(46, 117, 182, 0.4)',
                            'borderColor' => 'rgb(46, 117, 182)',
                            'pointBackgroundColor' => 'rgb(46, 117, 182)',
                            'pointBorderColor' => '#fff',
                        ],
                    ],
                ],
                'options' => [
                    'scale' => [
                        'ticks' => ['min' => 0, 'max' => 4, 'stepSize' => 1],
                    ],
                    'plugins' => [
                        'legend' => ['display' => false],
                    ],
                ],
            ];

            $jsonPayload = json_encode(['width' => 500, 'height' => 500, 'chart' => $qcData]);

            try {
                $opts = [
                    'http' => [
                        'method' => 'POST',
                        'header' => "Content-type: application/json\r\n",
                        'content' => $jsonPayload,
                        'timeout' => 15,
                    ],
                ];
                $context = stream_context_create($opts);
                $imageData = \file_get_contents('https://quickchart.io/chart', false, $context);
                if ($imageData) {
                    $chartUrl = 'data:image/png;base64,'.base64_encode($imageData);
                }
            } catch (\Exception $e) {
                // Ignore API failures quietly
            }
        }

        $pdf = Pdf::loadView('ami.checklist_pdf', compact('findings', 'tahun', 'unit', 'auditors', 'standards', 'chartUrl'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Laporan_Checklist_AMI';
        if ($unit) {
            $filename .= '_'.str_replace(' ', '_', $unit->nama);
        }
        $filename .= '_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }

    public function exportPkaPdf(Request $request, $id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $finding = AmiFinding::with(['unit', 'standard'])->findOrFail($id);
        $unit = $finding->unit;
        $standard = $finding->standard;
        $tahun = date('Y', strtotime($finding->tanggal));

        // Fetch all findings for this standard and unit in the same year to get P1-P5 scores
        $allFindings = AmiFinding::where('unit_id', $unit->id)
            ->where('standar_kode', $finding->standar_kode)
            ->whereYear('tanggal', $tahun)
            ->get();

        $auditors = collect();
        if ($unit) {
            $assignments = AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }

        $pdf = Pdf::loadView('ami.pka_pdf', compact('finding', 'unit', 'standard', 'tahun', 'allFindings', 'auditors'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'PKA_'.$finding->standar_kode.'_'.str_replace(' ', '_', $unit->nama).'_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }
}
