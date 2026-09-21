<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;

class StandardController extends Controller
{
    public function index(Request $request)
    {
        $periodes = \App\Models\SpmiPeriod::orderBy('created_at', 'desc')->get();
        // Fallback create default if empty
        if ($periodes->isEmpty()) {
            \App\Models\SpmiPeriod::create(['nama' => 'Periode Utama', 'is_active' => true]);
            $periodes = \App\Models\SpmiPeriod::orderBy('created_at', 'desc')->get();
        }
        
        $activePeriode = $periodes->where('is_active', true)->first();
        if (!$activePeriode) $activePeriode = $periodes->first();

        // Default to the first available period, or null if none
        $selectedPeriodeId = $request->query('periode_id', $activePeriode->id);
        
        // Ensure standard 'periode' is stored as period name or id, wait, in existing DB, 'periode' is string!
        // We will match the string name with the selected period's name
        $selectedPeriodeName = $periodes->firstWhere('id', $selectedPeriodeId)->nama ?? '';

        $query = Standard::orderBy('kode', 'asc');
        
        if ($selectedPeriodeName === 'Periode Utama' || empty($selectedPeriodeName)) {
            $query->where(function($q) use ($selectedPeriodeName) {
                $q->whereNull('periode')->orWhere('periode', '')->orWhere('periode', $selectedPeriodeName);
            });
        } else {
            $query->where('periode', $selectedPeriodeName);
        }

        $standards = $query->get();

        // Group only by kelompok
        $groupedStandards = $standards->groupBy(function($item) {
            return empty($item->kelompok) ? 'Tanpa Kelompok' : $item->kelompok;
        });
        
        return view('standar.index', compact('groupedStandards', 'periodes', 'selectedPeriodeId', 'activePeriode'));
    }

    public function create()
    {
        return view('standar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:standards,kode',
            'nama' => 'required|string|max:255',
            'is_akademik' => 'required|boolean',
            'kelompok' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sasaran_unit' => 'nullable|array',
            'indikator' => 'nullable|string',
            'target' => 'nullable|string',
            'acuan' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'indikator_p1' => 'nullable|string',
            'indikator_p2' => 'nullable|string',
            'indikator_p3' => 'nullable|string',
            'indikator_p4' => 'nullable|string',
            'indikator_p5' => 'nullable|string',
            'rubrik_penilaian' => 'nullable|array',
            'template_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;

        if ($request->hasFile('template_dokumen')) {
            $file = $request->file('template_dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('templates', $filename, 'public');
            $data['template_dokumen'] = $path;
        }

        Standard::create($data);

        return redirect()->route('standar-mutu.index')->with('success', 'Standar mutu baru berhasil ditambahkan.');
    }

    public function edit(Standard $standar_mutu)
    {
        return view('standar.edit', ['standard' => $standar_mutu]);
    }

    public function update(Request $request, Standard $standar_mutu)
    {
        $request->validate([
            'kode' => 'required|unique:standards,kode,' . $standar_mutu->id,
            'nama' => 'required|string|max:255',
            'is_akademik' => 'required|boolean',
            'kelompok' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sasaran_unit' => 'nullable|array',
            'indikator' => 'nullable|string',
            'target' => 'nullable|string',
            'acuan' => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'indikator_p1' => 'nullable|string',
            'indikator_p2' => 'nullable|string',
            'indikator_p3' => 'nullable|string',
            'indikator_p4' => 'nullable|string',
            'indikator_p5' => 'nullable|string',
            'rubrik_penilaian' => 'nullable|array',
            'template_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        if (!$request->has('sasaran_unit')) {
            $data['sasaran_unit'] = null;
        }

        if ($request->hasFile('template_dokumen')) {
            $file = $request->file('template_dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('templates', $filename, 'public');
            $data['template_dokumen'] = $path;
        }

        $standar_mutu->update($data);

        return redirect()->route('standar-mutu.index')->with('success', 'Standar mutu berhasil diperbarui.');
    }

    public function destroy(Standard $standar_mutu)
    {
        $standar_mutu->delete();
        return redirect()->route('standar-mutu.index')->with('success', 'Standar mutu berhasil dihapus.');
    }
}

