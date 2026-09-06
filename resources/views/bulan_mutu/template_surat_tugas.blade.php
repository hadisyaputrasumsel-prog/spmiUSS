<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Tugas Tim Auditor Mutu Internal</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.5; margin: 30px; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .border-bottom { border-bottom: 3px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .header-title { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-sub { font-size: 11pt; }
        .letter-number { text-align: center; font-weight: bold; text-decoration: underline; margin-top: 15px; font-size: 12pt; }
        .content { margin-top: 30px; margin-bottom: 20px; text-align: justify; }
        
        table.table-bordered { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        table.table-bordered th, table.table-bordered td { border: 1px solid #000; padding: 6px 10px; }
        table.table-bordered th { background-color: #f2f2f2; text-align: center; }
        
        .signature-box { width: 100%; margin-top: 50px; page-break-inside: avoid; }
        .signature-box td { border: none; padding: 5px; width: 50%; vertical-align: top; }
        .signature-space { height: 80px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header text-center border-bottom">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
    </div>

    <div class="letter-number">SURAT TUGAS</div>
    <div class="text-center" style="margin-bottom: 25px;">Nomor: ....../ST-AMI/LPMA/USS/{{ date('m/Y') }}</div>

    <div class="content">
        <p>Berdasarkan Program Kerja Lembaga Penjaminan Mutu (LPMA) Universitas Sumatera Selatan mengenai Pelaksanaan Siklus Sistem Penjaminan Mutu Internal (SPMI) Tahun Pelaksanaan <strong>{{ $tahun }}</strong>, dengan ini Ketua LPMA menugaskan nama-nama di bawah ini sebagai <strong>Tim Auditor Mutu Internal (AMI)</strong>:</p>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Nama Auditor</th>
                    <th style="width: 60%;">Unit Kerja / Auditee Yang Diaudit</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($assignments as $auditorName => $assignedUnits)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><b>{{ $auditorName }}</b></td>
                    <td>
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($assignedUnits as $assignment)
                                <li>{{ $assignment->unit ? $assignment->unit->nama_unit : 'Unit Tidak Diketahui' }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>Tim Auditor ditugaskan untuk melaksanakan tahapan Audit Mutu Internal (AMI) atau Evaluasi Diri (Desk Evaluation dan Visitasi) secara profesional, obyektif, dan berintegritas terhadap unit-unit kerja tersebut, sesuai dengan Jadwal Bulan Mutu yang telah ditetapkan.</p>
        <p>Demikian Surat Tugas ini dibuat untuk dilaksanakan dengan sebaik-baiknya dan penuh tanggung jawab. Laporan Hasil AMI diserahkan kepada LPMA selambat-lambatnya pada batas waktu yang tertera pada agenda Bulan Mutu.</p>
    </div>

    <table class="signature-box">
        <tr>
            <td></td>
            <td style="text-align: right; padding-right: 50px;">
                <div>Palembang, ........................ {{ date('Y') }}</div>
                <div><b>Ketua LPMA</b></div>
                <div class="signature-space"></div>
                <div><b>( .................................................... )</b></div>
                <div>NIDN:</div>
            </td>
        </tr>
    </table>
</body>
</html>
