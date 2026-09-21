<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Standard;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Aps4StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('../docs/Evaluasi Diri S1 - VERSI 2022.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan di: {$filePath}");
            return;
        }

        $this->command->info('Membaca file Excel...');
        
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(['Evaluasi Diri S1']);
        $spreadsheet = $reader->load($filePath);
        
        $sheet = $spreadsheet->getSheetByName('Evaluasi Diri S1');
        if (!$sheet) {
            $this->command->error('Sheet "Evaluasi Diri S1" tidak ditemukan.');
            return;
        }

        $rows = array_slice($sheet->toArray(), 0, 100);
        
        $currentKelompok = 'Standar Umum';
        $insertedCount = 0;

        foreach ($rows as $rowIndex => $rowData) {
            // Header information starts around row 5 (index 4)
            if ($rowIndex <= 5) continue;

            $colA = trim((string)($rowData[0] ?? ''));
            $colB = trim((string)($rowData[1] ?? ''));
            $colC = trim((string)($rowData[2] ?? ''));
            $colD = trim((string)($rowData[3] ?? ''));

            // Check if this row is a Group Header
            if ($colA !== '' && $colB === '' && $colC === '' && $colD === '') {
                if (str_starts_with($colA, 'A.') || str_starts_with($colA, 'B.') || str_starts_with($colA, 'C') || str_contains($colA, 'Total')) {
                    if (!str_contains(strtolower($colA), 'total')) {
                        $currentKelompok = trim(preg_replace('/\s+/', ' ', $colA));
                    }
                }
                continue;
            }
            
            // Or if A is empty but C has group like "C1. VISI MISI TUJUAN SASARAN"
            if ($colA === '' && $colB === '' && $colC !== '' && $colD === '') {
                if (!str_contains(strtolower($colC), 'total') && !str_contains(strtolower($colC), 'rata-rata')) {
                    $currentKelompok = trim(preg_replace('/\s+/', ' ', $colC));
                }
                continue;
            }

            // Valid row must have Kode (B) and Nama (C or D)
            if ($colA !== '' && $colD !== '' && !in_array(strtolower($colA), ['total', 'rata-rata'])) {
                // Treat this as an indicator row
                $kode = $colB !== '' ? $colB : $colA;
                
                $nama = $colC !== '' ? $colC : 'Indikator ' . $kode;
                $indikator = $colD;

                Standard::updateOrCreate(
                    ['kode' => $kode],
                    [
                        'nama' => preg_replace('/\s+/', ' ', $nama),
                        'indikator' => preg_replace('/\s+/', ' ', $indikator),
                        'kelompok' => $currentKelompok,
                        'periode' => 'STANDAR BAN PT APS 4.0',
                        'is_akademik' => 1,
                        'rubrik_penilaian' => [
                            'p1' => 'Sangat Kurang (Tidak Ada Bukti)',
                            'p2' => 'Kurang (Tidak Memenuhi Standar)',
                            'p3' => 'Cukup (Memenuhi Standar Minimal)',
                            'p4' => 'Baik (Memenuhi Standar/Sistematis)',
                            'p5' => 'Sangat Baik (Melampaui Standar)'
                        ]
                    ]
                );
                
                $insertedCount++;
            }
        }

        $this->command->info("Berhasil menginjeksi {$insertedCount} instrumen standar bermutu!");
    }
}
