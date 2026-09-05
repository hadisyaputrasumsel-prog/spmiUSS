<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;

class StandardController extends Controller
{
    public function index()
    {
        $standards = Standard::orderBy('kode', 'asc')->paginate(15);
        return view('standar.index', compact('standards'));
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
            'sasaran_unit' => 'nullable|array',
            'indikator' => 'nullable|string',
            'penanggung_jawab' => 'nullable|string|max:255',
            'indikator_p1' => 'nullable|string',
            'indikator_p2' => 'nullable|string',
            'indikator_p3' => 'nullable|string',
            'indikator_p4' => 'nullable|string',
            'indikator_p5' => 'nullable|string',
            'rubrik_penilaian' => 'nullable|array',
        ]);

        $data = $request->all();

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
            'sasaran_unit' => 'nullable|array',
            'indikator' => 'nullable|string',
            'penanggung_jawab' => 'nullable|string|max:255',
            'indikator_p1' => 'nullable|string',
            'indikator_p2' => 'nullable|string',
            'indikator_p3' => 'nullable|string',
            'indikator_p4' => 'nullable|string',
            'indikator_p5' => 'nullable|string',
            'rubrik_penilaian' => 'nullable|array',
        ]);

        $data = $request->all();
        
        if (!$request->has('sasaran_unit')) {
            $data['sasaran_unit'] = null;
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
