<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class PetunjukAmiSeeder extends Seeder
{
    public function run()
    {
        $text = "DOKUMEN PETUNJUK PELAKSANAAN AUDIT MUTU INTERNAL (AMI)

Sistem AmiraUSS memfasilitasi proses Audit Mutu Internal secara digital dengan mengacu pada siklus PPEPP. Berikut adalah langkah-langkah pelaksanaan AMI dalam aplikasi:

**1. TAHAP PENETAPAN & PELAKSANAAN (P1 & P2)**
a. Pimpinan/LPMA menetapkan Standar Mutu yang akan diaudit.
b. LPMA membuka periode Bulan Mutu Akademik pada tahun berjalan.
c. Unit (Auditee) melaksanakan aktivitas akademik dan non-akademik sesuai dengan standar.

**2. TAHAP EVALUASI / AMI (P3)**
a. Pengisian LED (Laporan Evaluasi Diri):
   Auditee (UPPS/Prodi/Unit) mengakses menu LED untuk melaporkan capaian/indikator secara mandiri, lengkap dengan tautan bukti (evidence).
b. Penugasan Auditor:
   LPMA memberikan penugasan kepada Auditor (Ketua dan Anggota) untuk memeriksa Unit.
c. Desk Evaluation & Verifikasi Evidence:
   Auditor menelaah LED yang disubmit auditee secara online sebelum turun ke lapangan.
d. Visitasi / Audit Lapangan:
   Auditor melakukan kunjungan ke unit/prodi, melakukan wawancara, mencatat KTS (Ketidaksesuaian), OB (Observasi), atau kelayakan, lalu menginputkannya sebagai Temuan AMI.
e. Closing Meeting:
   Penyampaian dan penyepakatan hasil temuan antara Auditor dan Auditee.

**3. TAHAP PENGENDALIAN (P4)**
a. Pengisian RTL (Rencana Tindak Lanjut):
   Auditee menindaklanjuti Temuan AMI dengan mengisi Rencana Tindak Lanjut di Dashboard/Form RTL, menyertakan deskripsi perbaikan dan target waktu.
b. Verifikasi RTL:
   Auditor atau LPMA memverifikasi apakah perbaikan RTL telah berstatus 'Selesai' atau 'Belum'.

**4. TAHAP PENINGKATAN (P5)**
a. Pelaksanaan Rapat Tinjauan Manajemen (RTM):
   Pimpinan dan LPMA menggunakan cetak Laporan AMI dan Berita Acara RTM dari sistem sebagai dasar pengambilan keputusan untuk peningkatan standar mutu siklus berikutnya.

**Petunjuk Khusus Penggunaan Sistem:**
- Seluruh dokumen laporan (PDF) dapat diunduh pada masing-masing menu (Cetak LED, Cetak AMI, Cetak RTL, Cetak RTM).
- Auditee hanya dapat mengakses unitnya sendiri setelah login.
- Harap perhatikan **Batas Waktu** tahap berjalan pada Dashboard untuk menghindari keterlambatan pengisian.";

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
