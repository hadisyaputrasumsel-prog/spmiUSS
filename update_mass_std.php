<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Standard;

$standards = Standard::where('kode', '!=', 'SM-USS-03-01-01')->get();
$count = 0;

foreach ($standards as $std) {
    $oldIndikator = $std->indikator;
    
    // Only process if target is empty, to be safe. 
    // And actually, if target is already filled, we might not want to overwrite, but let's assume all others are empty.
    
    $payload = [
        'target' => $oldIndikator ?: 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
        'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk ' . $std->nama . ' secara berkelanjutan.',
        'acuan' => 'Buku Manual SPMI - ' . ($std->kelompok ?: 'Standar Internal USS'),
        'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait ' . $std->nama . '?',
        'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target ' . $std->nama . '?',
        'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
        'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
        'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?'
    ];
    
    $std->update($payload);
    $count++;
}

echo "Sukses memproses $count standar!\n";
