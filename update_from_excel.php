<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Standard;

$file = __DIR__ . '/docs/Template Laporan PPEPP SPMI USS.xlsx';
$spreadsheet = IOFactory::load($file);

$count = 0;
foreach ($spreadsheet->getSheetNames() as $sheetName) {
    if (in_array($sheetName, ['Rekap', 'Panduan'])) continue;
    
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $highestRow = $sheet->getHighestRow();
    
    for ($row = 5; $row <= $highestRow; $row++) { // Data starts at row 5
        $kode = trim($sheet->getCell("B{$row}")->getValue() ?? '');
        $indikator = trim($sheet->getCell("D{$row}")->getValue() ?? '');
        $pj = trim($sheet->getCell("E{$row}")->getValue() ?? '');
        
        if (!empty($kode) && (!empty($indikator) || !empty($pj))) {
            $standard = Standard::where('kode', $kode)->first();
            if ($standard) {
                if (!empty($indikator)) $standard->indikator = $indikator;
                if (!empty($pj)) $standard->penanggung_jawab = $pj;
                $standard->save();
                $count++;
            }
        }
    }
}
echo "Updated $count standards from Excel.\n";
