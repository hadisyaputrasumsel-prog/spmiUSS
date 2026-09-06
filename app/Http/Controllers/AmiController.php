<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AmiFinding;
use App\Models\Unit;
use App\Models\Standard;

class AmiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = AmiFinding::with('unit')->orderBy('created_at', 'desc');

        $tahun = $request->query('tahun', date('Y'));
        $query->whereYear('tanggal', $tahun);

        $unit_id = $request->query('unit_id', 'all');
        if ($unit_id != 'all') {
            $query->where('unit_id', $unit_id);
        }

        $kategori = $request->query('kategori', 'all');
        if ($kategori != 'all') {
            $query->where('kategori_temuan', $kategori);
        }

        if ($user->role->kode === 'auditor') {
            $assignedUnitIds = \App\Models\AuditorAssignment::where('auditor_id', $user->id)
                ->where('tahun', $tahun)
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

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        
        $unit_id = $request->query('unit_id', 'all');
        $tahun = $request->query('tahun', date('Y'));
        
        $query = AmiFinding::with('unit')->whereYear('tanggal', $tahun);
        
        if ($unit_id && $unit_id != 'all') {
            $query->where('unit_id', $unit_id);
            $unit = \App\Models\Unit::find($unit_id);
        } else {
            $unit = null;
        }

        if ($request->has('jenis')) {
            $jenis = $request->query('jenis');
            $query->whereHas('unit', function($q) use ($jenis) {
                if ($jenis == 'akademik') {
                    $q->where('jenis', '!=', 'Non-Akademik');
                } else {
                    $q->where('jenis', 'Non-Akademik');
                }
            });
        }

        $findings = $query->orderBy('created_at', 'desc')->get();
        
        $auditors = collect();
        if ($unit) {
            $assignments = \App\Models\AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ami.pdf', compact('findings', 'tahun', 'unit', 'auditors'));
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'Laporan_AMI';
        if ($unit) $filename .= '_' . str_replace(' ', '_', $unit->nama);
        $filename .= '_' . $tahun . '.pdf';
        
        return $pdf->stream($filename);
    }
    
    public function exportRtlPdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        
        $unit_id = $request->query('unit_id', 'all');
        $tahun = $request->query('tahun', date('Y'));
        
        if ($unit_id == 'all' && auth()->user()->role->kode != 'auditor' && auth()->user()->role->kode != 'lpma' && auth()->user()->role->kode != 'super_admin' && auth()->user()->role->kode != 'pimpinan') {
            $unit_id = auth()->user()->unit_id;
        }
        
        $query = AmiFinding::with('unit')->whereYear('tanggal', $tahun);
        if ($unit_id && $unit_id != 'all') {
            $query->where('unit_id', $unit_id);
            $unit = \App\Models\Unit::find($unit_id);
        } else {
            $unit = null;
        }

        // Only include findings that have RTL
        $query->whereNotNull('rencana_tindakan')->where('rencana_tindakan', '!=', '');

        $findings = $query->orderBy('created_at', 'desc')->get();
        
        $auditors = collect();
        if ($unit) {
            $assignments = \App\Models\AuditorAssignment::with('auditor')->where('unit_id', $unit->id)->where('tahun', $tahun)->get();
            $auditors = $assignments->pluck('auditor');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ami.rtl_pdf', compact('findings', 'tahun', 'unit', 'auditors'));
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'Laporan_Tindak_Lanjut';
        if ($unit) $filename .= '_' . str_replace(' ', '_', $unit->nama);
        $filename .= '_' . $tahun . '.pdf';
        
        return $pdf->stream($filename);
    }
}
