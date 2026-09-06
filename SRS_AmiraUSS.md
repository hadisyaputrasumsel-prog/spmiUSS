# Software Requirements Specification (SRS) - AMIRA USS

## 1. Pendahuluan

### 1.1 Tujuan
Dokumen _Software Requirements Specification_ (SRS) ini dibuat untuk mendokumentasikan spesifikasi kebutuhan perangkat lunak **Aplikasi Penjaminan Mutu Internal (AMIRA USS)**. Dokumen ini bertujuan untuk menjadi panduan bagi pengembang, penguji (_tester_), serta pemangku kepentingan (LPMA, Auditor, Auditee) tentang fungsionalitas dan fitur yang ada dalam aplikasi.

### 1.2 Ruang Lingkup Sistem
AMIRA USS adalah sebuah sistem informasi berbasis web yang memfasilitasi dan mendigitalisasi siklus Sistem Penjaminan Mutu Internal (SPMI) berbasis PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) untuk Universitas Sumatera Selatan. Sistem ini meliputi pengisian Laporan Evaluasi Diri (LED), pelaksanaan Audit Mutu Internal (AMI), pelaporan Rencana Tindak Lanjut (RTL), hingga manajemen dan rekapitulasi pada Bulan Mutu (Siklus Audit) serta Rapat Tinjauan Manajemen (RTM).

### 1.3 Definisi, Akronim, dan Singkatan
* **SPMI**: Sistem Penjaminan Mutu Internal.
* **PPEPP**: Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan.
* **LED**: Laporan Evaluasi Diri.
* **AMI**: Audit Mutu Internal.
* **RTL**: Rencana Tindak Lanjut.
* **RTM**: Rapat Tinjauan Manajemen.
* **LPMA**: Lembaga Penjaminan Mutu Akademik.
* **UPPS**: Unit Pengelola Program Studi.
* **GPM/UPM**: Gugus Penjaminan Mutu / Unit Penjaminan Mutu.

---

## 2. Gambaran Umum Sistem

### 2.1 Perspektif Produk
AMIRA USS dikembangkan berbasis kerangka kerja Laravel dan di-host dalam lingkungan *containerized* (Docker/Portainer). Sistem ini memusatkan seluruh kegiatan administrasi mutu untuk perguruan tinggi, sehingga mengurangi proses manual/penggunaan dokumen cetak, dengan fungsi ekspor otomatis ke format _Berita Acara_ atau Dokumen Laporan _PDF_.

### 2.2 Karakteristik Pengguna (_Role Based_)
Sistem memiliki kontrol akses ketat berdasarkan peran (_role_), meliputi:
1. **Super Admin**: Memiliki hak akses penuh termasuk manajemen akun, role, dan base konfigurasi.
2. **LPMA (Lembaga Penjaminan Mutu)**: Mengelola pengaturan Bulan Mutu, menetapkan Standar Mutu, menugaskan auditor (_assigment_), serta memantau progres pelaksanaan SPMI seluruh unit.
3. **Auditor**: Memeriksa dokumen LED dari Auditee yang ditugaskan, memberikan penilaian, dan mencatat deskripsi temuan AMI.
4. **Auditee (UPPS, Prodi, Unit, GPM/UPM)**: Bertanggung jawab menyusun, mengisi dan menyerahkan dokumen LED serta menjawab temuan audit melalui borang RTL.
5. **Pimpinan**: Melihat laporan tingkat lanjut, status siklus pantauan mutu, dan laporan akhir AMI.

### 2.3 Lingkungan Operasi
* **Platform**: Aplikasi Web (Browser/Responsive)
* **Backend**: PHP 8.3, Framework Laravel 11.x
* **Database**: MySQL / MariaDB (Terhubung dalam Docker network)
* **Ekspor Dokumen**: Mengutilisasi DomPDF `barryvdh/laravel-dompdf`

---

## 3. Kebutuhan Fungsional

### 3.1 Manajemen Autentikasi dan Routing (FR-01)
* **FR-01.1**: Sistem harus menyediakan fitur otentikasi (_Login_ & _Logout_) bagi semua pengguna terdaftar.
* **FR-01.2**: Sistem harus membatasi akses halaman (_Middleware_) murni berdasarkan _role_ yang dimiliki pengguna.
* **FR-01.3**: Begitu pengguna dengan akses spesifik (seperti Auditee) _login_, sistem secara otomatis mendeteksi dan mengarahkannya ke _dashboard_ khusus unitnya (_Drill down unit PPEPP_).

### 3.2 Dashboard & Pemantauan Siklus (FR-02)
* **FR-02.1**: Sistem menampilkan rangkuman persentase **Status Tahapan PPEPP** (Tahap aktif: P1 Penetapan, P2 Pelaksanaan, P3 Evaluasi/AMI, P4 Pengendalian, P5 Peningkatan).
* **FR-02.2**: Sistem menampilkan _Micro-Timeline 7-Langkah_ (Pengisian ED -> Verifikasi Evidence -> Desk Evaluation -> Visitasi -> Closing -> RTL -> Verifikasi RTL) untuk melacak progress per unit.
* **FR-02.3**: Sistem akan secara cerdas menyoroti matriks *"Units Attention"* untuk unit-unit yang masih memiliki status Temuan RTL "_Belum_" atau sedang berjalan.
* **FR-02.4**: Tersedia _Matriks Penilaian Standar Mutu_ komprehensif bagi level pengevaluasi (Auditor dan LPMA).

### 3.3 Modul Manajemen Standar Mutu & Referensi (FR-03)
* **FR-03.1**: Modul CRUD (Create, Read, Update, Delete) matriks Standar Mutu Nasional dan Institusi (baik Akademik maupun Non-Akademik).
* **FR-03.2**: Manajemen entitas dan profil _Auditee_ (Unit Akademik / Non-Akademik).
* **FR-03.3**: Modul Penugasan Auditor (_Auditor Assignment_) untuk memetakan alokasi Auditor penilai kepada satu atau beberapa Unit Auditee spesifik per Tahun Ajaran.

### 3.4 Modul Laporan Evaluasi Diri / LED (FR-04)
* **FR-04.1**: Auditee dapat melakukan _entry_ / pengisian instrumen Laporan Evaluasi Diri (Simpan, Edit tahap draft, & Submit final).
* **FR-04.2**: Sistem memungkinkan Auditee atau peninjau untuk mengekstrak/ekspor dokumen LED _sebagian (Partial)_ ataupun _menyeluruh (Full)_ ke instrumen berformat PDF otomatis.
* **FR-04.3**: Sistem menyimpan historis perubahan/log evaluasi dari tiap butir mutu LED.

### 3.5 Modul Audit Mutu Internal / AMI (FR-05)
* **FR-05.1**: Auditor dapat mendigitalisasi catatan temuan audit (_Ami Findings_) ke sistem berdasarkan telaah _desk evaluation_ dan _visitasi_ lapangan.
* **FR-05.2**: Auditor mempunyai kapabilitas untuk mengimpor data temuan AMI secara massal (_bulk import_ melalui berkas excel) langsung ke sistem.
* **FR-05.3**: Tergenerate secara otomatis Dokumen Laporan Kertas Kerja AMI siap cetak dalam ekstensi `.pdf`.

### 3.6 Modul Rencana Tindak Lanjut / RTL (FR-06)
* **FR-06.1**: Auditee dapat langsung merekomendasikan komitmen tindak lanjut pada tiap temuan AMI melalui formulir responsif (_Update RTL_).
* **FR-06.2**: Dokumen tanggapan (Laporan Tindak Lanjut) dapat diekspor otomatis ke format PDF sebagai bukti pencantuman kesepakatan perbaikan per sub-unit.

### 3.7 Modul Bulan Mutu & Rapat Tinjauan Manajemen (FR-07)
* **FR-07.1**: Otoritas LPMA dapat melakukan instalasi/generate seluruh rangkaian kerangka _Bulan Mutu_ tahunan beserta pengaturan "_Tahun Aktif_".
* **FR-07.2**: Terdapat antarmuka _Pantau LED_ (Kelengkapan ED per unit) dan _Pantau Audit_ khusus panitia eksekutif/auditor (LPMA).
* **FR-07.3**: Pengkinian manual & dinamis status tahapan penyelenggaraan (Selesai, Berjalan).
* **FR-07.4**: Modul terpadu untuk mengekstraksi/Mencetak Berita Acara Rapat Tinjauan Manajemen otomatis melalui formulasi rute RTM PDF.

---

## 4. Kebutuhan Non-Fungsional

### 4.1 Keamanan & Enkripsi
* Mengimplementasikan autentikasi berlapis dan batasan gerbang otorisasi rute di tingkat _backend_ (_Middleware Role_).
* Membatasi visibilitas dan _scope query_ spesifik agar Auditee (Unit A) tidak secara tidak sah membuka berkas milik Auditee (Unit B).

### 4.2 Kinerja dan Performa Aplikasi
* Sistem _PDF Generator_ (terutama export Dokumen LED Full dan AMI) berjalan dibalik pengaturan *Memory Limit* & *Execution Time* yang telah ditingkatkan dinamis per fungsi agar mencegah kejadian instan _Resource Exhausted_ di lingkungan produksi Docker.

### 4.3 Ketersediaan Tinggi (High Availability)
* Dirancang beroperasi dalam jaringan _Docker/Portainer_ untuk memudahkan _deployment_ ke *Virtual Private Server* / *Cloud*, mereduksi malfungsi perbedaan *environment*, dan memastikan _uptime_ stabilitas untuk skala penggunaan Universitas Sumatera Selatan.
