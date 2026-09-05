<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BulanMutuActivity;
use App\Models\BulanMutuStatus;

class BulanMutuController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $activities = BulanMutuActivity::orderBy('index_kegiatan', 'asc')->get();
        
        // Get statuses for this year
        $statuses = BulanMutuStatus::where('tahun', $tahun)->get()->keyBy('kegiatan_id');
        
        return view('bulan_mutu.index', compact('activities', 'statuses', 'tahun'));
    }
    
    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $activities = BulanMutuActivity::orderBy('index_kegiatan', 'asc')->get();
        $statuses = BulanMutuStatus::where('tahun', $tahun)->get()->keyBy('kegiatan_id');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bulan_mutu.pdf', compact('activities', 'statuses', 'tahun'));
        return $pdf->stream('Jadwal_Bulan_Mutu_'.$tahun.'.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $tahun = $request->input('tahun', date('Y'));
        $userId = auth()->id(); 

        BulanMutuStatus::updateOrCreate(
            ['tahun' => $tahun, 'kegiatan_id' => $id],
            [
                'status' => $request->input('status'),
                'tanggal_pelaksanaan' => $request->input('tanggal_pelaksanaan'),
                'catatan' => $request->input('catatan'),
                'updated_by_id' => $userId
            ]
        );

        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui.');
    }

    public function setActiveYear(Request $request)
    {
        $tahun = $request->input('tahun');
        
        \App\Models\BulanMutuConfig::query()->update(['is_active' => false]);
        
        \App\Models\BulanMutuConfig::updateOrCreate(
            ['tahun' => $tahun],
            ['is_active' => true, 'updated_by_id' => auth()->id()]
        );

        return redirect()->back()->with('success', 'Tahun ' . $tahun . ' sekarang ditetapkan sebagai tahun pelaksanaan aktif.');
    }
}
