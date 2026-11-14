<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Standar Mutu SPMI — {{ $periode->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 18mm 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        /* Kop Surat USS */
        .kop-table {
            border-bottom: 2.5px double #0f172a;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 2px 4px;
        }
        .kop-center {
            text-align: center;
        }
        .kop-univ {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-lpma {
            font-size: 11pt;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        .kop-address {
            font-size: 8pt;
            color: #475569;
        }

        /* Banner Judul Buku */
        .banner-buku {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-left: 5px solid #0284c7;
            padding: 8px 12px;
            margin-bottom: 14px;
        }
        .banner-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin: 0;
        }
        .banner-subtitle {
            font-size: 9.5pt;
            color: #334155;
            margin-top: 3px;
        }

        /* Metadata Box */
        .meta-table {
            margin-bottom: 16px;
            border: 1px solid #cbd5e1;
            font-size: 8.5pt;
        }
        .meta-table td {
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .meta-label {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
            width: 25%;
        }

        /* Section Header */
        .section-header {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 1.5px solid #0284c7;
            padding-bottom: 4px;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        /* Rekap Table */
        .rekap-table {
            border: 1px solid #cbd5e1;
            font-size: 8pt;
            margin-bottom: 16px;
        }
        .rekap-table th {
            background-color: #0284c7;
            color: #ffffff;
            font-weight: bold;
            padding: 5px 6px;
            border: 1px solid #0284c7;
            text-align: center;
        }
        .rekap-table td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .rekap-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Card Standar Mutu Detail */
        .standar-card {
            border: 1px solid #cbd5e1;
            margin-bottom: 16px;
            page-break-inside: avoid;
            background: #ffffff;
        }
        .standar-header {
            background-color: #0f172a;
            color: #ffffff;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 9.5pt;
        }
        .standar-header .badge-kelompok {
            float: right;
            background-color: #0284c7;
            color: #ffffff;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: normal;
        }
        .standar-body {
            padding: 8px 10px;
        }

        /* Detail Tables Inside Standar */
        .detail-table {
            border: 1px solid #e2e8f0;
            margin-bottom: 8px;
            font-size: 8.5pt;
        }
        .detail-table td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .detail-label {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            width: 28%;
        }

        /* Indikator & Target Box */
        .box-indikator {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #10b981;
            padding: 6px 8px;
            margin-bottom: 8px;
            font-size: 8.5pt;
        }
        .box-title {
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
            font-size: 8.5pt;
        }

        /* Tabel PPEPP */
        .table-ppepp {
            border: 1px solid #cbd5e1;
            font-size: 8pt;
            margin-bottom: 8px;
        }
        .table-ppepp th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        .table-ppepp td {
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        .tahap-badge {
            font-weight: bold;
            color: #0284c7;
            width: 22%;
            background-color: #f8fafc;
        }

        /* Tabel Rubrik Matriks */
        .table-rubrik {
            border: 1px solid #cbd5e1;
            font-size: 8pt;
            margin-top: 6px;
        }
        .table-rubrik th {
            background-color: #0284c7;
            color: #ffffff;
            font-weight: bold;
            padding: 4px 6px;
            border: 1px solid #0284c7;
            text-align: center;
        }
        .table-rubrik td {
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        .skor-col {
            font-weight: bold;
            text-align: center;
            width: 14%;
        }
        .skor-4 { background-color: #ecfdf5; color: #065f46; }
        .skor-3 { background-color: #eff6ff; color: #1e40af; }
        .skor-2 { background-color: #fefce8; color: #854d0e; }
        .skor-1 { background-color: #fef2f2; color: #991b1b; }

        .page-break {
            page-break-after: always;
        }

        /* Tanda Tangan */
        .signature-table {
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            width: 50%;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT LPMA UNIVERSITAS SUMATERA SELATAN -->
    <table class="kop-table">
        <tr>
            <td class="kop-center">
                <div class="kop-univ">UNIVERSITAS SUMATERA SELATAN</div>
                <div class="kop-lpma">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
                <div class="kop-address">Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan | Email: lpma@universitassumsel.ac.id | Website: https://lpma.universitassumsel.ac.id</div>
            </td>
        </tr>
    </table>

    <!-- BANNER BUKU STANDAR -->
    <div class="banner-buku">
        <div class="banner-title">BUKU DOKUMEN STANDAR SPMI & MATRIKS PENILAIAN</div>
        <div class="banner-subtitle">
            INSTRUMEN ACUAN: <strong>{{ strtoupper($periode->nama) }}</strong> 
            {{ $periode->is_active ? '(Status: Instrumen Aktif Berlaku)' : '(Status: Arsip Dokumen Instrumen)' }}
        </div>
    </div>

    <!-- METADATA DOKUMEN -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Instrumen Standar</td>
            <td style="width: 30%;"><strong>{{ $periode->nama }}</strong></td>
            <td class="meta-label">Tanggal Terbit / Cetak</td>
            <td style="width: 25%;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Total Standar Mutu</td>
            <td><strong>{{ $standards->count() }} Standar</strong> ({{ $groupedStandards->count() }} Kelompok)</td>
            <td class="meta-label">Status Instrumen</td>
            <td>
                @if($periode->is_active)
                    <strong style="color: #10b981;">Aktif / Berlaku Saat Ini</strong>
                @else
                    <span style="color: #64748b;">Arsip Standar Mutu</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="meta-label">Kelompok Terdaftar</td>
            <td colspan="3">{{ $groupedStandards->keys()->implode(' • ') }}</td>
        </tr>
    </table>

    <!-- SECTION I: REKAPITULASI DAFTAR STANDAR MUTU -->
    <div class="section-header">BAGIAN I. REKAPITULASI STANDAR MUTU SPMI</div>
    <p style="font-size: 8.5pt; color: #475569; margin-top: 0; margin-bottom: 8px;">
        Berikut adalah daftar inventaris seluruh standar mutu yang dimuat dalam instrumen <strong>{{ $periode->nama }}</strong>:
    </p>

    <table class="rekap-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 17%;">Kode Standar</th>
                <th style="width: 35%;">Nama Standar Mutu</th>
                <th style="width: 23%;">Kelompok</th>
                <th style="width: 12%;">Sifat</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($standards as $std)
            <tr>
                <td style="text-align: center;">{{ $no++ }}</td>
                <td style="font-weight: bold; color: #0284c7;">{{ $std->kode }}</td>
                <td><strong>{{ $std->nama }}</strong></td>
                <td>{{ $std->kelompok ?: 'Tanpa Kelompok' }}</td>
                <td style="text-align: center;">{{ $std->is_akademik ? 'Akademik' : 'Non-Akademik' }}</td>
                <td style="text-align: center;">
                    <span style="color: {{ $std->is_active ? '#059669' : '#dc2626' }}; font-weight: bold;">
                        {{ $std->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #64748b; padding: 12px;">Belum ada standar mutu yang terdaftar pada instrumen ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- SECTION II: DETAIL SPESIFIKASI DAN MATRIKS PENILAIAN -->
    <div class="section-header" style="margin-top: 0;">BAGIAN II. DETAIL SPESIFIKASI STANDAR & MATRIKS PENILAIAN PPEPP</div>
    <p style="font-size: 8.5pt; color: #475569; margin-top: 0; margin-bottom: 12px;">
        Memuat rumusan pernyataan standar, indikator, target capaian, kriteria pelaksanaan audit 5 tahap (PPEPP), serta matriks rubrik penilaian mutu (Skor 4 s.d. 1) untuk setiap standar.
    </p>

    @foreach($groupedStandards as $kelompokNama => $stdGroup)
        <div style="background-color: #0284c7; color: #ffffff; padding: 5px 8px; font-weight: bold; font-size: 9pt; margin-top: 10px; margin-bottom: 10px; text-transform: uppercase;">
            Kelompok: {{ $kelompokNama }} ({{ count($stdGroup) }} Standar)
        </div>

        @foreach($stdGroup as $s)
        <div class="standar-card">
            <div class="standar-header">
                {{ $s->kode }} — {{ $s->nama }}
                <span class="badge-kelompok">{{ $s->is_akademik ? 'Akademik' : 'Non-Akademik' }}</span>
            </div>
            <div class="standar-body">
                <!-- Identitas Singkat -->
                <table class="detail-table">
                    <tr>
                        <td class="detail-label">Sasaran Unit Kerja</td>
                        <td>
                            @if(is_array($s->sasaran_unit) && count($s->sasaran_unit) > 0)
                                {{ implode(', ', $s->sasaran_unit) }}
                            @else
                                <span style="color: #64748b;">Universal (Seluruh Unit USS)</span>
                            @endif
                        </td>
                        <td class="detail-label" style="width: 22%;">Penanggung Jawab</td>
                        <td style="width: 28%;">{{ $s->penanggung_jawab ?: 'Dekan / Ketua Program Studi / Pimpinan Unit' }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Dokumen Acuan Utama</td>
                        <td colspan="3">{{ $s->acuan ?: 'Standar Nasional Pendidikan Tinggi (SN-Dikti) / Statuta USS' }}</td>
                    </tr>
                    @if($s->template_dokumen)
                    <tr>
                        <td class="detail-label">Dokumen Mutu Terkait</td>
                        <td colspan="3" style="color: #0284c7; font-size: 8pt; word-break: break-all;">
                            {{ $s->template_dokumen }}
                        </td>
                    </tr>
                    @endif
                </table>

                <!-- Pernyataan Standar & Indikator -->
                <div class="box-indikator">
                    <div class="box-title">Indikator Standar (Apa yang diukur):</div>
                    <div style="color: #1e293b;">{{ $s->indikator ?: 'Tersedianya implementasi standar sesuai kebijakan dan pedoman yang berlaku di Universitas Sumatera Selatan.' }}</div>
                </div>

                <!-- Target Capaian -->
                <div class="box-indikator" style="border-left-color: #0284c7;">
                    <div class="box-title">Target Capaian Mutu:</div>
                    <div style="color: #1e293b; white-space: pre-line;">{{ $s->target ?: 'Tercapainya 100% kepatuhan terhadap indikator standar yang dipersyaratkan.' }}</div>
                </div>

                <!-- Kriteria Audit PPEPP -->
                <div style="font-weight: bold; font-size: 8.5pt; color: #0f172a; margin-top: 6px; margin-bottom: 4px;">Kriteria Pelaksanaan Audit Siklus PPEPP:</div>
                <table class="table-ppepp">
                    <tr>
                        <td class="tahap-badge">P1 - Penetapan</td>
                        <td>{{ $s->indikator_p1 ?: 'Apakah terdapat dokumen resmi penetapan kebijakan dan standar mutu oleh pimpinan yang berwenang?' }}</td>
                    </tr>
                    <tr>
                        <td class="tahap-badge">P2 - Pelaksanaan</td>
                        <td>{{ $s->indikator_p2 ?: 'Apakah standar dan SOP telah dilaksanakan secara konsisten dengan bukti fisik pendukung yang absah?' }}</td>
                    </tr>
                    <tr>
                        <td class="tahap-badge">P3 - Evaluasi</td>
                        <td>{{ $s->indikator_p3 ?: 'Apakah dilakukan monitoring dan evaluasi capaian target standar secara berkala dan terdokumentasi?' }}</td>
                    </tr>
                    <tr>
                        <td class="tahap-badge">P4 - Pengendalian</td>
                        <td>{{ $s->indikator_p4 ?: 'Apakah dilakukan tindakan korektif, akar masalah (RCA), dan Rapat Tinjauan Manajemen (RTM) jika ada target yang belum tercapai?' }}</td>
                    </tr>
                    <tr>
                        <td class="tahap-badge">P5 - Peningkatan</td>
                        <td>{{ $s->indikator_p5 ?: 'Apakah dilakukan peningkatan target mutu standar secara berkelanjutan (continuous improvement / Kaizen)?' }}</td>
                    </tr>
                </table>

                <!-- Matriks Rubrik Penilaian -->
                <div style="font-weight: bold; font-size: 8.5pt; color: #0f172a; margin-top: 6px; margin-bottom: 4px;">Matriks Rubrik Penilaian Mutu (Skor 4 - 1):</div>
                <table class="table-rubrik">
                    <thead>
                        <tr>
                            <th class="skor-col skor-4">Skor 4<br><small style="font-weight: normal;">Sangat Baik / Melampaui</small></th>
                            <th class="skor-col skor-3">Skor 3<br><small style="font-weight: normal;">Baik / Memenuhi</small></th>
                            <th class="skor-col skor-2">Skor 2<br><small style="font-weight: normal;">Cukup / Belum Tercapai</small></th>
                            <th class="skor-col skor-1">Skor 1<br><small style="font-weight: normal;">Kurang / Menyimpang</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rubrik = is_array($s->rubrik_penilaian) ? $s->rubrik_penilaian : [];
                        @endphp
                        <tr>
                            <td style="font-size: 7.5pt; vertical-align: top;">
                                {{ $rubrik[4] ?? 'Pencapaian melampaui target yang ditetapkan (> 100%). Seluruh tahapan PPEPP berjalan konsisten, terdokumentasi rapi, dan terdapat inovasi/best practice.' }}
                            </td>
                            <td style="font-size: 7.5pt; vertical-align: top;">
                                {{ $rubrik[3] ?? 'Pencapaian memenuhi 100% dari target minimal yang ditetapkan. Siklus PPEPP berjalan baik sesuai SOP dan terdokumentasi secara teratur.' }}
                            </td>
                            <td style="font-size: 7.5pt; vertical-align: top;">
                                {{ $rubrik[2] ?? 'Pelaksanaan sudah berjalan namun target indikator belum tercapai maksimal (< 100%), atau dokumen pendukung belum lengkap secara sistematis.' }}
                            </td>
                            <td style="font-size: 7.5pt; vertical-align: top;">
                                {{ $rubrik[1] ?? 'Pelaksanaan tidak relevan dengan indikator, dokumen bukti tidak absah/tidak ada, dan siklus PPEPP tidak berjalan sebagaimana mestinya.' }}
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        @endforeach
    @endforeach

    <!-- LEMBAR PENGESAHAN DOKUMEN -->
    <div style="margin-top: 25px; page-break-inside: avoid; border-top: 1px solid #cbd5e1; padding-top: 15px;">
        <table class="signature-table">
            <tr>
                <td>
                    Ditetapkan & Diverifikasi Oleh:<br>
                    <strong>Ketua LPMA Universitas Sumatera Selatan</strong>
                    <div class="signature-space"></div>
                    <strong><u>{{ $kepalaLpmaNama }}</u></strong><br>
                    <span>{{ $kepalaLpmaJabatan }}</span>
                </td>
                <td>
                    Mengetahui & Mengesahkan:<br>
                    <strong>Rektor Universitas Sumatera Selatan</strong>
                    <div class="signature-space"></div>
                    <strong><u>{{ $rektorNama }}</u></strong><br>
                    <span>{{ $rektorJabatan }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- SCRIPT NOMOR HALAMAN DOMPDF -->
    <script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 8;
            $color = [0.4, 0.45, 0.5];

            // Header Halaman (Mulai Halaman 2)
            if ($pageNumber > 1) {
                $headerText = "Buku Standar Mutu SPMI — Instrumen: {{ $periode->nama }}";
                $canvas->text(42, 22, $headerText, $font, 7.5, $color);
                $canvas->line(42, 32, 553, 32, [0.8, 0.85, 0.9], 0.6);
            }

            // Footer Halaman
            $footerLeft = "Universitas Sumatera Selatan — Lembaga Penjaminan Mutu & Audit (LPMA)";
            $canvas->text(42, 815, $footerLeft, $font, $size, $color);

            $pageText = "Halaman " . $pageNumber . " dari " . $pageCount;
            $width = $fontMetrics->get_text_width($pageText, $font, $size);
            $canvas->text(553 - $width, 815, $pageText, $font, $size, $color);

            $canvas->line(42, 808, 553, 808, [0.8, 0.85, 0.9], 0.6);
        });
    }
    </script>
</body>
</html>
