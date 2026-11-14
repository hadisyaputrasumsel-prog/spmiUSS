<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Laporan Audit Mutu Internal (AMI) Tahun {{ $tahun }}</title>
    <style>
        /* Margin Kiri 4cm, Atas 3cm, Kanan 3cm, Bawah 3cm */
        @page {
            size: a4 portrait;
            margin: 3cm 3cm 3cm 4cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #111;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* Tipografi Judul */
        h1.bab-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 20px;
            color: #0f172a;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
        }

        h2.subbab-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 8px;
            color: #0f172a;
        }

        h3.subsubbab-title {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 6px;
            color: #334155;
        }

        p {
            margin-top: 0;
            margin-bottom: 10px;
            text-align: justify;
        }

        /* Tabel Standar */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 16px;
            font-size: 8.5pt;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #334155;
            padding: 5px 7px;
            vertical-align: middle;
        }

        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            color: #0f172a;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Badge Kategori */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 7.5pt;
            font-weight: bold;
            border-radius: 3px;
            text-align: center;
        }
        .badge-mayor { background-color: #fee2e2; color: #991b1b; }
        .badge-minor { background-color: #fef3c7; color: #92400e; }
        .badge-ob { background-color: #e0f2fe; color: #075985; }
        .badge-sesuai { background-color: #dcfce7; color: #166534; }
        .badge-ofi { background-color: #f3e8ff; color: #6b21a8; }
        .badge-tp { background-color: #ccfbf1; color: #115e59; }

        /* Komponen Grafik Bar */
        .chart-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }
        .chart-title {
            font-size: 9.5pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            color: #0f172a;
        }
        .bar-container {
            width: 100%;
            margin-bottom: 8px;
        }
        .bar-label {
            font-size: 8pt;
            font-weight: bold;
            color: #334155;
            margin-bottom: 2px;
        }
        .bar-track {
            background-color: #e2e8f0;
            height: 14px;
            border-radius: 3px;
            overflow: hidden;
            width: 100%;
        }
        .bar-fill {
            height: 100%;
            float: left;
        }

        /* Lembar Pengesahan */
        .pengesahan-table {
            width: 100%;
            border: none;
            margin-top: 30px;
        }
        .pengesahan-table td {
            border: none;
            vertical-align: top;
            padding: 0 10px;
        }

        /* Daftar Isi Titik-Titik */
        .toc-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
            font-size: 9.5pt;
        }
        .toc-item {
            margin-bottom: 7px;
            display: block;
            clear: both;
        }
        .toc-title {
            float: left;
            background: #fff;
            padding-right: 5px;
        }
        .toc-page {
            float: right;
            background: #fff;
            padding-left: 5px;
            font-weight: bold;
        }
        .toc-dots {
            border-bottom: 1px dotted #64748b;
            margin-top: 13px;
        }
    </style>
</head>
<body>

@php
    $logoCandidates = [
        public_path('images/logo-uss.png'),
        public_path('images/logo.png'),
        public_path('storage/logos/1788738305_Logo USS.png'),
        storage_path('app/public/logos/1788738305_Logo USS.png'),
    ];
    $logoBase64 = null;
    foreach ($logoCandidates as $cand) {
        if (file_exists($cand)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($cand));
            break;
        }
    }

    $tglAuditFormatted = $tanggalAuditTerpilih ? \Carbon\Carbon::parse($tanggalAuditTerpilih)->translatedFormat('d F Y') : '16 Oktober ' . $tahun;
    $tglTerbitFormatted = $laporan->tanggal_terbit ? $laporan->tanggal_terbit->translatedFormat('d F Y') : '20 November ' . $tahun;

    if (! function_exists('renderRichText')) {
        function renderRichText($content) {
            return \App\Models\LaporanAmi::renderRichText($content);
        }
    }
@endphp

<!-- ========================================================================= -->
<!-- 1. HALAMAN SAMPUL (COVER) - Tanpa Nomor Halaman                            -->
<!-- ========================================================================= -->
<div style="text-align: center; padding-top: 40px; height: 100%;">
    <div style="margin-bottom: 30px;">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo USS" style="width: 120px; height: auto;">
        @else
            <div style="font-size: 24pt; font-weight: bold; color: #0284c7;">UNIVERSITAS SUMATERA SELATAN</div>
        @endif
    </div>

    <div style="font-size: 13pt; font-weight: bold; letter-spacing: 1px; color: #475569; margin-bottom: 10px;">
        BUKU LAPORAN RESMI
    </div>

    <h1 style="font-size: 18pt; font-weight: 800; color: #0f172a; margin: 0 0 10px 0; line-height: 1.3;">
        AUDIT MUTU INTERNAL (AMI)<br>UNIVERSITAS SUMATERA SELATAN
    </h1>

    <div style="font-size: 14pt; font-weight: bold; color: #0284c7; margin-bottom: 25px;">
        TAHUN AKADEMIK {{ $tahun }}
    </div>

    <div style="width: 60px; height: 3px; background: #0284c7; margin: 0 auto 30px auto;"></div>

    <p style="text-align: center; font-size: 10pt; color: #334155; max-width: 80%; margin: 0 auto 80px auto; line-height: 1.6;">
        Potret Komprehensif Siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP) Standar SPMI dan Hasil Audit Mutu Internal di Lingkungan Universitas Sumatera Selatan
    </p>

    <div style="margin-top: 100px;">
        <div style="font-size: 10pt; font-weight: bold; color: #475569; margin-bottom: 5px;">Disusun Oleh :</div>
        <div style="font-size: 12pt; font-weight: bold; color: #0f172a; text-transform: uppercase;">
            LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)
        </div>
        <div style="font-size: 11pt; font-weight: bold; color: #0284c7; text-transform: uppercase; margin-top: 4px;">
            UNIVERSITAS SUMATERA SELATAN
        </div>
        <div style="font-size: 10pt; color: #64748b; margin-top: 15px;">
            PALEMBANG, {{ $tahun }}
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. LEMBAR PENGESAHAN (Halaman Romawi ii)                                  -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">LEMBAR PENGESAHAN</h1>

    <p style="text-align: justify; margin-top: 25px; margin-bottom: 20px;">
        Buku Laporan Hasil Audit Mutu Internal (AMI) Universitas Sumatera Selatan Tahun Akademik {{ $tahun }} telah disusun secara seksama oleh Tim Lembaga Penjaminan Mutu Akademik (LPMA) bersama Tim Auditor Internal berdasarkan hasil audit lapangan, verifikasi borang Evaluasi Diri (LED), serta rekapitulasi Rencana Tindak Lanjut (RTL) pada seluruh unit kerja.
    </p>

    <table class="data-table" style="margin-bottom: 30px;">
        <tr>
            <td style="width: 30%; background: #f8fafc; font-weight: bold;">Judul Dokumen</td>
            <td style="width: 70%;">Buku Laporan Hasil Audit Mutu Internal (AMI) Universitas Sumatera Selatan</td>
        </tr>
        <tr>
            <td style="background: #f8fafc; font-weight: bold;">Tahun Audit</td>
            <td>Tahun Akademik {{ $tahun }}</td>
        </tr>
        <tr>
            <td style="background: #f8fafc; font-weight: bold;">Nomor Dokumen</td>
            <td>{{ $laporan->nomor_dokumen ?: sprintf('01/LPMA-USS/AMI/XII/%d', $tahun) }}</td>
        </tr>
        <tr>
            <td style="background: #f8fafc; font-weight: bold;">Tanggal Terbit / Pengesahan</td>
            <td>{{ $tglTerbitFormatted }}</td>
        </tr>
        <tr>
            <td style="background: #f8fafc; font-weight: bold;">Versi / Status</td>
            <td>Versi {{ $laporan->versi ?: '1.0' }} (Dokumen Resmi Terverifikasi)</td>
        </tr>
    </table>

    <p style="text-align: center; font-weight: bold; margin-top: 40px; margin-bottom: 25px;">
        Disahkan di Palembang, pada tanggal {{ $tglTerbitFormatted }}
    </p>

    <table class="pengesahan-table">
        <tr>
            <td style="width: 50%; text-align: center;">
                Mengetahui dan Mengesahkan,<br>
                <b>{{ $laporan->penandatangan_lpma_jabatan ?: 'Kepala LPMA Universitas Sumatera Selatan' }}</b><br>
                Universitas Sumatera Selatan
                <br><br><br><br><br>
                <u><b>{{ $laporan->penandatangan_lpma_nama ?: 'Dr. Hadi Syaputra, S.Kom., M.Kom' }}</b></u><br>
                NIDN/NIP : {{ $laporan->penandatangan_lpma_nidn ?: '0214057801' }}
            </td>
            <td style="width: 50%; text-align: center;">
                Mengesahkan,<br>
                <b>{{ $laporan->penandatangan_rektor_jabatan ?: 'Rektor Universitas Sumatera Selatan' }}</b><br>
                Universitas Sumatera Selatan
                <br><br><br><br><br>
                <u><b>{{ $laporan->penandatangan_rektor_nama ?: 'Prof. Dr. Ir. H. M. Syahril, M.S.' }}</b></u><br>
                NIDN/NIP : {{ $laporan->penandatangan_rektor_nidn ?: '0015086002' }}
            </td>
        </tr>
    </table>
</div>

<!-- ========================================================================= -->
<!-- 3. KATA PENGANTAR (Halaman Romawi iii)                                    -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">KATA PENGANTAR</h1>

    <div style="font-size: 10pt; line-height: 1.6; text-align: justify;">
        {!! renderRichText($laporan->kata_pengantar) !!}
    </div>

    <div style="margin-top: 40px; float: right; width: 250px; text-align: center;">
        Palembang, {{ $tglTerbitFormatted }}<br>
        Kepala LPMA Universitas Sumatera Selatan,<br>
        <br><br><br><br>
        <u><b>{{ $laporan->penandatangan_lpma_nama ?: 'Dr. Hadi Syaputra, S.Kom., M.Kom' }}</b></u><br>
        NIDN: {{ $laporan->penandatangan_lpma_nidn ?: '0214057801' }}
    </div>
    <div style="clear: both;"></div>
</div>

<!-- ========================================================================= -->
<!-- 4. DAFTAR ISI, DAFTAR TABEL, DAFTAR GAMBAR (Halaman Romawi iv)            -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">DAFTAR ISI</h1>

    <div class="toc-list" style="margin-top: 15px;">
        <div class="toc-item">
            <span class="toc-title font-bold">HALAMAN SAMPUL</span>
            <span class="toc-page">i</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title font-bold">LEMBAR PENGESAHAN</span>
            <span class="toc-page">ii</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title font-bold">KATA PENGANTAR</span>
            <span class="toc-page">iii</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title font-bold">DAFTAR ISI</span>
            <span class="toc-page">iv</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title font-bold">DAFTAR TABEL & DAFTAR GAMBAR</span>
            <span class="toc-page">v</span>
            <div class="toc-dots"></div>
        </div>

        <div style="height: 10px;"></div>

        <div class="toc-item">
            <span class="toc-title font-bold">BAB I PENDAHULUAN</span>
            <span class="toc-page">1</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">1.1 Latar Belakang</span>
            <span class="toc-page">1</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">1.2 Dasar Hukum</span>
            <span class="toc-page">1</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">1.3 Tujuan Audit Mutu Internal</span>
            <span class="toc-page">2</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">1.4 Ruang Lingkup Audit</span>
            <span class="toc-page">2</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">1.5 Metode dan Mekanisme Audit</span>
            <span class="toc-page">2</span>
            <div class="toc-dots"></div>
        </div>

        <div style="height: 10px;"></div>

        <div class="toc-item">
            <span class="toc-title font-bold">BAB II PELAKSANAAN AUDIT MUTU INTERNAL</span>
            <span class="toc-page">3</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">2.1 Jadwal Pelaksanaan AMI</span>
            <span class="toc-page">3</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">2.2 Tim Auditor dan Sasaran Unit Audit</span>
            <span class="toc-page">3</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">2.3 Status Pengisian Laporan Evaluasi Diri (LED)</span>
            <span class="toc-page">4</span>
            <div class="toc-dots"></div>
        </div>

        <div style="height: 10px;"></div>

        <div class="toc-item">
            <span class="toc-title font-bold">BAB III HASIL AUDIT MUTU INTERNAL</span>
            <span class="toc-page">5</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">3.1 Rekapitulasi Temuan Audit per Kategori</span>
            <span class="toc-page">5</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">3.2 Skor Rata-Rata Capaian Standar</span>
            <span class="toc-page">6</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">3.3 Sepuluh Standar dengan Temuan KTS Terbanyak</span>
            <span class="toc-page">7</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">3.4 Analisis Grafik Hasil Audit</span>
            <span class="toc-page">8</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">3.5 Daftar Rincian Seluruh Temuan Non-Sesuai</span>
            <span class="toc-page">9</span>
            <div class="toc-dots"></div>
        </div>

        <div style="height: 10px;"></div>

        <div class="toc-item">
            <span class="toc-title font-bold">BAB IV TINDAK LANJUT HASIL AUDIT (RTL)</span>
            <span class="toc-page">10</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">4.1 Status Penyelesaian Rencana Tindak Lanjut</span>
            <span class="toc-page">10</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">4.2 Persentase dan Efektivitas RTL</span>
            <span class="toc-page">10</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">4.3 Daftar RTL Melewati Batas Waktu</span>
            <span class="toc-page">11</span>
            <div class="toc-dots"></div>
        </div>

        <div style="height: 10px;"></div>

        <div class="toc-item">
            <span class="toc-title font-bold">BAB V KESIMPULAN DAN REKOMENDASI</span>
            <span class="toc-page">12</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">5.1 Kesimpulan Umum</span>
            <span class="toc-page">12</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item" style="padding-left: 20px;">
            <span class="toc-title">5.2 Rekomendasi untuk Rapat Tinjauan Manajemen (RTM)</span>
            <span class="toc-page">12</span>
            <div class="toc-dots"></div>
        </div>

        <div style="height: 10px;"></div>

        <div class="toc-item">
            <span class="toc-title font-bold">LAMPIRAN</span>
            <span class="toc-page">13</span>
            <div class="toc-dots"></div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 5. DAFTAR TABEL & DAFTAR GAMBAR (Halaman Romawi v)                        -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">DAFTAR TABEL & GAMBAR</h1>

    <h2 class="subbab-title">DAFTAR TABEL</h2>
    <div class="toc-list" style="margin-top: 10px; margin-bottom: 25px;">
        <div class="toc-item">
            <span class="toc-title">Tabel 2.1 Jadwal Pelaksanaan Audit Mutu Internal Tahun {{ $tahun }}</span>
            <span class="toc-page">3</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 2.2 Tim Auditor dan Sasaran Unit Kerja</span>
            <span class="toc-page">3</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 2.3 Status Pengisian Laporan Evaluasi Diri (LED) per Unit</span>
            <span class="toc-page">4</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 3.1 Rekapitulasi Temuan Audit Universitas</span>
            <span class="toc-page">5</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 3.2 Rekapitulasi Temuan Audit per Unit Kerja</span>
            <span class="toc-page">5</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 3.3 Skor Rata-Rata Capaian Standar per Unit Kerja</span>
            <span class="toc-page">6</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 3.4 Skor Rata-Rata Capaian per Kelompok Standar SPMI</span>
            <span class="toc-page">6</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 3.5 Sepuluh Standar dengan Temuan KTS Terbanyak</span>
            <span class="toc-page">7</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 3.6 Daftar Rincian Seluruh Temuan Non-Sesuai</span>
            <span class="toc-page">9</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Tabel 4.1 Rekapitulasi Status Rencana Tindak Lanjut (RTL)</span>
            <span class="toc-page">10</span>
            <div class="toc-dots"></div>
        </div>
    </div>

    <h2 class="subbab-title">DAFTAR GAMBAR & GRAFIK</h2>
    <div class="toc-list" style="margin-top: 10px;">
        <div class="toc-item">
            <span class="toc-title">Grafik 3.1 Komposisi Kategori Temuan per Unit Kerja</span>
            <span class="toc-page">8</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Grafik 3.2 Perbandingan Skor Rata-Rata per Unit Kerja</span>
            <span class="toc-page">8</span>
            <div class="toc-dots"></div>
        </div>
        <div class="toc-item">
            <span class="toc-title">Grafik 3.3 Skor Rata-Rata Capaian per Kelompok Standar SPMI</span>
            <span class="toc-page">8</span>
            <div class="toc-dots"></div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 6. BAB I PENDAHULUAN (Halaman Arab 1)                                      -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">BAB I<br>PENDAHULUAN</h1>

    <h2 class="subbab-title">1.1 Latar Belakang</h2>
    <p>
        Sistem Penjaminan Mutu Internal (SPMI) merupakan pilar fundamental dalam menjamin dan meningkatkan mutu penyelenggaraan tridharma perguruan tinggi di Universitas Sumatera Selatan secara berkelanjutan. Sesuai dengan Permendikbudristek No. 53 Tahun 2023, perguruan tinggi diwajibkan mengoperasikan siklus penjaminan mutu yang terintegrasi berbasis PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan).
    </p>
    <p>
        Audit Mutu Internal (AMI) memegang peranan krusial pada tahapan <b>Evaluasi pelaksanaan standar</b>. AMI bukanlah kegiatan mencari kesalahan (mencari-cari kelemahan), melainkan upaya terencana dan sistematis untuk mengonfirmasi keselarasan praktik akademik dan operasional di tingkat unit dengan standar SPMI yang telah ditetapkan, mengidentifikasi peluang peningkatan (OFI), mengapresiasi praktik baik (Temuan Positif), serta merumuskan tindakan koreksi yang terarah atas setiap ketidaksesuaian yang dijumpai.
    </p>

    <h2 class="subbab-title">1.2 Dasar Hukum</h2>
    <p>Penyelenggaraan Audit Mutu Internal (AMI) Universitas Sumatera Selatan berlandaskan pada ketentuan perundang-undangan dan kebijakan internal sebagai berikut:</p>
    <div style="font-size: 9.5pt; line-height: 1.6; margin-left: 15px; margin-bottom: 15px;">
        {!! renderRichText($laporan->dasar_hukum) !!}
    </div>

    <h2 class="subbab-title">1.3 Tujuan Audit Mutu Internal</h2>
    <div style="font-size: 9.5pt; line-height: 1.6; margin-left: 15px; margin-bottom: 15px;">
        {!! renderRichText($laporan->tujuan) !!}
    </div>

    <h2 class="subbab-title">1.4 Ruang Lingkup Audit</h2>
    <div style="font-size: 9.5pt; line-height: 1.6; margin-left: 15px; margin-bottom: 15px;">
        {!! renderRichText($laporan->ruang_lingkup) !!}
    </div>

    <h2 class="subbab-title">1.5 Metode dan Mekanisme Audit</h2>
    <div style="font-size: 9.5pt; line-height: 1.6; margin-left: 15px; margin-bottom: 15px;">
        {!! renderRichText($laporan->metode) !!}
    </div>
</div>

<!-- ========================================================================= -->
<!-- 7. BAB II PELAKSANAAN AUDIT MUTU INTERNAL (Halaman Arab 3)                -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">BAB II<br>PELAKSANAAN AUDIT MUTU INTERNAL</h1>

    <h2 class="subbab-title">2.1 Jadwal Pelaksanaan AMI</h2>
    <p>
        Rangkaian tahapan Audit Mutu Internal (AMI) Tahun Akademik {{ $tahun }} diselenggarakan dengan memadukan agenda Bulan Mutu USS dan jadwal operasional audit sebagai berikut:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 2.1 Jadwal Pelaksanaan Audit Mutu Internal Tahun {{ $tahun }}
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 47%;">Tahapan Kegiatan</th>
                <th style="width: 25%;">Rentang Waktu</th>
                <th style="width: 20%;">Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @php $jadwalList = (array) ($laporan->jadwal_ami ?: []); @endphp
            @forelse($jadwalList as $i => $j)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td><b>{{ $j['kegiatan'] ?? '-' }}</b></td>
                    <td class="text-center">{{ $j['tanggal'] ?? '-' }}</td>
                    <td>{{ $j['penanggung_jawab'] ?? 'LPMA' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Jadwal kegiatan belum dikonfigurasi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="subbab-title">2.2 Tim Auditor dan Sasaran Unit Kerja</h2>
    <p>
        Berdasarkan Surat Keputusan Rektor, penugasan Tim Auditor Internal yang melaksanakan audit pada masing-masing unit kerja adalah sebagai berikut:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 2.2 Tim Auditor dan Sasaran Unit Kerja
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 34%;">Unit Kerja / Program Studi</th>
                <th style="width: 30%;">Auditor Ketua</th>
                <th style="width: 30%;">Auditor Anggota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auditorTeams as $idx => $team)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><b>{{ $team['unit']->nama }}</b><br><small style="color: #64748b;">{{ $team['unit']->jenis }}</small></td>
                    <td>{{ $team['ketua'] ? ($team['ketua']->nama_lengkap ?: $team['ketua']->name) : 'Lead Auditor' }}</td>
                    <td>
                        @if($team['anggota']->count() > 0)
                            @foreach($team['anggota'] as $ang)
                                &bull; {{ $ang->nama_lengkap ?: $ang->name }}<br>
                            @endforeach
                        @else
                            <span style="color:#64748b;">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="subbab-title">2.3 Status Pengisian Laporan Evaluasi Diri (LED)</h2>
    <p>
        Laporan Evaluasi Diri (LED) disusun mandiri oleh auditi pada sistem AMIRA sebagai bahan telaah awal desk evaluation sebelum visitasi lapangan dilakukan:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 2.3 Status Pengisian Laporan Evaluasi Diri (LED) per Unit Kerja
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 34%;">Unit Kerja</th>
                <th style="width: 12%;">Lengkap</th>
                <th style="width: 12%;">Draft</th>
                <th style="width: 12%;">Belum</th>
                <th style="width: 24%;">Status Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ledStatuses as $ls)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><b>{{ $ls['unit']->nama }}</b></td>
                    <td class="text-center font-bold" style="color: #16a34a;">{{ $ls['lengkap'] }}</td>
                    <td class="text-center" style="color: #d97706;">{{ $ls['draft'] }}</td>
                    <td class="text-center" style="color: #94a3b8;">{{ $ls['belum'] }}</td>
                    <td class="text-center">
                        <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                            {{ $ls['status'] }}
                        </span>
                        @if($ls['submitted_at'])
                            <br><small style="font-size: 7.5pt; color: #64748b;">({{ \Carbon\Carbon::parse($ls['submitted_at'])->format('d/m/Y') }})</small>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- ========================================================================= -->
<!-- 8. BAB III HASIL AUDIT MUTU INTERNAL (Halaman Arab 5)                     -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">BAB III<br>HASIL AUDIT MUTU INTERNAL</h1>

    <h2 class="subbab-title">3.1 Rekapitulasi Temuan Audit per Kategori</h2>
    <p>
        Dari seluruh unit kerja yang diaudit pada tahun akademik {{ $tahun }}, teridentifikasi total <b>{{ $totalFindings }} temuan</b> audit yang terdistribusi ke dalam kategori sebagai berikut:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 3.1 Rekapitulasi Temuan Audit Tingkat Universitas
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kategori Temuan</th>
                <th style="width: 20%;">Jumlah Temuan</th>
                <th style="width: 20%;">Persentase (%)</th>
                <th style="width: 40%;">Implikasi Mutu</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Sesuai (Kepatuhan Penuh)</b></td>
                <td class="text-center font-bold" style="color: #16a34a;">{{ $rekapNasional['Sesuai'] }}</td>
                <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['Sesuai'] / $totalFindings) * 100, 1) : 0 }}%</td>
                <td>Standar terpenuhi dan terbukti sahih mengikuti siklus PPEPP.</td>
            </tr>
            <tr>
                <td><b>Observasi (OB)</b></td>
                <td class="text-center font-bold" style="color: #0284c7;">{{ $rekapNasional['Observasi (OB)'] }}</td>
                <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['Observasi (OB)'] / $totalFindings) * 100, 1) : 0 }}%</td>
                <td>Potensi risiko masa depan; membutuhkan penyempurnaan SOP.</td>
            </tr>
            <tr>
                <td><b>Ketidaksesuaian Minor (KTS Minor)</b></td>
                <td class="text-center font-bold" style="color: #d97706;">{{ $rekapNasional['KTS Minor'] }}</td>
                <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['KTS Minor'] / $totalFindings) * 100, 1) : 0 }}%</td>
                <td>Penyimpangan administratif parsial tanpa mengganggu mutu utama.</td>
            </tr>
            <tr>
                <td><b>Ketidaksesuaian Mayor (KTS Mayor)</b></td>
                <td class="text-center font-bold" style="color: #dc2626;">{{ $rekapNasional['KTS Mayor'] }}</td>
                <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['KTS Mayor'] / $totalFindings) * 100, 1) : 0 }}%</td>
                <td>Penyimpangan signifikan / ketiadaan bukti pada butir wajib akreditasi.</td>
            </tr>
            <tr>
                <td><b>Peluang Peningkatan (OFI)</b></td>
                <td class="text-center font-bold" style="color: #7c3aed;">{{ $rekapNasional['Peluang Peningkatan (OFI)'] }}</td>
                <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['Peluang Peningkatan (OFI)'] / $totalFindings) * 100, 1) : 0 }}%</td>
                <td>Rekomendasi strategis untuk melampaui Standar Nasional (SN-Dikti).</td>
            </tr>
            <tr>
                <td><b>Temuan Positif (TP)</b></td>
                <td class="text-center font-bold" style="color: #0d9488;">{{ $rekapNasional['Temuan Positif (TP)'] }}</td>
                <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['Temuan Positif (TP)'] / $totalFindings) * 100, 1) : 0 }}%</td>
                <td>Praktik baik (best practice) yang patut dipertahankan dan ditularkan.</td>
            </tr>
            <tr style="background: #f8fafc; font-weight: bold;">
                <td class="text-right">TOTAL TEMUAN AUDIT</td>
                <td class="text-center" style="font-size: 10pt;">{{ $totalFindings }}</td>
                <td class="text-center">100.0%</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <div style="font-weight: bold; font-size: 9pt; margin-top: 15px; margin-bottom: 4px;">
        Tabel 3.2 Rekapitulasi Temuan Audit per Unit Kerja
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No</th>
                <th rowspan="2" style="width: 25%;">Unit Kerja</th>
                <th colspan="6">Kategori Temuan</th>
                <th rowspan="2" style="width: 10%;">Total</th>
                <th rowspan="2" style="width: 12%;">Skor Rata-Rata</th>
            </tr>
            <tr>
                <th style="width: 8%;">Sesuai</th>
                <th style="width: 8%;">OB</th>
                <th style="width: 8%;">Minor</th>
                <th style="width: 8%;">Mayor</th>
                <th style="width: 8%;">OFI</th>
                <th style="width: 8%;">TP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapPerUnit as $r)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><b>{{ $r['unit']->nama }}</b></td>
                    <td class="text-center font-bold" style="color: #16a34a;">{{ $r['counts']['Sesuai'] }}</td>
                    <td class="text-center" style="color: #0284c7;">{{ $r['counts']['Observasi (OB)'] }}</td>
                    <td class="text-center" style="color: #d97706;">{{ $r['counts']['KTS Minor'] }}</td>
                    <td class="text-center font-bold" style="color: #dc2626;">{{ $r['counts']['KTS Mayor'] }}</td>
                    <td class="text-center" style="color: #7c3aed;">{{ $r['counts']['Peluang Peningkatan (OFI)'] }}</td>
                    <td class="text-center" style="color: #0d9488;">{{ $r['counts']['Temuan Positif (TP)'] }}</td>
                    <td class="text-center font-bold" style="background: #f8fafc;">{{ $r['total'] }}</td>
                    <td class="text-center font-bold" style="background: #eff6ff; color: #1e40af;">{{ number_format($r['avg_score'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="subbab-title">3.2 Skor Rata-Rata Capaian Standar</h2>
    <p>
        Capaian pemenuhan standar dinilai dengan skala skor 1,00 s.d. 4,00 sesuai rubrik penilaian baku SPMI USS. Capaian per kelompok standar disajikan pada Tabel 3.4:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 3.4 Skor Rata-Rata Capaian per Kelompok Standar SPMI
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 52%;">Kelompok Standar SPMI</th>
                <th style="width: 20%;">Skor Capaian (1.00 - 4.00)</th>
                <th style="width: 20%;">Kategori Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapKelompokStandar as $kelompokName => $avgVal)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><b>{{ $kelompokName }}</b></td>
                    <td class="text-center font-bold" style="font-size: 9.5pt; color: #0284c7;">{{ number_format($avgVal, 2) }}</td>
                    <td class="text-center">
                        @if($avgVal >= 3.5)
                            <span class="badge" style="background: #dcfce7; color: #15803d;">Sangat Baik</span>
                        @elseif($avgVal >= 3.0)
                            <span class="badge" style="background: #eff6ff; color: #1d4ed8;">Baik</span>
                        @elseif($avgVal >= 2.0)
                            <span class="badge" style="background: #fef3c7; color: #b45309;">Cukup</span>
                        @else
                            <span class="badge" style="background: #fee2e2; color: #b91c1c;">Kurang</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="subbab-title">3.3 Sepuluh (10) Standar dengan Temuan KTS Terbanyak</h2>
    <p>
        Analisis Pareto atas butir standar yang paling sering mengalami ketidaksesuaian (KTS Mayor, KTS Minor, dan OB) guna dijadikan prioritas perbaikan pada siklus berikutnya:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 3.5 Sepuluh Standar dengan Temuan KTS Terbanyak
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 20%;">Kode Standar</th>
                <th style="width: 44%;">Nama / Butir Standar Mutu</th>
                <th style="width: 10%;">KTS Mayor</th>
                <th style="width: 10%;">KTS Minor</th>
                <th style="width: 10%;">Total KTS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topKtsStandards as $ts)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><b>{{ $ts['kode'] }}</b></td>
                    <td>{{ $ts['nama'] }}</td>
                    <td class="text-center font-bold" style="color: #dc2626;">{{ $ts['mayor'] }}</td>
                    <td class="text-center" style="color: #d97706;">{{ $ts['minor'] }}</td>
                    <td class="text-center font-bold" style="background: #f8fafc;">{{ $ts['total'] }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada temuan KTS yang tercatat.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ===================================================================== -->
    <!-- 3.4 TIGA GRAFIK ANALISIS HASIL AUDIT                                  -->
    <!-- ===================================================================== -->
    <h2 class="subbab-title">3.4 Analisis Grafik Hasil Audit</h2>
    <p>
        Representasi visual atas distribusi temuan dan capaian skor standar disajikan dalam tiga grafik berikut:
    </p>

    <!-- Grafik 1: Batang Bertumpuk Kategori Temuan per Unit -->
    <div class="chart-box">
        <div class="chart-title">Grafik 3.1 Komposisi Kategori Temuan per Unit Kerja (Batang Bertumpuk)</div>
        <div style="margin-bottom: 10px; font-size: 7.5pt; text-align: center;">
            <span style="display: inline-block; width: 12px; height: 8px; background: #16a34a; margin-right: 2px;"></span> Sesuai &nbsp;&nbsp;
            <span style="display: inline-block; width: 12px; height: 8px; background: #0284c7; margin-right: 2px;"></span> OB &nbsp;&nbsp;
            <span style="display: inline-block; width: 12px; height: 8px; background: #d97706; margin-right: 2px;"></span> KTS Minor &nbsp;&nbsp;
            <span style="display: inline-block; width: 12px; height: 8px; background: #dc2626; margin-right: 2px;"></span> KTS Mayor &nbsp;&nbsp;
            <span style="display: inline-block; width: 12px; height: 8px; background: #7c3aed; margin-right: 2px;"></span> OFI &nbsp;&nbsp;
            <span style="display: inline-block; width: 12px; height: 8px; background: #0d9488; margin-right: 2px;"></span> TP
        </div>

        @foreach($rekapPerUnit as $r)
            @php
                $tot = max(1, $r['total']);
                $pSesuai = ($r['counts']['Sesuai'] / $tot) * 100;
                $pOb = ($r['counts']['Observasi (OB)'] / $tot) * 100;
                $pMinor = ($r['counts']['KTS Minor'] / $tot) * 100;
                $pMayor = ($r['counts']['KTS Mayor'] / $tot) * 100;
                $pOfi = ($r['counts']['Peluang Peningkatan (OFI)'] / $tot) * 100;
                $pTp = ($r['counts']['Temuan Positif (TP)'] / $tot) * 100;
            @endphp
            <div class="bar-container">
                <div class="bar-label">
                    {{ $r['unit']->nama }} (Total: {{ $r['total'] }})
                </div>
                <div class="bar-track">
                    @if($pSesuai > 0)<div class="bar-fill" style="width: {{ $pSesuai }}%; background: #16a34a;"></div>@endif
                    @if($pOb > 0)<div class="bar-fill" style="width: {{ $pOb }}%; background: #0284c7;"></div>@endif
                    @if($pMinor > 0)<div class="bar-fill" style="width: {{ $pMinor }}%; background: #d97706;"></div>@endif
                    @if($pMayor > 0)<div class="bar-fill" style="width: {{ $pMayor }}%; background: #dc2626;"></div>@endif
                    @if($pOfi > 0)<div class="bar-fill" style="width: {{ $pOfi }}%; background: #7c3aed;"></div>@endif
                    @if($pTp > 0)<div class="bar-fill" style="width: {{ $pTp }}%; background: #0d9488;"></div>@endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Grafik 2: Batang Skor Rata-Rata per Unit -->
    <div class="chart-box">
        <div class="chart-title">Grafik 3.2 Perbandingan Skor Rata-Rata Capaian per Unit Kerja (Skala 1.00 - 4.00)</div>
        @foreach($rekapPerUnit as $r)
            @php
                $score = $r['avg_score'];
                $pScore = min(100, max(0, ($score / 4.0) * 100));
            @endphp
            <div class="bar-container">
                <div class="bar-label" style="display: flex; justify-content: space-between;">
                    <span>{{ $r['unit']->nama }}</span>
                    <span style="font-weight: bold; color: #1e40af;">{{ number_format($score, 2) }} / 4.00</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $pScore }}%; background: linear-gradient(to right, #38bdf8, #0284c7);"></div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Grafik 3: Batang Skor Rata-Rata per Kelompok Standar -->
    <div class="chart-box">
        <div class="chart-title">Grafik 3.3 Skor Rata-Rata Capaian per Kelompok Standar SPMI</div>
        @foreach($rekapKelompokStandar as $kName => $kScore)
            @php
                $pKScore = min(100, max(0, ($kScore / 4.0) * 100));
            @endphp
            <div class="bar-container">
                <div class="bar-label" style="display: flex; justify-content: space-between;">
                    <span>{{ $kName }}</span>
                    <span style="font-weight: bold; color: #0284c7;">{{ number_format($kScore, 2) }} / 4.00</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $pKScore }}%; background: #059669;"></div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ===================================================================== -->
    <!-- 3.5 DAFTAR RINCIAN TEMUAN NON-SESUAI                                  -->
    <!-- ===================================================================== -->
    <h2 class="subbab-title">3.5 Daftar Rincian Seluruh Temuan Non-Sesuai</h2>
    <p>
        Rincian temuan yang membutuhkan tindak lanjut korektif maupun observasi perbaikan (tidak termasuk kategori Sesuai) disajikan pada Tabel 3.6:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 3.6 Daftar Rincian Seluruh Temuan Non-Sesuai
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 18%;">Unit Kerja</th>
                <th style="width: 15%;">Kode Standar</th>
                <th style="width: 8%;">Tahap</th>
                <th style="width: 32%;">Uraian Temuan Audit</th>
                <th style="width: 14%;">Kategori</th>
                <th style="width: 8%;">Skor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nonSesuaiFindings as $f)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><b>{{ $f->unit->nama ?? 'Unit' }}</b></td>
                    <td>{{ $f->standar_kode }}</td>
                    <td class="text-center">{{ $f->tahap }}</td>
                    <td>{{ $f->uraian }}</td>
                    <td class="text-center">
                        @if($f->kategori_temuan == 'KTS Mayor')
                            <span class="badge badge-mayor">KTS Mayor</span>
                        @elseif($f->kategori_temuan == 'KTS Minor')
                            <span class="badge badge-minor">KTS Minor</span>
                        @elseif($f->kategori_temuan == 'Observasi (OB)')
                            <span class="badge badge-ob">Observasi</span>
                        @elseif($f->kategori_temuan == 'Peluang Peningkatan (OFI)')
                            <span class="badge badge-ofi">OFI</span>
                        @else
                            <span class="badge badge-tp">TP</span>
                        @endif
                    </td>
                    <td class="text-center font-bold">{{ $f->skor ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Tidak ada temuan non-sesuai. Seluruh standar terpenuhi secara memuaskan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ========================================================================= -->
<!-- 9. BAB IV TINDAK LANJUT HASIL AUDIT (Halaman Arab 10)                     -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">BAB IV<br>TINDAK LANJUT HASIL AUDIT (RTL)</h1>

    <h2 class="subbab-title">4.1 Status Penyelesaian Rencana Tindak Lanjut</h2>
    <p>
        Setiap temuan audit yang teridentifikasi disepakati bersama auditi untuk disusunkan Rencana Tindak Lanjut (RTL) yang mencakup tindakan koreksi, penanggung jawab (PIC), dan target batas waktu penyelesaian. Rekapitulasi progres penyelesaian RTL per unit kerja disajikan pada Tabel 4.1:
    </p>

    <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
        Tabel 4.1 Rekapitulasi Status Rencana Tindak Lanjut (RTL) per Unit Kerja
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 30%;">Unit Kerja</th>
                <th style="width: 12%;">Selesai</th>
                <th style="width: 12%;">Proses</th>
                <th style="width: 12%;">PTK</th>
                <th style="width: 12%;">Belum</th>
                <th style="width: 16%;">% Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rtlStatuses as $r)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><b>{{ $r['unit']->nama }}</b></td>
                    <td class="text-center font-bold" style="color: #16a34a;">{{ $r['selesai'] }}</td>
                    <td class="text-center" style="color: #0284c7;">{{ $r['proses'] }}</td>
                    <td class="text-center" style="color: #7c3aed;">{{ $r['ptk'] }}</td>
                    <td class="text-center" style="color: #dc2626;">{{ $r['belum'] }}</td>
                    <td class="text-center font-bold" style="background: #eff6ff; color: #1e40af;">
                        {{ $r['persentase'] }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="subbab-title">4.2 Persentase dan Efektivitas RTL</h2>
    <p>
        Tingkat penyelesaian Rencana Tindak Lanjut (RTL) secara keseluruhan mencapai <b>{{ $overallRtlPct }}%</b>. Tingginya angka ketercapaian ini mencerminkan komitmen kuat dari seluruh pimpinan auditi dalam memperbaiki sistem tata kelola dan menuntaskan akar permasalahan temuan audit sebelum siklus audit tahun berikutnya.
    </p>

    <h2 class="subbab-title">4.3 Daftar RTL yang Melewati Batas Waktu</h2>
    @if($totalRtlLewatWaktu == 0)
        <p style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px; border-radius: 4px; color: #15803d;">
            <b>Catatan Positif :</b> Tidak terdapat Rencana Tindak Lanjut (RTL) yang melewati batas waktu penyelesaian pada tahun audit {{ $tahun }}. Seluruh temuan telah berhasil diselesaikan (100% Selesai) tepat waktu sebelum batas akhir tahun akademik.
        </p>
    @else
        <div style="font-weight: bold; font-size: 9pt; margin-bottom: 4px;">
            Tabel 4.2 Daftar RTL yang Melewati Batas Waktu
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;">No</th>
                    <th style="width: 24%;">Unit Kerja</th>
                    <th style="width: 18%;">Kode Standar</th>
                    <th style="width: 32%;">Uraian RTL</th>
                    <th style="width: 20%;">Batas Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($overdueList as $od)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $od->unit->nama ?? '-' }}</td>
                        <td>{{ $od->standar_kode }}</td>
                        <td>{{ $od->rencana_tindakan }}</td>
                        <td class="text-center font-bold" style="color: #dc2626;">{{ $od->batas_waktu }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- ========================================================================= -->
<!-- 10. BAB V KESIMPULAN DAN REKOMENDASI (Halaman Arab 12)                    -->
<!-- ========================================================================= -->
<div class="page-break"></div>
<div>
    <h1 class="bab-title">BAB V<br>KESIMPULAN DAN REKOMENDASI</h1>

    <h2 class="subbab-title">5.1 Kesimpulan Umum</h2>
    <p>
        Berdasarkan hasil Audit Mutu Internal (AMI) Tahun Akademik {{ $tahun }} yang dilaksanakan terhadap <b>{{ count($targetUnits) }} unit kerja</b> dan <b>{{ count($standards) }} butir standar mutu SPMI</b>, diperoleh kesimpulan komprehensif sebagai berikut:
    </p>
    <div style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 9.5pt; line-height: 1.6;">
        <b>Ringkasan Kuantitatif Temuan AMI {{ $tahun }} :</b>
        <ul style="margin: 6px 0 0 15px; padding-left: 0;">
            <li>Total temuan yang diaudit: <b>{{ $totalFindings }} butir standar</b>.</li>
            <li>Standar dengan status <b>Sesuai</b> (Kepatuhan Penuh): <b>{{ $rekapNasional['Sesuai'] }} temuan</b> ({{ $totalFindings > 0 ? round(($rekapNasional['Sesuai'] / $totalFindings) * 100, 1) : 0 }}%).</li>
            <li>Temuan <b>Ketidaksesuaian Mayor (KTS Mayor)</b>: <b>{{ $rekapNasional['KTS Mayor'] }} temuan</b>.</li>
            <li>Temuan <b>Ketidaksesuaian Minor (KTS Minor)</b>: <b>{{ $rekapNasional['KTS Minor'] }} temuan</b>.</li>
            <li>Temuan <b>Observasi (OB)</b>: <b>{{ $rekapNasional['Observasi (OB)'] }} temuan</b>.</li>
            <li>Persentase penyelesaian Rencana Tindak Lanjut (RTL): <b>{{ $overallRtlPct }}% Tuntas (Selesai)</b>.</li>
        </ul>
    </div>

    <div style="font-size: 9.5pt; line-height: 1.6; text-align: justify; margin-bottom: 20px;">
        {!! renderRichText($laporan->kesimpulan) !!}
    </div>

    <h2 class="subbab-title">5.2 Rekomendasi untuk Rapat Tinjauan Manajemen (RTM) dan Pimpinan</h2>
    <p>
        Untuk menindaklanjuti temuan audit dan mewujudkan continuous quality improvement, Tim Auditor dan LPMA merekomendasikan poin-poin strategis berikut kepada Pimpinan Universitas dan jajaran UPPS:
    </p>
    <div style="font-size: 9.5pt; line-height: 1.6; margin-left: 15px; margin-bottom: 20px;">
        {!! renderRichText($laporan->rekomendasi_pimpinan) !!}
    </div>

    <div style="margin-top: 50px; text-align: center; page-break-inside: avoid;">
        <p style="font-size: 9.5pt; margin-bottom: 25px;">
            Palembang, {{ $tglTerbitFormatted }}<br>
            <b>Lembaga Penjaminan Mutu Akademik (LPMA)</b><br>
            Universitas Sumatera Selatan
        </p>

        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; text-align: center; border: none;">
                    Kepala LPMA Universitas Sumatera Selatan,<br><br><br><br>
                    <u><b>{{ $laporan->penandatangan_lpma_nama ?: 'Dr. Hadi Syaputra, S.Kom., M.Kom' }}</b></u><br>
                    NIDN: {{ $laporan->penandatangan_lpma_nidn ?: '0214057801' }}
                </td>
                <td style="width: 50%; text-align: center; border: none;">
                    Rektor USS,<br><br><br><br>
                    <u><b>{{ $laporan->penandatangan_rektor_nama ?: 'Prof. Dr. Ir. H. M. Syahril, M.S.' }}</b></u><br>
                    NIDN: {{ $laporan->penandatangan_rektor_nidn ?: '0015086002' }}
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 11. LAMPIRAN (Halaman Arab 13+)                                           -->
<!-- ========================================================================= -->
@php
    $lampOptions = (array) ($laporan->lampiran_options ?: ['form4', 'form5', 'daftar_hadir', 'standar']);
@endphp

@if(in_array('form4', $lampOptions) || in_array('form5', $lampOptions) || in_array('daftar_hadir', $lampOptions) || in_array('standar', $lampOptions))
<div class="page-break"></div>
<div>
    <h1 class="bab-title">LAMPIRAN DOKUMEN AUDIT</h1>

    @if(in_array('form4', $lampOptions))
        <h2 class="subbab-title">Lampiran 1: Form 4 Ringkasan Temuan Audit (KKA)</h2>
        <p>
            Ringkasan temuan audit yang ditandatangani oleh Auditi, Ketua Auditor, dan Penjamin Mutu LPMA:
        </p>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;">No</th>
                    <th style="width: 24%;">Unit Kerja</th>
                    <th style="width: 18%;">No KKA</th>
                    <th style="width: 32%;">Deskripsi Singkat Temuan</th>
                    <th style="width: 20%;">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nonSesuaiFindings as $f)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><b>{{ $f->unit->nama ?? '-' }}</b></td>
                        <td>KKA-{{ $tahun }}-{{ sprintf('%02d', $loop->iteration) }}</td>
                        <td>{{ $f->uraian }}</td>
                        <td class="text-center font-bold">{{ $f->kategori_temuan }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Tidak ada temuan ketidaksesuaian pada Form 4.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    @if(in_array('form5', $lampOptions))
        <h2 class="subbab-title" style="margin-top: 30px;">Lampiran 2: Form 5 Deskripsi Temuan Audit & RTL</h2>
        <p>
            Rincian matriks akar penyebab (RCA), rencana perbaikan, dan jadwal pencegahan untuk setiap temuan ketidaksesuaian:
        </p>

        @foreach($nonSesuaiFindings as $f)
            <div style="border: 1px solid #334155; padding: 10px; margin-bottom: 12px; font-size: 8.5pt; page-break-inside: avoid;">
                <div style="font-weight: bold; font-size: 9pt; color: #0284c7; margin-bottom: 6px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 4px;">
                    Temuan No. {{ $loop->iteration }} — {{ $f->standar_kode }} [{{ $f->kategori_temuan }}] — {{ $f->unit->nama ?? 'Unit' }}
                </div>
                <div><b>Deskripsi Temuan:</b> {{ $f->uraian }}</div>
                <div><b>Akar Masalah:</b> {{ $f->akar_penyebab ?: '-' }}</div>
                <div><b>Rencana Perbaikan:</b> {{ $f->rencana_tindakan ?: '-' }} (PIC: {{ $f->pic ?: '-' }}, Target: {{ $f->batas_waktu ?: '-' }})</div>
                <div><b>Jadwal Pencegahan:</b> {{ $f->jadwal_pencegahan ?: '-' }}</div>
                <div><b>Status Verifikasi:</b> <span style="font-weight: bold; color: #16a34a;">{{ $f->status_tindak_lanjut ?: 'Selesai' }}</span></div>
            </div>
        @endforeach
    @endif

    @if(in_array('daftar_hadir', $lampOptions))
        <div class="page-break"></div>
        <h2 class="subbab-title" style="margin-top: 25px;">Lampiran 3: Daftar Hadir & Berita Acara RTM</h2>
        <p>
            Berita Acara dan Daftar Hadir pelaksanaan Rapat Tinjauan Manajemen (RTM) serta ekspose hasil Audit Mutu Internal Tahun {{ $tahun }}:
        </p>

        <!-- Berita Acara Box -->
        <table class="data-table" style="margin-bottom: 20px;">
            <tr>
                <td style="width: 25%; font-weight: bold; background: #f1f5f9;">Hari / Tanggal</td>
                <td style="width: 75%;">{{ $tglAuditFormatted }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; background: #f1f5f9;">Waktu & Tempat</td>
                <td>09.00 WIB s.d. Selesai — Ruang Rapat Rektorat / Kampus Universitas Sumatera Selatan</td>
            </tr>
            <tr>
                <td style="font-weight: bold; background: #f1f5f9;">Agenda Rapat</td>
                <td>Penyampaian Laporan Hasil Audit Mutu Internal (AMI) Tahun Akademik {{ $tahun }} dan Penandatanganan Kesepakatan RTL</td>
            </tr>
            <tr>
                <td style="font-weight: bold; background: #f1f5f9;">Pimpinan Rapat</td>
                <td>{{ $laporan->penandatangan_rektor_nama ?: 'Rektor Universitas Sumatera Selatan' }} & {{ $laporan->penandatangan_lpma_nama ?: 'Kepala LPMA' }}</td>
            </tr>
        </table>

        <!-- Daftar Hadir Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;">No</th>
                    <th style="width: 32%;">Nama Lengkap</th>
                    <th style="width: 26%;">Jabatan / Unit Kerja</th>
                    <th style="width: 18%;">Peran</th>
                    <th style="width: 18%;">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @php $noHadir = 1; @endphp
                @if($laporan->penandatangan_rektor_nama)
                    <tr>
                        <td class="text-center">{{ $noHadir++ }}</td>
                        <td><b>{{ $laporan->penandatangan_rektor_nama }}</b></td>
                        <td>Rektorat USS</td>
                        <td>Pimpinan Rapat</td>
                        <td class="text-center" style="font-size: 8pt; color: #16a34a;"><i>(Tertanda)</i></td>
                    </tr>
                @endif
                @if($laporan->penandatangan_lpma_nama)
                    <tr>
                        <td class="text-center">{{ $noHadir++ }}</td>
                        <td><b>{{ $laporan->penandatangan_lpma_nama }}</b></td>
                        <td>LPMA USS</td>
                        <td>Penyelenggara</td>
                        <td class="text-center" style="font-size: 8pt; color: #16a34a;"><i>(Tertanda)</i></td>
                    </tr>
                @endif
                @foreach($targetUnits as $u)
                    @php $uTeam = $auditorTeams[$u->id] ?? null; @endphp
                    @if($uTeam && $uTeam['ketua'])
                        <tr>
                            <td class="text-center">{{ $noHadir++ }}</td>
                            <td>{{ $uTeam['ketua']->nama_lengkap ?: $uTeam['ketua']->name }}</td>
                            <td>Tim Auditor Mutu Internal</td>
                            <td>Auditor Ketua ({{ $u->nama }})</td>
                            <td class="text-center" style="font-size: 8pt; color: #16a34a;"><i>(Tertanda)</i></td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-center">{{ $noHadir++ }}</td>
                        <td>{{ $u->pimpinan ?: 'Pimpinan ' . $u->nama }}</td>
                        <td>{{ $u->nama }}</td>
                        <td>Auditi / Kepala Unit</td>
                        <td class="text-center" style="font-size: 8pt; color: #16a34a;"><i>(Tertanda)</i></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(in_array('standar', $lampOptions))
        <h2 class="subbab-title" style="margin-top: 30px;">Lampiran 4: Daftar Standar Mutu SPMI USS yang Diaudit</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 25%;">Kode Standar</th>
                    <th style="width: 42%;">Nama Standar Mutu</th>
                    <th style="width: 25%;">Kelompok Standar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($standards->take(50) as $s)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><b>{{ $s->kode }}</b></td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->kelompok }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endif

<!-- ========================================================================= -->
<!-- SKRIP EMBEDDED CANVAS DOMPDF UNTUK NOMOR HALAMAN (Romawi & Arab)          -->
<!-- ========================================================================= -->
<script type="text/php">
if (isset($pdf)) {
    $pdf->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        if ($pageNumber == 1) {
            return;
        }

        $font = $fontMetrics->get_font("Helvetica", "normal");
        $size = 8.5;
        $color = [0.4, 0.45, 0.5]; // Slate color

        // Header halaman (halaman 2 ke atas)
        $headerText = "Lembaga Penjaminan Mutu Akademik (LPMA) — Universitas Sumatera Selatan";
        $canvas->text(113, 35, $headerText, $font, 7.5, $color);
        $canvas->line(113, 45, 510, 45, [0.8, 0.85, 0.9], 0.75);

        // Footer & Nomor Halaman
        // Halaman 2 sampai 5 adalah Front Matter (Lembar Pengesahan s.d. Daftar Gambar) -> Angka Romawi
        if ($pageNumber >= 2 && $pageNumber <= 5) {
            $romans = [2 => 'ii', 3 => 'iii', 4 => 'iv', 5 => 'v'];
            $footerText = isset($romans[$pageNumber]) ? $romans[$pageNumber] : 'ii';
        } else {
            // Halaman 6 ke atas adalah Isi (Bab I ke atas) -> Angka Arab (dimulai dari 1)
            $footerText = (string) ($pageNumber - 5);
        }

        $textWidth = $fontMetrics->get_text_width($footerText, $font, $size);
        $canvas->text(510 - $textWidth, 800, $footerText, $font, $size, $color);

        // Footer Dokumen Kiri
        $docCode = "Laporan AMI USS Tahun {{ $tahun }}";
        $canvas->text(113, 800, $docCode, $font, 7.5, $color);
        $canvas->line(113, 792, 510, 792, [0.8, 0.85, 0.9], 0.75);
    });
}
</script>

</body>
</html>
