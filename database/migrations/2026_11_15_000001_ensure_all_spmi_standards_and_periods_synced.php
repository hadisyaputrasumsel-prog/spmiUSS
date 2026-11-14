<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Standard;
use App\Models\SpmiPeriod;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $period = SpmiPeriod::first();
        if (!$period) {
            $period = SpmiPeriod::create([
                'nama' => 'INSTRUMEN STANDAR USS',
                'is_active' => true,
            ]);
        }
        $periodName = $period->nama;

        $standards = array (
  0 => 
  array (
    'kode' => 'SM-USS-03-01-01',
    'nama' => 'Kompetensi Lulusan',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kualifikasi kemampuan lulusan yang mencakup sikap, pengetahuan, dan keterampilan (CPL). Kriteria mencakup masa studi, persentase kelulusan tepat waktu, waktu tunggu lulusan, dan wirausaha lulusan.',
    'target' => '- Masa studi lulusan rata-rata maksimal 5 tahun.
- Persentase kelulusan tepat waktu minimal 80%.
- Waktu tunggu lulusan mendapatkan pekerjaan pertama rata-rata maksimal 4 bulan.
- (Tambahan unit) Wirausaha 30%; TOEFL min. 400.',
    'acuan' => 'Buku Manual Standar Pendidikan - Kompetensi Lulusan (SM-USS)',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi',
    'indikator_p1' => 'Apakah terdapat bukti penetapan kompetensi umum lulusan oleh Universitas dan profil lulusan oleh Program Studi?',
    'indikator_p2' => 'Apakah proses pendidikan telah menjamin tercapainya CPL (masa studi 5 tahun, kelulusan 80%, waktu tunggu 4 bulan, wirausaha 30%)?',
    'indikator_p3' => 'Apakah dilakukan evaluasi kesesuaian kualifikasi lulusan (Tracer Study) secara berkala?',
    'indikator_p4' => 'Apakah dilakukan rapat tinjauan manajemen (RTM) tindak lanjut dan perbaikan jika ada target lulusan yang belum tercapai?',
    'indikator_p5' => 'Apakah dilakukan revisi/peningkatan target CPL dengan melibatkan stakeholders (dunia usaha/industri) minimal setiap 4 tahun?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Kompetensi Lulusan\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Kompetensi Lulusan\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Kompetensi Lulusan\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Kompetensi Lulusan\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  1 => 
  array (
    'kode' => 'SM-USS-03-01-02',
    'nama' => 'Isi Pembelajaran',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Isi Pembelajaran secara berkelanjutan.',
    'target' => 'Evaluasi kedalaman/keluasan materi min. 1x/tahun ajaran; peninjauan kurikulum min. 4 tahun sekali',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Isi Pembelajaran?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Isi Pembelajaran?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Isi Pembelajaran\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Isi Pembelajaran\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Isi Pembelajaran\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Isi Pembelajaran\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  2 => 
  array (
    'kode' => 'SM-USS-03-01-03',
    'nama' => 'Proses Pembelajaran',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Proses Pembelajaran secara berkelanjutan.',
    'target' => 'Semua MK punya RPS selaras CPL; kontrak RPS dibagikan awal kuliah; metode PBL/PjBL/studi kasus dsb.',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Proses Pembelajaran?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Proses Pembelajaran?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Proses Pembelajaran\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Proses Pembelajaran\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Proses Pembelajaran\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Proses Pembelajaran\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  3 => 
  array (
    'kode' => 'SM-USS-03-01-04',
    'nama' => 'Penilaian Pembelajaran',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penilaian Pembelajaran secara berkelanjutan.',
    'target' => 'Nilai diinput paling lambat 14 hari setelah ujian; prinsip edukatif-otentik-objektif-akuntabel-transparan',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi, Dosen',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penilaian Pembelajaran?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penilaian Pembelajaran?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penilaian Pembelajaran\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penilaian Pembelajaran\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penilaian Pembelajaran\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Penilaian Pembelajaran\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  4 => 
  array (
    'kode' => 'SM-USS-03-01-05',
    'nama' => 'Dosen dan Tenaga Kependidikan',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Dosen dan Tenaga Kependidikan secara berkelanjutan.',
    'target' => 'Dosen min. S2, jabfung min. Asisten Ahli; rasio dosen:mhs eksakta 1:20 (tol. 1:30), sosial/ekonomi 1:30 (tol. 1:45)',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Dosen dan Tenaga Kependidikan?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Dosen dan Tenaga Kependidikan?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Dosen dan Tenaga Kependidikan\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Dosen dan Tenaga Kependidikan\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Dosen dan Tenaga Kependidikan\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Dosen dan Tenaga Kependidikan\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  5 => 
  array (
    'kode' => 'SM-USS-03-01-06',
    'nama' => 'Sarana dan Prasarana Pembelajaran',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Sarana dan Prasarana Pembelajaran secara berkelanjutan.',
    'target' => '20 pernyataan standar (perabotan, lab, perpustakaan, TIK, ruang kelas dll.)',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi, Tim Pengelola Aset',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Sarana dan Prasarana Pembelajaran?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Sarana dan Prasarana Pembelajaran?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Sarana dan Prasarana Pembelajaran\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Sarana dan Prasarana Pembelajaran\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Sarana dan Prasarana Pembelajaran\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Sarana dan Prasarana Pembelajaran\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  6 => 
  array (
    'kode' => 'SM-USS-03-01-07',
    'nama' => 'Pengelolaan Pembelajaran',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pengelolaan Pembelajaran secara berkelanjutan.',
    'target' => 'Kebijakan pengelolaan seluruh prodi paling lambat 2021; monev tiap semester; lapor PDDikti ganjil ≤15 April, genap ≤15 Oktober',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor, WR I, Dekan, Ketua Prodi, BAUK, UPT, LPMA',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pengelolaan Pembelajaran?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pengelolaan Pembelajaran?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pengelolaan Pembelajaran\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pengelolaan Pembelajaran\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pengelolaan Pembelajaran\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pengelolaan Pembelajaran\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  7 => 
  array (
    'kode' => 'SM-USS-03-01-08',
    'nama' => 'Pembiayaan Pembelajaran',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pembiayaan Pembelajaran secara berkelanjutan.',
    'target' => 'Sistem pencatatan biaya per prodi; evaluasi ketercapaian akhir tahun anggaran; komponen investasi-operasional-personal',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor, WR I & WR II, Biro Akademik & Keuangan, LPMA',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pembiayaan Pembelajaran?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pembiayaan Pembelajaran?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pembiayaan Pembelajaran\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pembiayaan Pembelajaran\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pembiayaan Pembelajaran\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pembiayaan Pembelajaran\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  8 => 
  array (
    'kode' => 'SM-USS-03-01-09',
    'nama' => 'Proses Penerimaan Mahasiswa Baru',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Proses Penerimaan Mahasiswa Baru secara berkelanjutan.',
    'target' => 'Jadwal PMB ditetapkan 1 tahun sebelumnya; info disebar ≤6 bulan sebelum TA baru; kelulusan diumumkan ≤10 hari; target bertahap 80-100% (2019→2021)',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'WR bidang Akademik, Dekan, Ketua Prodi, BAUK, LPMA',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Proses Penerimaan Mahasiswa Baru?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Proses Penerimaan Mahasiswa Baru?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Proses Penerimaan Mahasiswa Baru\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Proses Penerimaan Mahasiswa Baru\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Proses Penerimaan Mahasiswa Baru\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Proses Penerimaan Mahasiswa Baru\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  9 => 
  array (
    'kode' => 'SM-USS-03-01-10',
    'nama' => 'Pembelajaran Entrepreneur',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pembelajaran Entrepreneur secara berkelanjutan.',
    'target' => 'Entrepreneur Center per prodi; sarana/media/lab entrepreneur',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pembelajaran Entrepreneur?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pembelajaran Entrepreneur?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pembelajaran Entrepreneur\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pembelajaran Entrepreneur\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pembelajaran Entrepreneur\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pembelajaran Entrepreneur\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  10 => 
  array (
    'kode' => 'SM-USS-03-01-11',
    'nama' => 'Kemampuan Bahasa Inggris',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Kemampuan Bahasa Inggris secara berkelanjutan.',
    'target' => 'English Learning Center per prodi; sarana/media pendidikan bahasa Inggris',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Rektor & WR I, Dekan & WD, Ketua Prodi',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Kemampuan Bahasa Inggris?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Kemampuan Bahasa Inggris?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Kemampuan Bahasa Inggris\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Kemampuan Bahasa Inggris\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Kemampuan Bahasa Inggris\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Kemampuan Bahasa Inggris\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  11 => 
  array (
    'kode' => 'SM-USS-03-01-12',
    'nama' => 'Kerjasama',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Kerjasama secara berkelanjutan.',
    'target' => 'MoU/MoA/PKS; prinsip kesetaraan-saling menghormati-saling menguntungkan; bukti laporan/jurnal/prosiding',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'Pimpinan universitas, seluruh fakultas & unit',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Kerjasama?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Kerjasama?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Kerjasama\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Kerjasama\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Kerjasama\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Kerjasama\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  12 => 
  array (
    'kode' => 'SM-USS-03-01-13',
    'nama' => 'Merdeka Belajar Kampus Merdeka (MBKM)',
    'kelompok' => 'Standar Pendidikan',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Merdeka Belajar Kampus Merdeka (MBKM) secara berkelanjutan.',
    'target' => '2 semester (40 sks) di luar PT + 1 semester (20 sks) di prodi lain; target Standar MBKM 60%→80% (2020→2021)',
    'acuan' => 'Buku Manual SPMI - Standar Pendidikan',
    'penanggung_jawab' => 'WR I, WR II, BAUK, Humas, Dekan, UPMI, LPMA',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Merdeka Belajar Kampus Merdeka (MBKM)?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Merdeka Belajar Kampus Merdeka (MBKM)?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Merdeka Belajar Kampus Merdeka (MBKM)\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Merdeka Belajar Kampus Merdeka (MBKM)\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Merdeka Belajar Kampus Merdeka (MBKM)\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Merdeka Belajar Kampus Merdeka (MBKM)\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  13 => 
  array (
    'kode' => 'SM-USS-03-02-01',
    'nama' => 'Hasil Penelitian',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Hasil Penelitian secara berkelanjutan.',
    'target' => 'Dosen min. 1 penelitian/tahun; mhs S1 min. 1/tahun; kesesuaian RIP >70%→>80% (2020→2021); publikasi >50%→>80%',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Hasil Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Hasil Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Hasil Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Hasil Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Hasil Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Hasil Penelitian\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  14 => 
  array (
    'kode' => 'SM-USS-03-02-02',
    'nama' => 'Isi Penelitian',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Isi Penelitian secara berkelanjutan.',
    'target' => 'Min. 1 penelitian/tahun (dasar & terapan); kesesuaian RIP >50%→>80% (2019→2020)',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Isi Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Isi Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Isi Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Isi Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Isi Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Isi Penelitian\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  15 => 
  array (
    'kode' => 'SM-USS-03-02-03',
    'nama' => 'Proses Penelitian',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Proses Penelitian secara berkelanjutan.',
    'target' => 'Bukti pelaksanaan & review proses >50%→>80%; dokumen perencanaan-pelaksanaan-pelaporan >70%→>90%',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Proses Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Proses Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Proses Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Proses Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Proses Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Proses Penelitian\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  16 => 
  array (
    'kode' => 'SM-USS-03-02-04',
    'nama' => 'Penilaian Penelitian',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penilaian Penelitian secara berkelanjutan.',
    'target' => '100% LPPM pakai instrumen penilaian terintegrasi; nilai proposal/hasil min. 75; publikasi min. terindeks SINTA-5; tugas akhir setara 6 sks',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'Rektor, WR I, LPPM, Dekan/Direktur, Ketua Prodi',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penilaian Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penilaian Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penilaian Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penilaian Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penilaian Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Penilaian Penelitian\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  17 => 
  array (
    'kode' => 'SM-USS-03-02-05',
    'nama' => 'Peneliti',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Peneliti secara berkelanjutan.',
    'target' => 'Kesesuaian dgn RIP >80% (2020); verifikasi jadwal >80%; rekomendasi publikasi >88,5%; pengumpulan laporan >90%',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Peneliti?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Peneliti?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Peneliti\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Peneliti\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Peneliti\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Peneliti\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  18 => 
  array (
    'kode' => 'SM-USS-03-02-06',
    'nama' => 'Sarana dan Prasarana Penelitian',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Sarana dan Prasarana Penelitian secara berkelanjutan.',
    'target' => 'Ketersediaan sarana >70%→>95% (2019→2020); fasilitas keselamatan & kesehatan kerja',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Sarana dan Prasarana Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Sarana dan Prasarana Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Sarana dan Prasarana Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Sarana dan Prasarana Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Sarana dan Prasarana Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Sarana dan Prasarana Penelitian\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  19 => 
  array (
    'kode' => 'SM-USS-03-02-07',
    'nama' => 'Pengelolaan Penelitian',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pengelolaan Penelitian secara berkelanjutan.',
    'target' => 'RIP/Renstra/panduan penelitian oleh LPPM; SOP >70%→>90%; laporan tahunan 1x/tahun',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pengelolaan Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pengelolaan Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pengelolaan Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pengelolaan Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pengelolaan Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Pengelolaan Penelitian\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  20 => 
  array (
    'kode' => 'SM-USS-03-02-08',
    'nama' => 'Pendanaan dan Pembiayaan Penelitian',
    'kelompok' => 'Standar Penelitian',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pendanaan dan Pembiayaan Penelitian secara berkelanjutan.',
    'target' => 'Alokasi dana penelitian internal minimal 7%; target >60%→>80% (2019→2020); tersedia dana pendamping (matching fund)',
    'acuan' => 'Buku Manual SPMI - Standar Penelitian',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pendanaan dan Pembiayaan Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pendanaan dan Pembiayaan Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pendanaan dan Pembiayaan Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pendanaan dan Pembiayaan Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pendanaan dan Pembiayaan Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Pendanaan dan Pembiayaan Penelitian\' dilampaui. Pelaksanaan penelitian sangat terstruktur, melibatkan mahasiswa secara aktif, dan hasilnya dipublikasikan di jurnal bereputasi tinggi (misal Q1/Q2 atau Sinta 1/2) serta diimplementasikan di masyarakat/industri. PPEPP sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  21 => 
  array (
    'kode' => 'SM-USS-03-03-01',
    'nama' => 'Hasil PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Hasil PkM secara berkelanjutan.',
    'target' => 'Dosen & mhs S1 min. 1 PkM/tahun; kesesuaian RIP >70%→>80% (2019→2021); publikasi >50%→>80%',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Hasil PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Hasil PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Hasil PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Hasil PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Hasil PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Hasil PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  22 => 
  array (
    'kode' => 'SM-USS-03-03-02',
    'nama' => 'Isi PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Isi PkM secara berkelanjutan.',
    'target' => 'Min. 2x/tahun pelaksanaan terkait kedalaman/keluasan materi; kesesuaian RIP >50%→>80% (2019→2020)',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Isi PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Isi PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Isi PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Isi PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Isi PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Isi PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  23 => 
  array (
    'kode' => 'SM-USS-03-03-03',
    'nama' => 'Proses PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Proses PkM secara berkelanjutan.',
    'target' => 'Bukti pelaksanaan & review >50%→>80%; dokumen perencanaan-pelaksanaan-pelaporan >70%→>90%',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Proses PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Proses PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Proses PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Proses PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Proses PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Proses PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  24 => 
  array (
    'kode' => 'SM-USS-03-03-04',
    'nama' => 'Penilaian PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penilaian PkM secara berkelanjutan.',
    'target' => 'Kesesuaian rencana/jadwal >50%→>80% (2019→2020-21); rekomendasi perbaikan >70%→>88,5%; pelaporan >80%→>90%',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'LPPM (pelaksana), WR I (pengawasan)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penilaian PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penilaian PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penilaian PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penilaian PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penilaian PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Penilaian PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  25 => 
  array (
    'kode' => 'SM-USS-03-03-05',
    'nama' => 'Pelaksana PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pelaksana PkM secara berkelanjutan.',
    'target' => 'Ketua peneliti min. S2; capaian dicantumkan >50%→>80%; min. 2 dosen penerima hibah, target >80%→>90%',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'LPPM (pelaksana), WR I (evaluasi & peningkatan)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pelaksana PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pelaksana PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pelaksana PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pelaksana PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pelaksana PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Pelaksana PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  26 => 
  array (
    'kode' => 'SM-USS-03-03-06',
    'nama' => 'Sarana dan Prasarana PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Sarana dan Prasarana PkM secara berkelanjutan.',
    'target' => 'Ketersediaan sarana >70%→>95% (2019→2020); sarana keselamatan & kesehatan kerja',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'WR I (pemilik), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Sarana dan Prasarana PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Sarana dan Prasarana PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Sarana dan Prasarana PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Sarana dan Prasarana PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Sarana dan Prasarana PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Sarana dan Prasarana PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  27 => 
  array (
    'kode' => 'SM-USS-03-03-07',
    'nama' => 'Pengelolaan PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pengelolaan PkM secara berkelanjutan.',
    'target' => 'RIP/Renstra/panduan PkM; SOP >70%→>90%; laporan tahunan 1x/tahun; target implementasi 65%→80% (2020→2021)',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'LPPM (pelaksana), WR I (pengawasan), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pengelolaan PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pengelolaan PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pengelolaan PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pengelolaan PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pengelolaan PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Pengelolaan PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  28 => 
  array (
    'kode' => 'SM-USS-03-03-08',
    'nama' => 'Pendanaan dan Pembiayaan PkM',
    'kelompok' => 'Standar PkM',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pendanaan dan Pembiayaan PkM secara berkelanjutan.',
    'target' => 'Dana eksternal minimal 7% dari total pemasukan, target >60%→>80% (2020→2021); dana internal pendamping >50%→>80%',
    'acuan' => 'Buku Manual SPMI - Standar PkM',
    'penanggung_jawab' => 'WR I (pemilik/evaluasi), LPPM (pelaksana), LPMA (pengendalian)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pendanaan dan Pembiayaan PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pendanaan dan Pembiayaan PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pendanaan dan Pembiayaan PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pendanaan dan Pembiayaan PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pendanaan dan Pembiayaan PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Target \'Pendanaan dan Pembiayaan PkM\' dilampaui. Program PkM memberikan dampak hilirisasi yang nyata, inovatif, dan berkelanjutan bagi masyarakat mitra, serta dipublikasikan pada jurnal bereputasi. PPEPP berjalan sangat konsisten.',
    ),
    'template_dokumen' => NULL,
  ),
  29 => 
  array (
    'kode' => 'SM-USS-T-PN-01',
    'nama' => 'Penggunaan TIK Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penggunaan TIK Penelitian secara berkelanjutan.',
    'target' => 'Kepala STK pastikan dosen gunakan TIK tiap tahun anggaran; STK & WR II jamin sarana/pembiayaan TIK; Ketua Prodi jamin akses dosen; Tim Pengawasan TIK cek kendala min. 3 bulan sekali',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'Kepala STK, WR II, Ketua Prodi, Tim Pengawasan TIK',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penggunaan TIK Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penggunaan TIK Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penggunaan TIK Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penggunaan TIK Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penggunaan TIK Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Penggunaan TIK Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  30 => 
  array (
    'kode' => 'SM-USS-T-PN-02',
    'nama' => 'Penugasan Dosen Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penugasan Dosen Penelitian secara berkelanjutan.',
    'target' => 'Dosen ajukan proposal sesuai panduan LPPM; LPPM lapor jumlah judul; Rektor terbitkan SK penugasan tiap periode',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'Dosen, LPPM, Rektor',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penugasan Dosen Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penugasan Dosen Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penugasan Dosen Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penugasan Dosen Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penugasan Dosen Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Penugasan Dosen Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  31 => 
  array (
    'kode' => 'SM-USS-T-PN-03',
    'nama' => 'Pembiayaan Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pembiayaan Penelitian secara berkelanjutan.',
    'target' => 'USS wajib danai minimal 1 penelitian/tahun via dana internal (APBU/hibah eksternal/kerja sama pihak ketiga); besaran berdasarkan skema & kompleksitas',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'WR I & WR II, LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pembiayaan Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pembiayaan Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pembiayaan Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pembiayaan Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pembiayaan Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pembiayaan Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  32 => 
  array (
    'kode' => 'SM-USS-T-PN-04',
    'nama' => 'Akses Sarana dan Prasarana Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Akses Sarana dan Prasarana Penelitian secara berkelanjutan.',
    'target' => 'Prasarana sesuai roadmap & isu strategis (lab riset tematik, fasilitas lapangan, software analisis SPSS/NVivo/MATLAB); alokasi anggaran jelas',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'WR I, LPPM, unit Sarpras',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Akses Sarana dan Prasarana Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Akses Sarana dan Prasarana Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Akses Sarana dan Prasarana Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Akses Sarana dan Prasarana Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Akses Sarana dan Prasarana Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Akses Sarana dan Prasarana Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  33 => 
  array (
    'kode' => 'SM-USS-T-PN-05',
    'nama' => 'Penilaian Penelitian (versi 53/2023)',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penilaian Penelitian (versi 53/2023) secara berkelanjutan.',
    'target' => 'Penilaian edukatif-objektif-akuntabel-transparan, menjunjung otonomi keilmuan',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'LPPM, reviewer/penilai',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penilaian Penelitian (versi 53/2023)?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penilaian Penelitian (versi 53/2023)?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penilaian Penelitian (versi 53/2023)\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penilaian Penelitian (versi 53/2023)\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penilaian Penelitian (versi 53/2023)\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Penilaian Penelitian (versi 53/2023)\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  34 => 
  array (
    'kode' => 'SM-USS-T-PN-06',
    'nama' => 'Proses Pengawasan Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Proses Pengawasan Penelitian secara berkelanjutan.',
    'target' => 'Pengawasan berkala kualitas & anggaran; LPPM pastikan dana sesuai rencana; laporan dipertanggungjawabkan ke pimpinan',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'LPPM, WR I',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Proses Pengawasan Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Proses Pengawasan Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Proses Pengawasan Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Proses Pengawasan Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Proses Pengawasan Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Proses Pengawasan Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  35 => 
  array (
    'kode' => 'SM-USS-T-PN-07',
    'nama' => 'Proses Pelaksanaan Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Proses Pelaksanaan Penelitian secara berkelanjutan.',
    'target' => 'Tahapan persiapan–lapangan/lab–analisis–dokumentasi; minimalkan bias & penuhi regulasi nasional/internasional',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'Peneliti, LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Proses Pelaksanaan Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Proses Pelaksanaan Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Proses Pelaksanaan Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Proses Pelaksanaan Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Proses Pelaksanaan Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Proses Pelaksanaan Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  36 => 
  array (
    'kode' => 'SM-USS-T-PN-08',
    'nama' => 'Perencanaan Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Perencanaan Penelitian secara berkelanjutan.',
    'target' => 'Identifikasi isu strategis, rumusan masalah, metodologi, luaran terukur, selaras RIP/Renstra/prioritas nasional',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'LPPM, peneliti',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Perencanaan Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Perencanaan Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Perencanaan Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Perencanaan Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Perencanaan Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Perencanaan Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  37 => 
  array (
    'kode' => 'SM-USS-T-PN-09',
    'nama' => 'Mutu Luaran Penelitian / Relevansi Penelitian',
    'kelompok' => 'Tambahan Penelitian 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Mutu Luaran Penelitian / Relevansi Penelitian secara berkelanjutan.',
    'target' => 'Kepala LPPM jamin pedoman & kesesuaian judul dengan roadmap prodi tiap tahun',
    'acuan' => 'Buku Manual SPMI - Tambahan Penelitian 53/2023',
    'penanggung_jawab' => 'Kepala LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Mutu Luaran Penelitian / Relevansi Penelitian?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Mutu Luaran Penelitian / Relevansi Penelitian?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Mutu Luaran Penelitian / Relevansi Penelitian\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Mutu Luaran Penelitian / Relevansi Penelitian\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Mutu Luaran Penelitian / Relevansi Penelitian\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Mutu Luaran Penelitian / Relevansi Penelitian\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  38 => 
  array (
    'kode' => 'SM-USS-T-PKM-01',
    'nama' => 'Akses Sarana PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Akses Sarana PkM secara berkelanjutan.',
    'target' => 'WR Keuangan/SDM/Sapras/Kerjasama jamin sarana penuhi kriteria minimal & standar mutu-keamanan; peneliti bertanggung jawab atas risiko penggunaan sarana institusi',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'WR terkait (Keuangan/SDM/Sapras/Kerjasama)',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Akses Sarana PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Akses Sarana PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Akses Sarana PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Akses Sarana PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Akses Sarana PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Akses Sarana PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  39 => 
  array (
    'kode' => 'SM-USS-T-PKM-02',
    'nama' => 'Prasarana PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Prasarana PkM secara berkelanjutan.',
    'target' => 'Sesuai roadmap PkM & isu strategis wilayah (pemberdayaan desa, pertanian, kesehatan, UMKM): ruang pelatihan, lab lapangan, teknologi tepat guna, infrastruktur digital',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'LPPM, unit Sarpras',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Prasarana PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Prasarana PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Prasarana PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Prasarana PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Prasarana PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Prasarana PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  40 => 
  array (
    'kode' => 'SM-USS-T-PKM-03',
    'nama' => 'Pembiayaan PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pembiayaan PkM secara berkelanjutan.',
    'target' => 'Dana seluruh tahapan PkM (perencanaan-pelaksanaan-evaluasi-pelaporan) sesuai prinsip relevansi-efektivitas-efisiensi-akuntabilitas-keberlanjutan',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'WR I & WR II, LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pembiayaan PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pembiayaan PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pembiayaan PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pembiayaan PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pembiayaan PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pembiayaan PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  41 => 
  array (
    'kode' => 'SM-USS-T-PKM-04',
    'nama' => 'Penugasan PkM Dosen',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penugasan PkM Dosen secara berkelanjutan.',
    'target' => 'Proposal diajukan paling lambat akhir semester; orisinalitas target 80%; validitas data target 90%',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'Dosen, LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penugasan PkM Dosen?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penugasan PkM Dosen?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penugasan PkM Dosen\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penugasan PkM Dosen\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penugasan PkM Dosen\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Penugasan PkM Dosen\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  42 => 
  array (
    'kode' => 'SM-USS-T-PKM-05',
    'nama' => 'Penggunaan TIK PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penggunaan TIK PkM secara berkelanjutan.',
    'target' => 'Aspek SDM, infrastruktur TIK, sistem informasi, penggunaan TIK dalam proses pengabdian',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'Kepala STK, LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penggunaan TIK PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penggunaan TIK PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penggunaan TIK PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penggunaan TIK PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penggunaan TIK PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Penggunaan TIK PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  43 => 
  array (
    'kode' => 'SM-USS-T-PKM-06',
    'nama' => 'Penilaian PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Penilaian PkM secara berkelanjutan.',
    'target' => 'Mengukur dampak sosial-ekonomi hasil riset dosen (kualitas hidup, pengentasan masalah sosial); Kepala LPPM jamin reviewer kompeten tiap tahun',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'Kepala LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Penilaian PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Penilaian PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Penilaian PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Penilaian PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Penilaian PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Penilaian PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  44 => 
  array (
    'kode' => 'SM-USS-T-PKM-07',
    'nama' => 'Perencanaan PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Perencanaan PkM secara berkelanjutan.',
    'target' => 'Keterarahan, keselarasan Rencana Induk Pengabdian/Renstra, akuntabilitas, kualitas ilmiah, dasar monev',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Perencanaan PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Perencanaan PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Perencanaan PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Perencanaan PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Perencanaan PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Perencanaan PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  45 => 
  array (
    'kode' => 'SM-USS-T-PKM-08',
    'nama' => 'Pelaksanaan PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pelaksanaan PkM secara berkelanjutan.',
    'target' => 'Metodologi tepat, minimalkan bias, ikuti regulasi (paralel dengan versi Penelitian)',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'Pelaksana PkM, LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pelaksanaan PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pelaksanaan PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pelaksanaan PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pelaksanaan PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pelaksanaan PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pelaksanaan PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  46 => 
  array (
    'kode' => 'SM-USS-T-PKM-09',
    'nama' => 'Pengendalian PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pengendalian PkM secara berkelanjutan.',
    'target' => 'LPPM jamin keselarasan dengan Roadmap/Renstra; pemantauan-evaluasi berkala wajib; pengawasan anggaran (verifikasi laporan keuangan & realisasi)',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pengendalian PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pengendalian PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pengendalian PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pengendalian PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pengendalian PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pengendalian PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  47 => 
  array (
    'kode' => 'SM-USS-T-PKM-10',
    'nama' => 'Pengawasan PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Pengawasan PkM secara berkelanjutan.',
    'target' => 'Pengawasan aspek waktu-lokasi-metode-sasaran sesuai proposal; jaga relevansi, etika, efisiensi dana',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'LPPM, WR I',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Pengawasan PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Pengawasan PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Pengawasan PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Pengawasan PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Pengawasan PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Pengawasan PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  48 => 
  array (
    'kode' => 'SM-USS-T-PKM-11',
    'nama' => 'Mutu PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Mutu PkM secara berkelanjutan.',
    'target' => 'Luaran PkM (publikasi, produk teknologi/inovasi, HKI/paten); Kepala LPPM jamin pedoman tersedia tiap tahun',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'Kepala LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Mutu PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Mutu PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Mutu PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Mutu PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Mutu PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Mutu PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  49 => 
  array (
    'kode' => 'SM-USS-T-PKM-12',
    'nama' => 'Relevansi PkM',
    'kelompok' => 'Tambahan PkM 53/2023',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Fakultas',
      1 => 'Program Studi',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Relevansi PkM secara berkelanjutan.',
    'target' => 'Dosen/peneliti wajib hasilkan luaran termanfaatkan (teknologi tepat guna/model/kebijakan) dalam 1 semester; mahasiswa terlibat implementasi dalam 1 tahun (≥70% tim); diseminasi selambatnya 6 bulan, min. 1 forum/penelitian',
    'acuan' => 'Buku Manual SPMI - Tambahan PkM 53/2023',
    'penanggung_jawab' => 'Dosen/peneliti, LPPM',
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Relevansi PkM?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Relevansi PkM?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Relevansi PkM\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Relevansi PkM\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Relevansi PkM\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Relevansi PkM\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  50 => 
  array (
    'kode' => 'NA-01',
    'nama' => 'Organisasi dan Tata Kelola / Manajemen',
    'kelompok' => 'Non-Akademik',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Non-Akademik',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Organisasi dan Tata Kelola / Manajemen secara berkelanjutan.',
    'target' => 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
    'acuan' => 'Buku Manual SPMI - Non-Akademik',
    'penanggung_jawab' => NULL,
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Organisasi dan Tata Kelola / Manajemen?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Organisasi dan Tata Kelola / Manajemen?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Organisasi dan Tata Kelola / Manajemen\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Organisasi dan Tata Kelola / Manajemen\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Organisasi dan Tata Kelola / Manajemen\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Organisasi dan Tata Kelola / Manajemen\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  51 => 
  array (
    'kode' => 'NA-02',
    'nama' => 'Kemahasiswaan',
    'kelompok' => 'Non-Akademik',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Non-Akademik',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Kemahasiswaan secara berkelanjutan.',
    'target' => 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
    'acuan' => 'Buku Manual SPMI - Non-Akademik',
    'penanggung_jawab' => NULL,
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Kemahasiswaan?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Kemahasiswaan?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Kemahasiswaan\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Kemahasiswaan\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Kemahasiswaan\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Kemahasiswaan\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  52 => 
  array (
    'kode' => 'NA-03',
    'nama' => 'Sumber Daya Manusia (SDM / Kepegawaian)',
    'kelompok' => 'Non-Akademik',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Non-Akademik',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Sumber Daya Manusia (SDM / Kepegawaian) secara berkelanjutan.',
    'target' => 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
    'acuan' => 'Buku Manual SPMI - Non-Akademik',
    'penanggung_jawab' => NULL,
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Sumber Daya Manusia (SDM / Kepegawaian)?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Sumber Daya Manusia (SDM / Kepegawaian)?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Sumber Daya Manusia (SDM / Kepegawaian)\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Sumber Daya Manusia (SDM / Kepegawaian)\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Sumber Daya Manusia (SDM / Kepegawaian)\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Sumber Daya Manusia (SDM / Kepegawaian)\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  53 => 
  array (
    'kode' => 'NA-04',
    'nama' => 'Sarana dan Prasarana',
    'kelompok' => 'Non-Akademik',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Non-Akademik',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Sarana dan Prasarana secara berkelanjutan.',
    'target' => 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
    'acuan' => 'Buku Manual SPMI - Non-Akademik',
    'penanggung_jawab' => NULL,
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Sarana dan Prasarana?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Sarana dan Prasarana?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Sarana dan Prasarana\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Sarana dan Prasarana\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Sarana dan Prasarana\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Sarana dan Prasarana\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  54 => 
  array (
    'kode' => 'NA-05',
    'nama' => 'Kerjasama',
    'kelompok' => 'Non-Akademik',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Non-Akademik',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Kerjasama secara berkelanjutan.',
    'target' => 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
    'acuan' => 'Buku Manual SPMI - Non-Akademik',
    'penanggung_jawab' => NULL,
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Kerjasama?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Kerjasama?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Kerjasama\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Kerjasama\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Kerjasama\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Kerjasama\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  55 => 
  array (
    'kode' => 'NA-06',
    'nama' => 'Keuangan',
    'kelompok' => 'Non-Akademik',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Non-Akademik',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Keuangan secara berkelanjutan.',
    'target' => 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
    'acuan' => 'Buku Manual SPMI - Non-Akademik',
    'penanggung_jawab' => NULL,
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Keuangan?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Keuangan?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Keuangan\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Keuangan\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Keuangan\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Keuangan\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
  56 => 
  array (
    'kode' => 'NA-07',
    'nama' => 'Kesejahteraan',
    'kelompok' => 'Non-Akademik',
    'is_akademik' => 1,
    'is_active' => 1,
    'sasaran_unit' => 
    array (
      0 => 'Non-Akademik',
    ),
    'indikator' => 'Kriteria pemenuhan dan panduan pelaksanaan untuk Kesejahteraan secara berkelanjutan.',
    'target' => 'Sesuai dengan target batas minimal yang ditetapkan Universitas/Prodi.',
    'acuan' => 'Buku Manual SPMI - Non-Akademik',
    'penanggung_jawab' => NULL,
    'indikator_p1' => 'Apakah terdapat bukti penetapan / SK resmi (kebijakan/manual baku) terkait Kesejahteraan?',
    'indikator_p2' => 'Apakah pelaksanaan operasional unit dan kegiatan lapangan telah mengacu secara konsisten terhadap target Kesejahteraan?',
    'indikator_p3' => 'Apakah telah dilakukan pengumpulan data, pemantauan, serta proses Monitoring dan Evaluasi (Monev) berkala terhadap implementasi standar ini?',
    'indikator_p4' => 'Apakah pernah dilakukan Rapat Tinjauan Manajemen (RTM) serta tindakan korektif jika ditemukan ketidaksesuaian atau target yang tidak tercapai?',
    'indikator_p5' => 'Apakah terdapat dokumen atau bukti inisiatif untuk rencana peningkatan (revisi naik) target dan kualitas standar di siklus berikutnya?',
    'rubrik_penilaian' => 
    array (
      1 => 'Kurang (Menyimpang): Tidak ada pelaksanaan yang relevan dengan indikator \'Kesejahteraan\' (target 0%), atau sama sekali tidak ada dokumen bukti yang absah. Siklus PPEPP tidak berjalan dan Standar diabaikan.',
      2 => 'Cukup (Belum Tercapai): Pelaksanaan \'Kesejahteraan\' sudah berjalan namun pencapaian target indikator belum maksimal (< 100%). Terdapat ketidaklengkapan dokumen bukti fisik, atau beberapa tahapan dalam siklus PPEPP (misal: tahap Evaluasi atau Pengendalian) tidak dilaksanakan secara sistematis.',
      3 => 'Baik (Tercapai): Pencapaian indikator \'Kesejahteraan\' telah memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan dengan baik sesuai SOP, terdokumentasi secara teratur, dan pelaksanaan kegiatan telah dievaluasi dengan instrumen yang valid.',
      4 => 'Sangat Baik (Melampaui): Pencapaian indikator \'Kesejahteraan\' melampaui target yang ditetapkan (kualitas/kuantitas > 100%). Seluruh tahapan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) berjalan konsisten, terdokumentasi dengan sangat rapi, dan terdapat inovasi atau pengakuan praktik baik (best practice) dari pihak luar.',
    ),
    'template_dokumen' => NULL,
  ),
);

        foreach ($standards as $item) {
            Standard::updateOrCreate(
                ['kode' => $item['kode']],
                [
                    'nama' => $item['nama'],
                    'kelompok' => $item['kelompok'],
                    'periode' => $periodName,
                    'is_akademik' => $item['is_akademik'],
                    'is_active' => 1,
                    'sasaran_unit' => $item['sasaran_unit'],
                    'indikator' => $item['indikator'],
                    'target' => $item['target'],
                    'acuan' => $item['acuan'],
                    'penanggung_jawab' => $item['penanggung_jawab'],
                    'indikator_p1' => $item['indikator_p1'],
                    'indikator_p2' => $item['indikator_p2'],
                    'indikator_p3' => $item['indikator_p3'],
                    'indikator_p4' => $item['indikator_p4'],
                    'indikator_p5' => $item['indikator_p5'],
                    'rubrik_penilaian' => $item['rubrik_penilaian'],
                    'template_dokumen' => $item['template_dokumen'],
                ]
            );
        }

        // Pastikan standar dengan kode SM-USS dan NA diarahkan ke periode utama
        Standard::where(function($q) {
            $q->where('kode', 'like', 'SM-USS-%')->orWhere('kode', 'like', 'NA-%');
        })->where(function($q) use ($periodName) {
            $q->whereNull('periode')->orWhere('periode', '')->orWhere('periode', 'Periode Utama');
        })->update(['periode' => $periodName, 'is_active' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to delete standard master data on rollback
    }
};