<?php

namespace App\Imports;

use App\Models\AmiFinding;
use App\Models\Standard;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Carbon;

class AmiImport implements ToModel, WithStartRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Instrumen AMI' => $this
        ];
    }

    public function startRow(): int
    {
        return 5; // Data starts at row 5 (Header is row 4)
    }

    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        // Columns based on Template Laporan PPEPP SPMI USS.xlsx (Instrumen AMI sheet):
        // 0: No
        // 1: Kelompok Standar
        // 2: Kode Standar
        // 3: Nama Standar
        // 4: Tahap PPEPP
        // 5: Kriteria
        // 6: Unit (auditee)
        // 7: Bukti
        // 8: Kondisi
        // 9: Kategori Temuan
        // 10: Uraian Ketidaksesuaian
        // 11: Akar Penyebab
        // 12: Rencana Tindakan Koreksi (PTK)
        // 13: PIC
        // 14: Batas Waktu
        // 15: Status Verifikasi
        // 16: Auditor
        // 17: Tanggal Audit

        if (empty($row[2])) {
            return null; // Skip empty rows without Standard code
        }

        // Try to resolve standard
        $standard = Standard::where('kode', $row[2])->first();
        if (!$standard) {
            $standard = Standard::where('nama', $row[2])->first();
        }

        // Try to resolve unit
        $unitName = trim($row[6] ?? '');
        $unit = null;
        if (!empty($unitName)) {
            $unit = Unit::where('nama', 'LIKE', '%' . $unitName . '%')->first();
        }
        if (!$unit) {
            $unit = Unit::first(); // Fallback to a default unit if empty
        }

        if (!$standard || !$unit) {
            return null; // Skip if invalid relationships
        }

        // Parse Tahap (e.g. "1. PENETAPAN" -> "P1")
        $tahapString = $row[4] ?? '';
        $tahap = 'P2';
        if (strpos($tahapString, '1.') !== false) $tahap = 'P1';
        elseif (strpos($tahapString, '2.') !== false) $tahap = 'P2';
        elseif (strpos($tahapString, '3.') !== false) $tahap = 'P3';
        elseif (strpos($tahapString, '4.') !== false) $tahap = 'P4';
        elseif (strpos($tahapString, '5.') !== false) $tahap = 'P5';

        $tanggal = null;
        if (!empty($row[17])) {
            try {
                if (is_numeric($row[17])) {
                    $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[17])->format('Y-m-d');
                } else {
                    $tanggal = Carbon::parse($row[17])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $tanggal = date('Y-m-d');
            }
        } else {
            $tanggal = date('Y-m-d');
        }

        $batas_waktu = null;
        if (!empty($row[14])) {
            try {
                if (is_numeric($row[14])) {
                    $batas_waktu = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[14])->format('Y-m-d');
                } else {
                    $batas_waktu = Carbon::parse($row[14])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Ignore
            }
        }

        $kategori = trim($row[9] ?? '');
        if (empty($kategori)) $kategori = 'Sesuai';
        $status_tindak_lanjut = in_array($kategori, ['KTS Minor', 'KTS Mayor']) ? 'Belum' : 'N/A';
        
        $uraian = trim($row[10] ?? '');
        if (empty($uraian) && !empty($row[8])) {
            $uraian = trim($row[8]); // Fallback to kondisi/fakta
        }

        return new AmiFinding([
            'jenis' => $standard->is_akademik ? 'akademik' : 'nonakademik',
            'standar_kode' => $standard->kode,
            'tahap' => $tahap,
            'unit_id' => $unit->id,
            'kategori_temuan' => $kategori,
            'uraian' => $uraian,
            'rencana_tindakan' => $row[12] ?? null,
            'pic' => $row[13] ?? null,
            'batas_waktu' => $batas_waktu,
            'auditor_id' => auth()->id() ?? 1,
            'tanggal' => $tanggal,
            'status_tindak_lanjut' => $status_tindak_lanjut,
        ]);
    }
}
