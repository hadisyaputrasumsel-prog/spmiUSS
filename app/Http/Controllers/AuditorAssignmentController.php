<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditorAssignment;
use App\Models\User;
use App\Models\Unit;

class AuditorAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $assignments = AuditorAssignment::where('tahun', $tahun)
            ->with(['auditor', 'unit'])
            ->get();
            
        // Get users who are auditors (role_id = 2 typically, or just search by role relation)
        $auditors = User::whereHas('role', function($query) {
            $query->where('kode', 'auditor')->orWhere('kode', 'super_admin');
        })->get();
        
        $units = Unit::all();

        return view('auditor.index', compact('assignments', 'auditors', 'units', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'auditor_id' => 'required|exists:users,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        AuditorAssignment::firstOrCreate([
            'tahun' => $request->tahun,
            'auditor_id' => $request->auditor_id,
            'unit_id' => $request->unit_id,
        ]);

        return redirect()->back()->with('success', 'Auditor berhasil ditugaskan ke Unit.');
    }

    public function destroy($id)
    {
        AuditorAssignment::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penugasan auditor berhasil dihapus.');
    }
}
