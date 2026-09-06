<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Pengumpulan LED</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.5; margin: 30px; }
        .text-center { text-align: center; }
        .border-bottom { border-bottom: 3px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .header-title { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-sub { font-size: 11pt; }
        .content { margin-top: 30px; margin-bottom: 20px; }
        
        table.table-bordered { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; font-size: 10pt; }
        table.table-bordered th, table.table-bordered td { border: 1px solid #000; padding: 6px; }
        table.table-bordered th { background-color: #f2f2f2; text-align: center; }
        
        .signature-box { width: 100%; margin-top: 50px; page-break-inside: avoid; }
        .signature-box td { border: none; padding: 5px; width: 50%; vertical-align: top; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="header text-center border-bottom">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
    </div>

    <div class="text-center" style="margin-bottom: 25px;">
        <div style="font-size: 12pt; font-weight: bold; text-decoration: underline;">REKAPITULASI PENGAJUAN LAPORAN EVALUASI DIRI (LED)</div>
        <div style="font-size: 11pt; margin-top: 5px;">Siklus SPMI Tahun: {{ $tahun }}</div>
    </div>

    <div class="content">
        <p>Terkait tahapan Bulan Mutu <strong>"{{ $activity->nama }}"</strong>, berikut adalah status akhir penerimaan berkas Laporan Evaluasi Diri (LED) dari seluruh unit kerja di lingkungan Universitas Sumatera Selatan:</p>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Nama Unit Kerja / Auditee</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 20%;">Tanggal Pengajuan</th>
                    <th style="width: 20%;">Status Pengumpulan</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1;
                    $terkumpulCount = 0; 
                @endphp
                @foreach($units as $unit)
                    @php
                        $sub = $submissions->get($unit->id);
                        if($sub) $terkumpulCount++;
                    @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $unit->nama }}</td>
                    <td class="text-center">{{ $unit->jenis }}</td>
                    <td class="text-center">
                        {{ $sub ? \Carbon\Carbon::parse($sub->submitted_at)->format('d-m-Y H:i') : '-' }}
                    </td>
                    <td class="text-center" style="font-weight: bold; color: {{ $sub ? 'green' : 'red' }};">
                        {{ $sub ? 'Terkumpul / Diajukan' : 'Belum Diajukan' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>Berdasarkan data di atas, dari total <strong>{{ count($units) }}</strong> unit yang wajib menyetorkan LED, sebanyak <strong>{{ $terkumpulCount }}</strong> unit telah menyerahkan laporan secara lengkap untuk diproses ke tahapan Audit Mutu Internal (AMI).</p>
    </div>

    <table class="signature-box">
        <tr>
            <td></td>
            <td style="text-align: right; padding-right: 50px;">
                <div>Palembang, ........................ {{ date('Y') }}</div>
                <div><b>Ketua LPMA (PIC Kegiatan)</b></div>
                <div class="signature-space"></div>
                <div><b>( .................................................... )</b></div>
                <div>NIDN:</div>
            </td>
        </tr>
    </table>
</body>
</html>
