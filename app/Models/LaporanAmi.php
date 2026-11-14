<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAmi extends Model
{
    use HasFactory;

    protected $table = 'laporan_ami';

    protected $fillable = [
        'tahun',
        'nomor_dokumen',
        'tanggal_terbit',
        'versi',
        'kata_pengantar',
        'dasar_hukum',
        'tujuan',
        'ruang_lingkup',
        'metode',
        'jadwal_ami',
        'kesimpulan',
        'rekomendasi_pimpinan',
        'penandatangan_lpma_nama',
        'penandatangan_lpma_nidn',
        'penandatangan_lpma_jabatan',
        'penandatangan_rektor_nama',
        'penandatangan_rektor_nidn',
        'penandatangan_rektor_jabatan',
        'lampiran_options',
        'selected_unit_ids',
        'file_path',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'jadwal_ami' => 'array',
        'lampiran_options' => 'array',
        'selected_unit_ids' => 'array',
    ];

    /**
     * Format teks naratif (HTML TinyMCE atau teks biasa) agar aman dirender di PDF & Web.
     */
    public static function renderRichText(?string $content): string
    {
        if (empty($content)) {
            return '-';
        }
        if (preg_match('/<[a-z][\s\S]*>/i', $content)) {
            return strip_tags($content, '<p><br><b><strong><i><em><u><ul><ol><li><table><thead><tbody><tr><td><th><span><div>');
        }

        return nl2br(e($content));
    }

    /**
     * Teks baku default apabila belum pernah diisi oleh LPMA.
     */
    public static function getDefaultData(int $tahun): array
    {
        return [
            'tahun' => $tahun,
            'nomor_dokumen' => sprintf('01/LPMA-USS/AMI/XII/%d', $tahun),
            'tanggal_terbit' => sprintf('%d-11-20', $tahun),
            'versi' => '1.0',
            'kata_pengantar' => "Puji syukur kami panjatkan ke hadirat Allah SWT, Tuhan Yang Maha Esa, atas limpahan rahmat dan karunia-Nya sehingga Buku Laporan Hasil Audit Mutu Internal (AMI) Universitas Sumatera Selatan (USS) Tahun Akademik {$tahun} dapat diselesaikan dengan baik.\n\nAudit Mutu Internal (AMI) merupakan instrumen strategis dalam siklus Sistem Penjaminan Mutu Internal (SPMI) berbasis PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan) untuk memelihara serta meningkatkan mutu pendidikan tinggi secara berkelanjutan (continuous quality improvement). Laporan ini merangkum potret komprehensif capaian standar SPMI, temuan audit (KTS Mayor, KTS Minor, Observasi, Peluang Peningkatan, dan Temuan Positif), serta efektivitas Rencana Tindak Lanjut (RTL) pada seluruh unit kerja di lingkungan Universitas Sumatera Selatan.\n\nKami menyampaikan apresiasi dan penghargaan setinggi-tingginya kepada Tim Auditor Internal atas dedikasi dan profesionalismenya, para Auditi (Pimpinan Fakultas, Program Studi, dan Unit Kerja Non-Akademik) atas keterbukaan dan kerjasamanya, serta Pimpinan Universitas atas arahan dan dukungannya. Semoga laporan ini menjadi landasan kokoh bagi Rapat Tinjauan Manajemen (RTM) dalam merumuskan kebijakan perbaikan mutu dan persiapan akreditasi institusi maupun program studi.",
            'dasar_hukum' => "1. Undang-Undang Republik Indonesia Nomor 12 Tahun 2012 tentang Pendidikan Tinggi.\n2. Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi Nomor 53 Tahun 2023 tentang Penjaminan Mutu Pendidikan Tinggi.\n3. Peraturan Badan Akreditasi Nasional Perguruan Tinggi (BAN-PT) Nomor 5 Tahun 2019 tentang Instrumen Akreditasi Program Studi (IAPS 4.0).\n4. Statuta Universitas Sumatera Selatan.\n5. Kebijakan Sistem Penjaminan Mutu Internal (SPMI) Universitas Sumatera Selatan.\n6. Manual SPMI Universitas Sumatera Selatan (Siklus PPEPP).\n7. Standar SPMI Universitas Sumatera Selatan (Standar Pendidikan, Penelitian, Pengabdian kepada Masyarakat, dan Standar Tambahan).\n8. Surat Keputusan Rektor Universitas Sumatera Selatan tentang Pengangkatan Tim Auditor Mutu Internal Tahun {$tahun}.",
            'tujuan' => "1. Memverifikasi kesesuaian antara pelaksanaan kegiatan akademik dan non-akademik di unit kerja dengan Standar SPMI Universitas Sumatera Selatan yang telah ditetapkan.\n2. Mengidentifikasi area kekuatan (Temuan Positif) dan potensi risiko mutu (Ketidaksesuaian Mayor, Ketidaksesuaian Minor, dan Observasi).\n3. Menilai efektivitas dan kepatuhan pelaksanaan siklus Evaluasi Diri (LED) oleh Program Studi dan Unit Kerja.\n4. Merumuskan Rencana Tindak Lanjut (RTL) yang terukur, realistis, dan berorientasi pencegahan.\n5. Menyediakan data dan analisis obyektif sebagai bahan masukan utama bagi Pimpinan Universitas dalam pelaksanaan Rapat Tinjauan Manajemen (RTM) dan perencanaan program tahun berikutnya.",
            'ruang_lingkup' => "Ruang lingkup Audit Mutu Internal Tahun {$tahun} mencakup seluruh siklus tridharma perguruan tinggi dan tata kelola pendukung, meliputi:\n1. Standar Pendidikan (Kurikulum, Dosen, Proses Pembelajaran, Penilaian, Sarana Prasarana, dan Suasana Akademik).\n2. Standar Penelitian dan Pengabdian kepada Masyarakat (PkM) serta integrasinya ke dalam proses pembelajaran.\n3. Standar Tata Pamong, Kepemimpinan, dan Sistem Penjaminan Mutu.\n4. Standar Kemahasiswaan dan Layanan Fasilitas Pendukung.\n5. Unit sasaran audit mencakup seluruh Program Studi, Fakultas/UPPS, serta Unit/Biro Non-Akademik di lingkungan Universitas Sumatera Selatan.",
            'metode' => "Pelaksanaan Audit Mutu Internal Tahun {$tahun} dilaksanakan melalui tiga tahapan utama yang terintegrasi:\n1. Desk Evaluation (Audit Dokumen): Auditor menelaah Laporan Evaluasi Diri (LED) dan dokumen bukti sahih yang diunggah auditi ke dalam sistem AMIRA untuk menilai ketercapaian 5 tahapan siklus PPEPP.\n2. Visitasi / Audit Lapangan: Auditor melakukan konfirmasi faktual, wawancara mendalam dengan pimpinan auditi, dosen, tendik, dan perwakilan mahasiswa, serta observasi fisik atas sarana dan prasarana pendukung.\n3. Perumusan Temuan dan Verifikasi RTL: Temuan audit diklasifikasikan ke dalam kategori Sesuai, Observasi (OB), KTS Minor, KTS Mayor, Peluang Peningkatan (OFI), atau Temuan Positif (TP). Auditi dan auditor menyepakati akar masalah (RCA), tindakan koreksi, jadwal perbaikan, serta rencana pencegahan yang dipantau hingga status Selesai.",
            'jadwal_ami' => [
                ['kegiatan' => 'Sosialisasi & Workshop Penyusunan LED', 'tanggal' => sprintf('%d-08-01 s.d. %d-08-10', $tahun, $tahun), 'penanggung_jawab' => 'LPMA USS'],
                ['kegiatan' => 'Pengisian & Finalisasi LED oleh Auditi di Sistem AMIRA', 'tanggal' => sprintf('%d-08-11 s.d. %d-09-10', $tahun, $tahun), 'penanggung_jawab' => 'Pimpinan Unit & Tim Penyusun LED'],
                ['kegiatan' => 'Desk Evaluation / Audit Dokumen oleh Tim Auditor', 'tanggal' => sprintf('%d-09-15 s.d. %d-09-30', $tahun, $tahun), 'penanggung_jawab' => 'Tim Auditor Mutu Internal'],
                ['kegiatan' => 'Visitasi & Audit Lapangan (Wawancara & Klarifikasi)', 'tanggal' => sprintf('%d-10-05 s.d. %d-10-25', $tahun, $tahun), 'penanggung_jawab' => 'Auditor Ketua & Anggota'],
                ['kegiatan' => 'Penyusunan Form 4 (KKA) dan Form 5 (RTL)', 'tanggal' => sprintf('%d-10-26 s.d. %d-11-05', $tahun, $tahun), 'penanggung_jawab' => 'Auditor & Auditi'],
                ['kegiatan' => 'Verifikasi Rencana Tindak Lanjut (RTL) & Finalisasi', 'tanggal' => sprintf('%d-11-06 s.d. %d-11-15', $tahun, $tahun), 'penanggung_jawab' => 'Auditor & LPMA'],
                ['kegiatan' => 'Penerbitan Buku Laporan AMI & Rapat Tinjauan Manajemen (RTM)', 'tanggal' => sprintf('%d-11-20 s.d. %d-12-05', $tahun, $tahun), 'penanggung_jawab' => 'LPMA & Rektorat USS'],
            ],
            'kesimpulan' => "Berdasarkan keseluruhan hasil audit, implementasi Sistem Penjaminan Mutu Internal (SPMI) di lingkungan Universitas Sumatera Selatan telah berjalan secara sistematis dan terstruktur mengikuti siklus PPEPP. Sebagian besar standar telah dipenuhi dengan baik oleh unit kerja akademik maupun non-akademik. Temuan ketidaksesuaian yang teridentifikasi umumnya bersumber pada keterbatasan dokumentasi berkala dan kebutuhan peningkatan integrasi penelitian/PkM dosen ke dalam proses pembelajaran.\n\nKomitmen auditi dalam menyelesaikan Rencana Tindak Lanjut (RTL) sangat tinggi, terbukti dengan tercapainya persentase penyelesaian RTL yang signifikan sesuai jadwal yang disepakati.",
            'rekomendasi_pimpinan' => "1. Pimpinan Universitas dan Fakultas perlu memprioritaskan alokasi anggaran penjaminan mutu, khususnya untuk fasilitasi integrasi riset dosen ke dalam bahan ajar dan modul perkuliahan.\n2. Unit Pengelola Program Studi (UPPS) wajib menyelenggarakan monitoring berkala terhadap pengarsipan RPS dan bukti umpan balik penilaian mahasiswa setiap awal dan akhir semester.\n3. LPMA secara terprogram menyelenggarakan pelatihan berkelanjutan bagi Gugus Penjaminan Mutu (GPM) Fakultas dan Unit Penjaminan Mutu (UPM) Program Studi.\n4. Hasil temuan AMI ini direkomendasikan menjadi agenda pembahasan utama dalam Rapat Tinjauan Manajemen (RTM) Universitas guna menetapkan target peningkatan standar mutu SPMI pada siklus berikutnya.",
            'penandatangan_lpma_nama' => 'Dr. H. Ahmad Fauzi, M.Pd',
            'penandatangan_lpma_nidn' => '0214057801',
            'penandatangan_lpma_jabatan' => 'Kepala Lembaga Penjaminan Mutu Akademik (LPMA)',
            'penandatangan_rektor_nama' => 'Prof. Dr. Ir. H. M. Syahril, M.S.',
            'penandatangan_rektor_nidn' => '0015086002',
            'penandatangan_rektor_jabatan' => 'Rektor Universitas Sumatera Selatan',
            'lampiran_options' => ['form4', 'form5', 'daftar_hadir', 'standar'],
            'selected_unit_ids' => ['all'],
        ];
    }
}
