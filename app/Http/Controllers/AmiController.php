<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AmiFinding;
use App\Models\Unit;
use App\Models\Standard;

class AmiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = AmiFinding::with('unit')->orderBy('created_at', 'desc');

        if ($user->role->kode === 'auditor') {
            $assignedUnitIds = \App\Models\AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', date('Y'))
                ->pluck('unit_id');
            $query->whereIn('unit_id', $assignedUnitIds);
        }

        $findings = $query->get();
        return view('ami.index', compact('findings'));
    }

    public function create()
    {
        $user = auth()->user();
        $unitsQuery = Unit::query();

        if ($user->role->kode === 'auditor') {
            $assignedUnitIds = \App\Models\AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', date('Y'))
                ->pluck('unit_id');
            $unitsQuery->whereIn('id', $assignedUnitIds);
        }

        $units = $unitsQuery->get();
        $standards = Standard::all();
        
        $ledEntry = null;
        $stageData = [];
        if (request('unit_id') && request('standar_kode')) {
            $ledEntry = \App\Models\LedEntry::where('unit_id', request('unit_id'))
                            ->where('objek_kode', request('standar_kode'))
                            ->where('tahun', date('Y'))
                            ->first();
                            
            if ($ledEntry) {
                $stages = \App\Models\LedEntryStage::where('led_entry_id', $ledEntry->id)->get();
                foreach ($stages as $stage) {
                    $stageData[$stage->tahap] = $stage;
                }
            }
        }
        
        return view('ami.create', compact('units', 'standards', 'ledEntry', 'stageData'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role->kode === 'auditor') {
            $isAssigned = \App\Models\AuditorAssignment::where('auditor_id', $user->id)
                ->where('unit_id', $request->unit_id)
                ->where('tahun', date('Y'))
                ->exists();
                
            if (!$isAssigned) {
                return redirect()->back()->withErrors(['error' => 'Anda tidak ditugaskan untuk mengaudit unit ini pada periode berjalan.'])->withInput();
            }
        }

        // Validation could be added here
        $data = $request->except('_token');
        
        $data['auditor_id'] = $user->id; 
        
        AmiFinding::create($data);

        return redirect()->route('ami.index')->with('success', 'Temuan audit (AMI) berhasil dicatat.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AmiImport, $request->file('file'));
            return redirect()->route('ami.index')->with('success', 'Data Instrumen AMI berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('ami.index')->withErrors(['error' => 'Gagal mengimpor data: ' . $e->getMessage()]);
        }
    }

    public function updateRtl(Request $request, $id)
    {
        $finding = AmiFinding::findOrFail($id);
        
        $request->validate([
            'rencana_tindakan' => 'required|string',
            'pic' => 'required|string|max:255',
            'batas_waktu' => 'required|date',
        ]);

        $finding->rencana_tindakan = $request->rencana_tindakan;
        $finding->pic = $request->pic;
        $finding->batas_waktu = $request->batas_waktu;
        $finding->status_tindak_lanjut = 'Sedang Berjalan';
        $finding->save();

        return redirect()->back()->with('success', 'Rencana Tindak Lanjut (RTL) berhasil disimpan.');
    }
}
