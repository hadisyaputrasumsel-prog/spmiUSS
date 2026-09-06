<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan AMI</title>
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
        table.data th { background-color: #e2e8f0; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        
        /* Badges */
        .badge-k { background-color: #548235; color: white; padding: 2px 4px; display: inline-block; text-align: center; }
        .badge-ob { background-color: #2E75B6; color: white; padding: 2px 4px; display: inline-block; text-align: center; }
        .badge-minor { background-color: #BF8F00; color: white; padding: 2px 4px; display: inline-block; text-align: center; }
        .badge-mayor { background-color: #C00000; color: white; padding: 2px 4px; display: inline-block; text-align: center; }
        .badge-default { background-color: #666; color: white; padding: 2px 4px; display: inline-block; text-align: center; }
    </style>
</head>
<body>

    <!-- PAGE 1: COVER -->
    <div class="cover">
        <h1>LAPORAN AUDIT MUTU INTERNAL</h1>
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
        <strong>Lembaga Penjaminan Mutu Akademik (LPMA)</strong><br>
        Universitas Sumatera Selatan<br>
        Jl. Letnan Murod, Km. 5, Palembang<br>
        Email: lpma@uss.ac.id
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- PAGE 2: PENGESAHAN -->
    <div class="page-title">LEMBAR IDENTITAS & PENGESAHAN</div>
    
    <h3 style="font-size: 14px; margin-bottom: 10px;">1. Identitas Teraudit</h3>
    <table class="table-identitas">
        <tr>
            <td>Institusi Teraudit</td>
            <td>: {{ $unit ? $unit->nama : 'Seluruh Unit Kerja Universitas Sumatera Selatan' }}</td>
        </tr>
        <tr>
            <td>Alamat Institusi</td>
            <td>: Kampus Universitas Sumatera Selatan, Palembang</td>
        </tr>
    </table>
    
    <h3 style="font-size: 14px; margin-bottom: 10px;">2. Identitas Auditor</h3>
    <table class="table-identitas">
        <tr>
            <td>Tanggal Audit</td>
            <td>: {{ date('d F Y') }}</td>
        </tr>
        <tr>
            <td>Ketua Tim Auditor</td>
            <td>: {{ ($auditors ?? collect())->count() > 0 ? $auditors[0]->name : 'Ketua LPMA / Lead Auditor' }}</td>
        </tr>
        @if(($auditors ?? collect())->count() > 1)
        <tr>
            <td>Anggota Tim Auditor</td>
            <td>: 
                @foreach($auditors->slice(1) as $anggota)
                    {{ $anggota->name }}<br>&nbsp;&nbsp;
                @endforeach
            </td>
        </tr>
        @endif
    </table>
    
    <table class="table-ttd">
        <tr>
            <td>
                Ketua Tim Auditor,<br><br><br><br><br>
                <strong><u>{{ ($auditors ?? collect())->count() > 0 ? $auditors[0]->name : 'Lead Auditor' }}</u></strong>
            </td>
            <td>
                Teraudit (Auditee),<br><br><br><br><br>
                <strong><u>Pimpinan {{ $unit ? $unit->nama : 'Unit' }}</u></strong>
            </td>
        </tr>
    </table>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- PAGE 3: KATA PENGANTAR -->
    <div class="page-title">KATA PENGANTAR</div>
    <div style="text-align: justify; font-size: 12px; line-height: 1.8;">
        <p>Puji syukur kami panjatkan kepada Tuhan Yang Maha Esa atas rahmat dan hidayah-Nya sehingga Laporan Audit Mutu Akademik Internal (AMAI) di lingkungan Universitas Sumatera Selatan dapat diselesaikan dengan baik.</p>
        
        <p>Laporan Audit Mutu Internal (AMI) ini disusun sebagai bagian dari instrumen penjaminan mutu dalam rangka mengukur, mengevaluasi, dan meningkatkan kualitas pendidikan, penelitian, serta tata kelola kelembagaan sesuai dengan Standar Penjaminan Mutu Internal (SPMI) Universitas Sumatera Selatan yang merujuk pada standar kementerian terbaru.</p>
        
        <p>Proses audit yang dilakukan ini bukanlah sekadar investigasi kelemahan, melainkan sebuah proses reflektif interaktif antara Auditor dengan Unit Kerja Teraudit guna memetakan ruang-ruang peningkatan secara holistik. Setiap temuan (Observasi, KTS, dsb.) dicatat untuk ditindaklanjuti secara terstruktur guna menuju perbaikan berkelanjutan <i>(Continuous Quality Improvement)</i>.</p>
        
        <p>Kami mengucapkan apresiasi setinggi-tingginya kepada seluruh pihak baik Jajaran Pimpinan, Dekanat, Program Studi, maupun Unit Kerja lainnya yang telah berpartisipasi dan bersinergi dalam audit ini. Harapan kami, laporan perbaikan mutu ini dapat menjadi pijakan kuat Universitas Sumatera Selatan untuk terus berkembang.</p>
        
        <p style="text-align: right; margin-top: 40px;">
            Palembang, {{ date('F Y') }}<br><br><br>
            <strong>Tim Auditor / LPMA</strong>
        </p>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>
    
    <!-- PAGE 4: DATA TEMUAN -->
    <div class="page-title">REKAPITULASI TEMUAN AUDIT TAHUN {{ $tahun }}</div>
    
    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                @if(!$unit)<th width="12%">Unit</th>@endif
                <th width="12%">Standar</th>
                <th width="28%">Uraian Temuan</th>
                <th width="8%">Kategori</th>
                <th width="26%">Rencana Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($findings as $index => $finding)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ date('d M Y', strtotime($finding->tanggal)) }}</td>
                @if(!$unit)<td>{{ $finding->unit->nama ?? '-' }}</td>@endif
                <td><strong>{{ $finding->standar_kode }}</strong><br><small>T: {{ $finding->tahap }}</small></td>
                <td>{{ $finding->uraian }}</td>
                <td class="text-center">
                    @if(strpos(strtolower($finding->kategori_temuan), 'sesuai') !== false || $finding->kategori_temuan == 'K')
                        <span class="badge-k">{{ $finding->kategori_temuan }}</span>
                    @elseif(strpos(strtolower($finding->kategori_temuan), 'ob') !== false)
                        <span class="badge-ob">{{ $finding->kategori_temuan }}</span>
                    @elseif(strpos(strtolower($finding->kategori_temuan), 'minor') !== false)
                        <span class="badge-minor">{{ $finding->kategori_temuan }}</span>
                    @elseif(strpos(strtolower($finding->kategori_temuan), 'mayor') !== false)
                        <span class="badge-mayor">{{ $finding->kategori_temuan }}</span>
                    @else
                        <span class="badge-default">{{ $finding->kategori_temuan }}</span>
                    @endif
                </td>
                <td>
                    <b>R:</b> {{ $finding->rencana_tindakan ?: 'Belum diisi' }}<br>
                    <b>Batas:</b> {{ $finding->batas_waktu ? date('d/m/Y', strtotime($finding->batas_waktu)) : '-' }}<br>
                    <b>PIC:</b> {{ $finding->pic ?: '-' }} - <i>{{ $finding->status_tindak_lanjut ?: 'Belum' }}</i>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $unit ? 6 : 7 }}" class="text-center" style="padding: 20px;">Tidak ada temuan / observasi yang tercatat untuk periode ini. Semua sesuai standar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
