<?php
require __DIR__.'/vendor/autoload.php';

$file = __DIR__.'/docs/Template Laporan PPEPP SPMI USS.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

$sheets = ['Instrumen AMI', 'Instrumen AMI Non-Akademik'];

foreach ($sheets as $sheetName) {
    echo "=========================================\n";
    echo "Sheet: $sheetName\n";
    echo "=========================================\n";
    $sheet = $spreadsheet->getSheetByName($sheetName);
    if (!$sheet) {
        echo "Not found!\n";
        continue;
    }
    
    $rows = $sheet->toArray();
    
    // Rows 4 and 5 (0-indexed 3 and 4, though let's just do 3 and 4, maybe 5)
    $slice = array_slice($rows, 3, 3);
    
    foreach ($slice as $rowIndex => $row) {
        $rowData = array_map(function($val) {
            return $val === null ? 'NULL' : $val;
        }, $row);
        echo "Row " . ($rowIndex + 4) . ":\n";
        print_r($rowData);
    }
}
