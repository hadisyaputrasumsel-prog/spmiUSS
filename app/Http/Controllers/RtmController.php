<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BulanMutuConfig;

class RtmController extends Controller
{
    public function generatePdf(Request $request)
    {
        ini_set('memory_limit', '-1'); // Increase memory limit for PDF generation
        set_time_limit(300); // Increase execution time limit for long DOMPDF processing
        $activeConfig = BulanMutuConfig::where('is_active', true)->first();
        $tahun = $activeConfig ? $activeConfig->tahun : date('Y');
        
        $data = [
            'tanggal' => date('d F Y'),
            'tempat' => 'Ruang Rapat Utama USS',
            'tahun' => $tahun,
        ];

        $pdf = Pdf::loadView('rtm.berita_acara_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("Berita_Acara_RTM_SPMI_{$tahun}.pdf");
    }
}
