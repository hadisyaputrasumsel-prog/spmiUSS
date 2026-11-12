<?php

namespace App\Http\Controllers;

use App\Models\AuditorAssignment;
use App\Models\BulanMutuConfig;
use App\Models\EvidenceDocument;
use App\Models\Standard;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class EvidenceDocumentController extends Controller
{
    /**
     * Display global (university-wide) and unit-scoped evidence document libraries.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $roleKode = $user->role->kode ?? '';
        $isAdminOrLpma = in_array($roleKode, ['super_admin', 'lpma'], true);
        $isAuditee = in_array($roleKode, ['auditee_upps', 'auditee_prodi', 'auditee_unit', 'gpm_upm'], true);

        $globalDocs = EvidenceDocument::with(['uploader'])
            ->where('scope', 'global')
            ->orderBy('kategori')
            ->orderBy('judul')
            ->get();

        $units = collect();
        $selectedUnitId = $request->query('unit_id', 'all');

        if ($isAdminOrLpma || $roleKode === 'pimpinan') {
            $units = Unit::orderBy('nama')->get();
        } elseif ($user->isAuditor() && ! $isAuditee) {
            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)->pluck('unit_id')->unique();
            $units = Unit::whereIn('id', $assignedUnitIds)->orderBy('nama')->get();
        } elseif ($user->unit_id !== null) {
            $units = Unit::where('id', $user->unit_id)->get();
            $selectedUnitId = (string) $user->unit_id;
        }

        $unitDocsQuery = EvidenceDocument::with(['unit', 'uploader'])
            ->where('scope', 'unit')
            ->orderBy('kategori')
            ->orderBy('judul');

        if ($isAdminOrLpma || $roleKode === 'pimpinan') {
            if ($selectedUnitId !== 'all' && is_numeric($selectedUnitId)) {
                $unitDocsQuery->where('unit_id', (int) $selectedUnitId);
            }
        } elseif ($user->isAuditor() && ! $isAuditee) {
            $allowedIds = $units->pluck('id')->all();
            if ($selectedUnitId !== 'all' && in_array((int) $selectedUnitId, $allowedIds, true)) {
                $unitDocsQuery->where('unit_id', (int) $selectedUnitId);
            } else {
                $unitDocsQuery->whereIn('unit_id', $allowedIds === [] ? [-1] : $allowedIds);
            }
        } else {
            $unitDocsQuery->where('unit_id', $user->unit_id ?? -1);
        }

        $unitDocs = $unitDocsQuery->get();
        $standards = Standard::activePeriod()->orderBy('kode')->get();
        $activeYear = BulanMutuConfig::where('is_active', true)->value('tahun') ?? (int) date('Y');

        $canManageGlobal = $isAdminOrLpma;
        $canManageUnit = $isAdminOrLpma || ($isAuditee && $user->unit_id !== null);

        return view('evidence_documents.index', compact(
            'globalDocs',
            'unitDocs',
            'units',
            'standards',
            'selectedUnitId',
            'activeYear',
            'canManageGlobal',
            'canManageUnit'
        ));
    }

    /**
     * Store a new global or unit-scoped evidence document.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $roleKode = $user->role->kode ?? '';
        $isAdminOrLpma = in_array($roleKode, ['super_admin', 'lpma'], true);
        $isAuditee = in_array($roleKode, ['auditee_upps', 'auditee_prodi', 'auditee_unit', 'gpm_upm'], true);

        if (! $isAdminOrLpma && ! ($isAuditee && $user->unit_id !== null)) {
            abort(403, 'Anda tidak memiliki izin untuk mengunggah dokumen ke pustaka.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'scope' => 'required|in:global,unit',
            'unit_id' => 'nullable|integer|exists:units,id',
            'tahun' => 'nullable|integer|min:2020|max:2100',
            'standar_kode' => 'nullable|string|max:255',
            'tahap' => 'nullable|in:P1,P2,P3,P4,P5',
            'auto_attach' => 'nullable|boolean',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:15360',
            'external_url' => 'nullable|url|max:1000',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if (! $request->hasFile('file') && empty($validated['external_url'])) {
            return redirect()->back()
                ->withErrors(['file' => 'Harap unggah file dokumen atau isi tautan URL eksternal.'])
                ->withInput();
        }

        // Enforce scope and unit isolation strictly on the server side.
        $scope = $isAdminOrLpma ? $validated['scope'] : 'unit';
        $unitId = null;
        if ($scope === 'unit') {
            $unitId = $isAdminOrLpma
                ? ($validated['unit_id'] ?? $user->unit_id)
                : $user->unit_id;

            if ($unitId === null) {
                return redirect()->back()
                    ->withErrors(['unit_id' => 'Unit pemilik dokumen harus dipilih.'])
                    ->withInput();
            }
        }

        $filePath = null;
        $originalFilename = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $safeName = time().'_'.Str::random(6).'_'.preg_replace('/[^A-Za-z0-9._-]/', '_', $originalFilename);
            $filePath = $file->storeAs('evidence_documents', $safeName, 'local');
        }

        EvidenceDocument::create([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'scope' => $scope,
            'unit_id' => $unitId,
            'tahun' => $validated['tahun'] ?? null,
            'standar_kode' => $scope === 'global' ? ($validated['standar_kode'] ?? null) : null,
            'tahap' => $scope === 'global' ? ($validated['tahap'] ?? null) : null,
            'auto_attach' => $scope === 'global' && $isAdminOrLpma && $request->boolean('auto_attach'),
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'external_url' => $validated['external_url'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'uploaded_by_id' => $user->id,
        ]);

        return redirect()->route('evidence-documents.index')->with('success', 'Dokumen berhasil ditambahkan ke Bank Dokumen.');
    }

    /**
     * Authorize and stream or redirect to the requested evidence document.
     */
    public function download(EvidenceDocument $evidenceDocument): Response
    {
        if (! $evidenceDocument->canBeViewedBy(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses ke dokumen unit ini.');
        }

        if ($evidenceDocument->file_path && Storage::disk('local')->exists($evidenceDocument->file_path)) {
            $fullPath = Storage::disk('local')->path($evidenceDocument->file_path);
            $filename = $evidenceDocument->original_filename ?: basename($evidenceDocument->file_path);

            return response()->file($fullPath, [
                'Content-Disposition' => 'inline; filename="'.addslashes($filename).'"',
            ]);
        }

        if (! empty($evidenceDocument->external_url)) {
            return redirect()->away($evidenceDocument->external_url);
        }

        abort(404, 'Berkas dokumen tidak ditemukan.');
    }

    /**
     * Delete an evidence document when authorized.
     */
    public function destroy(EvidenceDocument $evidenceDocument): RedirectResponse
    {
        if (! $evidenceDocument->canBeManagedBy(auth()->user())) {
            abort(403, 'Anda tidak memiliki wewenang untuk menghapus dokumen ini.');
        }

        if ($evidenceDocument->file_path && Storage::disk('local')->exists($evidenceDocument->file_path)) {
            Storage::disk('local')->delete($evidenceDocument->file_path);
        }

        $evidenceDocument->delete();

        return redirect()->route('evidence-documents.index')->with('success', 'Dokumen berhasil dihapus dari Bank Dokumen.');
    }
}
