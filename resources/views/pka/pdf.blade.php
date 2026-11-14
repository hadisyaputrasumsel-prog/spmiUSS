<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Program Kerja Audit (PKA)</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            color: #111;
            line-height: 1.35;
        }
        .page-break {
            page-break-after: always;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 2px 4px;
        }
        .kop-logo {
            width: 75px;
            text-align: center;
        }
        .kop-logo img {
            max-height: 70px;
            max-width: 70px;
        }
        .kop-text {
            text-align: center;
            padding: 0 10px;
        }
        .kop-text .inst-name {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            line-height: 1.2;
        }
        .kop-text .unit-name {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
            line-height: 1.2;
        }
        .kop-text .address {
            font-size: 8.5pt;
            color: #333;
            margin: 2px 0 0 0;
        }
        .kop-no {
            width: 130px;
            vertical-align: middle;
            text-align: right;
        }
        .box-no {
            display: inline-block;
            border: 1.5px solid #000;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 9pt;
            text-align: center;
            background-color: #fff;
        }
        .title-banner {
            background-color: #e2e8f0;
            border: 1px solid #000;
            padding: 6px 8px;
            font-weight: bold;
            font-size: 9.5pt;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        table.identitas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 8.5pt;
        }
        table.identitas-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: top;
        }
        .box-section {
            border: 1px solid #000;
            margin-bottom: 8px;
            font-size: 8.5pt;
        }
        .box-section-title {
            background-color: #f1f5f9;
            font-weight: bold;
            padding: 4px 8px;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .box-section-content {
            padding: 6px 8px;
            text-align: justify;
            min-height: 28px;
        }
        .langkah-header {
            font-weight: bold;
            font-size: 9pt;
            margin: 6px 0 4px 0;
            text-transform: uppercase;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-top: 4px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
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
@endphp

@foreach($pkas as $idx => $pka)
    <!-- KOP -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @else
                    <div style="font-weight:bold; font-size:16pt; color:#0284c7;">USS</div>
                @endif
            </td>
            <td class="kop-text">
                <div class="unit-name">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
                <div class="inst-name">UNIVERSITAS SUMATERA SELATAN</div>
                <div class="address">Jl. Letnan Murod No. 55, Talang Ratu, KM 5, Palembang</div>
            </td>
            <td class="kop-no">
                <div class="box-no">No : {{ $pka->nomor }}</div>
            </td>
        </tr>
    </table>

    <!-- JUDUL PKA -->
    <div class="title-banner">
        PROGRAM KERJA AUDIT (PKA) — AUDIT MUTU INTERNAL — {{ $pka->ruang_lingkup }} — {{ mb_strtoupper($pka->unit->nama) }}
    </div>

    <!-- TABEL IDENTITAS -->
    <table class="identitas-table">
        <tr>
            <td style="width: 55%;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="border:none; padding:2px 0; width: 25%; font-weight: bold;">Auditi</td>
                        <td style="border:none; padding:2px 4px; width: 3%;">:</td>
                        <td style="border:none; padding:2px 0;">{{ $pka->unit->nama }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:2px 0; font-weight: bold;">Ruang Lingkup</td>
                        <td style="border:none; padding:2px 4px;">:</td>
                        <td style="border:none; padding:2px 0;">{{ $pka->ruang_lingkup }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:2px 0; font-weight: bold;">Kriteria</td>
                        <td style="border:none; padding:2px 4px;">:</td>
                        <td style="border:none; padding:2px 0;">{{ $pka->kriteria }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 45%;">
                <div style="margin-bottom: 6px;">
                    <b>Disusun Oleh</b> : {{ $pka->penyusun ? ($pka->penyusun->nama_lengkap ?: $pka->penyusun->name) : '................................' }}<br>
                    <b>Tanggal</b> : {{ $pka->tanggal_disusun ? $pka->tanggal_disusun->translatedFormat('d F Y') : '-' }}<br>
                    <b>Paraf</b> :
                </div>
                <div style="border-top: 1px solid #ccc; padding-top: 6px;">
                    <b>Diperiksa Oleh</b> : {{ $pka->pemeriksa ? ($pka->pemeriksa->nama_lengkap ?: $pka->pemeriksa->name) : '................................' }}<br>
                    <b>Tanggal</b> : {{ $pka->tanggal_diperiksa ? $pka->tanggal_diperiksa->translatedFormat('d F Y') : '-' }}<br>
                    <b>Paraf</b> :
                </div>
            </td>
        </tr>
    </table>

    <!-- TENTATIVE AUDIT OBJECTIVE -->
    <div class="box-section">
        <div class="box-section-title">Tentative Audit Objective</div>
        <div class="box-section-content">
            {!! nl2br(e($pka->tentative_audit_objective ?: '-')) !!}
        </div>
    </div>

    <!-- TUJUAN AUDIT -->
    <div class="box-section">
        <div class="box-section-title">Tujuan Audit</div>
        <div class="box-section-content">
            {!! nl2br(e($pka->tujuan_audit ?: '-')) !!}
        </div>
    </div>

    <!-- LANGKAH KERJA -->
    <div class="langkah-header">LANGKAH KERJA:</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 52%;">URAIAN LANGKAH-LANGKAH KERJA</th>
                <th style="width: 11%;">Anggaran Waktu</th>
                <th style="width: 10%;">Realisasi</th>
                <th style="width: 9%;">No KKA</th>
                <th style="width: 13%;">Disusun oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pka->langkah as $l)
            <tr>
                <td class="text-center">{{ $l->no_urut }}</td>
                <td style="text-align: justify;">{{ $l->uraian }}</td>
                <td class="text-center">{{ $l->anggaran_waktu ?: '-' }}</td>
                <td class="text-center">{{ $l->realisasi ?: '-' }}</td>
                <td class="text-center">{{ $l->no_kka ?: '-' }}</td>
                <td class="text-center">{{ $l->disusun_oleh ?: ($pka->penyusun ? ($pka->penyusun->nama_lengkap ?: $pka->penyusun->name) : '-') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; color: #666;">Belum ada langkah kerja yang didefinisikan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>
