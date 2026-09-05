<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Standard;
use App\Models\NonacademicCategory;

$filePath = __DIR__ . '/docs/Template Laporan PPEPP SPMI USS.xlsx';

if (!file_exists($filePath)) {
    echo "File not found!\n";
    exit;
}

$spreadsheet = IOFactory::load($filePath);

// 1. Process Akademik (Instrumen AMI)
$sheet = $spreadsheet->getSheetByName('Instrumen AMI');
$highestRow = $sheet->getHighestRow();

$compiledIndicators = [];

for ($row = 6; $row <= $highestRow; $row++) {
    $kodeStandar = trim($sheet->getCell('C' . $row)->getValue());
    $tahap = trim($sheet->getCell('E' . $row)->getValue());
    $kriteria = trim($sheet->getCell('F' . $row)->getValue());

    if (!empty($kodeStandar) && !empty($kriteria)) {
        // format tahap to just P1, P2 etc
        preg_match('/^(\d)\./', $tahap, $matches);
        $tahapLabel = isset($matches[1]) ? 'P' . $matches[1] : $tahap;
        
        if (!isset($compiledIndicators[$kodeStandar])) {
            $compiledIndicators[$kodeStandar] = [];
        }
        $compiledIndicators[$kodeStandar][] = "**{$tahapLabel}**: {$kriteria}";
    }
}

$updated = 0;
foreach ($compiledIndicators as $kode => $indicators) {
    $standard = Standard::where('kode', $kode)->first();
    if ($standard) {
        $standard->indikator = implode("\n\n", $indicators);
        $standard->save();
        $updated++;
    }
}
echo "Akademik: Updated {$updated} standards.\n";

// 2. Process Non-Akademik (Instrumen AMI Non-Akademik)
$sheetNA = $spreadsheet->getSheetByName('Instrumen AMI Non-Akademik');
if ($sheetNA) {
    $highestRowNA = $sheetNA->getHighestRow();
    $compiledNA = [];

    for ($row = 6; $row <= $highestRowNA; $row++) {
        $kodeStandar = trim($sheetNA->getCell('B' . $row)->getValue());
        $tahap = trim($sheetNA->getCell('D' . $row)->getValue());
        $kriteria = trim($sheetNA->getCell('E' . $row)->getValue());

        if (!empty($kodeStandar) && !empty($kriteria)) {
            preg_match('/^(\d)\./', $tahap, $matches);
            $tahapLabel = isset($matches[1]) ? 'P' . $matches[1] : $tahap;
            
            if (!isset($compiledNA[$kodeStandar])) {
                $compiledNA[$kodeStandar] = [];
            }
            $compiledNA[$kodeStandar][] = "**{$tahapLabel}**: {$kriteria}";
        }
    }

    $updatedNA = 0;
    foreach ($compiledNA as $kode => $indicators) {
        $standard = NonacademicCategory::where('kode', $kode)->first();
        if ($standard) {
            $standard->indikator = implode("\n\n", $indicators);
            $standard->save();
            $updatedNA++;
        }
    }
    echo "Non-Akademik: Updated {$updatedNA} categories.\n";
}
