<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rencana & Tindak Lanjut AMI</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .page-break { page-break-after: always; }
        
        /* Cover Page */
        .cover { text-align: center; margin-top: 150px; }
        .cover h1 { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .cover h2 { font-size: 18px; font-weight: normal; margin-bottom: 50px; }
        .cover h3 { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .cover h4 { font-size: 16px; font-weight: normal; margin-bottom: 80px; }
        
        .footer-cover { position: absolute; bottom: 50px; width: 100%; text-align: center; font-size: 10px; color: #555; }
        
        /* Pages */
        .page-title { font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 30px; margin-top: 20px; text-decoration: underline; }
        
        /* Table Styles (Pengesahan) */
        .table-identitas { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 12px; }
        .table-identitas td { padding: 8px; vertical-align: top; }
        .table-identitas td:first-child { width: 30%; font-weight: bold; }
        
        .table-ttd { width: 100%; border-collapse: collapse; margin-top: 50px; text-align: center; font-size: 12px; }
        .table-ttd td { width: 50%; padding: 10px; vertical-align: bottom; height: 100px; }
        
        /* Table Styles (Findings) */
        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 9px; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        table.data th { background-color: #f1f5f9; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        
        .status-badge { background-color: #3b82f6; color: white; padding: 2px 4px; display: inline-block; text-align: center; }
        .status-badge.selesai { background-color: #10b981; }
    </style>
</head>
<body>

    <!-- PAGE 1: COVER -->
    <div class="cover">
        <h1>LAPORAN TINDAK LANJUT (LTL)<br>AUDIT MUTU INTERNAL</h1>
        <h2>Tahun Akademik {{ $tahun }}</h2>
        
        <br><br><br><br>
        
        @if($unit)
            <h3>{{ $unit->nama }}</h3>
            <h4>{{ $unit->jenis ?: 'Fakultas / Program Studi' }}</h4>
        @else
            <h3>Seluruh Unit Kerja</h3>
            <h4>Audit AMI Tingkat Universitas</h4>
        @endif
        
        <h4>UNIVERSITAS SUMATERA SELATAN</h4>
    </div>
    
    <div class="footer-cover">
        <strong>Pusat Penjaminan Mutu</strong><br>
        Universitas Sumatera Selatan<br>
        Dokumen Pengendalian dan Peningkatan (Siklus PPEPP)
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- PAGE 2: PENGESAHAN -->
    <div class="page-title">LEMBAR IDENTITAS & PENGESAHAN LTL</div>
    
    <table class="table-identitas">
        <tr>
            <td>Unit Kerja (Teraudit)</td>
            <td>: {{ $unit ? $unit->nama : 'Seluruh Unit Kerja Universitas Sumatera Selatan' }}</td>
        </tr>
        <tr>
            <td>Periode Audit</td>
            <td>: Tahun Akademik {{ $tahun }}</td>
        </tr>
        <tr>
            <td>Fase SPMI</td>
            <td>: Pengendalian / Peningkatan Mutu</td>
        </tr>
        <tr>
            <td>Status Tindak Lanjut</td>
            <td>: {{ $findings->where('status_tindak_lanjut', 'Selesai')->count() }} Selesai / {{ $findings->count() }} Total Temuan</td>
        </tr>
    </table>
    
    <table class="table-ttd">
        <tr>
            <td>
                Tim Auditor / Verifikator Mutu,<br><br><br><br><br>
                <strong><u>{{ ($auditors ?? collect())->count() > 0 ? $auditors[0]->name : 'Ketua LPMA / Lead Auditor' }}</u></strong>
            </td>
            <td>
                Penanggung Jawab Tindak Lanjut,<br><br><br><br><br>
                <strong><u>Pimpinan {{ $unit ? $unit->nama : 'Unit' }}</u></strong>
            </td>
        </tr>
    </table>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- PAGE 3: KATA PENGANTAR -->
    <div class="page-title">KATA PENGANTAR</div>
    <div style="text-align: justify; font-size: 12px; line-height: 1.8;">
        <p>Puji syukur kami panjatkan ke hadirat Tuhan Yang Maha Esa atas rahmat dan karunia-Nya sehingga Laporan Tindak Lanjut <i>(Corrective & Preventive Action)</i> Audit Mutu Internal (AMI) ini dapat disusun dan diselesaikan dengan baik.</p>
        
        <p>Rencana dan Laporan Tindak Lanjut ini merupakan bentuk akuntabilitas komitmen kami dalam menjalankan siklus ke-4 dan ke-5 dari PPEPP (Pengendalian dan Peningkatan) atas temuan hasil Audit Mutu Internal. Dokumen ini memuat langkah-langkah sistematis yang dilakukan oleh unit kerja untuk menanggulangi ketidaksesuaian standar maupun peluang peningkatan (Observasi) yang ditemukan oleh para Auditor Mutu.</p>
        
        <p>Dengan adanya Laporan Tindak Lanjut ini, kami menegaskan komitmen untuk menjaga standar penjaminan mutu sesuai dengan kerangka Peraturan Menteri Pendidikan yang terbaru, agar kelembagaan Universitas Sumatera Selatan senantiasa menuju keunggulan.</p>
        
        <p style="text-align: right; margin-top: 40px;">
            Palembang, {{ date('F Y') }}<br><br><br>
            <strong>Tim Jaminan Mutu / Pimpinan Unit</strong>
        </p>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>
    
    <!-- PAGE 4: DATA TINDAK LANJUT -->
    <div class="page-title">MATRIKS RENCANA DAN TINDAK LANJUT AUDIT TAHUN {{ $tahun }}</div>
    
    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                @if(!$unit)<th width="12%">Unit</th>@endif
                <th width="10%">Standar (Kategori)</th>
                <th width="25%">Deskripsi Temuan / KTS</th>
                <th width="25%">Rencana & Aktual Tindak Lanjut</th>
                <th width="10%">Batas Waktu</th>
                <th width="9%">PIC</th>
                <th width="5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($findings as $index => $finding)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                @if(!$unit)<td>{{ $finding->unit->nama ?? '-' }}</td>@endif
                <td><strong>{{ $finding->standar_kode }}</strong><br>({{ $finding->kategori_temuan }})</td>
                <td>{{ $finding->uraian }}</td>
                <td>{{ $finding->rencana_tindakan }}</td>
                <td class="text-center">{{ $finding->batas_waktu ? date('d/m/Y', strtotime($finding->batas_waktu)) : '-' }}</td>
                <td class="text-center">{{ $finding->pic ?: '-' }}</td>
                <td class="text-center">
                    <span class="status-badge {{ strtolower($finding->status_tindak_lanjut) == 'selesai' ? 'selesai' : '' }}">
                        {{ $finding->status_tindak_lanjut ?: 'Berjalan' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $unit ? 7 : 8 }}" class="text-center" style="padding: 20px;">Belum ada rencana tindak lanjut yang dilaporkan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
