<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpmiPeriod;

class SpmiPeriodController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:spmi_periods,nama'
        ]);

        SpmiPeriod::create([
            'nama' => $request->nama,
            'is_active' => false
        ]);

        return redirect()->back()->with('success', 'Periode SPMI berhasil ditambahkan.');
    }

    public function activate($id)
    {
        // Deactivate all others
        SpmiPeriod::query()->update(['is_active' => false]);
        
        // Activate target
        $period = SpmiPeriod::findOrFail($id);
        $period->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Periode SPMI ' . $period->nama . ' berhasil diaktifkan. Formulir LED dan Tindak Lanjut AMI kini akan merujuk pada standar-standar di periode ini.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|unique:spmi_periods,nama,' . $id
        ]);

        $period = SpmiPeriod::findOrFail($id);
        $oldNama = $period->nama;
        $period->update([
            'nama' => $request->nama
        ]);

        // Also update standard period strings matching the old name
        \App\Models\Standard::where('periode', $oldNama)->update(['periode' => $request->nama]);

        return redirect()->back()->with('success', 'Nama instrumen periode berhasil diubah menjadi ' . $request->nama . '.');
    }
}
