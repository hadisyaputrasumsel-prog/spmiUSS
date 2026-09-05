<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmiIlmuKomputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Unit
        $unit = \App\Models\Unit::firstOrCreate([
            'nama' => 'Prodi Ilmu Komputer',
        ], [
            'jenis' => 'Program Studi',
        ]);

        // 2. Create Users
        $kaprodi = \App\Models\User::firstOrCreate([
            'email' => 'kaprodi.ilkom@uss.ac.id',
        ], [
            'name' => 'Kaprodi Ilmu Komputer',
            'password' => bcrypt('password'),
            'role_id' => 5, // auditee_prodi
        ]);

        $auditor = \App\Models\User::firstOrCreate([
            'email' => 'auditor.ilkom@uss.ac.id',
        ], [
            'name' => 'Budi Auditor (Ilkom)',
            'password' => bcrypt('password'),
            'role_id' => 3, // auditor
        ]);

        // 3. Create Auditor Assignments for 2024 and 2025
        \App\Models\AuditorAssignment::firstOrCreate(['tahun' => 2024, 'auditor_id' => $auditor->id, 'unit_id' => $unit->id]);
        \App\Models\AuditorAssignment::firstOrCreate(['tahun' => 2025, 'auditor_id' => $auditor->id, 'unit_id' => $unit->id]);

        // 4. Create LED Entries and AMI Findings for both years
        $standards = \App\Models\Standard::limit(5)->get();
        $years = [2024, 2025];

        foreach ($years as $year) {
            foreach ($standards as $index => $standard) {
                // 1. Create LedEntry
                $ledEntry = \App\Models\LedEntry::firstOrCreate([
                    'unit_id' => $unit->id,
                    'objek_kode' => $standard->kode,
                    'tahun' => $year,
                ], [
                    'jenis' => 'akademik',
                    'diisi_oleh_id' => $kaprodi->id,
                ]);

                // 2. Create LedEntryStage (Tahap P1 & P2 as examples of completed LED)
                \App\Models\LedEntryStage::firstOrCreate([
                    'led_entry_id' => $ledEntry->id,
                    'tahap' => 'P1',
                ], [
                    'tanggal' => $year . '-02-15',
                    'penanggung_jawab' => 'Ketua Prodi',
                    'uraian' => 'Penetapan target capaian standar ' . $standard->nama,
                    'bukti' => json_encode(['Dokumen_SK_' . $year . '.pdf']),
                ]);
                
                \App\Models\LedEntryStage::firstOrCreate([
                    'led_entry_id' => $ledEntry->id,
                    'tahap' => 'P2',
                ], [
                    'tanggal' => $year . '-06-15',
                    'penanggung_jawab' => 'Dosen / Tendik',
                    'uraian' => 'Pelaksanaan kegiatan sesuai standar ' . $standard->nama,
                    'bukti' => json_encode(['Laporan_Pelaksanaan_' . $year . '.pdf']),
                ]);

                // 3. AMI Finding
                $isCompleted = ($year == 2024 || $index < 2); // Make 2024 fully complete, 2025 partially complete
                
                \App\Models\AmiFinding::create([
                    'jenis' => 'akademik',
                    'standar_kode' => $standard->kode,
                    'tahap' => 'P3', // Evaluasi/AMI
                    'unit_id' => $unit->id,
                    'kategori_temuan' => $index % 2 == 0 ? 'KTS Minor' : 'KTS Mayor',
                    'uraian' => 'Terdapat ketidaksesuaian pada standar ' . $standard->nama . ' tahun ' . $year,
                    'rencana_tindakan' => 'Melakukan revisi panduan dan sosialisasi',
                    'pic' => 'Ketua Prodi',
                    'batas_waktu' => $year . '-11-30',
                    'auditor_id' => $auditor->id,
                    'tanggal' => $year . '-09-15',
                    'status_tindak_lanjut' => $isCompleted ? 'Selesai' : 'Belum',
                ]);
            }
        }
    }
}
