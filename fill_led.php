<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Standard;
use App\Models\LedEntry;
use App\Models\LedEntryStage;
use App\Models\Unit;
use App\Models\User;

$units = Unit::all();

$years = [2024, 2025, 2026];
$stages = ['P1', 'P2', 'P3', 'P4', 'P5'];

foreach ($units as $unit) {
    $user = User::where('unit_id', $unit->id)->first();
    $user_id = $user ? $user->id : 1;

    $standards = Standard::whereNull('sasaran_unit')
        ->orWhereJsonContains('sasaran_unit', $unit->jenis)
        ->get();
        
    $jenisStr = $unit->jenis == 'non-akademik' ? 'non_akademik' : 'akademik';

    foreach ($years as $tahun) {
        foreach ($standards as $standard) {
            $entry = LedEntry::updateOrCreate(
                ['tahun' => $tahun, 'jenis' => $jenisStr, 'objek_kode' => $standard->kode, 'unit_id' => $unit->id],
                ['diisi_oleh_id' => $user_id]
            );

            $uraianDetails = [
                'P1' => "Telah dilakukan penetapan standar {$standard->nama} pada awal tahun ajaran {$tahun}. Dokumen telah disahkan dan didistribusikan ke seluruh bagian {$unit->nama}.",
                'P2' => "Pelaksanaan aktivitas yang terkait dengan standar {$standard->nama} telah diimplementasikan sesuai SOP yang berlaku di {$unit->nama}.",
                'P3' => "Dilakukan monitoring dan evaluasi (Monev) internal pada unit {$unit->nama}. Capaian indikator untuk standar {$standard->nama} menunjukkan hasil yang sangat memuaskan.",
                'P4' => "Berdasarkan hasil evaluasi, dilakukan rapat pengendalian. Tindak lanjut yang diambil meliputi perbaikan sistem dan peningkatan fasilitas di {$unit->nama}.",
                'P5' => "Berdasarkan tren capaian tahun ini, unit {$unit->nama} merekomendasikan peningkatkan target (baseline) untuk standar {$standard->nama} pada RTM tahun berikutnya."
            ];

            foreach ($stages as $tahap) {
                if ($tahap == 'P1') {
                    $tanggal = $tahun . "-06-15";
                } elseif ($tahap == 'P2') {
                    $tanggal = $tahun . "-07-15";
                } elseif ($tahap == 'P3') {
                    $tanggal = $tahun . "-08-15";
                } elseif ($tahap == 'P4') {
                    $tanggal = $tahun . "-09-15";
                } elseif ($tahap == 'P5') {
                    $tanggal = ($tahun+1) . "-10-15";
                } else {
                    $tanggal = $tahun . "-07-15";
                }

                LedEntryStage::updateOrCreate(
                    ['led_entry_id' => $entry->id, 'tahap' => $tahap],
                    [
                        'tanggal' => $tanggal,
                        'penanggung_jawab' => 'Ketua ' . $unit->nama,
                        'uraian' => $uraianDetails[$tahap],
                        'catatan' => 'Semua bukti/dokumen terkait telah disiapkan dan divalidasi.'
                    ]
                );
            }
        }
        echo "Done filling LED for " . $unit->nama . " year " . $tahun . "\n";
    }
}
