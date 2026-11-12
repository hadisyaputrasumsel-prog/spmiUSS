<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class PetunjukAmiSeeder extends Seeder
{
    /**
     * Default HTML guidance content for AMI execution.
     */
    public static function defaultContent(): string
    {
        return <<<'HTML'
<h3>DOKUMEN PETUNJUK PELAKSANAAN AUDIT MUTU INTERNAL (AMI)</h3>
<p>Sistem <strong>AMIRA</strong> memfasilitasi proses Audit Mutu Internal secara digital dengan mengacu pada siklus <strong>PPEPP</strong>. Berikut adalah langkah-langkah pelaksanaan AMI dalam aplikasi:</p>

<h4>1. TAHAP PENETAPAN &amp; PELAKSANAAN (P1 &amp; P2)</h4>
<ol>
    <li>Pimpinan / LPMA menetapkan Standar Mutu serta mengunggah Dokumen Induk Universitas (seperti Kebijakan Mutu &amp; Manual Mutu) di menu <strong>Bank Dokumen</strong>.</li>
    <li>LPMA membuka dan mengaktifkan periode <strong>Bulan Mutu</strong> pada tahun berjalan.</li>
    <li>Unit / Prodi (Auditee) melaksanakan aktivitas akademik dan non-akademik sesuai standar, serta dapat menyimpan dokumen internal unit (seperti SOP Prodi dan Kurikulum) di pustaka privat unit pada menu <strong>Bank Dokumen</strong>.</li>
</ol>

<h4>2. TAHAP EVALUASI / AMI (P3)</h4>
<ol>
    <li><strong>Pengisian LED (Laporan Evaluasi Diri):</strong> Auditee (UPPS/Prodi/Unit) mengakses menu <strong>Evaluasi Diri (LED)</strong> untuk melaporkan capaian indikator setiap tahapan PPEPP, melampirkan bukti khusus maupun memilih bukti dari Bank Dokumen, lalu menekan tombol <em>Ajukan Penilaian LED</em> agar data dikunci untuk Auditor.</li>
    <li><strong>Penugasan Auditor:</strong> LPMA menetapkan penugasan tim Auditor (Ketua dan Anggota) untuk memeriksa masing-masing unit pada menu <strong>Penugasan Auditor</strong>.</li>
    <li><strong>Desk Evaluation &amp; Verifikasi Evidence:</strong> Auditor menelaah isian LED dan bukti dokumen auditee secara daring sebelum turun ke lapangan.</li>
    <li><strong>Visitasi / Audit Lapangan:</strong> Auditor melakukan visitasi ke unit/prodi, mencatat skor capaian, Kesesuaian, Observasi (OB), atau Ketidaksesuaian (KTS Minor / KTS Mayor) pada menu <strong>Instrumen AMI</strong>.</li>
    <li><strong>Closing Meeting:</strong> Penyampaian dan penyepakatan hasil temuan audit antara Auditor dan Auditee.</li>
</ol>

<h4>3. TAHAP PENGENDALIAN (P4)</h4>
<ol>
    <li><strong>Pengisian RTL (Rencana Tindak Lanjut):</strong> Auditee menindaklanjuti temuan KTS/OB dengan mengisi akar penyebab, rencana tindakan perbaikan, PIC, dan batas waktu pada form RTL.</li>
    <li><strong>Verifikasi RTL:</strong> Auditor atau LPMA memantau dan memverifikasi status penyelesaian tindak lanjut hingga berstatus <em>Selesai</em>.</li>
</ol>

<h4>4. TAHAP PENINGKATAN (P5)</h4>
<ol>
    <li><strong>Pelaksanaan Rapat Tinjauan Manajemen (RTM):</strong> Pimpinan dan LPMA menggunakan laporan hasil AMI, Form 5 (RCA/RTL), dan Berita Acara RTM dari sistem sebagai dasar pengambilan keputusan peningkatan standar mutu pada siklus berikutnya.</li>
</ol>

<h4>Petunjuk Khusus Penggunaan Sistem</h4>
<ul>
    <li>Seluruh dokumen laporan PDF (LED, Laporan BAP/AMI, Checklist AMI, PKA, Form 5 RTL, dan Berita Acara RTM) dapat dicetak langsung pada masing-masing menu terkait.</li>
    <li>Auditee hanya dapat mengakses data unitnya sendiri serta dokumen global LPMA; dokumen internal antar-prodi terisolasi aman per unit.</li>
    <li>Harap perhatikan <strong>Batas Waktu</strong> jadwal Bulan Mutu pada halaman Ringkasan agar pengisian LED dan tindak lanjut tidak terlambat.</li>
</ul>
HTML;
    }

    public function run(): void
    {
        $text = self::defaultContent();

        $setting = Setting::first();
        if ($setting) {
            $setting->update(['petunjuk_ami' => $text]);
        } else {
            Setting::create([
                'nama_kampus' => 'Universitas Sumatera Selatan',
                'petunjuk_ami' => $text,
            ]);
        }
    }
}
