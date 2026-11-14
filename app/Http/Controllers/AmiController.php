<?php

namespace App\Http\Controllers;

use App\Imports\AmiImport;
use App\Models\AmiFinding;
use App\Models\AuditorAssignment;
use App\Models\AuditSesi;
use App\Models\BulanMutuConfig;
use App\Models\EvidenceDocument;
use App\Models\LedEntry;
use App\Models\LedEntryStage;
use App\Models\Pka;
use App\Models\Standard;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class AmiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = AmiFinding::with(['unit', 'standard', 'auditor'])->orderBy('created_at', 'desc');

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $availableYears = BulanMutuConfig::availableYears($tahun);

        if ($user->role->kode === 'auditor' || $user->isAuditor()) {
            $assignedYears = AuditorAssignment::where('auditor_id', $user->id)
                ->pluck('tahun')
                ->map(fn ($y) => (int) $y)
                ->all();
            $availableYears = array_values(array_unique(array_merge($availableYears, $assignedYears)));
            rsort($availableYears);
        }

        $query->whereYear('tanggal', $tahun);

        $unit_id = $request->query('unit_id', 'all');
        if ($unit_id !== 'all' && $unit_id) {
            $query->where('unit_id', $unit_id);
        }

        $kategori = $request->query('kategori', 'all');
        if ($kategori !== 'all' && $kategori) {
            if ($kategori === 'KTS') {
                $query->where('kategori_temuan', 'LIKE', '%KTS%');
            } else {
                $query->where('kategori_temuan', $kategori);
            }
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

    public function create(Request $request)
    {
        $user = auth()->user();
        $unitsQuery = Unit::query();

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $availableYears = BulanMutuConfig::availableYears($tahun);

        if ($user->role->kode === 'auditor') {
            $assignedYears = AuditorAssignment::where('auditor_id', $user->id)
                ->pluck('tahun')
                ->map(fn ($y) => (int) $y)
                ->all();
            $availableYears = array_values(array_unique(array_merge($availableYears, $assignedYears)));
            rsort($availableYears);

            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
                ->pluck('unit_id');

            // If not found in this specific year, fall back to any assigned units so dropdown is not empty
            if ($assignedUnitIds->isEmpty()) {
                $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)->pluck('unit_id');
            }

            $unitsQuery->whereIn('id', $assignedUnitIds);
        }

        $units = $unitsQuery->orderBy('nama')->get();
        $standards = Standard::activePeriod()->get();

        $ledEntry = null;
        $stageData = [];
        $sharedDocumentsMap = collect();
        $autoAttachedByStage = ['P1' => collect(), 'P2' => collect(), 'P3' => collect(), 'P4' => collect(), 'P5' => collect()];

        if ($request->filled('unit_id') && $request->filled('standar_kode')) {
            $existingFinding = AmiFinding::where('unit_id', $request->query('unit_id'))
                ->where('standar_kode', $request->query('standar_kode'))
                ->forTahun($tahun)
                ->first();

            if ($existingFinding) {
                return redirect()->route('led.audit', [
                    'kode' => $request->query('standar_kode'),
                    'unit_id' => $request->query('unit_id'),
                    'tahun' => $tahun,
                ])->with('info', 'Standar '.$request->query('standar_kode').' sudah memiliki temuan/penilaian. Dialihkan ke halaman Audit Standar.');
            }

            $ledEntry = LedEntry::where('unit_id', $request->query('unit_id'))
                ->where('objek_kode', $request->query('standar_kode'))
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
                $autoAttachedByStage = EvidenceDocument::autoAttachedByStage((string) $request->query('standar_kode'), (int) $tahun);
            }
        }

        return view('ami.create', compact('units', 'standards', 'ledEntry', 'stageData', 'sharedDocumentsMap', 'autoAttachedByStage', 'tahun', 'availableYears'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'unit_id' => 'required|exists:units,id',
            'jenis' => 'required|string',
            'standar_kode' => 'required|string',
            'tahap' => 'required|string',
            'kategori_temuan' => 'required|string|in:Sesuai,Observasi (OB),KTS Minor,KTS Mayor,Temuan Positif (TP),Peluang Peningkatan (OFI)',
            'skor' => 'nullable|numeric|min:1|max:4',
            'uraian' => 'required|string',
            'akar_penyebab' => 'nullable|string',
            'akibat' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
            'rencana_tindakan' => 'nullable|string',
            'pic' => 'nullable|string|max:255',
            'batas_waktu' => 'nullable|date',
            'rencana_pencegahan' => 'nullable|string',
            'jadwal_pencegahan' => 'nullable|string',
        ]);

        $findingYear = (int) date('Y', strtotime($validated['tanggal']));

        if ($user->role->kode === 'auditor') {
            $isAssigned = AuditorAssignment::where('auditor_id', $user->id)
                ->where('unit_id', $validated['unit_id'])
                ->where('tahun', $findingYear)
                ->exists();

            if (! $isAssigned) {
                // If not assigned for exact year, check if assigned in any year
                $hasAnyAssignment = AuditorAssignment::where('auditor_id', $user->id)
                    ->where('unit_id', $validated['unit_id'])
                    ->exists();

                if (! $hasAnyAssignment) {
                    return redirect()->back()
                        ->withErrors(['error' => 'Anda tidak ditugaskan untuk mengaudit unit ini.'])
                        ->withInput();
                }
            }
        }

        $validated['auditor_id'] = $user->id;
        $validated['status_tindak_lanjut'] = in_array($validated['kategori_temuan'], ['Sesuai', 'Temuan Positif (TP)'], true) ? 'Selesai' : 'Belum';

        if (! Schema::hasColumn('ami_findings', 'jadwal_pencegahan')) {
            unset($validated['jadwal_pencegahan']);
        }
        if (! Schema::hasColumn('ami_findings', 'rencana_pencegahan')) {
            unset($validated['rencana_pencegahan']);
        }
        if (AmiFinding::hasTahunColumn()) {
            $validated['tahun'] = $findingYear;
        }

        try {
            AmiFinding::create($validated);
        } catch (\Throwable $e) {
            Log::error('AMI Store Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()
                ->with('error', 'Gagal mencatat temuan: basis data belum lengkap. Silakan jalankan perintah migrasi: php artisan migrate')
                ->withInput();
        }

        return redirect()->route('ami.index', ['unit_id' => $validated['unit_id'], 'tahun' => $findingYear])
            ->with('success', 'Temuan audit (AMI) berhasil dicatat.');
    }

    public function edit($id)
    {
        $finding = AmiFinding::with(['unit', 'standard'])->findOrFail($id);

        if (! $this->canModifyFinding($finding)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah temuan ini.');
        }

        if ($finding->status_tindak_lanjut === 'Selesai') {
            return redirect()->back()->with('error', 'Temuan yang verifikasi RTL-nya sudah Selesai tidak dapat diubah.');
        }

        $standards = Standard::activePeriod()->get();

        return view('ami.edit', compact('finding', 'standards'));
    }

    public function update(Request $request, $id)
    {
        $finding = AmiFinding::findOrFail($id);

        if (! $this->canModifyFinding($finding)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah temuan ini.');
        }

        if ($finding->status_tindak_lanjut === 'Selesai') {
            return redirect()->back()->with('error', 'Temuan yang verifikasi RTL-nya sudah Selesai tidak dapat diubah.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'standar_kode' => 'required|string',
            'tahap' => 'required|string',
            'kategori_temuan' => 'required|string|in:Sesuai,Observasi (OB),KTS Minor,KTS Mayor,Temuan Positif (TP),Peluang Peningkatan (OFI)',
            'skor' => 'nullable|numeric|min:1|max:4',
            'uraian' => 'required|string',
            'akar_penyebab' => 'nullable|string',
            'akibat' => 'nullable|string',
            'rekomendasi' => 'nullable|string',
            'rencana_tindakan' => 'nullable|string',
            'pic' => 'nullable|string|max:255',
            'batas_waktu' => 'nullable|date',
            'rencana_pencegahan' => 'nullable|string',
            'jadwal_pencegahan' => 'nullable|string',
        ]);

        if (! Schema::hasColumn('ami_findings', 'jadwal_pencegahan')) {
            unset($validated['jadwal_pencegahan']);
        }
        if (! Schema::hasColumn('ami_findings', 'rencana_pencegahan')) {
            unset($validated['rencana_pencegahan']);
        }

        try {
            $finding->update($validated);
        } catch (\Throwable $e) {
            Log::error('AMI Update Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui temuan: basis data belum lengkap. Silakan jalankan perintah migrasi: php artisan migrate')
                ->withInput();
        }

        $findingYear = (int) date('Y', strtotime($finding->tanggal));

        return redirect()->route('ami.index', ['unit_id' => $finding->unit_id, 'tahun' => $findingYear])
            ->with('success', 'Temuan audit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $finding = AmiFinding::findOrFail($id);

        if (! $this->canModifyFinding($finding)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus temuan ini.');
        }

        if ($finding->status_tindak_lanjut === 'Selesai' && ! in_array(auth()->user()->role->kode ?? '', ['super_admin', 'lpma'], true) && $finding->kategori_temuan !== 'Sesuai') {
            return redirect()->back()->with('error', 'Temuan non-sesuai yang verifikasi RTL-nya sudah Selesai tidak dapat dihapus.');
        }

        $unitId = $finding->unit_id;
        $findingYear = (int) date('Y', strtotime($finding->tanggal));
        $finding->delete();

        return redirect()->route('ami.index', ['unit_id' => $unitId, 'tahun' => $findingYear])
            ->with('success', 'Temuan audit berhasil dihapus.');
    }

    private function canModifyFinding(AmiFinding $finding): bool
    {
        $user = auth()->user();
        if (in_array($user->role->kode ?? '', ['super_admin', 'lpma'], true)) {
            return true;
        }

        if ($finding->auditor_id === $user->id) {
            return true;
        }

        $tahun = (int) date('Y', strtotime((string) $finding->tanggal));

        return AuditorAssignment::where('auditor_id', $user->id)
            ->where('unit_id', $finding->unit_id)
            ->where('tahun', (string) $tahun)
            ->where('peran', 'Auditor Ketua')
            ->exists();
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
            'jadwal_pencegahan' => 'nullable|string',
            'pic' => 'required|string|max:255',
            'batas_waktu' => 'required|date',
        ]);

        $finding->akar_penyebab = $request->akar_penyebab;
        $finding->akibat = $request->akibat;
        $finding->rekomendasi = $request->rekomendasi;
        $finding->tanggapan_auditi = $request->tanggapan_auditi;
        $finding->rencana_tindakan = $request->rencana_tindakan;
        if (Schema::hasColumn('ami_findings', 'rencana_pencegahan')) {
            $finding->rencana_pencegahan = $request->rencana_pencegahan;
        }
        if (Schema::hasColumn('ami_findings', 'jadwal_pencegahan')) {
            $finding->jadwal_pencegahan = $request->jadwal_pencegahan;
        }
        $finding->pic = $request->pic;
        $finding->batas_waktu = $request->batas_waktu;
        if (in_array($finding->status_tindak_lanjut, ['Belum', 'N/A', ''], true) || ! $finding->status_tindak_lanjut) {
            $finding->status_tindak_lanjut = 'Sedang Berjalan';
        }

        try {
            $finding->save();
        } catch (\Throwable $e) {
            Log::error('AMI RTL Save Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()
                ->with('error', 'Gagal menyimpan RTL: basis data belum lengkap. Silakan jalankan perintah migrasi: php artisan migrate')
                ->withInput();
        }

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
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $unit_id = $request->query('unit_id', 'all');
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $query = AmiFinding::with('unit')->whereYear('tanggal', $tahun);

        if ($unit_id && $unit_id !== 'all') {
            $query->where('unit_id', $unit_id);
            $unit = Unit::find($unit_id);
        } else {
            $unit = null;
        }

        if ($request->has('jenis')) {
            $jenis = $request->query('jenis');
            $query->whereHas('unit', function ($q) use ($jenis) {
                if ($jenis === 'akademik') {
                    $q->where('jenis', '!=', 'Non-Akademik');
                } else {
                    $q->where('jenis', 'Non-Akademik');
                }
            });
        }

        $findings = $query->orderBy('created_at', 'desc')->get();

        $auditSesi = null;
        $auditors = collect();
        if ($unit) {
            $auditSesi = Schema::hasTable('audit_sesi') ? AuditSesi::where('unit_id', $unit->id)->where('tahun', $tahun)->first() : null;
            $assignments = AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }

        // Bug 1 fix: resolve tanggal audit
        $tanggalAudit = null;
        if ($auditSesi && $auditSesi->tanggal_audit) {
            $tanggalAudit = $auditSesi->tanggal_audit->translatedFormat('d F Y');
        } elseif ($findings->isNotEmpty()) {
            $minDate = $findings->min('tanggal');
            if ($minDate) {
                $tanggalAudit = Carbon::parse($minDate)->translatedFormat('d F Y');
            }
        }
        if (! $tanggalAudit) {
            $tanggalAudit = date('d F Y');
        }

        $pdf = Pdf::loadView('ami.pdf', compact('findings', 'tahun', 'unit', 'auditors', 'auditSesi', 'tanggalAudit'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Laporan_BAP_AMI';
        if ($unit) {
            $filename .= '_'.str_replace(' ', '_', $unit->nama);
        }
        $filename .= '_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }

    public function exportKkaPdf(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $unit_id = $request->query('unit_id');
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        if (! $unit_id || $unit_id === 'all') {
            if (auth()->user()->role->kode === 'auditor') {
                $unit_id = AuditorAssignment::where('auditor_id', auth()->id())->where('tahun', $tahun)->value('unit_id');
            }
            if (! $unit_id) {
                $unit_id = Unit::first()?->id;
            }
        }

        $unit = Unit::findOrFail($unit_id);
        $auditSesi = Schema::hasTable('audit_sesi') ? AuditSesi::firstOrNew(['unit_id' => $unit->id, 'tahun' => $tahun]) : new AuditSesi(['unit_id' => $unit->id, 'tahun' => $tahun]);

        if (! $auditSesi->exists) {
            $slug = strtoupper(str_replace(['Prodi ', 'Fakultas ', ' '], ['', '', ''], $unit->nama));
            $auditSesi->no_kka = 'KKA-'.$slug.'-C6-01';
            $auditSesi->kriteria = 'Kriteria C.6 Pendidikan — IAPS 4.0 BAN-PT / Standar SPMI USS';
            $auditSesi->lokasi = 'Kampus Palembang USS';
            $auditSesi->ruang_lingkup = 'Desk Review / Telaah Dokumen — data TA '.$tahun;
            $auditSesi->distribusi = ['Auditi', 'Auditor', 'LPMA', 'Arsip'];
        }

        $assignments = AuditorAssignment::with('auditor')
            ->where('unit_id', $unit->id)
            ->where('tahun', $tahun)
            ->get();

        $ketua = $assignments->firstWhere('peran', 'Auditor Ketua')?->auditor
            ?? $assignments->first(fn ($a) => str_contains(strtolower((string) ($a->peran ?? '')), 'ketua'))?->auditor
            ?? $assignments->first()?->auditor;

        $anggota = $assignments->filter(function ($a) use ($ketua) {
            return ! $ketua || $a->auditor_id !== $ketua->id;
        })->pluck('auditor');

        $query = AmiFinding::with('standard')->where('unit_id', $unit->id)->whereYear('tanggal', $tahun);

        // Filter out Sesuai unless requested with ?semua=1
        if ($request->query('semua') !== '1') {
            $query->where('kategori_temuan', '!=', 'Sesuai');
        }

        if ($request->filled('kategori') && $request->query('kategori') !== 'all') {
            $kat = $request->query('kategori');
            if ($kat === 'KTS') {
                $query->where('kategori_temuan', 'LIKE', '%KTS%');
            } else {
                $query->where('kategori_temuan', $kat);
            }
        }

        $findings = $query->orderBy('created_at', 'asc')->get();

        $pdf = Pdf::loadView('ami.form4_kka_pdf', compact('unit', 'tahun', 'auditSesi', 'ketua', 'anggota', 'findings'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Form4_KKA_Ringkasan_Temuan_'.str_replace(' ', '_', $unit->nama).'_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }

    public function exportRtlPdf(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $unit_id = $request->query('unit_id');
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        if (! $unit_id || $unit_id === 'all') {
            if (auth()->user()->role->kode === 'auditor') {
                $unit_id = AuditorAssignment::where('auditor_id', auth()->id())->where('tahun', $tahun)->value('unit_id');
            } elseif (! in_array(auth()->user()->role->kode, ['lpma', 'super_admin', 'pimpinan'], true)) {
                $unit_id = auth()->user()->unit_id;
            }
            if (! $unit_id) {
                $unit_id = Unit::first()?->id;
            }
        }

        $unit = Unit::findOrFail($unit_id);
        $auditSesi = Schema::hasTable('audit_sesi') ? AuditSesi::firstOrNew(['unit_id' => $unit->id, 'tahun' => $tahun]) : new AuditSesi(['unit_id' => $unit->id, 'tahun' => $tahun]);

        if (! $auditSesi->exists) {
            $slug = strtoupper(str_replace(['Prodi ', 'Fakultas ', ' '], ['', '', ''], $unit->nama));
            $auditSesi->no_kka = 'KKA-'.$slug.'-C6-01';
            $auditSesi->kriteria = 'Kriteria C.6 Pendidikan — IAPS 4.0 BAN-PT / Standar SPMI USS';
            $auditSesi->lokasi = 'Kampus Palembang USS';
            $auditSesi->ruang_lingkup = 'Desk Review / Telaah Dokumen — data TA '.$tahun;
            $auditSesi->distribusi = ['Auditi', 'Auditor', 'LPMA', 'Arsip'];
        }

        $assignments = AuditorAssignment::with('auditor')
            ->where('unit_id', $unit->id)
            ->where('tahun', $tahun)
            ->get();

        $ketua = $assignments->firstWhere('peran', 'Auditor Ketua')?->auditor
            ?? $assignments->first(fn ($a) => str_contains(strtolower((string) ($a->peran ?? '')), 'ketua'))?->auditor
            ?? $assignments->first()?->auditor;

        $anggota = $assignments->filter(function ($a) use ($ketua) {
            return ! $ketua || $a->auditor_id !== $ketua->id;
        })->pluck('auditor');

        $query = AmiFinding::with('standard')->where('unit_id', $unit->id)->whereYear('tanggal', $tahun);

        // Filter: default exclude Sesuai
        $kategoriParam = $request->query('kategori');
        if ($kategoriParam) {
            if ($kategoriParam === 'KTS') {
                $query->where('kategori_temuan', 'LIKE', '%KTS%');
            } elseif ($kategoriParam !== 'all') {
                $query->where('kategori_temuan', $kategoriParam);
            }
        } else {
            $query->where('kategori_temuan', '!=', 'Sesuai');
        }

        $findings = $query->orderBy('created_at', 'asc')->get();

        $pdf = Pdf::loadView('ami.rtl_pdf', compact('unit', 'tahun', 'auditSesi', 'ketua', 'anggota', 'findings'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Form5_Deskripsi_Temuan_'.str_replace(' ', '_', $unit->nama).'_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }

    public function exportChecklistPdf(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $unit_id = $request->query('unit_id', 'all');
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $query = AmiFinding::with('unit')->whereYear('tanggal', $tahun);

        $unit = null;
        if ($unit_id && $unit_id !== 'all') {
            $query->where('unit_id', $unit_id);
            $unit = Unit::find($unit_id);
        }

        if ($request->has('jenis')) {
            $jenis = $request->query('jenis');
            $query->whereHas('unit', function ($q) use ($jenis) {
                if ($jenis === 'akademik') {
                    $q->where('jenis', '!=', 'Non-Akademik');
                } else {
                    $q->where('jenis', 'Non-Akademik');
                }
            });
        }

        $findings = $query->orderBy('created_at', 'desc')->get();

        $auditSesi = null;
        $auditors = collect();
        if ($unit) {
            $auditSesi = Schema::hasTable('audit_sesi') ? AuditSesi::where('unit_id', $unit->id)->where('tahun', $tahun)->first() : null;
            $assignments = AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }

        // Bug 1 fix: resolve tanggal audit
        $tanggalAudit = null;
        if ($auditSesi && $auditSesi->tanggal_audit) {
            $tanggalAudit = $auditSesi->tanggal_audit->translatedFormat('d F Y');
        } elseif ($findings->isNotEmpty()) {
            $minDate = $findings->min('tanggal');
            if ($minDate) {
                $tanggalAudit = Carbon::parse($minDate)->translatedFormat('d F Y');
            }
        }
        if (! $tanggalAudit) {
            $tanggalAudit = date('d F Y');
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

        // Generate radar chart data with Bug 2 fix (Check for 'Umum' and don't hardcode P1=4)
        $chartLabels = [];
        $chartData = [];

        foreach ($standards as $index => $std) {
            $stdScores = [];
            $fUmum = $findings->where('standar_kode', $std->kode)->where('tahap', 'Umum')->whereNotNull('skor')->sortByDesc('created_at')->first();
            if ($fUmum) {
                $stdScores[] = (float) $fUmum->skor;
            } else {
                foreach (['P1', 'P2', 'P3', 'P4', 'P5'] as $t) {
                    $f = $findings->where('standar_kode', $std->kode)->where('tahap', $t)->whereNotNull('skor')->sortByDesc('created_at')->first();
                    if ($f) {
                        $stdScores[] = (float) $f->skor;
                    }
                }
            }
            $stdAvg = count($stdScores) > 0 ? array_sum($stdScores) / count($stdScores) : 0;
            $chartLabels[] = 'S'.($index + 1);
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
                        'timeout' => 10,
                    ],
                ];
                $context = stream_context_create($opts);
                $imageData = @file_get_contents('https://quickchart.io/chart', false, $context);
                if ($imageData) {
                    $chartUrl = 'data:image/png;base64,'.base64_encode($imageData);
                }
            } catch (\Exception $e) {
                // Ignore API failures quietly
            }
        }

        $pdf = Pdf::loadView('ami.checklist_pdf', compact('findings', 'tahun', 'unit', 'auditors', 'standards', 'chartUrl', 'auditSesi', 'tanggalAudit'));
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
        $tahun = (int) date('Y', strtotime($finding->tanggal));

        // Check if there is an official PKA for this unit and standard
        $pka = null;
        if (Schema::hasTable('pka')) {
            $pka = Pka::with(['unit', 'penyusun', 'pemeriksa', 'langkah'])
                ->where('unit_id', $unit->id)
                ->where('tahun', $tahun)
                ->where(function ($q) use ($finding) {
                    $q->where('standar_kode', $finding->standar_kode)
                        ->orWhere('nomor', 'LIKE', '%'.$finding->standar_kode.'%');
                })
                ->first();

            if (! $pka) {
                // Check if any PKA exists for this unit and year
                $pka = Pka::with(['unit', 'penyusun', 'pemeriksa', 'langkah'])
                    ->where('unit_id', $unit->id)
                    ->where('tahun', $tahun)
                    ->first();
            }
        }

        if ($pka) {
            return app(PkaController::class)->exportPdf($pka->id);
        }

        // Fallback to legacy single finding PKA template
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
