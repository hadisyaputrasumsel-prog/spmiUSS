<?php 
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
use App\Models\Standard; 

$standar = Standard::where('kode', 'SM-USS-03-01-01')->first(); 
if($standar) { 
    $standar->update([ 
        'indikator' => 'Kualifikasi kemampuan lulusan yang mencakup sikap, pengetahuan, dan keterampilan (CPL). Kriteria mencakup masa studi, persentase kelulusan tepat waktu, waktu tunggu lulusan, dan wirausaha lulusan.', 
        'target' => "- Masa studi lulusan rata-rata maksimal 5 tahun.\n- Persentase kelulusan tepat waktu minimal 80%.\n- Waktu tunggu lulusan mendapatkan pekerjaan pertama rata-rata maksimal 4 bulan.\n- (Tambahan unit) Wirausaha 30%; TOEFL min. 400.", 
        'acuan' => 'Buku Manual Standar Pendidikan - Kompetensi Lulusan (SM-USS)', 
        'indikator_p1' => 'Apakah terdapat bukti penetapan kompetensi umum lulusan oleh Universitas dan profil lulusan oleh Program Studi?', 
        'indikator_p2' => 'Apakah proses pendidikan telah menjamin tercapainya CPL (masa studi 5 tahun, kelulusan 80%, waktu tunggu 4 bulan, wirausaha 30%)?', 
        'indikator_p3' => 'Apakah dilakukan evaluasi kesesuaian kualifikasi lulusan (Tracer Study) secara berkala?', 
        'indikator_p4' => 'Apakah dilakukan rapat tinjauan manajemen (RTM) tindak lanjut dan perbaikan jika ada target lulusan yang belum tercapai?', 
        'indikator_p5' => 'Apakah dilakukan revisi/peningkatan target CPL dengan melibatkan stakeholders (dunia usaha/industri) minimal setiap 4 tahun?' 
    ]); 
    echo "Sukses diupdate!\n"; 
} else { 
    echo "Gagal menemukan standar!\n"; 
}
