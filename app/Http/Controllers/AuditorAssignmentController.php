<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditorAssignment;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Validation\Rule;

class AuditorAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $activeConfig = \App\Models\BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        $query = AuditorAssignment::where('tahun', $tahun)->with(['auditor', 'unit']);
        
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->query('unit_id'));
        }
        
        $assignments = $query->get();
            
        // Allow all users, including Kaprodi/Unit/UPPS to dual-role as Auditors
        $auditors = User::whereHas('role', function($query) {
            $query->whereNotIn('kode', ['pimpinan']); // Usually Pimpinan is an observer, but allowed if needed
        })->get();
        
        $units = Unit::all();
        $filter_unit_id = $request->query('unit_id');

        return view('auditor.index', compact('assignments', 'auditors', 'units', 'tahun', 'filter_unit_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'auditor_id' => [
                'required',
                'exists:users,id',
                Rule::unique('auditor_assignments')->where(function ($query) use ($request) {
                    return $query->where('tahun', $request->tahun)
                                 ->where('unit_id', $request->unit_id);
                })
            ],
            'unit_id' => 'required|exists:units,id',
            'peran' => 'required|string',
        ], [
            'auditor_id.unique' => 'Auditor ini sudah ditugaskan ke unit tersebut pada tahun ini.'
        ]);

        AuditorAssignment::create([
            'tahun' => $request->tahun,
            'auditor_id' => $request->auditor_id,
            'unit_id' => $request->unit_id,
            'peran' => $request->peran,
        ]);

        return redirect()->back()->with('success', 'Auditor berhasil ditugaskan ke Unit.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'auditor_id' => [
                'required',
                'exists:users,id',
                Rule::unique('auditor_assignments')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('tahun', $request->tahun)
                                 ->where('unit_id', $request->unit_id);
                })
            ],
            'unit_id' => 'required|exists:units,id',
            'peran' => 'required|string',
        ], [
            'auditor_id.unique' => 'Auditor ini sudah ditugaskan ke unit tersebut pada tahun ini.'
        ]);

        $assignment = AuditorAssignment::findOrFail($id);
        $assignment->update([
            'tahun' => $request->tahun,
            'auditor_id' => $request->auditor_id,
            'unit_id' => $request->unit_id,
            'peran' => $request->peran,
        ]);

        return redirect()->back()->with('success', 'Penugasan auditor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AuditorAssignment::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penugasan auditor berhasil dihapus.');
    }
}
