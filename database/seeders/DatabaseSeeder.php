<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['kode' => 'super_admin', 'nama' => 'Super Administrator', 'deskripsi' => 'Administrasi teknis akun'],
            ['kode' => 'lpma', 'nama' => 'LPMA', 'deskripsi' => 'Pengelola keseluruhan siklus SPMI/AMI'],
            ['kode' => 'auditor', 'nama' => 'Auditor', 'deskripsi' => 'Pelaksana audit'],
            ['kode' => 'auditee_upps', 'nama' => 'Fakultas / UPPS', 'deskripsi' => 'Auditee Fakultas'],
            ['kode' => 'auditee_prodi', 'nama' => 'Program Studi', 'deskripsi' => 'Auditee Prodi'],
            ['kode' => 'auditee_unit', 'nama' => 'Unit / Biro', 'deskripsi' => 'Auditee Unit Non-Akademik'],
            ['kode' => 'pimpinan', 'nama' => 'Rektor / Wakil Rektor', 'deskripsi' => 'Eksekutif'],
            ['kode' => 'gpm_upm', 'nama' => 'GPM / UPM', 'deskripsi' => 'Pendamping mutu di unit'],
        ];
        DB::table('roles')->insert($roles);

        // 2. Units
        $units = [
            ['nama' => 'Universitas', 'jenis' => 'Universitas'],
            ['nama' => 'Fakultas Ekonomi', 'jenis' => 'Fakultas'],
            ['nama' => 'Prodi Manajemen - Fakultas Ekonomi', 'jenis' => 'Program Studi'],
            ['nama' => 'Fakultas Ilmu Komunikasi', 'jenis' => 'Fakultas'],
            ['nama' => 'Fakultas Pertanian (Agribisnis)', 'jenis' => 'Fakultas'],
            ['nama' => 'Fakultas Pertanian (Ilmu Perikanan)', 'jenis' => 'Fakultas'],
            ['nama' => 'Fakultas Ilmu Komputer', 'jenis' => 'Fakultas'],
            ['nama' => 'BAUK', 'jenis' => 'Non-Akademik'],
            ['nama' => 'Biro SDM', 'jenis' => 'Non-Akademik'],
            ['nama' => 'Biro Keuangan', 'jenis' => 'Non-Akademik'],
            ['nama' => 'UPT Sarana Prasarana', 'jenis' => 'Non-Akademik'],
            ['nama' => 'Kantor Kerjasama', 'jenis' => 'Non-Akademik'],
            ['nama' => 'Bagian Kemahasiswaan', 'jenis' => 'Non-Akademik'],
        ];
        DB::table('units')->insert($units);

        // 3. Standards (Pendidikan, Penelitian, PkM, Tambahan)
        $standards = [
            // Pendidikan
            ['kode' => 'SM-USS-03-01-01', 'nama' => 'Kompetensi Lulusan', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-02', 'nama' => 'Isi Pembelajaran', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-03', 'nama' => 'Proses Pembelajaran', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-04', 'nama' => 'Penilaian Pembelajaran', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-05', 'nama' => 'Dosen dan Tenaga Kependidikan', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-06', 'nama' => 'Sarana dan Prasarana Pembelajaran', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-07', 'nama' => 'Pengelolaan Pembelajaran', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-08', 'nama' => 'Pembiayaan Pembelajaran', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-09', 'nama' => 'Proses Penerimaan Mahasiswa Baru', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-10', 'nama' => 'Pembelajaran Entrepreneur', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-11', 'nama' => 'Kemampuan Bahasa Inggris', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-12', 'nama' => 'Kerjasama', 'kelompok' => 'Pendidikan'],
            ['kode' => 'SM-USS-03-01-13', 'nama' => 'Merdeka Belajar Kampus Merdeka (MBKM)', 'kelompok' => 'Pendidikan'],

            // Penelitian
            ['kode' => 'SM-USS-03-02-01', 'nama' => 'Hasil Penelitian', 'kelompok' => 'Penelitian'],
            ['kode' => 'SM-USS-03-02-02', 'nama' => 'Isi Penelitian', 'kelompok' => 'Penelitian'],
            ['kode' => 'SM-USS-03-02-03', 'nama' => 'Proses Penelitian', 'kelompok' => 'Penelitian'],
            ['kode' => 'SM-USS-03-02-04', 'nama' => 'Penilaian Penelitian', 'kelompok' => 'Penelitian'],
            ['kode' => 'SM-USS-03-02-05', 'nama' => 'Peneliti', 'kelompok' => 'Penelitian'],
            ['kode' => 'SM-USS-03-02-06', 'nama' => 'Sarana dan Prasarana Penelitian', 'kelompok' => 'Penelitian'],
            ['kode' => 'SM-USS-03-02-07', 'nama' => 'Pengelolaan Penelitian', 'kelompok' => 'Penelitian'],
            ['kode' => 'SM-USS-03-02-08', 'nama' => 'Pendanaan dan Pembiayaan Penelitian', 'kelompok' => 'Penelitian'],

            // PkM
            ['kode' => 'SM-USS-03-03-01', 'nama' => 'Hasil PkM', 'kelompok' => 'PkM'],
            ['kode' => 'SM-USS-03-03-02', 'nama' => 'Isi PkM', 'kelompok' => 'PkM'],
            ['kode' => 'SM-USS-03-03-03', 'nama' => 'Proses PkM', 'kelompok' => 'PkM'],
            ['kode' => 'SM-USS-03-03-04', 'nama' => 'Penilaian PkM', 'kelompok' => 'PkM'],
            ['kode' => 'SM-USS-03-03-05', 'nama' => 'Pelaksana PkM', 'kelompok' => 'PkM'],
            ['kode' => 'SM-USS-03-03-06', 'nama' => 'Sarana dan Prasarana PkM', 'kelompok' => 'PkM'],
            ['kode' => 'SM-USS-03-03-07', 'nama' => 'Pengelolaan PkM', 'kelompok' => 'PkM'],
            ['kode' => 'SM-USS-03-03-08', 'nama' => 'Pendanaan dan Pembiayaan PkM', 'kelompok' => 'PkM'],

            // Tambahan Penelitian
            ['kode' => 'SM-USS-T-PN-01', 'nama' => 'Penggunaan TIK Penelitian', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-02', 'nama' => 'Penugasan Dosen Penelitian', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-03', 'nama' => 'Pembiayaan Penelitian', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-04', 'nama' => 'Akses Sarana dan Prasarana Penelitian', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-05', 'nama' => 'Penilaian Penelitian (versi 53/2023)', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-06', 'nama' => 'Proses Pengawasan Penelitian', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-07', 'nama' => 'Proses Pelaksanaan Penelitian', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-08', 'nama' => 'Perencanaan Penelitian', 'kelompok' => 'Tambahan Penelitian'],
            ['kode' => 'SM-USS-T-PN-09', 'nama' => 'Mutu Luaran Penelitian / Relevansi Penelitian', 'kelompok' => 'Tambahan Penelitian'],

            // Tambahan PkM
            ['kode' => 'SM-USS-T-PKM-01', 'nama' => 'Akses Sarana PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-02', 'nama' => 'Prasarana PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-03', 'nama' => 'Pembiayaan PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-04', 'nama' => 'Penugasan PkM Dosen', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-05', 'nama' => 'Penggunaan TIK PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-06', 'nama' => 'Penilaian PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-07', 'nama' => 'Perencanaan PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-08', 'nama' => 'Pelaksanaan PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-09', 'nama' => 'Pengendalian PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-10', 'nama' => 'Pengawasan PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-11', 'nama' => 'Mutu PkM', 'kelompok' => 'Tambahan PkM'],
            ['kode' => 'SM-USS-T-PKM-12', 'nama' => 'Relevansi PkM', 'kelompok' => 'Tambahan PkM'],
        ];
        DB::table('standards')->insert($standards);

        // 4. Non-academic Categories
        $nonacademic = [
            ['kode' => 'NA-01', 'nama' => 'Organisasi dan Tata Kelola / Manajemen'],
            ['kode' => 'NA-02', 'nama' => 'Kemahasiswaan'],
            ['kode' => 'NA-03', 'nama' => 'Sumber Daya Manusia (SDM / Kepegawaian)'],
            ['kode' => 'NA-04', 'nama' => 'Sarana dan Prasarana'],
            ['kode' => 'NA-05', 'nama' => 'Kerjasama'],
            ['kode' => 'NA-06', 'nama' => 'Keuangan'],
            ['kode' => 'NA-07', 'nama' => 'Kesejahteraan'],
        ];
        DB::table('nonacademic_categories')->insert($nonacademic);

        // 5. Bulan Mutu Activities
        $activities = [
            ['index_kegiatan' => 1, 'nama' => 'Rapat Persiapan & Penyusunan Jadwal Bulan Mutu', 'tahap' => 'P1', 'pic' => 'Kepala LPMA & Tim LPMA'],
            ['index_kegiatan' => 2, 'nama' => 'Penerbitan Surat Tugas Tim Auditor', 'tahap' => 'P1', 'pic' => 'Rektor / Kepala LPMA'],
            ['index_kegiatan' => 3, 'nama' => 'Sosialisasi Standar Mutu & Kebijakan SPMI', 'tahap' => 'P2', 'pic' => 'LPMA'],
            ['index_kegiatan' => 4, 'nama' => 'Pengisian Evaluasi Diri & Bukti Pelaksanaan PPEPP', 'tahap' => 'P2', 'pic' => 'Fakultas / Prodi / Unit'],
            ['index_kegiatan' => 5, 'nama' => 'Pelaksanaan Audit Mutu Akademik Internal', 'tahap' => 'P3', 'pic' => 'Tim Auditor LPMA'],
            ['index_kegiatan' => 6, 'nama' => 'Pelaksanaan Audit Mutu Internal Unit Non-Akademik', 'tahap' => 'P3', 'pic' => 'Tim Auditor LPMA'],
            ['index_kegiatan' => 7, 'nama' => 'Penyusunan Laporan Hasil AMAI/EMI', 'tahap' => 'P3', 'pic' => 'Ketua Tim Auditor'],
            ['index_kegiatan' => 8, 'nama' => 'Penerbitan Permintaan Tindakan Korektif (PTK)', 'tahap' => 'P4', 'pic' => 'LPMA / Auditor'],
            ['index_kegiatan' => 9, 'nama' => 'Rapat Tinjauan Manajemen (RTM)', 'tahap' => 'P4', 'pic' => 'Rektor & Pimpinan'],
            ['index_kegiatan' => 10, 'nama' => 'Pemantauan Tindak Lanjut & Penyusunan LTL', 'tahap' => 'P4', 'pic' => 'Unit & LPMA'],
            ['index_kegiatan' => 11, 'nama' => 'Pelaporan Hasil ke Sistem SPMI Nasional', 'tahap' => 'P5', 'pic' => 'LPMA'],
            ['index_kegiatan' => 12, 'nama' => 'Evaluasi Pelaksanaan Bulan Mutu', 'tahap' => 'P5', 'pic' => 'Kepala LPMA & Pimpinan'],
        ];
        DB::table('bulan_mutu_activities')->insert($activities);

        // 6. Users
        DB::table('users')->insert([
            [
                'name' => 'Super Administrator',
                'email' => 'admin@uss.ac.id',
                'password' => bcrypt('password'),
                'role_id' => 1, // super_admin
            ],
            [
                'name' => 'Kepala LPMA',
                'email' => 'lpma@uss.ac.id',
                'password' => bcrypt('password'),
                'role_id' => 2, // lpma
            ],
            [
                'name' => 'Dekan Fakultas Ekonomi',
                'email' => 'dekan.fe@uss.ac.id',
                'password' => bcrypt('password'),
                'role_id' => 4, // auditee_upps
            ],
            [
                'name' => 'Ketua Prodi Manajemen',
                'email' => 'kaprodi.manajemen@uss.ac.id',
                'password' => bcrypt('password'),
                'role_id' => 5, // auditee_prodi
            ],
            [
                'name' => 'Ketua Prodi Ilmu Komputer',
                'email' => 'kaprodi.ilkom@uss.ac.id',
                'password' => bcrypt('password'),
                'role_id' => 5, // auditee_prodi
            ],
            [
                'name' => 'Kepala BAUK',
                'email' => 'bauk@uss.ac.id',
                'password' => bcrypt('password'),
                'role_id' => 6, // auditee_unit
            ],
            [
                'name' => 'Rektor USS',
                'email' => 'rektor@uss.ac.id',
                'password' => bcrypt('password'),
                'role_id' => 7, // pimpinan
            ],
        ]);
    }
}
