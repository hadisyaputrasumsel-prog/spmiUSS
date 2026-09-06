<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;

use App\Models\LedEntry;
use App\Models\LedEntryStage;

class LedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $activeConfig = \App\Models\BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        
        $unit_id = $request->query('unit_id', 'all');
        $status = $request->query('status', 'all');
        if (!in_array($user->role->kode, ['super_admin', 'lpma', 'auditor'])) {
            $unit_id = $user->unit_id; // Force unit for non-admins
        }

        $assignedUnitIds = [];
        if ($user->role->kode == 'auditor') {
            $assignedUnitIds = \App\Models\AuditorAssignment::where('auditor_id', $user->id)
                                ->where('tahun', $tahun)
                                ->pluck('unit_id')->toArray();
            
            if ($unit_id == 'all' || !in_array($unit_id, $assignedUnitIds)) {
                $unit_id = count($assignedUnitIds) > 0 ? $assignedUnitIds[0] : -1;
            }
        }

        if (in_array($user->role->kode, ['super_admin', 'lpma', 'auditor'])) {
            if ($unit_id && $unit_id != 'all' && $unit_id != -1) {
                $unit = \App\Models\Unit::find($unit_id);
                
                if ($unit && stripos($unit->nama, 'LPMA') !== false) {
                    // LPMA manages all university assurance & accreditation
                    $standardsQuery = Standard::query();
                } else {
                    $jenisUnit = $unit->jenis ?? '';
                    $standardsQuery = Standard::where(function($q) use ($jenisUnit) {
                        $q->whereNull('sasaran_unit')
                          ->orWhereJsonContains('sasaran_unit', $jenisUnit);
                    });
                    
                    if ($unit && stripos($unit->nama, 'LPPM') !== false) {
                        $standardsQuery->orWhere('kelompok', 'LIKE', '%Penelitian%')
                                       ->orWhere('kelompok', 'LIKE', '%Pengabdian%');
                    }
                }
            } elseif ($user->role->kode == 'auditor' && $unit_id == -1) {
                $standardsQuery = Standard::where('id', -1); // Return empty
            } else {
                $standardsQuery = Standard::query();
            }
        } else {
            if (isset($user->unit) && stripos($user->unit->nama, 'LPMA') !== false) {
                $standardsQuery = Standard::query();
            } else {
                $jenisUnit = $user->unit->jenis ?? '';
                $standardsQuery = Standard::where(function($q) use ($jenisUnit) {
                    $q->whereNull('sasaran_unit')
                      ->orWhereJsonContains('sasaran_unit', $jenisUnit);
                });
                
                if (isset($user->unit) && stripos($user->unit->nama, 'LPPM') !== false) {
                    $standardsQuery->orWhere('kelompok', 'LIKE', '%Penelitian%')
                                   ->orWhere('kelompok', 'LIKE', '%Pengabdian%');
                }
            }
        }

        $standards = $standardsQuery->get();

        if ($status != 'all') {
            $filteredStandards = collect();
            foreach ($standards as $standard) {
                $entryQuery = LedEntry::where('objek_kode', $standard->kode)
                    ->where('tahun', $tahun);
                if ($unit_id && $unit_id != 'all') {
                    $entryQuery->where('unit_id', $unit_id);
                }
                $entry = $entryQuery->with('stages')->first();

                $isLengkap = $entry && $entry->stages->count() >= 4;
                
                if ($status == 'lengkap' && $isLengkap) {
                    $filteredStandards->push($standard);
                } elseif ($status == 'draft' && !$isLengkap) {
                    $filteredStandards->push($standard);
                }
            }
            $standards = $filteredStandards;
        }
        $hasSubmitted = \App\Models\LedSubmission::where('unit_id', $unit_id)
                            ->where('tahun', $tahun)
                            ->exists();

        return view('led.index', compact('standards', 'tahun', 'unit_id', 'status', 'hasSubmitted'));
    }

    public function edit(Request $request, $kode)
    {
        $standard = Standard::where('kode', $kode)->firstOrFail();

        $activeConfig = \App\Models\BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        
        $unit_id = auth()->user()->unit_id;
        if (in_array(auth()->user()->role->kode, ['super_admin', 'lpma', 'auditor'])) {
            $unit_id = $request->query('unit_id', $unit_id);
        }

        $entryQuery = LedEntry::where('objek_kode', $kode)->where('tahun', $tahun);
        if ($unit_id && $unit_id != 'all') {
            $entryQuery->where('unit_id', $unit_id);
        }
        $entry = $entryQuery->with('stages')->first();

        $stageData = [];
        if ($entry) {
            foreach ($entry->stages as $st) {
                $stageData[$st->tahap] = $st;
            }
        }
        
        $hasSubmitted = \App\Models\LedSubmission::where('unit_id', $unit_id)
                            ->where('tahun', $tahun)
                            ->exists();

        return view('led.edit', compact('standard', 'entry', 'stageData', 'tahun', 'unit_id', 'hasSubmitted'));
    }

    public function exportPdf(Request $request, $kode)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $standard = Standard::where('kode', $kode)->firstOrFail();

        $activeConfig = \App\Models\BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        
        $unit_id = auth()->user()->unit_id;
        if (in_array(auth()->user()->role->kode, ['super_admin', 'lpma'])) {
            $unit_id = $request->query('unit_id', $unit_id);
        }
        
        $unit = $unit_id && $unit_id != 'all' ? \App\Models\Unit::find($unit_id) : null;

        $entryQuery = LedEntry::where('objek_kode', $kode)->where('tahun', $tahun);
        if ($unit_id && $unit_id != 'all') {
            $entryQuery->where('unit_id', $unit_id);
        }
        $entry = $entryQuery->with('stages')->first();

        $stageData = [];
        if ($entry) {
            foreach ($entry->stages as $st) {
                $stageData[$st->tahap] = $st;
            }
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('led.pdf', compact('standard', 'entry', 'stageData', 'tahun', 'unit'));
        return $pdf->stream('LED_'.$kode.'_'.$tahun.'.pdf');
    }

    public function exportFullPdf(Request $request, $unit_id)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $activeConfig = \App\Models\BulanMutuConfig::where('is_active', true)->first();
        $tahun = $request->query('tahun', $activeConfig ? $activeConfig->tahun : date('Y'));
        
        $unit = \App\Models\Unit::findOrFail($unit_id);
        
        // Fetch standards applicable to this unit
        if (stripos($unit->nama, 'LPMA') !== false) {
            $standards = Standard::all();
        } else {
            $jenisUnit = $unit->jenis ?? '';
            $query = Standard::where(function($q) use ($jenisUnit) {
                $q->whereNull('sasaran_unit')
                  ->orWhereJsonContains('sasaran_unit', $jenisUnit);
            });
            if (stripos($unit->nama, 'LPPM') !== false) {
                $query->orWhere('kelompok', 'LIKE', '%Penelitian%')
                      ->orWhere('kelompok', 'LIKE', '%Pengabdian%');
            }
            $standards = $query->get();
        }

        // Preload entries for this unit & year
        $entries = LedEntry::where('tahun', $tahun)
                           ->where('unit_id', $unit_id)
                           ->with('stages')
                           ->get()
                           ->keyBy('objek_kode');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('led.pdf_full', compact('standards', 'entries', 'tahun', 'unit'));
        return $pdf->stream('Full_LED_'.str_replace(' ', '_', $unit->nama).'_'.$tahun.'.pdf');
    }

    public function update(Request $request, $kode)
    {
        $stages = $request->input('stages', []);
        $tahun = date('Y');
        $unit_id = auth()->user()->unit_id ?? 1; // Fallback to 1 if null (admin etc)
        $user_id = auth()->id();

        $standard = Standard::where('kode', $kode)->firstOrFail();
        $jenis = ($standard->kelompok == 'Non-Akademik') ? 'nonakademik' : 'akademik';

        $statusSubmit = $request->input('status', 'draft');

        // Find or create LedEntry
        $entry = LedEntry::firstOrCreate(
            ['tahun' => $tahun, 'jenis' => $jenis, 'objek_kode' => $kode, 'unit_id' => $unit_id],
            ['diisi_oleh_id' => $user_id, 'status_pengisian' => $statusSubmit]
        );
        $entry->update(['status_pengisian' => $statusSubmit, 'diisi_oleh_id' => $user_id]);

        // Process stages
        foreach ($stages as $tahap => $data) {
            $buktiFiles = [];
            
            // Handle multiple file uploads
            if ($request->hasFile("stages.{$tahap}.dokumen")) {
                foreach ($request->file("stages.{$tahap}.dokumen") as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('public/led_documents', $filename);
                    $buktiFiles[] = str_replace('public/', 'storage/', $path);
                }
            }

            // Handle external links (e.g. Google Drive)
            $buktiLinks = [];
            if (!empty($data['links'])) {
                // Split by newline or comma
                $rawLinks = preg_split('/[\n,]+/', $data['links']);
                foreach ($rawLinks as $link) {
                    $link = trim($link);
                    if (!empty($link)) {
                        $buktiLinks[] = $link;
                    }
                }
            }

            // Compile all evidence into JSON structure
            $buktiData = [];
            if (count($buktiFiles) > 0) $buktiData['files'] = $buktiFiles;
            if (count($buktiLinks) > 0) $buktiData['links'] = $buktiLinks;

            // Prepare stage data
            $stageData = [
                'tanggal' => $data['tanggal'] ?? null,
                'penanggung_jawab' => $data['penanggung_jawab'] ?? null,
                'uraian' => $data['uraian'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'data_spesifik' => $data['data_spesifik'] ?? null,
            ];

            if (count($buktiData) > 0) {
                $stageData['bukti'] = json_encode($buktiData);
            }

            LedEntryStage::updateOrCreate(
                ['led_entry_id' => $entry->id, 'tahap' => $tahap],
                $stageData
            );
        }

        // Return back with a success message (to be displayed via session alert, optional)
        return redirect()->route('led.index')->with('success', 'Data Evaluasi Diri berhasil disimpan!');
    }

    public function submit(Request $request) 
    {
        $request->validate([
            'verifikasi' => 'required|accepted',
            'tahun' => 'required|integer'
        ]);

        $tahun = $request->input('tahun');
        
        // If super admin and they passed a unit_id, use it. Otherwise use auth unit
        if (in_array(auth()->user()->role->kode, ['super_admin', 'lpma']) && $request->filled('unit_id') && $request->input('unit_id') != 'all') {
            $unit_id = $request->input('unit_id');
        } else {
            $unit_id = auth()->user()->unit_id;
        }

        if (!$unit_id || $unit_id == 'all') {
            return redirect()->back()->with('error', 'Unit tidak valid.');
        }

        \App\Models\LedSubmission::updateOrCreate(
            ['tahun' => $tahun, 'unit_id' => $unit_id],
            ['submitted_by_id' => auth()->id(), 'submitted_at' => now()]
        );

        return redirect()->route('led.index', ['tahun' => $tahun])->with('success', 'Pengajuan Penilaian LED berhasil dilakukan. Data telah dikunci dan diserahkan ke Auditor.');
    }
}
