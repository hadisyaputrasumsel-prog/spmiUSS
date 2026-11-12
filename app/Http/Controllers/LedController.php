<?php

namespace App\Http\Controllers;

use App\Models\AuditorAssignment;
use App\Models\BulanMutuConfig;
use App\Models\EvidenceDocument;
use App\Models\LedEntry;
use App\Models\LedEntryStage;
use App\Models\LedSubmission;
use App\Models\Standard;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $unit_id = $request->query('unit_id', 'all');
        $status = $request->query('status', 'all');
        if (! in_array($user->role->kode, ['super_admin', 'lpma', 'auditor'])) {
            $unit_id = $user->unit_id; // Force unit for non-admins
        }

        $assignedUnitIds = [];
        if ($user->role->kode == 'auditor') {
            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
                ->pluck('unit_id')->toArray();

            if ($unit_id == 'all' || ! in_array($unit_id, $assignedUnitIds)) {
                $unit_id = count($assignedUnitIds) > 0 ? $assignedUnitIds[0] : -1;
            }
        }

        if (in_array($user->role->kode, ['super_admin', 'lpma', 'auditor'])) {
            if ($unit_id && $unit_id != 'all' && $unit_id != -1) {
                $unit = Unit::find($unit_id);

                if ($unit && stripos($unit->nama, 'LPMA') !== false) {
                    // LPMA manages all university assurance & accreditation
                    $standardsQuery = Standard::activePeriod();
                } else {
                    $jenisUnit = $unit->jenis ?? '';
                    $standardsQuery = Standard::activePeriod()->where(function ($q) use ($jenisUnit) {
                        $q->whereNull('sasaran_unit')
                            ->orWhereJsonContains('sasaran_unit', $jenisUnit);
                    });

                    if ($unit && stripos($unit->nama, 'LPPM') !== false) {
                        $standardsQuery->orWhere('kelompok', 'LIKE', '%Penelitian%')
                            ->orWhere('kelompok', 'LIKE', '%Pengabdian%');
                    }
                }
            } elseif ($user->role->kode == 'auditor' && $unit_id == -1) {
                $standardsQuery = Standard::where('id', -1); // Return empty
            } else {
                $standardsQuery = Standard::activePeriod();
            }
        } else {
            if (isset($user->unit) && stripos($user->unit->nama, 'LPMA') !== false) {
                $standardsQuery = Standard::activePeriod();
            } else {
                $jenisUnit = $user->unit->jenis ?? '';
                $standardsQuery = Standard::activePeriod()->where(function ($q) use ($jenisUnit) {
                    $q->whereNull('sasaran_unit')
                        ->orWhereJsonContains('sasaran_unit', $jenisUnit);
                });

                if (isset($user->unit) && stripos($user->unit->nama, 'LPPM') !== false) {
                    $standardsQuery->orWhere('kelompok', 'LIKE', '%Penelitian%')
                        ->orWhere('kelompok', 'LIKE', '%Pengabdian%');
                }
            }
        }

        $standards = $standardsQuery->get();

        if ($status != 'all') {
            $filteredStandards = collect();
            foreach ($standards as $standard) {
                $entryQuery = LedEntry::where('objek_kode', $standard->kode)
                    ->where('tahun', $tahun);
                if ($unit_id && $unit_id != 'all') {
                    $entryQuery->where('unit_id', $unit_id);
                }
                $entry = $entryQuery->with('stages')->first();

                $isLengkap = $entry && $entry->stages->count() >= 4;

                if ($status == 'lengkap' && $isLengkap) {
                    $filteredStandards->push($standard);
                } elseif ($status == 'draft' && ! $isLengkap) {
                    $filteredStandards->push($standard);
                }
            }
            $standards = $filteredStandards;
        }
        $hasSubmitted = LedSubmission::where('unit_id', $unit_id)
            ->where('tahun', $tahun)
            ->exists();

        return view('led.index', compact('standards', 'tahun', 'unit_id', 'status', 'hasSubmitted'));
    }

    public function edit(Request $request, $kode)
    {
        $standard = Standard::where('kode', $kode)->firstOrFail();

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $unit_id = auth()->user()->unit_id;
        if (in_array(auth()->user()->role->kode, ['super_admin', 'lpma', 'auditor'])) {
            $unit_id = $request->query('unit_id', $unit_id);
        }

        $entryQuery = LedEntry::where('objek_kode', $kode)->where('tahun', $tahun);
        if ($unit_id && $unit_id != 'all') {
            $entryQuery->where('unit_id', $unit_id);
        }
        $entry = $entryQuery->with('stages')->first();

        $stageData = [];
        if ($entry) {
            foreach ($entry->stages as $st) {
                $stageData[$st->tahap] = $st;
            }
        }

        $hasSubmitted = LedSubmission::where('unit_id', $unit_id)
            ->where('tahun', $tahun)
            ->exists();

        $globalDocs = collect();
        $unitDocs = collect();
        $autoAttachedByStage = ['P1' => collect(), 'P2' => collect(), 'P3' => collect(), 'P4' => collect(), 'P5' => collect()];
        if (Schema::hasTable('evidence_documents')) {
            $globalDocs = EvidenceDocument::where('scope', 'global')
                ->orderBy('kategori')
                ->orderBy('judul')
                ->get();

            if ($unit_id && $unit_id != 'all') {
                $unitDocs = EvidenceDocument::where('scope', 'unit')
                    ->where('unit_id', (int) $unit_id)
                    ->orderBy('kategori')
                    ->orderBy('judul')
                    ->get();
            }

            $autoAttachedByStage = EvidenceDocument::autoAttachedByStage($kode, (int) $tahun);
        }

        return view('led.edit', compact(
            'standard',
            'entry',
            'stageData',
            'tahun',
            'unit_id',
            'hasSubmitted',
            'globalDocs',
            'unitDocs',
            'autoAttachedByStage'
        ));
    }

    public function exportPdf(Request $request, $kode)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $standard = Standard::where('kode', $kode)->firstOrFail();

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $unit_id = auth()->user()->unit_id;
        if (in_array(auth()->user()->role->kode, ['super_admin', 'lpma'])) {
            $unit_id = $request->query('unit_id', $unit_id);
        }

        $unit = $unit_id && $unit_id != 'all' ? Unit::find($unit_id) : null;

        $entryQuery = LedEntry::where('objek_kode', $kode)->where('tahun', $tahun);
        if ($unit_id && $unit_id != 'all') {
            $entryQuery->where('unit_id', $unit_id);
        }
        $entry = $entryQuery->with('stages')->first();

        $stageData = [];
        if ($entry) {
            foreach ($entry->stages as $st) {
                $stageData[$st->tahap] = $st;
            }
        }

        [$sharedDocumentsMap, $autoAttachedByStage] = $this->loadEvidenceContextForStages(
            collect($stageData),
            $kode,
            $unit_id && $unit_id != 'all' ? (int) $unit_id : null,
            (int) $tahun
        );

        $pdf = Pdf::loadView('led.pdf', compact(
            'standard',
            'entry',
            'stageData',
            'tahun',
            'unit',
            'sharedDocumentsMap',
            'autoAttachedByStage'
        ));

        return $pdf->stream('LED_'.$kode.'_'.$tahun.'.pdf');
    }

    public function exportFullPdf(Request $request, $unit_id)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $unit = Unit::findOrFail($unit_id);

        // Fetch standards applicable to this unit
        if (stripos($unit->nama, 'LPMA') !== false) {
            $standards = Standard::activePeriod()->get();
        } else {
            $jenisUnit = $unit->jenis ?? '';
            $query = Standard::activePeriod()->where(function ($q) use ($jenisUnit) {
                $q->whereNull('sasaran_unit')
                    ->orWhereJsonContains('sasaran_unit', $jenisUnit);
            });
            if (stripos($unit->nama, 'LPPM') !== false) {
                $query->orWhere('kelompok', 'LIKE', '%Penelitian%')
                    ->orWhere('kelompok', 'LIKE', '%Pengabdian%');
            }
            $standards = $query->get();
        }

        // Preload entries for this unit & year
        $entries = LedEntry::where('tahun', $tahun)
            ->where('unit_id', $unit_id)
            ->with('stages')
            ->get()
            ->keyBy('objek_kode');

        $allStages = $entries->flatMap(fn (LedEntry $item) => $item->stages);
        [$sharedDocumentsMap] = $this->loadEvidenceContextForStages(
            $allStages,
            '',
            (int) $unit_id,
            (int) $tahun
        );

        $pdf = Pdf::loadView('led.pdf_full', compact('standards', 'entries', 'tahun', 'unit', 'sharedDocumentsMap'));

        return $pdf->stream('Full_LED_'.str_replace(' ', '_', $unit->nama).'_'.$tahun.'.pdf');
    }

    public function update(Request $request, $kode)
    {
        $stages = $request->input('stages', []);
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->input('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $user = auth()->user();
        $user_id = $user->id;

        $unit_id = $user->unit_id ?? 1;
        if (in_array($user->role->kode ?? '', ['super_admin', 'lpma'], true) && $request->filled('unit_id') && is_numeric($request->input('unit_id'))) {
            $unit_id = (int) $request->input('unit_id');
        }

        $standard = Standard::where('kode', $kode)->firstOrFail();
        $jenis = ($standard->kelompok == 'Non-Akademik') ? 'nonakademik' : 'akademik';

        $statusSubmit = $request->input('status', 'draft');

        // Find or create LedEntry
        $entry = LedEntry::firstOrCreate(
            ['tahun' => $tahun, 'jenis' => $jenis, 'objek_kode' => $kode, 'unit_id' => $unit_id],
            ['diisi_oleh_id' => $user_id, 'status_pengisian' => $statusSubmit]
        );
        $entry->update(['status_pengisian' => $statusSubmit, 'diisi_oleh_id' => $user_id]);

        $existingStages = LedEntryStage::where('led_entry_id', $entry->id)->get()->keyBy('tahap');
        $hasEvidenceTable = Schema::hasTable('evidence_documents');

        // Process stages
        foreach ($stages as $tahap => $data) {
            $existingBukti = $existingStages->has($tahap) ? $existingStages->get($tahap)->decodedBukti() : ['files' => [], 'links' => [], 'shared_document_ids' => []];
            $buktiFiles = $this->resolveRetainedFiles($data, $existingBukti['files']);
            $sharedDocumentIds = $this->resolveAuthorizedSharedDocIds($data['shared_document_ids'] ?? [], (int) $unit_id, $hasEvidenceTable);

            // Handle newly uploaded files (either stage-only or promoted to private unit library)
            if ($request->hasFile("stages.{$tahap}.dokumen")) {
                $saveToUnitLibrary = $hasEvidenceTable && ! empty($data['save_to_unit_library']);
                $customTitle = trim((string) ($data['library_title'] ?? ''));
                $customCategory = trim((string) ($data['library_category'] ?? 'SOP'));

                foreach ($request->file("stages.{$tahap}.dokumen") as $file) {
                    if ($saveToUnitLibrary) {
                        $doc = $this->storePrivateUnitDocument($file, $standard, (string) $tahap, (int) $unit_id, (int) $tahun, $user_id, $customTitle, $customCategory);
                        $sharedDocumentIds[] = $doc->id;
                    } else {
                        $filename = time().'_'.$file->getClientOriginalName();
                        $path = $file->storeAs('public/led_documents', $filename);
                        $buktiFiles[] = str_replace('public/', 'storage/', $path);
                    }
                }
            }

            $buktiLinks = $this->parseEvidenceLinks($data['links'] ?? '');

            $buktiData = [];
            if (count($buktiFiles) > 0) {
                $buktiData['files'] = array_values(array_unique($buktiFiles));
            }
            if (count($buktiLinks) > 0) {
                $buktiData['links'] = $buktiLinks;
            }
            if (count($sharedDocumentIds) > 0) {
                $buktiData['shared_document_ids'] = array_values(array_unique($sharedDocumentIds));
            }

            $rawTanggal = isset($data['tanggal']) ? trim((string) $data['tanggal']) : '';

            $stageData = [
                'tanggal' => $rawTanggal !== '' ? $rawTanggal : null,
                'penanggung_jawab' => $data['penanggung_jawab'] ?? null,
                'uraian' => $data['uraian'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'data_spesifik' => $data['data_spesifik'] ?? null,
                'bukti' => count($buktiData) > 0 ? json_encode($buktiData) : null,
            ];

            LedEntryStage::updateOrCreate(
                ['led_entry_id' => $entry->id, 'tahap' => $tahap],
                $stageData
            );
        }

        return redirect()->route('led.index', ['unit_id' => $unit_id, 'tahun' => $tahun])->with('success', 'Data Evaluasi Diri berhasil disimpan!');
    }

    /**
     * Preserve previously uploaded files unless explicitly unchecked for removal.
     *
     * @param  array<string, mixed>  $stageInput
     * @param  array<int, string>  $existingFiles
     * @return array<int, string>
     */
    private function resolveRetainedFiles(array $stageInput, array $existingFiles): array
    {
        if (array_key_exists('existing_files_present', $stageInput)) {
            $kept = array_values(array_filter((array) ($stageInput['keep_files'] ?? [])));

            return array_values(array_intersect($existingFiles, $kept));
        }

        return $existingFiles;
    }

    /**
     * Filter submitted shared document IDs so a unit can only attach global docs or its own docs.
     *
     * @param  array<int, mixed>  $rawIds
     * @return array<int, int>
     */
    private function resolveAuthorizedSharedDocIds(array $rawIds, int $unitId, bool $hasEvidenceTable): array
    {
        if (! $hasEvidenceTable || $rawIds === []) {
            return [];
        }

        $ids = array_values(array_filter(array_map('intval', $rawIds), fn (int $id): bool => $id > 0));
        if ($ids === []) {
            return [];
        }

        return EvidenceDocument::accessibleByUnit($unitId)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all();
    }

    /**
     * Store an uploaded LED file directly into the unit's private document library.
     */
    private function storePrivateUnitDocument(
        UploadedFile $file,
        Standard $standard,
        string $tahap,
        int $unitId,
        int $tahun,
        int $userId,
        string $customTitle,
        string $category
    ): EvidenceDocument {
        $originalName = $file->getClientOriginalName();
        $safeName = time().'_'.Str::random(6).'_'.preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
        $filePath = $file->storeAs('evidence_documents', $safeName, 'local');

        $title = $customTitle !== ''
            ? $customTitle
            : pathinfo($originalName, PATHINFO_FILENAME).' ('.$standard->kode.' - '.$tahap.')';

        return EvidenceDocument::create([
            'judul' => Str::limit($title, 250, ''),
            'kategori' => $category !== '' ? $category : 'SOP',
            'scope' => 'unit',
            'unit_id' => $unitId,
            'tahun' => $tahun,
            'auto_attach' => false,
            'file_path' => $filePath,
            'original_filename' => $originalName,
            'uploaded_by_id' => $userId,
        ]);
    }

    /**
     * Parse newline- or comma-separated external evidence URLs.
     *
     * @return array<int, string>
     */
    private function parseEvidenceLinks(string $rawInput): array
    {
        if (trim($rawInput) === '') {
            return [];
        }

        $links = [];
        foreach (preg_split('/[\n,]+/', $rawInput) ?: [] as $link) {
            $link = trim($link);
            if ($link !== '') {
                $links[] = $link;
            }
        }

        return array_values(array_unique($links));
    }

    /**
     * Preload EvidenceDocument models referenced by LED stages and auto-attached global docs.
     *
     * @param  Collection<int, LedEntryStage>  $stages
     * @return array{0: Collection<int, EvidenceDocument>, 1: array<string, Collection<int, EvidenceDocument>>}
     */
    private function loadEvidenceContextForStages(Collection $stages, string $standarKode, ?int $unitId, int $tahun): array
    {
        if (! Schema::hasTable('evidence_documents')) {
            return [collect(), ['P1' => collect(), 'P2' => collect(), 'P3' => collect(), 'P4' => collect(), 'P5' => collect()]];
        }

        $docIds = $stages->flatMap(fn (LedEntryStage $stage): array => $stage->decodedBukti()['shared_document_ids'])
            ->unique()
            ->values()
            ->all();

        $sharedMap = $docIds === []
            ? collect()
            : EvidenceDocument::accessibleByUnit($unitId)->whereIn('id', $docIds)->get()->keyBy('id');

        $autoAttached = $standarKode !== ''
            ? EvidenceDocument::autoAttachedByStage($standarKode, $tahun)
            : ['P1' => collect(), 'P2' => collect(), 'P3' => collect(), 'P4' => collect(), 'P5' => collect()];

        return [$sharedMap, $autoAttached];
    }

    public function submit(Request $request)
    {
        $request->validate([
            'verifikasi' => 'required|accepted',
            'tahun' => 'required|integer',
        ]);

        $tahun = $request->input('tahun');

        // If super admin and they passed a unit_id, use it. Otherwise use auth unit
        if (in_array(auth()->user()->role->kode, ['super_admin', 'lpma']) && $request->filled('unit_id') && $request->input('unit_id') != 'all') {
            $unit_id = $request->input('unit_id');
        } else {
            $unit_id = auth()->user()->unit_id;
        }

        if (! $unit_id || $unit_id == 'all') {
            return redirect()->back()->with('error', 'Unit tidak valid.');
        }

        LedSubmission::updateOrCreate(
            ['tahun' => $tahun, 'unit_id' => $unit_id],
            ['submitted_by_id' => auth()->id(), 'submitted_at' => now()]
        );

        return redirect()->route('led.index', ['tahun' => $tahun])->with('success', 'Pengajuan Penilaian LED berhasil dilakukan. Data telah dikunci dan diserahkan ke Auditor.');
    }
}
