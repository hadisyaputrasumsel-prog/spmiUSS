<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau: Buku Laporan Audit Mutu Internal (AMI) Tahun {{ $tahun }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #525659;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
        }

        .top-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background-color: #1e293b;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
            z-index: 1000;
        }

        .top-toolbar .title {
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .btn-back { background: #475569; color: #fff; }
        .btn-back:hover { background: #334155; }
        .btn-print { background: #0284c7; color: #fff; }
        .btn-print:hover { background: #0369a1; }
        .btn-archive { background: #16a34a; color: #fff; }
        .btn-archive:hover { background: #15803d; }

        .book-container {
            max-width: 820px;
            margin: 80px auto 40px auto;
            background: #fff;
            padding: 50px 60px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-radius: 4px;
            line-height: 1.6;
            font-size: 10.5pt;
        }

        .page-divider {
            border-top: 2px dashed #94a3b8;
            margin: 50px 0;
            position: relative;
            text-align: center;
        }
        .page-divider span {
            position: relative;
            top: -12px;
            background: #cbd5e1;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
            color: #475569;
        }

        h1.bab-title {
            font-size: 15pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
            margin-bottom: 24px;
        }

        h2.subbab-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 22px;
            margin-bottom: 10px;
            color: #0f172a;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 20px 0;
            font-size: 9pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .chart-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .chart-title {
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 12px;
            color: #0f172a;
        }
        .bar-container {
            width: 100%;
            margin-bottom: 10px;
        }
        .bar-label {
            font-size: 8.5pt;
            font-weight: bold;
            color: #334155;
            margin-bottom: 3px;
        }
        .bar-track {
            background-color: #e2e8f0;
            height: 16px;
            border-radius: 4px;
            overflow: hidden;
            width: 100%;
        }
        .bar-fill {
            height: 100%;
            float: left;
        }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            font-size: 8pt;
            font-weight: bold;
            border-radius: 4px;
        }
        .badge-mayor { background-color: #fee2e2; color: #991b1b; }
        .badge-minor { background-color: #fef3c7; color: #92400e; }
        .badge-ob { background-color: #e0f2fe; color: #075985; }
        .badge-sesuai { background-color: #dcfce7; color: #166534; }
    </style>
</head>
<body>

<!-- Fixed Topbar -->
<div class="top-toolbar">
    <div class="title">
        <i class="bi bi-book-half" style="font-size: 1.25rem; color: #38bdf8;"></i>
        <span>Pratinjau: Buku Laporan AMI Universitas Sumatera Selatan — Tahun {{ $tahun }}</span>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('laporan_ami.index', ['tahun' => $tahun]) }}" class="btn-action btn-back">
            <i class="bi bi-arrow-left"></i> Pengaturan
        </a>
        <a href="{{ route('laporan_ami.pdf', ['tahun' => $tahun]) }}" target="_blank" class="btn-action btn-print">
            <i class="bi bi-printer"></i> Cetak / Unduh PDF
        </a>
    </div>
</div>

<div class="book-container">

    @php
        $logoPath = public_path('storage/logos/1788738305_Logo USS.png');
        if (!file_exists($logoPath)) {
            $logoPath = storage_path('app/public/logos/1788738305_Logo USS.png');
        }
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
        $tglTerbitFormatted = $laporan->tanggal_terbit ? $laporan->tanggal_terbit->translatedFormat('d F Y') : '20 November ' . $tahun;
        if (! function_exists('renderRichText')) {
            function renderRichText($content) {
                return \App\Models\LaporanAmi::renderRichText($content);
            }
        }
    @endphp

    <!-- 1. COVER -->
    <div style="text-align: center; padding: 40px 0;">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo USS" style="width: 130px; height: auto; margin-bottom: 25px;">
        @endif
        <div style="font-size: 12pt; font-weight: bold; letter-spacing: 1px; color: #475569;">BUKU LAPORAN RESMI</div>
        <h1 style="font-size: 20pt; font-weight: 800; color: #0f172a; margin: 10px 0;">
            AUDIT MUTU INTERNAL (AMI)<br>UNIVERSITAS SUMATERA SELATAN
        </h1>
        <div style="font-size: 14pt; font-weight: bold; color: #0284c7; margin-bottom: 25px;">
            TAHUN AKADEMIK {{ $tahun }}
        </div>
        <p style="text-align: center; color: #475569; max-width: 90%; margin: 0 auto 60px auto;">
            Potret Komprehensif Siklus Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan (PPEPP) Standar SPMI dan Hasil Audit Mutu Internal di Lingkungan Universitas Sumatera Selatan
        </p>

        <div style="margin-top: 80px;">
            <div style="font-size: 10pt; font-weight: bold; color: #64748b;">Disusun Oleh :</div>
            <div style="font-size: 12pt; font-weight: bold; color: #0f172a;">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
            <div style="font-size: 11pt; font-weight: bold; color: #0284c7;">UNIVERSITAS SUMATERA SELATAN</div>
            <div style="font-size: 10pt; color: #64748b; margin-top: 10px;">PALEMBANG, {{ $tahun }}</div>
        </div>
    </div>

    <div class="page-divider"><span>Halaman Lembar Pengesahan (ii)</span></div>

    <!-- 2. LEMBAR PENGESAHAN -->
    <div>
        <h1 class="bab-title">LEMBAR PENGESAHAN</h1>
        <table class="data-table">
            <tr>
                <td style="width: 30%; background: #f8fafc; font-weight: bold;">Judul Dokumen</td>
                <td>Buku Laporan Hasil Audit Mutu Internal (AMI) Universitas Sumatera Selatan</td>
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
                <td style="background: #f8fafc; font-weight: bold;">Tanggal Pengesahan</td>
                <td>{{ $tglTerbitFormatted }}</td>
            </tr>
            <tr>
                <td style="background: #f8fafc; font-weight: bold;">Versi / Status</td>
                <td>Versi {{ $laporan->versi ?: '1.0' }} (Dokumen Resmi Terverifikasi)</td>
            </tr>
        </table>

        <div style="display: flex; justify-content: space-between; margin-top: 50px; text-align: center;">
            <div style="width: 45%;">
                Mengetahui dan Mengesahkan,<br>
                <b>{{ $laporan->penandatangan_lpma_jabatan ?: 'Kepala LPMA Universitas Sumatera Selatan' }}</b><br>
                Universitas Sumatera Selatan
                <br><br><br><br>
                <u><b>{{ $laporan->penandatangan_lpma_nama ?: 'Dr. Hadi Syaputra, S.Kom., M.Kom' }}</b></u><br>
                NIDN: {{ $laporan->penandatangan_lpma_nidn ?: '0214057801' }}
            </div>
            <div style="width: 45%;">
                Mengesahkan,<br>
                <b>{{ $laporan->penandatangan_rektor_jabatan ?: 'Rektor Universitas Sumatera Selatan' }}</b><br>
                Universitas Sumatera Selatan
                <br><br><br><br>
                <u><b>{{ $laporan->penandatangan_rektor_nama ?: 'Prof. Dr. Ir. H. M. Syahril, M.S.' }}</b></u><br>
                NIDN: {{ $laporan->penandatangan_rektor_nidn ?: '0015086002' }}
            </div>
        </div>
    </div>

    <div class="page-divider"><span>Halaman Kata Pengantar (iii)</span></div>

    <!-- 3. KATA PENGANTAR -->
    <div>
        <h1 class="bab-title">KATA PENGANTAR</h1>
        <div style="text-align: justify; line-height: 1.7;">
            {!! renderRichText($laporan->kata_pengantar) !!}
        </div>
        <div style="float: right; text-align: center; margin-top: 30px;">
            Palembang, {{ $tglTerbitFormatted }}<br>
            Kepala LPMA Universitas Sumatera Selatan,<br><br><br><br>
            <u><b>{{ $laporan->penandatangan_lpma_nama ?: 'Dr. Hadi Syaputra, S.Kom., M.Kom' }}</b></u>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="page-divider"><span>Bab I Pendahuluan (1)</span></div>

    <!-- 4. BAB I -->
    <div>
        <h1 class="bab-title">BAB I<br>PENDAHULUAN</h1>
        <h2 class="subbab-title">1.1 Latar Belakang</h2>
        <p>Sistem Penjaminan Mutu Internal (SPMI) merupakan pilar fundamental dalam menjamin dan meningkatkan mutu penyelenggaraan tridharma perguruan tinggi di Universitas Sumatera Selatan secara berkelanjutan berbasis PPEPP.</p>

        <h2 class="subbab-title">1.2 Dasar Hukum</h2>
        <div style="line-height: 1.6; margin-left: 15px;">{!! renderRichText($laporan->dasar_hukum) !!}</div>

        <h2 class="subbab-title">1.3 Tujuan Audit Mutu Internal</h2>
        <div style="line-height: 1.6; margin-left: 15px;">{!! renderRichText($laporan->tujuan) !!}</div>

        <h2 class="subbab-title">1.4 Ruang Lingkup Audit</h2>
        <div style="line-height: 1.6; margin-left: 15px;">{!! renderRichText($laporan->ruang_lingkup) !!}</div>

        <h2 class="subbab-title">1.5 Metode dan Mekanisme Audit</h2>
        <div style="line-height: 1.6; margin-left: 15px;">{!! renderRichText($laporan->metode) !!}</div>
    </div>

    <div class="page-divider"><span>Bab II Pelaksanaan (3)</span></div>

    <!-- 5. BAB II -->
    <div>
        <h1 class="bab-title">BAB II<br>PELAKSANAAN AUDIT MUTU INTERNAL</h1>

        <h2 class="subbab-title">2.1 Jadwal Pelaksanaan AMI</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th>Tahapan Kegiatan</th>
                    <th style="width: 25%;">Rentang Waktu</th>
                    <th style="width: 25%;">Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>
                @php $jadwalList = (array) ($laporan->jadwal_ami ?: []); @endphp
                @foreach($jadwalList as $i => $j)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td><b>{{ $j['kegiatan'] ?? '-' }}</b></td>
                        <td class="text-center">{{ $j['tanggal'] ?? '-' }}</td>
                        <td>{{ $j['penanggung_jawab'] ?? 'LPMA' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="subbab-title">2.2 Tim Auditor dan Sasaran Unit Kerja</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;">No</th>
                    <th>Unit Kerja / Program Studi</th>
                    <th>Auditor Ketua</th>
                    <th>Auditor Anggota</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditorTeams as $idx => $team)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><b>{{ $team['unit']->nama }}</b></td>
                        <td>{{ $team['ketua'] ? ($team['ketua']->nama_lengkap ?: $team['ketua']->name) : 'Lead Auditor' }}</td>
                        <td>
                            @foreach($team['anggota'] as $ang)
                                &bull; {{ $ang->nama_lengkap ?: $ang->name }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-divider"><span>Bab III Hasil Audit (5)</span></div>

    <!-- 6. BAB III -->
    <div>
        <h1 class="bab-title">BAB III<br>HASIL AUDIT MUTU INTERNAL</h1>

        <h2 class="subbab-title">3.1 Rekapitulasi Temuan Audit per Kategori</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kategori Temuan</th>
                    <th>Jumlah Temuan</th>
                    <th>Persentase (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Sesuai (Kepatuhan Penuh)</b></td>
                    <td class="text-center font-bold" style="color: #16a34a;">{{ $rekapNasional['Sesuai'] }}</td>
                    <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['Sesuai'] / $totalFindings) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td><b>Observasi (OB)</b></td>
                    <td class="text-center font-bold" style="color: #0284c7;">{{ $rekapNasional['Observasi (OB)'] }}</td>
                    <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['Observasi (OB)'] / $totalFindings) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td><b>Ketidaksesuaian Minor (KTS Minor)</b></td>
                    <td class="text-center font-bold" style="color: #d97706;">{{ $rekapNasional['KTS Minor'] }}</td>
                    <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['KTS Minor'] / $totalFindings) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td><b>Ketidaksesuaian Mayor (KTS Mayor)</b></td>
                    <td class="text-center font-bold" style="color: #dc2626;">{{ $rekapNasional['KTS Mayor'] }}</td>
                    <td class="text-center font-bold">{{ $totalFindings > 0 ? round(($rekapNasional['KTS Mayor'] / $totalFindings) * 100, 1) : 0 }}%</td>
                </tr>
                <tr style="background: #f8fafc; font-weight: bold;">
                    <td>TOTAL TEMUAN AUDIT</td>
                    <td class="text-center" style="font-size: 11pt;">{{ $totalFindings }}</td>
                    <td class="text-center">100.0%</td>
                </tr>
            </tbody>
        </table>

        <h2 class="subbab-title">3.2 Skor Rata-Rata Capaian Standar</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Unit Kerja</th>
                    <th>Total Temuan</th>
                    <th>Skor Rata-Rata (1.00 - 4.00)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapPerUnit as $r)
                    <tr>
                        <td><b>{{ $r['unit']->nama }}</b></td>
                        <td class="text-center">{{ $r['total'] }}</td>
                        <td class="text-center font-bold" style="color: #0284c7; font-size: 10.5pt;">{{ number_format($r['avg_score'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="subbab-title">3.4 Grafik Visualisasi Hasil Audit</h2>

        <!-- Grafik 1 -->
        <div class="chart-box">
            <div class="chart-title">Grafik 3.1 Komposisi Kategori Temuan per Unit Kerja (Batang Bertumpuk)</div>
            @foreach($rekapPerUnit as $r)
                @php
                    $tot = max(1, $r['total']);
                    $pSesuai = ($r['counts']['Sesuai'] / $tot) * 100;
                    $pOb = ($r['counts']['Observasi (OB)'] / $tot) * 100;
                    $pMinor = ($r['counts']['KTS Minor'] / $tot) * 100;
                    $pMayor = ($r['counts']['KTS Mayor'] / $tot) * 100;
                @endphp
                <div class="bar-container">
                    <div class="bar-label">{{ $r['unit']->nama }} (Total: {{ $r['total'] }})</div>
                    <div class="bar-track">
                        @if($pSesuai > 0)<div class="bar-fill" style="width: {{ $pSesuai }}%; background: #16a34a;" title="Sesuai: {{ $r['counts']['Sesuai'] }}"></div>@endif
                        @if($pOb > 0)<div class="bar-fill" style="width: {{ $pOb }}%; background: #0284c7;" title="OB: {{ $r['counts']['Observasi (OB)'] }}"></div>@endif
                        @if($pMinor > 0)<div class="bar-fill" style="width: {{ $pMinor }}%; background: #d97706;" title="KTS Minor: {{ $r['counts']['KTS Minor'] }}"></div>@endif
                        @if($pMayor > 0)<div class="bar-fill" style="width: {{ $pMayor }}%; background: #dc2626;" title="KTS Mayor: {{ $r['counts']['KTS Mayor'] }}"></div>@endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Grafik 2 -->
        <div class="chart-box">
            <div class="chart-title">Grafik 3.2 Skor Rata-Rata Capaian per Unit Kerja (Skala 1.00 - 4.00)</div>
            @foreach($rekapPerUnit as $r)
                @php
                    $score = $r['avg_score'];
                    $pScore = min(100, max(0, ($score / 4.0) * 100));
                @endphp
                <div class="bar-container">
                    <div class="bar-label" style="display: flex; justify-content: space-between;">
                        <span>{{ $r['unit']->nama }}</span>
                        <span style="font-weight: bold; color: #0284c7;">{{ number_format($score, 2) }} / 4.00</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ $pScore }}%; background: #0284c7;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="page-divider"><span>Bab IV Tindak Lanjut (10)</span></div>

    <!-- 7. BAB IV -->
    <div>
        <h1 class="bab-title">BAB IV<br>TINDAK LANJUT HASIL AUDIT (RTL)</h1>
        <p>Tingkat penyelesaian Rencana Tindak Lanjut (RTL) secara keseluruhan mencapai <b>{{ $overallRtlPct }}%</b>.</p>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Unit Kerja</th>
                    <th>Selesai</th>
                    <th>Proses</th>
                    <th>PTK</th>
                    <th>Belum</th>
                    <th>% Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rtlStatuses as $r)
                    <tr>
                        <td><b>{{ $r['unit']->nama }}</b></td>
                        <td class="text-center font-bold" style="color: #16a34a;">{{ $r['selesai'] }}</td>
                        <td class="text-center" style="color: #0284c7;">{{ $r['proses'] }}</td>
                        <td class="text-center" style="color: #7c3aed;">{{ $r['ptk'] }}</td>
                        <td class="text-center" style="color: #dc2626;">{{ $r['belum'] }}</td>
                        <td class="text-center font-bold" style="color: #1e40af;">{{ $r['persentase'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-divider"><span>Bab V Kesimpulan & Rekomendasi (12)</span></div>

    <!-- 8. BAB V -->
    <div>
        <h1 class="bab-title">BAB V<br>KESIMPULAN DAN REKOMENDASI</h1>
        <h2 class="subbab-title">5.1 Kesimpulan Umum</h2>
        <div style="line-height: 1.6; text-align: justify;">{!! renderRichText($laporan->kesimpulan) !!}</div>

        <h2 class="subbab-title">5.2 Rekomendasi Pimpinan / RTM</h2>
        <div style="line-height: 1.6; margin-left: 15px;">{!! renderRichText($laporan->rekomendasi_pimpinan) !!}</div>
    </div>

    <!-- 9. LAMPIRAN DOKUMEN -->
    @php
        $lampOptions = (array) ($laporan->lampiran_options ?: ['form4', 'form5', 'daftar_hadir', 'standar']);
    @endphp

    @if(in_array('form4', $lampOptions) || in_array('form5', $lampOptions) || in_array('daftar_hadir', $lampOptions) || in_array('standar', $lampOptions))
        <div class="page-divider"><span>Lampiran Dokumen Audit (13+)</span></div>

        <div>
            <h1 class="bab-title">LAMPIRAN DOKUMEN AUDIT</h1>

            @if(in_array('form4', $lampOptions))
                <h2 class="subbab-title">Lampiran 1: Form 4 Ringkasan Temuan Audit (KKA)</h2>
                <p>Ringkasan temuan audit yang ditandatangani oleh Auditi, Ketua Auditor, dan Penjamin Mutu LPMA:</p>

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
                <p>Rincian matriks akar penyebab (RCA), rencana perbaikan, dan jadwal pencegahan:</p>

                @foreach($nonSesuaiFindings as $f)
                    <div style="border: 1px solid #cbd5e1; padding: 12px; margin-bottom: 12px; font-size: 9pt; border-radius: 6px;">
                        <div style="font-weight: bold; color: #0284c7; margin-bottom: 6px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 4px;">
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
                <h2 class="subbab-title" style="margin-top: 30px;">Lampiran 3: Daftar Hadir & Berita Acara RTM</h2>
                <p>Berita Acara dan Daftar Hadir pelaksanaan Rapat Tinjauan Manajemen (RTM) serta ekspose hasil Audit Mutu Internal Tahun {{ $tahun }}:</p>

                <table class="data-table" style="margin-bottom: 20px;">
                    <tr>
                        <td style="width: 25%; font-weight: bold; background: #f8fafc;">Hari / Tanggal</td>
                        <td style="width: 75%;">{{ $tanggalAuditTerpilih ? \Carbon\Carbon::parse($tanggalAuditTerpilih)->translatedFormat('d F Y') : '16 Oktober ' . $tahun }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #f8fafc;">Waktu & Tempat</td>
                        <td>09.00 WIB s.d. Selesai — Ruang Rapat Rektorat / Kampus Universitas Sumatera Selatan</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #f8fafc;">Agenda Rapat</td>
                        <td>Penyampaian Laporan Hasil Audit Mutu Internal (AMI) Tahun Akademik {{ $tahun }} dan Penandatanganan Kesepakatan RTL</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; background: #f8fafc;">Pimpinan Rapat</td>
                        <td>{{ $laporan->penandatangan_rektor_nama ?: 'Rektor Universitas Sumatera Selatan' }} & {{ $laporan->penandatangan_lpma_nama ?: 'Kepala LPMA' }}</td>
                    </tr>
                </table>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">No</th>
                            <th style="width: 32%;">Nama Lengkap</th>
                            <th style="width: 26%;">Jabatan / Unit Kerja</th>
                            <th style="width: 18%;">Peran</th>
                            <th style="width: 18%;">Status</th>
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
                                <td class="text-center font-bold" style="color: #16a34a;">Hadir</td>
                            </tr>
                        @endif
                        @if($laporan->penandatangan_lpma_nama)
                            <tr>
                                <td class="text-center">{{ $noHadir++ }}</td>
                                <td><b>{{ $laporan->penandatangan_lpma_nama }}</b></td>
                                <td>LPMA USS</td>
                                <td>Penyelenggara</td>
                                <td class="text-center font-bold" style="color: #16a34a;">Hadir</td>
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
                                    <td class="text-center font-bold" style="color: #16a34a;">Hadir</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-center">{{ $noHadir++ }}</td>
                                <td>{{ $u->pimpinan ?: 'Pimpinan ' . $u->nama }}</td>
                                <td>{{ $u->nama }}</td>
                                <td>Auditi / Kepala Unit</td>
                                <td class="text-center font-bold" style="color: #16a34a;">Hadir</td>
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
</div>

</body>
</html>
