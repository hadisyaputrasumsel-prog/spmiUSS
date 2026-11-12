<?php

namespace App\Http\Controllers;

use App\Models\BulanMutuConfig;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $tahun = (int) $request->query('tahun', BulanMutuConfig::activeYear());
        $availableYears = BulanMutuConfig::availableYears($tahun);
        $units = Unit::orderBy('nama', 'asc')->get();

        return view('unit.index', compact('units', 'tahun', 'availableYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'pimpinan' => 'nullable|string|max:255',
            'nuptk_pimpinan' => 'nullable|string|max:100',
        ]);

        Unit::create($request->all());

        return redirect()->route('manajemen-auditee.index')->with('success', 'Auditee (Unit) baru berhasil ditambahkan.');
    }

    public function edit(Unit $manajemen_auditee)
    {
        $unit = $manajemen_auditee;

        return view('unit.edit', compact('unit'));
    }

    public function update(Request $request, Unit $manajemen_auditee)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:50',
            'pimpinan' => 'nullable|string|max:255',
            'nuptk_pimpinan' => 'nullable|string|max:100',
        ]);

        $manajemen_auditee->update($request->all());

        return redirect()->route('manajemen-auditee.index')->with('success', 'Data Auditee berhasil diperbarui.');
    }

    public function destroy(Unit $manajemen_auditee)
    {
        // Using $manajemen_auditee because resource route names it that way
        $manajemen_auditee->delete();

        return redirect()->route('manajemen-auditee.index')->with('success', 'Auditee berhasil dihapus.');
    }
}
