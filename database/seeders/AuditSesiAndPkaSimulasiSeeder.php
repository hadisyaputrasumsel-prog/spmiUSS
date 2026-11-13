<?php

namespace Database\Seeders;

use App\Models\AmiFinding;
use App\Models\AmiNilaiTahap;
use App\Models\AuditSesi;
use App\Models\LaporanAmi;
use App\Models\Pka;
use App\Models\PkaLangkah;
use App\Models\Standard;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditSesiAndPkaSimulasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tentukan Unit (Prodi Ilmu Komputer atau Unit ID 17)
        $unit = Unit::where('id', 17)->first()
            ?? Unit::where('nama_unit', 'like', '%Ilmu Komputer%')->first();

        $unitId = $unit ? $unit->id : 17;

        // 2. Tentukan Auditor Penyusun & Pemeriksa
        $auditorKetua = User::where('name', 'like', '%Budi Fachrudin%')->first()
            ?? User::where('email', 'like', '%auditor%')->first()
            ?? User::first();

        $auditorAnggota = User::where('name', 'like', '%Auditor Satu%')->first()
            ?? $auditorKetua;

        // 3. Update or Create Audit Sesi 2023 [SIMULASI]
        AuditSesi::updateOrCreate(
            [
                'unit_id' => $unitId,
                'tahun' => 2023,
            ],
            [
                'no_kka' => 'KKA-ILKOM-C6-01 [SIMULASI]',
                'kriteria' => 'Kriteria C.6 Pendidikan — Matriks Penilaian LED-LKPS BAN-PT No. 5/2019 / Standar SPMI USS',
                'lokasi' => 'Kampus Palembang USS',
                'ruang_lingkup' => 'Kurikulum & Proses Pembelajaran (C.6.4) — data TA 2022/2023',
                'tanggal_audit' => '2023-10-16',
                'wakil_auditi' => 'Kaprodi Ilmu Komputer [SIMULASI]',
                'distribusi' => ['Auditi', 'Auditor', 'LPMA', 'Arsip'],
                'penjamin_mutu' => 'Dr. H. Ahmad Fauzi, M.Pd [SIMULASI]',
                'catatan_penutup' => "VI. Catatan Penutup dan Tindak Lanjut:\nTemuan KTS Major pada C.6.4.g memerlukan perhatian segera dari Dekan dan Rektor terkait alokasi anggaran integrasi penelitian ke dalam bahan ajar. Auditi berkomitmen menyelesaikan akar penyebab sebelum Audit Tindak Lanjut (ATL) yang dijadwalkan pada 16 November 2023.",
            ]
        );

        // 4. Cari Standar C.6.4.a.A atau C6 atau standar pertama
        $standard = Standard::where('kode', 'C.6.4.a.A')->first()
            ?? Standard::where('kode', 'like', 'C.6%')->first()
            ?? Standard::first();

        $standarKode = $standard ? $standard->kode : 'C.6.4.a.A';

        // 5. Update or Create PKA [SIMULASI]
        $pka = Pka::updateOrCreate(
            [
                'unit_id' => $unitId,
                'tahun' => 2023,
                'nomor' => 'PKA-C6-38 [SIMULASI]',
            ],
            [
                'ruang_lingkup' => 'C.6.4.a — Kurikulum, CPL, dan RPS',
                'kriteria' => 'Kriteria C.6 Pendidikan',
                'standar_kode' => $standarKode,
                'tentative_audit_objective' => 'Keterlibatan stakeholder dalam pemutakhiran kurikulum belum formal',
                'tujuan_audit' => 'Memverifikasi dokumen peninjauan kurikulum dan bukti pelibatan stakeholder',
                'disusun_oleh' => $auditorAnggota->id,
                'diperiksa_oleh' => $auditorKetua->id,
                'tanggal_disusun' => '2023-10-10',
                'tanggal_diperiksa' => '2023-10-12',
            ]
        );

        // 6. Langkah kerja PKA (7 langkah sesuai template baku LPMA)
        $pka->langkah()->delete();

        $langkahKerja = [
            [
                'uraian' => 'Pelajari dokumen kurikulum prodi yang berlaku',
                'anggaran_waktu' => '2 Jam',
                'realisasi' => '2 Jam',
                'no_kka' => 'KKA-01',
                'disusun_oleh' => $auditorAnggota->nama_gelar,
            ],
            [
                'uraian' => 'Periksa RPS setiap mata kuliah semester ganjil dan genap',
                'anggaran_waktu' => '3 Jam',
                'realisasi' => '3.5 Jam',
                'no_kka' => 'KKA-02',
                'disusun_oleh' => $auditorAnggota->nama_gelar,
            ],
            [
                'uraian' => 'Verifikasi pemenuhan CPL pada setiap RPS',
                'anggaran_waktu' => '2 Jam',
                'realisasi' => '2 Jam',
                'no_kka' => 'KKA-03',
                'disusun_oleh' => $auditorAnggota->nama_gelar,
            ],
            [
                'uraian' => 'Cek bukti pelibatan pemangku kepentingan (stakeholder) dalam peninjauan kurikulum',
                'anggaran_waktu' => '1.5 Jam',
                'realisasi' => '2 Jam',
                'no_kka' => 'KKA-04',
                'disusun_oleh' => $auditorAnggota->nama_gelar,
            ],
            [
                'uraian' => 'Cek notulen, daftar hadir, dan SK tim penyusun kurikulum',
                'anggaran_waktu' => '1 Jam',
                'realisasi' => '1 Jam',
                'no_kka' => 'KKA-05',
                'disusun_oleh' => $auditorAnggota->nama_gelar,
            ],
            [
                'uraian' => 'Wawancara kaprodi dan dosen pengampu tentang implementasi kurikulum',
                'anggaran_waktu' => '2 Jam',
                'realisasi' => '2 Jam',
                'no_kka' => 'KKA-06',
                'disusun_oleh' => $auditorAnggota->nama_gelar,
            ],
            [
                'uraian' => 'Rumuskan kesimpulan dan catat pada KKA',
                'anggaran_waktu' => '1 Jam',
                'realisasi' => '1 Jam',
                'no_kka' => 'KKA-07',
                'disusun_oleh' => $auditorKetua->nama_gelar,
            ],
        ];

        foreach ($langkahKerja as $idx => $item) {
            PkaLangkah::create([
                'pka_id' => $pka->id,
                'no_urut' => $idx + 1,
                'uraian' => $item['uraian'],
                'anggaran_waktu' => $item['anggaran_waktu'],
                'realisasi' => $item['realisasi'],
                'no_kka' => $item['no_kka'],
                'disusun_oleh' => $item['disusun_oleh'],
            ]);
        }

        // 7. Seed / Sesuaikan Data Simulasi Temuan 2023 untuk Unit 17
        // Menjamin tepat 50 temuan: 47 Sesuai, 1 OB (SM-USS-03-01-11), 1 KTS Minor (SM-USS-03-02-01), 1 KTS Mayor (SM-USS-03-01-13)
        // Rata-rata skor tepat 3.24 dan 100% RTL selesai.
        $existing2023 = AmiFinding::where('unit_id', $unitId)->whereYear('tanggal', 2023)->get();
        foreach ($existing2023 as $old) {
            $old->delete();
        }

        // 3 Temuan Non-Sesuai
        $nonSesuaiData = [
            [
                'standar_kode' => 'SM-USS-03-01-13',
                'kategori_temuan' => 'KTS Mayor',
                'skor' => 1,
                'tahap' => 'P4',
                'uraian' => 'Integrasi kegiatan penelitian dan PkM dosen ke dalam proses pembelajaran (C.6.4.g) belum terdokumentasi dan belum memiliki alokasi anggaran operasional yang terintegrasi pada RPS.',
                'akar_penyebab' => 'Belum ada pedoman teknis operasional integrasi riset/PkM dosen dalam RPS di tingkat Program Studi.',
                'akibat' => 'Rasio NMKI/NDTPS berpotensi tidak memenuhi ambang batas instrumen akreditasi IAPS 4.0 BAN-PT.',
                'rekomendasi' => 'Pimpinan Fakultas dan Prodi segera menerbitkan SK pedoman integrasi riset dan menyusun modul pembelajaran berbasis hasil riset dosen.',
                'tanggapan_auditi' => 'Auditi menyepakati temuan dan berkomitmen merampungkan integrasi riset sebelum tahun ajaran baru.',
                'rencana_tindakan' => 'Menyusun SK Pedoman Integrasi Penelitian dan PkM dalam RPS serta merevisi 10 mata kuliah keahlian.',
                'jadwal_pencegahan' => 'Maksimal 3 bulan sebelum masa peninjauan kurikulum berkala.',
                'pic' => 'Kaprodi & Koordinator Kurikulum',
                'batas_waktu' => '2023-11-30',
                'status_tindak_lanjut' => 'Selesai',
            ],
            [
                'standar_kode' => 'SM-USS-03-02-01',
                'kategori_temuan' => 'KTS Minor',
                'skor' => 2,
                'tahap' => 'P3',
                'uraian' => 'Pelaksanaan evaluasi dan umpan balik penilaian hasil studi mahasiswa semester akhir belum terdokumentasi secara terpusat pada sistem informasi akademik.',
                'akar_penyebab' => 'Dosen pengampu sebagian masih memberikan umpan balik tugas secara manual atau lisan tanpa rekam jejak LMS.',
                'akibat' => 'Kurangnya transparansi dan akuntabilitas tindak lanjut klarifikasi nilai bagi mahasiswa tingkat akhir.',
                'rekomendasi' => 'Mewajibkan rekapitulasi nilai dan umpan balik tugas diunggah pada modul e-learning USS selambatnya 1 minggu setelah ujian.',
                'tanggapan_auditi' => 'Akan disosialisasikan dan diwajibkan kepada seluruh dosen tetap program studi.',
                'rencana_tindakan' => 'Sosialisasi pengisian rubrik evaluasi berkala dan monitoring mingguan oleh GPM.',
                'jadwal_pencegahan' => 'Setiap awal semester sebelum perkuliahan dimulai.',
                'pic' => 'Sekretaris Prodi & GPM',
                'batas_waktu' => '2023-11-20',
                'status_tindak_lanjut' => 'Selesai',
            ],
            [
                'standar_kode' => 'SM-USS-03-01-11',
                'kategori_temuan' => 'Observasi (OB)',
                'skor' => 3,
                'tahap' => 'P2',
                'uraian' => 'Sertifikasi kompetensi keahlian dan kemampuan bahasa Inggris mahasiswa sudah berjalan baik, namun arsip sertifikat digital belum terintegrasi ke PDDIKTI.',
                'akar_penyebab' => 'Proses sinkronisasi data sertifikat masih berlangsung secara berkala tiap semester.',
                'akibat' => 'Potensi keterlambatan penerbitan Surat Keterangan Pendamping Ijazah (SKPI).',
                'rekomendasi' => 'Mengintegrasikan database sertifikasi ke sistem SIAKAD USS secara otomatis.',
                'tanggapan_auditi' => 'Siap berkoordinasi dengan UPT TIK USS untuk integrasi data.',
                'rencana_tindakan' => 'Melakukan integrasi API database sertifikasi dengan SIAKAD.',
                'jadwal_pencegahan' => 'Sinkronisasi berkala tiap penutupan yudisium.',
                'pic' => 'Koordinator Kemahasiswaan & UPT TIK',
                'batas_waktu' => '2023-12-10',
                'status_tindak_lanjut' => 'Selesai',
            ],
        ];

        foreach ($nonSesuaiData as $item) {
            $createdFinding = AmiFinding::create([
                'jenis' => 'akademik',
                'standar_kode' => $item['standar_kode'],
                'tahap' => $item['tahap'],
                'unit_id' => $unitId,
                'tahun' => 2023,
                'status_audit' => 'Sudah Diaudit',
                'kategori_temuan' => $item['kategori_temuan'],
                'skor' => $item['skor'],
                'uraian' => $item['uraian'],
                'akar_penyebab' => $item['akar_penyebab'],
                'akibat' => $item['akibat'],
                'rekomendasi' => $item['rekomendasi'],
                'tanggapan_auditi' => $item['tanggapan_auditi'],
                'rencana_tindakan' => $item['rencana_tindakan'],
                'jadwal_pencegahan' => $item['jadwal_pencegahan'],
                'pic' => $item['pic'],
                'batas_waktu' => $item['batas_waktu'],
                'tanggal' => '2023-10-16',
                'auditor_id' => $auditorKetua->id,
                'status_tindak_lanjut' => $item['status_tindak_lanjut'],
            ]);

            // Nilai tahap
            foreach (['P1', 'P2', 'P3', 'P4', 'P5'] as $t) {
                $stageScore = ($t === $item['tahap']) ? $item['skor'] : 3;
                AmiNilaiTahap::updateOrCreate(
                    ['ami_id' => $createdFinding->id, 'tahap' => $t],
                    ['skor' => $stageScore, 'catatan' => null]
                );
            }
        }

        // 47 Temuan Sesuai
        // Agar rata-rata skor = 3.24:
        // Total skor dibutuhkan = 50 * 3.24 = 162.
        // Skor 3 temuan non-sesuai: 1 + 2 + 3 = 6.
        // Sisa skor untuk 47 temuan sesuai = 162 - 6 = 156.
        // 15 temuan dengan skor 4 (15 * 4 = 60) + 32 temuan dengan skor 3 (32 * 3 = 96) = 156!
        // SM-USS-03-01-01 diberi skor 3 sesuai kriteria selesai poin 9.
        $standards = Standard::whereNotIn('kode', ['SM-USS-03-01-13', 'SM-USS-03-02-01', 'SM-USS-03-01-11'])
            ->orderBy('id')
            ->take(47)
            ->get();

        foreach ($standards as $idx => $std) {
            $score = ($std->kode !== 'SM-USS-03-01-01' && $idx <= 15) ? 4 : 3;
            $createdFinding = AmiFinding::create([
                'jenis' => ($std->kelompok == 'Non-Akademik') ? 'nonakademik' : 'akademik',
                'standar_kode' => $std->kode,
                'tahap' => 'Umum',
                'unit_id' => $unitId,
                'tahun' => 2023,
                'status_audit' => 'Sudah Diaudit',
                'kategori_temuan' => 'Sesuai',
                'skor' => $score,
                'uraian' => 'Pelaksanaan dan pemenuhan standar '.$std->nama.' telah berjalan dengan sangat baik dan terdokumentasi sesuai siklus PPEPP.',
                'akar_penyebab' => null,
                'akibat' => null,
                'rekomendasi' => null,
                'tanggapan_auditi' => null,
                'rencana_tindakan' => null,
                'jadwal_pencegahan' => null,
                'pic' => null,
                'batas_waktu' => null,
                'tanggal' => '2023-10-16',
                'auditor_id' => $auditorKetua->id,
                'status_tindak_lanjut' => 'Selesai',
            ]);

            foreach (['P1', 'P2', 'P3', 'P4', 'P5'] as $t) {
                AmiNilaiTahap::updateOrCreate(
                    ['ami_id' => $createdFinding->id, 'tahap' => $t],
                    ['skor' => $score, 'catatan' => null]
                );
            }
        }

        // 8. Seed Laporan AMI untuk tahun 2023
        LaporanAmi::updateOrCreate(
            ['tahun' => 2023],
            LaporanAmi::getDefaultData(2023)
        );
    }
}
