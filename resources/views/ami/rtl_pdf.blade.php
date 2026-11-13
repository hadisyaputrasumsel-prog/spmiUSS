<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form 5 — Deskripsi Temuan Audit</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.35;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table {
            border-bottom: 2px solid #000;
            margin-bottom: 6px;
            padding-bottom: 4px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 2px 4px;
        }
        .kop-logo {
            width: 65px;
            text-align: center;
        }
        .kop-logo img {
            max-height: 60px;
            max-width: 60px;
        }
        .kop-center {
            text-align: center;
        }
        .kop-center .inst {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-center .sub {
            font-size: 9.5pt;
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
            padding: 5px 8px;
            font-weight: bold;
            font-size: 8.5pt;
            text-align: center;
            background: #fff;
        }
        .title-banner {
            background-color: #f1f5f9;
            border: 1px solid #000;
            text-align: center;
            padding: 4px 6px;
            margin-bottom: 8px;
        }
        .title-banner h2 {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
        }
        .title-banner h3 {
            font-size: 9.5pt;
            font-weight: bold;
            margin: 2px 0 0 0;
            padding: 0;
        }
        table.identitas-table {
            border: 1px solid #000;
            margin-bottom: 8px;
            font-size: 8pt;
        }
        table.identitas-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: top;
        }
        .temuan-header {
            background-color: #e2e8f0;
            border: 1px solid #000;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 0;
            border-bottom: none;
        }
        table.temuan-table {
            border: 1px solid #000;
            margin-bottom: 8px;
            font-size: 8pt;
        }
        table.temuan-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        .field-label {
            width: 25%;
            font-weight: bold;
            background-color: #f8fafc;
        }
        .ttd-box {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 8pt;
            page-break-inside: avoid;
        }
        .closing-section {
            border: 1px solid #000;
            padding: 12px;
            margin-top: 15px;
            background-color: #f8fafc;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
@php
    $logoPath = public_path('storage/logos/1788738305_Logo USS.png');
    if (!file_exists($logoPath)) {
        $logoPath = storage_path('app/public/logos/1788738305_Logo USS.png');
    }
    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

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
@endphp

@forelse($findings as $index => $finding)
    <!-- KOP -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @else
                    <div style="font-weight:bold; font-size:14pt; color:#0284c7;">USS</div>
                @endif
            </td>
            <td class="kop-center">
                <div class="inst">UNIVERSITAS SUMATERA SELATAN</div>
                <div class="sub">LEMBAGA PENJAMINAN MUTU DAN AKREDITASI (LPMA)</div>
            </td>
            <td class="kop-no">
                <div class="box-no">No : {{ $auditSesi->no_kka ?: 'KKA-'.$tahun }}</div>
            </td>
        </tr>
    </table>

    <!-- BANNER -->
    <div class="title-banner">
        <h2>BORANG AUDIT MUTU INTERNAL</h2>
        <h3>Deskripsi Temuan Audit</h3>
    </div>

    <!-- IDENTITAS DARI AUDIT SESI -->
    <table class="identitas-table">
        <tr>
            <td style="width: 50%;">
                <b>Auditi:</b> {{ $unit->nama }}
            </td>
            <td style="width: 50%;">
                <b>Kriteria:</b> {{ $auditSesi->kriteria ?: ($finding->standar_kode . ' — ' . ($finding->standard->nama ?? 'Standar Mutu')) }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Lokasi:</b> {{ $auditSesi->lokasi ?: 'Kampus USS Palembang' }}
            </td>
            <td>
                <b>Ruang Lingkup:</b> {{ $auditSesi->ruang_lingkup ?: 'Desk Review / Visitasi TA ' . $tahun }} &nbsp;|&nbsp; <b>Tanggal Audit:</b> {{ $tanggalAuditFormatted }}
            </td>
        </tr>
        <tr>
            <td>
                <b>Wakil Auditi:</b> {{ $auditSesi->wakil_auditi ?: ($unit->pimpinan ?: 'Pimpinan Unit') }}
            </td>
            <td>
                <b>Auditor Ketua:</b> {{ $ketua ? ($ketua->nama_lengkap ?: $ketua->name) : 'Lead Auditor' }}<br>
                <b>Auditor Anggota:</b>
                @if($anggota->count() > 0)
                    @foreach($anggota as $ang)
                        {{ $ang->nama_lengkap ?: $ang->name }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
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

    <!-- JUDUL TEMUAN BLOK -->
    <div class="temuan-header">
        Temuan No. {{ $index + 1 }} — {{ $finding->standar_kode }} [{{ $finding->kategori_temuan }}]
    </div>

    <!-- DETAIL TEMUAN -->
    <table class="temuan-table">
        <tr>
            <td class="field-label">Deskripsi Temuan</td>
            <td>{!! nl2br(e($finding->uraian)) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Kriteria</td>
            <td>
                <b>{{ $finding->standar_kode }}</b> — {{ $finding->standard->nama ?? '-' }}
                @if($finding->standard && $finding->standard->pernyataan)
                    <div style="margin-top: 2px; color: #333;">{!! nl2br(e($finding->standard->pernyataan)) !!}</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="field-label">Akibat</td>
            <td>{!! nl2br(e($finding->akibat ?: '-')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Akar Penyebab / Masalah</td>
            <td>{!! nl2br(e($finding->akar_penyebab ?: '-')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Rekomendasi disepakati dgn audit</td>
            <td>{!! nl2br(e($finding->rekomendasi ?: '-')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Tanggapan Auditi</td>
            <td>{!! nl2br(e($finding->tanggapan_auditi ?: 'Auditi menyetujui temuan dan rekomendasi ini.')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Rencana Perbaikan</td>
            <td>{!! nl2br(e($finding->rencana_tindakan ?: '-')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Jadwal Perbaikan</td>
            <td>
                {{ $finding->batas_waktu ? date('d F Y', strtotime($finding->batas_waktu)) : '-' }}
                &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                <b>Penanggung Jawab:</b> {{ $finding->pic ?: '-' }}
            </td>
        </tr>
        <tr>
            <td class="field-label">Rencana Pencegahan</td>
            <td>{!! nl2br(e($finding->rencana_pencegahan ?: '-')) !!}</td>
        </tr>
        <tr>
            <td class="field-label">Jadwal Pencegahan</td>
            <td>
                {{ $finding->jadwal_pencegahan ?: 'Rutin mengikuti siklus SPMI' }}
                &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                <b>Penanggung Jawab:</b> {{ $finding->pic ?: 'Gugus Kendali Mutu' }}
            </td>
        </tr>
    </table>

    <!-- PERSETUJUAN PER TEMUAN -->
    <div class="ttd-box">
        <div style="font-weight: bold; margin-bottom: 4px;">Tempat Persetujuan</div>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; vertical-align: top; padding: 2px;">
                    <b>Pimpinan Auditi</b><br><br><br>
                    ( <b>{{ $auditSesi->wakil_auditi ?: ($unit->pimpinan ?: '................................') }}</b> )<br>
                    Tanda Tangan: ..............................
                </td>
                <td style="width: 50%; border: none; vertical-align: top; padding: 2px;">
                    <b>Ketua Auditor</b><br><br><br>
                    ( <b>{{ $ketua ? ($ketua->nama_lengkap ?: $ketua->name) : '................................' }}</b> )<br>
                    Tanda Tangan: ..............................
                </td>
            </tr>
        </table>
        <div style="margin-top: 6px; padding-top: 4px; border-top: 1px dashed #ccc;">
            <b>Direview oleh :</b> &nbsp; <b>Penjamin Mutu Audit</b><br><br><br>
            ( <b>{{ $auditSesi->penjamin_mutu ?: '................................' }}</b> ) &nbsp;&nbsp;&nbsp;&nbsp; Tanda Tangan: ..............................
        </div>
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif

@empty
    <div style="text-align: center; margin-top: 50px;">
        <h3>Tidak ada temuan audit yang memenuhi kriteria filter untuk dicetak pada Form 5.</h3>
    </div>
@endforelse

<!-- CATATAN PENUTUP DAN TINDAK LANJUT (OPSIONAL) -->
@if(!empty($auditSesi->catatan_penutup))
    <div class="page-break"></div>
    <!-- KOP -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @else
                    <div style="font-weight:bold; font-size:14pt; color:#0284c7;">USS</div>
                @endif
            </td>
            <td class="kop-center">
                <div class="inst">UNIVERSITAS SUMATERA SELATAN</div>
                <div class="sub">LEMBAGA PENJAMINAN MUTU DAN AKREDITASI (LPMA)</div>
            </td>
            <td class="kop-no">
                <div class="box-no">No : {{ $auditSesi->no_kka ?: 'KKA-'.$tahun }}</div>
            </td>
        </tr>
    </table>

    <div class="title-banner">
        <h2>BORANG AUDIT MUTU INTERNAL</h2>
        <h3>Catatan Penutup dan Tindak Lanjut</h3>
    </div>

    <div class="closing-section">
        <h4 style="margin: 0 0 10px 0; font-size: 10pt; text-transform: uppercase;">VI. Catatan Penutup dan Tindak Lanjut</h4>
        <div style="font-size: 9pt; line-height: 1.6; text-align: justify;">
            {!! nl2br(e($auditSesi->catatan_penutup)) !!}
        </div>
    </div>

    <div class="ttd-box" style="margin-top: 20px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; vertical-align: top; padding: 2px;">
                    <b>Pimpinan Auditi</b><br><br><br><br>
                    ( <b>{{ $auditSesi->wakil_auditi ?: ($unit->pimpinan ?: '................................') }}</b> )<br>
                    Tanda Tangan: ..............................
                </td>
                <td style="width: 50%; border: none; vertical-align: top; padding: 2px;">
                    <b>Ketua Auditor</b><br><br><br><br>
                    ( <b>{{ $ketua ? ($ketua->nama_lengkap ?: $ketua->name) : '................................' }}</b> )<br>
                    Tanda Tangan: ..............................
                </td>
            </tr>
        </table>
        <div style="margin-top: 10px; padding-top: 6px; border-top: 1px dashed #ccc;">
            <b>Direview oleh :</b> &nbsp; <b>Penjamin Mutu Audit</b><br><br><br><br>
            ( <b>{{ $auditSesi->penjamin_mutu ?: '................................' }}</b> ) &nbsp;&nbsp;&nbsp;&nbsp; Tanda Tangan: ..............................
        </div>
    </div>
@endif

</body>
</html>
