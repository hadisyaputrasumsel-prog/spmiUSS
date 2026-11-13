<?php

namespace App\Http\Controllers;

use App\Models\AuditorAssignment;
use App\Models\BulanMutuConfig;
use App\Models\Pka;
use App\Models\PkaLangkah;
use App\Models\Standard;
use App\Models\Unit;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PkaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $unit_id = $request->query('unit_id', 'all');

        if (! Schema::hasTable('pka')) {
            $pkas = collect();
            $units = Unit::orderBy('nama')->get();
            $availableYears = BulanMutuConfig::availableYears($tahun);

            return view('pka.index', compact('pkas', 'units', 'tahun', 'unit_id', 'availableYears'))
                ->with('warning', 'Tabel PKA belum ada di basis data. Silakan jalankan migrasi: php artisan migrate');
        }

        $query = Pka::with(['unit', 'penyusun', 'pemeriksa', 'langkah'])->where('tahun', $tahun);

        if ($unit_id && $unit_id !== 'all') {
            $query->where('unit_id', $unit_id);
        }

        if ($user->role->kode === 'auditor') {
            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
                ->pluck('unit_id');
            $query->whereIn('unit_id', $assignedUnitIds);
            $units = Unit::whereIn('id', $assignedUnitIds)->orderBy('nama')->get();
        } else {
            $units = Unit::orderBy('nama')->get();
        }

        $pkas = $query->orderBy('nomor')->get();
        $availableYears = BulanMutuConfig::availableYears($tahun);

        return view('pka.index', compact('pkas', 'units', 'tahun', 'unit_id', 'availableYears'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $unit_id = $request->query('unit_id');

        if ($user->role->kode === 'auditor') {
            $assignedUnitIds = AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
                ->pluck('unit_id');
            $units = Unit::whereIn('id', $assignedUnitIds)->orderBy('nama')->get();
            if (! $unit_id && $units->isNotEmpty()) {
                $unit_id = $units->first()->id;
            }
        } else {
            $units = Unit::orderBy('nama')->get();
        }

        $standards = Standard::activePeriod()->get();
        $auditors = User::whereHas('role', function ($q) {
            $q->whereIn('kode', ['auditor', 'auditor_anggota', 'lpma', 'super_admin']);
        })->get();

        return view('pka.create', compact('units', 'standards', 'auditors', 'tahun', 'unit_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tahun' => 'required|integer',
            'nomor' => 'required|string|max:100',
            'ruang_lingkup' => 'required|string',
            'kriteria' => 'required|string',
            'standar_kode' => 'nullable|string',
            'tentative_audit_objective' => 'nullable|string',
            'tujuan_audit' => 'nullable|string',
            'disusun_oleh' => 'nullable|exists:users,id',
            'diperiksa_oleh' => 'nullable|exists:users,id',
            'tanggal_disusun' => 'nullable|date',
            'tanggal_diperiksa' => 'nullable|date',
            'langkah' => 'required|array|min:1',
            'langkah.*.uraian' => 'required|string',
            'langkah.*.anggaran_waktu' => 'nullable|string',
            'langkah.*.realisasi' => 'nullable|string',
            'langkah.*.no_kka' => 'nullable|string',
            'langkah.*.disusun_oleh' => 'nullable|string',
        ]);

        $pka = Pka::create([
            'unit_id' => $validated['unit_id'],
            'tahun' => $validated['tahun'],
            'nomor' => $validated['nomor'],
            'ruang_lingkup' => $validated['ruang_lingkup'],
            'kriteria' => $validated['kriteria'],
            'standar_kode' => $validated['standar_kode'] ?? null,
            'tentative_audit_objective' => $validated['tentative_audit_objective'] ?? null,
            'tujuan_audit' => $validated['tujuan_audit'] ?? null,
            'disusun_oleh' => $validated['disusun_oleh'] ?? auth()->id(),
            'diperiksa_oleh' => $validated['diperiksa_oleh'] ?? null,
            'tanggal_disusun' => $validated['tanggal_disusun'] ?? null,
            'tanggal_diperiksa' => $validated['tanggal_diperiksa'] ?? null,
        ]);

        $defaultPenyusunName = $pka->penyusun ? ($pka->penyusun->nama_lengkap ?: $pka->penyusun->name) : auth()->user()->name;

        foreach ($validated['langkah'] as $index => $step) {
            PkaLangkah::create([
                'pka_id' => $pka->id,
                'no_urut' => $index + 1,
                'uraian' => $step['uraian'],
                'anggaran_waktu' => $step['anggaran_waktu'] ?? null,
                'realisasi' => $step['realisasi'] ?? null,
                'no_kka' => $step['no_kka'] ?? null,
                'disusun_oleh' => $step['disusun_oleh'] ?? $defaultPenyusunName,
            ]);
        }

        return redirect()->route('pka.index', ['unit_id' => $pka->unit_id, 'tahun' => $pka->tahun])
            ->with('success', 'Program Kerja Audit (PKA) berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pka = Pka::with('langkah')->findOrFail($id);
        $units = Unit::orderBy('nama')->get();
        $standards = Standard::activePeriod()->get();
        $auditors = User::whereHas('role', function ($q) {
            $q->whereIn('kode', ['auditor', 'auditor_anggota', 'lpma', 'super_admin']);
        })->get();

        return view('pka.edit', compact('pka', 'units', 'standards', 'auditors'));
    }

    public function update(Request $request, $id)
    {
        $pka = Pka::findOrFail($id);

        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tahun' => 'required|integer',
            'nomor' => 'required|string|max:100',
            'ruang_lingkup' => 'required|string',
            'kriteria' => 'required|string',
            'standar_kode' => 'nullable|string',
            'tentative_audit_objective' => 'nullable|string',
            'tujuan_audit' => 'nullable|string',
            'disusun_oleh' => 'nullable|exists:users,id',
            'diperiksa_oleh' => 'nullable|exists:users,id',
            'tanggal_disusun' => 'nullable|date',
            'tanggal_diperiksa' => 'nullable|date',
            'langkah' => 'required|array|min:1',
            'langkah.*.uraian' => 'required|string',
            'langkah.*.anggaran_waktu' => 'nullable|string',
            'langkah.*.realisasi' => 'nullable|string',
            'langkah.*.no_kka' => 'nullable|string',
            'langkah.*.disusun_oleh' => 'nullable|string',
        ]);

        $pka->update([
            'unit_id' => $validated['unit_id'],
            'tahun' => $validated['tahun'],
            'nomor' => $validated['nomor'],
            'ruang_lingkup' => $validated['ruang_lingkup'],
            'kriteria' => $validated['kriteria'],
            'standar_kode' => $validated['standar_kode'] ?? null,
            'tentative_audit_objective' => $validated['tentative_audit_objective'] ?? null,
            'tujuan_audit' => $validated['tujuan_audit'] ?? null,
            'disusun_oleh' => $validated['disusun_oleh'] ?? null,
            'diperiksa_oleh' => $validated['diperiksa_oleh'] ?? null,
            'tanggal_disusun' => $validated['tanggal_disusun'] ?? null,
            'tanggal_diperiksa' => $validated['tanggal_diperiksa'] ?? null,
        ]);

        $pka->langkah()->delete();

        $defaultPenyusunName = $pka->penyusun ? ($pka->penyusun->nama_lengkap ?: $pka->penyusun->name) : auth()->user()->name;

        foreach ($validated['langkah'] as $index => $step) {
            PkaLangkah::create([
                'pka_id' => $pka->id,
                'no_urut' => $index + 1,
                'uraian' => $step['uraian'],
                'anggaran_waktu' => $step['anggaran_waktu'] ?? null,
                'realisasi' => $step['realisasi'] ?? null,
                'no_kka' => $step['no_kka'] ?? null,
                'disusun_oleh' => $step['disusun_oleh'] ?? $defaultPenyusunName,
            ]);
        }

        return redirect()->route('pka.index', ['unit_id' => $pka->unit_id, 'tahun' => $pka->tahun])
            ->with('success', 'Program Kerja Audit (PKA) berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pka = Pka::findOrFail($id);
        $unit_id = $pka->unit_id;
        $tahun = $pka->tahun;
        $pka->delete();

        return redirect()->route('pka.index', ['unit_id' => $unit_id, 'tahun' => $tahun])
            ->with('success', 'Program Kerja Audit (PKA) berhasil dihapus.');
    }

    public function exportPdf($id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $pka = $id instanceof Pka
            ? $id->loadMissing(['unit', 'penyusun', 'pemeriksa', 'langkah'])
            : Pka::with(['unit', 'penyusun', 'pemeriksa', 'langkah'])->findOrFail($id);

        $pdf = Pdf::loadView('pka.pdf', [
            'pkas' => [$pka],
            'single' => true,
        ]);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'PKA_'.$pka->nomor.'_'.str_replace(' ', '_', $pka->unit->nama).'.pdf';

        return $pdf->stream($filename);
    }

    public function exportUnitPdf(Request $request, $unit_id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = (int) $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));

        $unit = Unit::findOrFail($unit_id);
        $pkas = Pka::with(['unit', 'penyusun', 'pemeriksa', 'langkah'])
            ->where('unit_id', $unit->id)
            ->where('tahun', $tahun)
            ->orderBy('nomor')
            ->get();

        if ($pkas->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada dokumen PKA yang disusun untuk unit '.$unit->nama.' pada tahun '.$tahun);
        }

        $pdf = Pdf::loadView('pka.pdf', [
            'pkas' => $pkas,
            'single' => false,
        ]);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Semua_PKA_'.str_replace(' ', '_', $unit->nama).'_'.$tahun.'.pdf';

        return $pdf->stream($filename);
    }
}
