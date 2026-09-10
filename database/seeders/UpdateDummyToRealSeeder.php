<?php

namespace Database\Seeders;

use App\Models\AmiFinding;
use Illuminate\Database\Seeder;

class UpdateDummyToRealSeeder extends Seeder
{
    public function run()
    {
        $findings = AmiFinding::where('uraian', 'like', '%Dummy temuan%')->get();

        $p1Uraian = [
            'Dokumen kebijakan dan standar telah tersedia dan disahkan, namun sosialisasi ke tingkat dosen dirasa masih perlu ditingkatkan.',
            'Penetapan target capaian standar mutu sudah jelas dan merujuk pada Renstra Universitas secara terstruktur.',
            'Indikator capaian pada dokumen standar sudah spesifik, namun beberapa target kuantitatif pelaksanaannya perlu diperjelas.',
        ];

        $p2Uraian = [
            'Proses pelaksanaan kegiatan akademik sudah mengacu pada SOP terkait, akan tetapi pengarsipan dokumen bukti fisik (hardcopy) belum konsisten.',
            'Pelaksanaan tridharma terpantau berjalan lancar dan terkoordinasi dengan baik sesuai rencana operasional yang ditetapkan di awal semester.',
            'Masih ditemukan sedikit ketidaksesuaian prosedur dan tahap pengerjaan pada beberapa kegiatan layanan fasilitas kemahasiswaan.',
        ];

        $p3Uraian = [
            'Monitoring dan evaluasi ketercapaian standar telah dilakukan secara berkala tiap kuartal dan terdokumentasi dalam laporan berkala.',
            'Instrumen kuesioner monev kepuasan layanan sudah disebarkan, namun tingkat partisipasi responden (mahasiswa) masih di bawah target minimal.',
            'Terdapat beberapa temuan atau celah ketidakcapaian pada siklus mutu kali ini yang terdeteksi secara otomatis dari hasil data evaluasi diri (LED).',
        ];

        $p4Uraian = [
            'Rapat Tinjauan Manajemen (RTM) level unit telah diselenggarakan untuk merumuskan langkah korektif, namun belum diteruskan dalam bentuk SK tindak lanjut resmi.',
            'Pengendalian belum sepenuhnya merata; masih terdapat beberapa poin observasi dari periode visitasi kampus sebelumnya yang belum tuntas.',
            'Tim gugus penjaminan mutu fakultas telah menugaskan unit-unit terkait untuk segera melakukan pembenahan berkas-berkas laporan yang terlambat.',
        ];

        $p5Uraian = [
            'Terdapat inisiatif peninjauan kembali profil dan target lulusan yang kini disesuaikan dengan tren kebutuhan industri teknologi terkini (upgrading standar).',
            'Sedang disusun draf pembaharuan target yang akan melakukan tahapan benchmarking dengan universitas terkemuka lainnya di skala yang lebih luas.',
            'Pimpinan dan seluruh sivitas sepakat untuk perlahan menaikkan batas ambang skor kelulusan minimal pada standar pembelajaran di tahun-tahun mendatang.',
        ];

        $rtlList = [
            'Membuat surat edaran resmi dan mengadakan forum rapat sosialisasi dokumen standar mutu kepada seluruh stakeholder internal di awal bulan depan.',
            'Melengkapi kembali semua dokumen bukti (meliputi Daftar Hadir, Notulensi Rapat, dan Dokumentasi Foto) pada setiap agenda kegiatan prodi agar lebih valid.',
            'Melakukan revisi teknis manual presensi harian dan segera mengintegrasikannya dengan Sistem Informasi Manajemen Kampus yang baru sebelum tahun ajaran baru.',
            'Membentuk tim Task-Force (satgas) khusus tingkat universitas dalam waktu dekat untuk menyusun ulang borang dan dokumen evaluasi kinerja dosen yang dirasa kurang akurat.',
            'Mengagendakan acara lokakarya peninjauan kurikulum prodi berkelanjutan dengan mengundang praktisi industri dan alumni dalam periode waktu selambatnya 3 bulan ke depan.',
        ];

        foreach ($findings as $finding) {
            $uraian = '';

            switch ($finding->tahap) {
                case 'P1': $uraian = collect($p1Uraian)->random();
                    break;
                case 'P2': $uraian = collect($p2Uraian)->random();
                    break;
                case 'P3': $uraian = collect($p3Uraian)->random();
                    break;
                case 'P4': $uraian = collect($p4Uraian)->random();
                    break;
                case 'P5': $uraian = collect($p5Uraian)->random();
                    break;
                default: $uraian = 'Prosedur pemenuhan mutu secara keseluruhan tercapai dengan baik, namun butuh pengawasan dan perbaikan SOP di beberapa lini.';
            }

            $finding->uraian = $uraian;

            if ($finding->kategori_temuan != 'Sesuai') {
                $finding->rencana_tindakan = collect($rtlList)->random();
                $finding->pic = collect(['Kaprodi', 'Ketua GPM', 'Dekan', 'Kepala Tata Usaha', 'Sekretaris Prodi', 'Perwakilan Dosen', 'Biro Akademik'])->random();
            } else {
                $finding->rencana_tindakan = null;
                $finding->pic = null;
            }

            // Optionally, randomly update category if it's Sesuai too much, but I'll leave them as they were randomly assigned
            $finding->save();
        }
    }
}
