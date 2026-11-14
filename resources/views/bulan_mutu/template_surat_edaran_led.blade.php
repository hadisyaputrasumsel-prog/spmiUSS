<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Edaran Pengisian Evaluasi Diri (LED) - Siklus SPMI {{ $tahun }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            color: #000; 
            line-height: 1.45; 
            margin: 25px 35px; 
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-justify { text-align: justify; }
        
        /* Header Kop Surat */
        .header { margin-bottom: 15px; }
        .header-title { font-size: 13pt; font-weight: bold; text-transform: uppercase; line-height: 1.25; }
        .header-sub { font-size: 10pt; color: #333; margin-top: 3px; }
        .border-kop { border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 8px; }
        
        .letter-number { 
            text-align: center; 
            font-weight: bold; 
            text-decoration: underline; 
            font-size: 12pt; 
            letter-spacing: 0.5px;
        }
        
        .content { 
            margin-top: 15px; 
            margin-bottom: 20px; 
            text-align: justify; 
        }
        
        table { border-collapse: collapse; }
        table.table-bordered { 
            width: 100%; 
            margin-top: 12px; 
            margin-bottom: 15px; 
            font-size: 10pt; 
        }
        table.table-bordered th, table.table-bordered td { 
            border: 1px solid #000; 
            padding: 5px 8px; 
        }
        table.table-bordered th { 
            background-color: #f2f2f2; 
            text-align: center; 
            font-weight: bold; 
        }
        
        .signature-box { 
            width: 100%; 
            margin-top: 35px; 
            page-break-inside: avoid; 
        }
        .signature-box td { 
            border: none; 
            padding: 4px; 
            vertical-align: top; 
        }
        .signature-space { height: 70px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- KOP SURAT LPMA USS -->
    <div class="header text-center border-kop">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan | Email: lpma@universitassumsel.ac.id</div>
    </div>

    <!-- NOMOR DAN JUDUL SURAT EDARAN -->
    <div class="letter-number">SURAT EDARAN</div>
    <div class="text-center" style="margin-top: 4px; margin-bottom: 12px; font-size: 11pt;">
        Nomor: {{ $nomorSurat }}
    </div>

    <div class="text-center text-bold" style="font-size: 11pt; margin-bottom: 20px; text-transform: uppercase;">
        TENTANG<br>
        PENGISIAN LAPORAN EVALUASI DIRI (LED) DAN PENGUNGGAHAN DOKUMEN BUKTI SIKLUS PPEPP<br>
        DALAM RANGKA AUDIT MUTU INTERNAL (AMI) SIKLUS TAHUN {{ $tahun }}
    </div>

    <!-- TUJUAN SURAT -->
    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <td style="width: 90px; vertical-align: top;"><strong>Kepada Yth.</strong></td>
            <td style="width: 15px; vertical-align: top;">:</td>
            <td style="vertical-align: top;">
                1. Para Dekan Fakultas<br>
                2. Para Ketua Program Studi<br>
                3. Para Kepala Biro / Lembaga / Unit Kerja Penunjang<br>
                di lingkungan Universitas Sumatera Selatan<br>
                di -<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<strong>Tempat</strong>
            </td>
        </tr>
    </table>

    <!-- ISI SURAT EDARAN -->
    <div class="content">
        <p>Dengan hormat,</p>
        <p>
            Dalam rangka penjaminan dan peningkatan mutu pendidikan tinggi secara berkelanjutan (<em>Continuous Quality Improvement</em>) serta implementasi Siklus Sistem Penjaminan Mutu Internal (SPMI) berbasis <strong>PPEPP</strong> (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan) sesuai agenda <strong>Bulan Mutu Universitas Sumatera Selatan Tahun {{ $tahun }}</strong>, bersama ini Lembaga Penjaminan Mutu Akademik (LPMA) menginstruksikan kepada seluruh Pimpinan Unit Pengelola Program Studi (UPPS), Program Studi, dan Unit Kerja Non-Akademik hal-hal sebagai berikut:
        </p>

        <ol style="margin-top: 8px; margin-bottom: 12px; padding-left: 22px; line-height: 1.5;">
            <li style="margin-bottom: 8px;">
                <strong>Kewajiban Pengisian Laporan Evaluasi Diri (LED):</strong><br>
                Seluruh Unit Kerja / Program Studi wajib melaksanakan pengisian instrumen Laporan Evaluasi Diri (LED) secara mandiri, objektif, dan komprehensif atas ketercapaian seluruh kriteria Standar SPMI USS (Pendidikan, Penelitian, Pengabdian kepada Masyarakat, serta Standar Tata Pamong dan Pengelolaan).
            </li>
            <li style="margin-bottom: 8px;">
                <strong>Penggunaan Aplikasi AMIRA:</strong><br>
                Proses pengisian instrumen LED dan penilaian mandiri dilakukan secara daring melalui sistem informasi <strong>AMIRA (Aplikasi Monitoring & Evaluasi Mutu Internal)</strong> pada modul <em>Laporan Evaluasi Diri (LED)</em> menggunakan akun resmi unit masing-masing.
            </li>
            <li style="margin-bottom: 8px;">
                <strong>Kelengkapan Dokumen Bukti Sahih (Evidence PPEPP):</strong><br>
                Setiap butir standar yang dilaporkan wajib dilengkapi dengan dokumen bukti sahih (tautan URL atau berkas digital), yang mencakup bukti penetapan standar, bukti realisasi pelaksanaan, hasil evaluasi berkala, serta dokumen tindak lanjut perbaikan mutu sebelumnya.
            </li>
            <li style="margin-bottom: 8px;">
                <strong>Jadwal dan Batas Waktu Pengisian:</strong><br>
                Pengisian instrumen LED dan pengunggahan berkas bukti dukung dimulai pada tanggal <strong>{{ $tanggalMulai }}</strong> dan batas akhir (<em>cut-off</em>) penutupan sistem adalah tanggal <strong>{{ $tanggalSelesai }}</strong> pukul 23.59 WIB. Berkas LED yang telah difinalisasi akan dikunci oleh sistem untuk keperluan penelaahan auditor.
            </li>
            <li style="margin-bottom: 8px;">
                <strong>Audit Dokumen (Desk Evaluation) & Visitasi Lapangan:</strong><br>
                Laporan Evaluasi Diri yang telah difinalisasi akan menjadi basis data utama bagi Tim Auditor Mutu Internal dalam melaksanakan Evaluasi Dokumen (<em>Desk Evaluation</em>) sebelum dilaksanakannya visitasi/audit lapangan (AMAI).
            </li>
        </ol>

        <p>
            Demikian Surat Edaran ini disampaikan untuk dipedomani dan dilaksanakan dengan penuh komitmen dan tanggung jawab demi terselenggaranya budaya mutu yang unggul di lingkungan Universitas Sumatera Selatan. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.
        </p>
    </div>

    <!-- TANDA TANGAN & TEMBUSAN -->
    <table class="signature-box">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 10pt; line-height: 1.35;">
                    <strong>Tembusan Yth.:</strong><br>
                    1. Rektor Universitas Sumatera Selatan (sebagai laporan)<br>
                    2. Para Wakil Rektor Universitas Sumatera Selatan<br>
                    3. Para Pimpinan Unit Kerja<br>
                    4. Arsip LPMA
                </div>
            </td>
            <td style="width: 50%; text-align: right; padding-right: 20px;">
                <div>Ditetapkan di : Palembang</div>
                <div>Pada tanggal : {{ $tanggalSurat }}</div>
                <div style="height: 8px;"></div>
                <div><strong>Kepala Lembaga Penjaminan Mutu Akademik</strong></div>
                <div><strong>Universitas Sumatera Selatan,</strong></div>
                <div class="signature-space"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
                <div>NIDN / NIP: ............................................</div>
            </td>
        </tr>
    </table>

    <!-- HALAMAN 2: LAMPIRAN DAFTAR UNIT SASARAN -->
    <div class="page-break"></div>

    <div class="header text-center border-kop">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
    </div>

    <div style="font-size: 10pt; margin-bottom: 10px;">
        <strong>LAMPIRAN SURAT EDARAN KEPALA LPMA UNIVERSITAS SUMATERA SELATAN</strong><br>
        Nomor &nbsp;: {{ $nomorSurat }}<br>
        Tanggal : {{ $tanggalSurat }}<br>
        Tentang : Daftar Unit Kerja & Program Studi Sasaran Pengisian Evaluasi Diri (LED) Siklus {{ $tahun }}
    </div>

    <div class="text-center text-bold" style="font-size: 11pt; margin-top: 15px; margin-bottom: 15px;">
        DAFTAR UNIT KERJA SASARAN PENGISIAN LAPORAN EVALUASI DIRI (LED)<br>
        SIKLUS BULAN MUTU TAHUN {{ $tahun }}
    </div>

    <table class="table-bordered">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 44%;">Nama Unit / Program Studi</th>
                <th style="width: 20%;">Kategori Unit</th>
                <th style="width: 30%;">Penanggung Jawab (Auditee)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($units as $u)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td><strong>{{ $u->nama }}</strong></td>
                <td class="text-center">{{ ucfirst($u->jenis ?? 'Akademik') }}</td>
                <td>Pimpinan Unit / Tim LED</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-box" style="margin-top: 25px;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: right; padding-right: 20px;">
                <div>Palembang, {{ $tanggalSurat }}</div>
                <div><strong>Kepala LPMA USS,</strong></div>
                <div class="signature-space"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
            </td>
        </tr>
    </table>

</body>
</html>
