<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Template Bukti Kegiatan SPMI</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10pt; color: #333; line-height: 1.5; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .border-bottom { border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 15px; }
        .header-title { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        table.table-bordered th, table.table-bordered td { border: 1px solid #000; padding: 6px 10px; }
        .label-cell { width: 180px; font-weight: bold; }
        .content-box { border: 1px solid #000; min-height: 150px; padding: 10px; margin-bottom: 20px; }
        .signature-box { width: 100%; margin-top: 50px; }
        .signature-box td { border: none; padding: 5px; text-align: center; width: 50%; vertical-align: top; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="header text-center border-bottom">
        <div class="header-title">LEMBAGA PENJAMINAN MUTU (LPMA)</div>
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div style="font-size: 10pt; margin-top: 5px;">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
    </div>

    <div class="text-center" style="margin-bottom: 25px;">
        <div style="font-size: 12pt; font-weight: bold; text-decoration: underline;">FORMULIR BUKTI KEGIATAN SIKLUS SPMI</div>
        <div style="font-size: 11pt; margin-top: 5px;">Tahun Pelaksanaan: {{ $tahun }}</div>
    </div>

    <table>
        <tr>
            <td class="label-cell">Nama Kegiatan</td>
            <td>: {{ $activity->nama }}</td>
        </tr>
        <tr>
            <td class="label-cell">Tahapan (PPEPP)</td>
            <td>: {{ $activity->tahap }}</td>
        </tr>
        <tr>
            <td class="label-cell">Penanggung Jawab (PIC)</td>
            <td>: {{ $activity->pic }}</td>
        </tr>
        <tr>
            <td class="label-cell">Hari / Tanggal</td>
            <td>: .....................................................</td>
        </tr>
        <tr>
            <td class="label-cell">Waktu Pelaksanaan</td>
            <td>: .....................................................</td>
        </tr>
        <tr>
            <td class="label-cell">Tempat</td>
            <td>: .....................................................</td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-bottom: 5px;">Agenda / Pembahasan:</div>
    <div class="content-box">
        <!-- Ruang Kosong untuk ditulis tangan -->
    </div>

    <div style="font-weight: bold; margin-bottom: 5px;">Kesimpulan / Keputusan / Hasil:</div>
    <div class="content-box">
        <!-- Ruang Kosong untuk ditulis tangan -->
    </div>

    <table class="signature-box" style="margin-bottom: 30px;">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div>Ketua LPMA Universitas Sumatera Selatan</div>
                <div class="signature-space"></div>
                <div>( .................................................... )</div>
                <div>NIDN:</div>
            </td>
            <td>
                <div>Palembang, ........................ {{ date('Y') }}</div>
                <div>PIC Kegiatan ({{ $activity->pic }})</div>
                <div class="signature-space"></div>
                <div>( .................................................... )</div>
                <div>NIP/NIDN:</div>
            </td>
        </tr>
    </table>

    <div style="page-break-after: always;"></div>

    <div class="header text-center border-bottom">
        <div class="header-title">LEMBAGA PENJAMINAN MUTU (LPMA)</div>
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
    </div>

    <div class="text-center" style="margin-bottom: 25px;">
        <div style="font-size: 12pt; font-weight: bold; text-decoration: underline;">DAFTAR HADIR KEGIATAN SPMI</div>
        <div style="font-size: 11pt; margin-top: 5px;">Tahun Pelaksanaan: {{ $tahun }}</div>
    </div>

    <table style="width: 100%; font-size: 10pt; margin-bottom: 20px;">
        <tr>
            <td style="width: 150px; font-weight: bold;">Nama Kegiatan</td>
            <td>: {{ $activity->nama }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Hari / Tanggal</td>
            <td>: .....................................................</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Waktu</td>
            <td>: .....................................................</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tempat</td>
            <td>: .....................................................</td>
        </tr>
    </table>

    <table class="table-bordered" style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Lengkap</th>
                <th style="width: 25%;">Utusan / Unit Kerja</th>
                <th style="width: 20%;">Jabatan</th>
                <th style="width: 20%;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 15; $i++)
            <tr>
                <td style="text-align: center;">{{ $i }}</td>
                <td style="height: 25px;"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 30px; page-break-inside: avoid;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="text-align: center; width: 50%;">
                <div>Palembang, ........................ {{ date('Y') }}</div>
                <div><b>Penanggung Jawab / Pimpinan Rapat</b></div>
                <div style="height: 70px;"></div>
                <div>( .................................................... )</div>
            </td>
        </tr>
    </table>
</body>
</html>
