<?php

namespace App\Http\Controllers;

use App\Models\AuditorAssignment;
use App\Models\AuditSesi;
use App\Models\BulanMutuConfig;
use App\Models\Unit;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AuditSesiController extends Controller
{
    public function edit(Request $request)
    {
        $user = auth()->user();
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $unit_id = $request->query('unit_id');

        if (! $unit_id || $unit_id === 'all') {
            // Find first assigned unit or first unit
            if ($user->role->kode === 'auditor') {
                $firstAssigned = AuditorAssignment::where('auditor_id', $user->id)
                    ->where('tahun', $tahun)
                    ->value('unit_id');
                $unit_id = $firstAssigned ?: Unit::first()?->id;
            } else {
                $unit_id = Unit::first()?->id;
            }
        }

        $unit = Unit::findOrFail($unit_id);

        // Authorization check: Super Admin, LPMA, or assigned auditor for this unit and year
        if (! in_array($user->role->kode, ['super_admin', 'lpma'], true)) {
            $isAssigned = AuditorAssignment::where('auditor_id', $user->id)
                ->where('unit_id', $unit->id)
                ->where('tahun', $tahun)
                ->exists();

            if (! $isAssigned) {
                abort(403, 'Anda tidak ditugaskan sebagai auditor untuk unit ini pada tahun '.$tahun);
            }
        }

        if (! Schema::hasTable('audit_sesi')) {
            try {
                AppServiceProvider::syncSchema();
            } catch (\Throwable $e) {
                // fallback
            }
        }

        $auditSesi = Schema::hasTable('audit_sesi') ? AuditSesi::firstOrNew([
            'unit_id' => $unit->id,
            'tahun' => $tahun,
        ]) : new AuditSesi([
            'unit_id' => $unit->id,
            'tahun' => $tahun,
        ]);

        // Default suggestions if new
        if (! $auditSesi->exists) {
            $slug = strtoupper(str_replace(['Prodi ', 'Fakultas ', ' '], ['', '', ''], $unit->nama));
            $auditSesi->no_kka = 'KKA-'.$slug.'-C6-01';
            $auditSesi->kriteria = 'Kriteria C.6 Pendidikan — IAPS 4.0 BAN-PT / Standar SPMI USS';
            $auditSesi->lokasi = 'Kampus Palembang USS';
            $auditSesi->ruang_lingkup = 'Desk Review / Telaah Dokumen — data TA '.$tahun.'/'.($tahun + 1);
            $auditSesi->distribusi = ['Auditi', 'Auditor', 'LPMA', 'Arsip'];
        }

        $assignments = AuditorAssignment::with('auditor')
            ->where('unit_id', $unit->id)
            ->where('tahun', $tahun)
            ->get();

        $ketua = $assignments->firstWhere('peran', 'Auditor Ketua')?->auditor
            ?? $assignments->first()?->auditor;

        $anggota = $assignments->filter(function ($a) use ($ketua) {
            return ! $ketua || $a->auditor_id !== $ketua->id;
        })->pluck('auditor');

        // All units for switcher dropdown
        if (in_array($user->role->kode, ['super_admin', 'lpma'], true)) {
            $units = Unit::orderBy('nama')->get();
        } else {
            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
                ->pluck('unit_id');
            $units = Unit::whereIn('id', $assignedUnitIds)->orderBy('nama')->get();
        }

        return view('ami.audit_sesi', compact('unit', 'tahun', 'auditSesi', 'ketua', 'anggota', 'units', 'assignments'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tahun' => 'required|integer',
            'no_kka' => 'nullable|string|max:100',
            'kriteria' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'ruang_lingkup' => 'nullable|string',
            'tanggal_audit' => 'nullable|date',
            'wakil_auditi' => 'nullable|string|max:255',
            'distribusi' => 'nullable|array',
            'penjamin_mutu' => 'nullable|string|max:255',
            'catatan_penutup' => 'nullable|string',
        ]);

        $unitId = (int) $validated['unit_id'];
        $tahun = (int) $validated['tahun'];

        if (! in_array($user->role->kode, ['super_admin', 'lpma'], true)) {
            $isAssigned = AuditorAssignment::where('auditor_id', $user->id)
                ->where('unit_id', $unitId)
                ->where('tahun', $tahun)
                ->exists();

            if (! $isAssigned) {
                abort(403, 'Anda tidak ditugaskan untuk unit ini.');
            }
        }

        try {
            if (! Schema::hasTable('audit_sesi') || ! Schema::hasColumn('audit_sesi', 'tanggal_audit')) {
                AppServiceProvider::syncSchema();
            }

            if (Schema::hasTable('audit_sesi')) {
                $payload = [];
                $columns = ['no_kka', 'kriteria', 'lokasi', 'ruang_lingkup', 'tanggal_audit', 'wakil_auditi', 'distribusi', 'penjamin_mutu', 'catatan_penutup'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('audit_sesi', $col)) {
                        $payload[$col] = $validated[$col] ?? null;
                    }
                }
                if (isset($payload['distribusi']) && ! is_array($payload['distribusi'])) {
                    $payload['distribusi'] = [];
                }

                AuditSesi::updateOrCreate(
                    ['unit_id' => $unitId, 'tahun' => $tahun],
                    $payload
                );

                return redirect()->route('audit-sesi.edit', ['unit_id' => $unitId, 'tahun' => $tahun])
                    ->with('success', 'Data Sesi Audit berhasil disimpan.');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error saving audit_sesi: '.$e->getMessage());

            return redirect()->route('audit-sesi.edit', ['unit_id' => $unitId, 'tahun' => $tahun])
                ->with('error', 'Gagal menyimpan data sesi audit: '.$e->getMessage());
        }

        return redirect()->route('audit-sesi.edit', ['unit_id' => $unitId, 'tahun' => $tahun])
            ->with('error', 'Tabel audit_sesi belum siap di database.');
    }
}
