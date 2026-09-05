<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Unit;
use App\Models\Standard;
use App\Models\AmiFinding;
use App\Models\User;

$unit = Unit::where('nama', 'like', '%Ilmu Komputer%')->first();
$auditor = User::where('role_id', 3)->first(); // auditor
if (!$auditor) $auditor = User::first();

$standards = Standard::whereNull('sasaran_unit')
        ->orWhereJsonContains('sasaran_unit', 'akademik')
        ->get();

foreach ($standards as $index => $standard) {
    $kategori = ['OB', 'K', 'KTS Minor', 'KTS Mayor'][rand(0, 3)];
    $status = 'Selesai';
    
    if (in_array($kategori, ['OB', 'K'])) {
        $uraian = "Pelaksanaan standar {$standard->nama} sudah berjalan dengan sangat baik dan memenuhi target.";
        $rencana = "-";
        $status = "-";
    } else {
        $uraian = "Terdapat ketidaksesuaian pelaksanaan pada standar {$standard->nama}. Bukti fisik belum lengkap seluruhnya.";
        $rencana = "Melengkapi dokumen pendukung pada semester berikutnya dan melakukan review secara berkala.";
    }

    // Assuming year is either stored or we just create it (AmiFinding usually uses the date to imply year)
    AmiFinding::updateOrCreate([
        'unit_id' => $unit->id,
        'standar_kode' => $standard->kode,
        'tanggal' => '2024-09-15', // use date for 2024
    ], [
        'jenis' => 'akademik',
        'tahap' => 'P3', // Evaluasi/AMI
        'kategori_temuan' => $kategori,
        'uraian' => $uraian,
        'rencana_tindakan' => $rencana,
        'pic' => 'Ketua Prodi',
        'batas_waktu' => '2024-11-30',
        'auditor_id' => $auditor->id,
        'status_tindak_lanjut' => $status,
    ]);
}
echo "Done filling AMI for Ilmu Komputer 2024";
