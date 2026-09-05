<?php
require 'vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('docs/Template Laporan PPEPP SPMI USS.xlsx');
$sheetNames = $spreadsheet->getSheetNames();
echo "Sheets: " . implode(', ', $sheetNames) . "\n";
foreach($sheetNames as $sheetName) {
    $sheet = $spreadsheet->getSheetByName($sheetName);
    echo "--- $sheetName ---\n";
    $highestRow = min($sheet->getHighestRow(), 10); // get first 10 rows
    for ($row = 1; $row <= $highestRow; $row++) {
        $cellValues = [];
        for ($col = 'A'; $col <= 'E'; $col++) {
            $cellValues[] = $sheet->getCell($col . $row)->getFormattedValue();
        }
        echo implode(" | ", $cellValues) . "\n";
    }
}
