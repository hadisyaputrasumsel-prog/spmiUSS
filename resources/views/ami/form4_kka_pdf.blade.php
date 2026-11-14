<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form 4 — Ringkasan Temuan Audit (KKA)</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.35;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table {
            border-bottom: 2px solid #000;
            margin-bottom: 8px;
            padding-bottom: 6px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 2px 4px;
        }
        .kop-logo {
            width: 70px;
            text-align: center;
        }
        .kop-logo img {
            max-height: 65px;
            max-width: 65px;
        }
        .kop-center {
            text-align: center;
        }
        .kop-center .inst {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-center .sub {
            font-size: 10pt;
            font-weight: bold;
            color: #333;
            margin-top: 2px;
        }
        .kop-no {
            width: 140px;
            text-align: right;
            vertical-align: middle;
        }
        .box-no {
            display: inline-block;
            border: 1.5px solid #000;
            padding: 6px 8px;
            font-weight: bold;
            font-size: 9pt;
            text-align: center;
            background: #fff;
        }
        .title-banner {
            background-color: #f1f5f9;
            border: 1px solid #000;
            text-align: center;
            padding: 6px 8px;
            margin-bottom: 10px;
        }
        .title-banner h2 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
        }
        .title-banner h3 {
            font-size: 10pt;
            font-weight: bold;
            margin: 2px 0 0 0;
            padding: 0;
        }
        table.identitas-table {
            border: 1px solid #000;
            margin-bottom: 10px;
            font-size: 8.5pt;
        }
        table.identitas-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        table.data-table {
            border: 1px solid #000;
            margin-bottom: 12px;
            font-size: 8.5pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .ttd-section {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 8.5pt;
            page-break-inside: avoid;
        }
        .ttd-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        .badge-cat {
            font-weight: bold;
            padding: 2px 4px;
            display: inline-block;
            border-radius: 3px;
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

    $distArray = $auditSesi->distribusi ?: ['Auditi', 'Auditor', 'LPMA', 'Arsip'];

    $tanggalAuditFormatted = '-';
    if ($auditSesi->tanggal_audit) {
        $tanggalAuditFormatted = $auditSesi->tanggal_audit->translatedFormat('d F Y');
    } elseif ($findings->isNotEmpty()) {
        $minDate = $findings->min('tanggal');
        if ($minDate) {
            $tanggalAuditFormatted = \Carbon\Carbon::parse($minDate)->translatedFormat('d F Y');
        }
    }
    if ($tanggalAuditFormatted === '-') {
        $tanggalAuditFormatted = '16 Oktober ' . $tahun;
    }

    // Pihak Auditi
    $namaAuditi = $auditSesi->wakil_auditi ?: ($unit->pimpinan ?: null);
    if (! $namaAuditi) {
        $unitUser = \App\Models\User::where('unit_id', $unit->id)->first();
        if ($unitUser && ! empty($unitUser->nama_lengkap ?: $unitUser->name)) {
            $namaAuditi = $unitUser->nama_lengkap ?: $unitUser->name;
        } else {
            $namaAuditi = str_contains($unit->nama, 'Prodi') ? 'Ketua ' . $unit->nama : (str_contains($unit->nama, 'Fakultas') ? 'Dekan ' . $unit->nama : 'Pimpinan ' . $unit->nama);
        }
    }
    $nipAuditi = $unit->pimpinan_nip ? 'NIP/NIDN. ' . $unit->pimpinan_nip : 'Pimpinan Auditi';

    // Ketua Auditor
    $namaAuditor = $ketua ? ($ketua->nama_lengkap ?: ($ketua->name === 'Kepala LPMA' ? 'Dr. Hadi Syaputra, S.Kom., M.Kom' : $ketua->name)) : 'Ketua Tim Auditor';
    $nipAuditor = ($ketua && ($ketua->nidn || $ketua->nip))
        ? ('NIDN/NIP. ' . ($ketua->nidn ?: $ketua->nip))
        : ($ketua && in_array(strtolower($ketua->name), ['kepala lpma', 'lpma']) ? 'NIDN. 0214057801' : 'Auditor Ketua');

    // Penjamin Mutu (Kepala LPMA)
    $namaPenjaminMutu = $auditSesi->penjamin_mutu ?: 'Dr. Hadi Syaputra, S.Kom., M.Kom';
    $nidnPenjaminMutu = '0214057801';
@endphp

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
        <td class="kop-center">
            <div class="inst">UNIVERSITAS SUMATERA SELATAN</div>
            <div class="sub">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        </td>
        <td class="kop-no">
            <div class="box-no">No : {{ $auditSesi->no_kka ?: 'KKA-'.$tahun }}</div>
        </td>
    </tr>
</table>

<!-- TITLE BANNER -->
<div class="title-banner">
    <h2>BORANG AUDIT MUTU INTERNAL</h2>
    <h3>Ringkasan Temuan Audit</h3>
</div>

<!-- TABEL IDENTITAS -->
<table class="identitas-table">
    <tr>
        <td style="width: 50%;">
            <b>Auditi:</b><br>
            {{ $unit->nama }}
        </td>
        <td style="width: 50%;">
            <b>Kriteria:</b><br>
            {{ $auditSesi->kriteria ?: 'Standar SPMI USS / BAN-PT' }}
        </td>
    </tr>
    <tr>
        <td style="width: 50%;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 0; width: 30%;"><b>Lokasi:</b></td>
                    <td style="border: none; padding: 0;">{{ $auditSesi->lokasi ?: 'Kampus USS Palembang' }}</td>
                </tr>
            </table>
        </td>
        <td style="width: 50%;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 0; width: 35%;"><b>Ruang Lingkup:</b></td>
                    <td style="border: none; padding: 0;">{{ $auditSesi->ruang_lingkup ?: 'Desk Review — TA '.$tahun }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 0;"><b>Tanggal Audit:</b></td>
                    <td style="border: none; padding: 0;">{{ $tanggalAuditFormatted }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <b>Wakil Auditi:</b><br>
            {{ $namaAuditi ?: ($unit->nama ? 'Pimpinan ' . $unit->nama : 'Pimpinan Unit') }}
        </td>
        <td>
            <b>Auditor Ketua:</b><br>
            {{ $namaAuditor ?: 'Ketua Tim Auditor' }}
            <div style="margin-top: 4px;">
                <b>Auditor Anggota:</b><br>
                @if($anggota->count() > 0)
                    @foreach($anggota as $ang)
                        {{ $ang->nama_lengkap ?: $ang->name }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Distribusi:</b> &nbsp;&nbsp;
            [ {{ in_array('Auditi', $distArray) ? 'X' : ' ' }} ] Auditi &nbsp;&nbsp;&nbsp;&nbsp;
            [ {{ in_array('Auditor', $distArray) ? 'X' : ' ' }} ] Auditor &nbsp;&nbsp;&nbsp;&nbsp;
            [ {{ in_array('LPMA', $distArray) ? 'X' : ' ' }} ] LPMA &nbsp;&nbsp;&nbsp;&nbsp;
            [ {{ in_array('Arsip', $distArray) ? 'X' : ' ' }} ] Arsip
        </td>
    </tr>
</table>

<!-- TABEL TEMUAN -->
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 77%;">Diskripsi Temuan</th>
            <th style="width: 18%;">Kategori<br><small style="font-weight: normal;">(KTS Major / KTS Minor / OB / OFI / TP)</small></th>
        </tr>
    </thead>
    <tbody>
        @forelse($findings as $index => $f)
        @php
            $kat = $f->kategori_temuan;
            $badgeColor = '#333';
            if (str_contains($kat, 'Mayor')) $badgeColor = '#c00000';
            elseif (str_contains($kat, 'Minor')) $badgeColor = '#bf8f00';
            elseif (str_contains($kat, 'OB') || str_contains($kat, 'Observasi')) $badgeColor = '#0284c7';
            elseif (str_contains($kat, 'TP') || str_contains($kat, 'Positif')) $badgeColor = '#059669';
            elseif (str_contains($kat, 'OFI') || str_contains($kat, 'Peluang')) $badgeColor = '#7c3aed';
        @endphp
        <tr>
            <td class="text-center" style="font-weight: bold;">{{ $index + 1 }}</td>
            <td>
                <b>{{ $f->standar_kode }}</b> — {{ $f->standard->nama ?? 'Standar Mutu' }}
                @if($f->skor)
                    <span style="color: #666; font-size: 8pt;">(Skor: {{ $f->skor }})</span>
                @endif
                <div style="margin-top: 4px; text-align: justify;">
                    {!! nl2br(e($f->uraian)) !!}
                </div>
            </td>
            <td class="text-center" style="vertical-align: middle;">
                <span class="badge-cat" style="color: {{ $badgeColor }};">
                    {{ $kat }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center" style="padding: 15px; color: #666;">
                Tidak ada temuan audit non-sesuai yang tercatat pada periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- TEMPAT PERSETUJUAN -->
<div class="ttd-section">
    <table style="width: 100%; border: none; margin-bottom: 6px;">
        <tr>
            <td style="border: none; padding: 0; font-weight: bold; font-size: 9pt; text-transform: uppercase;">
                Tempat Persetujuan
            </td>
            <td style="border: none; padding: 0; text-align: right; font-size: 8.5pt; color: #333;">
                Palembang, {{ $tanggalAuditFormatted }}
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 33.33%; border: none; text-align: center; vertical-align: top; padding: 4px 6px;">
                <div style="font-weight: bold; font-size: 8.5pt;">Pihak Auditi,</div>
                <div style="font-size: 8pt; color: #555; margin-bottom: 45px;">{{ $unit->nama }}</div>
                <div style="font-size: 8.5pt;">
                    <u><b>{{ $namaAuditi }}</b></u>
                </div>
                <div style="font-size: 7.5pt; color: #555; margin-top: 2px;">
                    {{ $nipAuditi }}
                </div>
            </td>
            <td style="width: 33.33%; border: none; text-align: center; vertical-align: top; padding: 4px 6px;">
                <div style="font-weight: bold; font-size: 8.5pt;">Ketua Auditor,</div>
                <div style="font-size: 8pt; color: #555; margin-bottom: 45px;">Tim Auditor Mutu Internal</div>
                <div style="font-size: 8.5pt;">
                    <u><b>{{ $namaAuditor }}</b></u>
                </div>
                <div style="font-size: 7.5pt; color: #555; margin-top: 2px;">
                    {{ $nipAuditor }}
                </div>
            </td>
            <td style="width: 33.33%; border: none; text-align: center; vertical-align: top; padding: 4px 6px;">
                <div style="font-weight: bold; font-size: 8.5pt;">Penjamin Mutu Audit,</div>
                <div style="font-size: 8pt; color: #555; margin-bottom: 45px;">Kepala LPMA Universitas Sumatera Selatan</div>
                <div style="font-size: 8.5pt;">
                    <u><b>{{ $namaPenjaminMutu }}</b></u>
                </div>
                <div style="font-size: 7.5pt; color: #555; margin-top: 2px;">
                    NIDN. {{ $nidnPenjaminMutu }}
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
