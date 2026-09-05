<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$lpma = \App\Models\User::where('email', 'lpma@uss.ac.id')->first();
$activities = \App\Models\BulanMutuActivity::orderBy('index_kegiatan')->get();

\App\Models\BulanMutuStatus::unguard();
\App\Models\BulanMutuConfig::unguard();

foreach($activities as $act) {
    $year = 2024;
    $month = 7;
    $day = min(28, max(1, $act->index_kegiatan * 3));
    
    if ($act->tahap == 'P1') {
        $month = 6;
    } elseif ($act->tahap == 'P2') {
        $month = 7;
    } elseif ($act->tahap == 'P3') {
        $month = 8;
        if ($act->index_kegiatan == 5) $day = 1;
        elseif ($act->index_kegiatan == 6) $day = 8;
        elseif ($act->index_kegiatan == 7) $day = 29;
    } elseif ($act->tahap == 'P4') {
        $month = 9; // September
        if ($act->index_kegiatan == 8) {
            $day = 10;
        } elseif ($act->index_kegiatan == 9) {
            $year = 2025; // RTM next year
            $month = 10; // October
            $day = 10;
        } elseif ($act->index_kegiatan == 10) {
            $year = 2025;
            $month = 10;
            $day = 12;
        }
    } elseif ($act->tahap == 'P5') {
        $year = 2025;
        $month = 10; // October
        if ($act->index_kegiatan == 11) {
            $day = 15; // October 15 deadline
        } elseif ($act->index_kegiatan == 12) {
            $day = 20;
        }
    }
    
    // Semua sudah terlaksana karena RTM siklus 2024 selesai di Oktober 2025
    $status = 'Terlaksana Sesuai Rencana';
    
    $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
    \App\Models\BulanMutuStatus::updateOrCreate(
        ['tahun' => 2024, 'kegiatan_id' => $act->id],
        ['status' => $status, 'tanggal_pelaksanaan' => $dateStr, 'updated_by_id' => $lpma->id]
    );
}
\App\Models\BulanMutuConfig::updateOrCreate(['tahun' => 2024], ['bulan_pelaksanaan' => 'Juni - Oktober', 'is_active' => false, 'updated_by_id' => $lpma->id]);

echo "Done\n";
