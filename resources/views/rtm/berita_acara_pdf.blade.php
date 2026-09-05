<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara RTM SPMI</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        h1, h2, h3 { margin: 0; padding: 0; }
        h1 { font-size: 14pt; }
        h2 { font-size: 12pt; }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header p {
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px 8px;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        
        .signature-table {
            border: none;
            width: 100%;
            margin-top: 50px;
        }
        .signature-table td {
            border: none;
            text-align: center;
            padding: 0;
            width: 50%;
        }
        .signature-box {
            height: 80px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="fw-bold">UNIVERSITAS SUMATERA SELATAN</h1>
        <h2 class="fw-bold">Lembaga Penjaminan Mutu dan Akreditasi (LPMA)</h2>
        <p>Palembang — lpma.uss.ac.id</p>
    </div>

    <div class="text-center" style="margin-bottom: 20px;">
        <h2 class="fw-bold">BERITA ACARA RAPAT TINJAUAN MANAJEMEN (RTM)</h2>
        <h2 class="fw-bold">Sistem Penjaminan Mutu Internal (SPMI) — Universitas Sumatera Selatan</h2>
        <p style="font-size: 10pt;">No. Formulir: FR-RTM-USS-01  |  Dasar: MM-USS-02-05 (Manual Peningkatan)</p>
    </div>

    <p style="text-align: justify;">
        Pada hari ini <strong>______________</strong> tanggal <strong>______________</strong> bulan <strong>______________</strong> tahun <strong>{{ $tahun }}</strong>, bertempat di <strong>{{ $tempat }}</strong>, telah dilaksanakan Rapat Tinjauan Manajemen (RTM) dalam rangka meninjau hasil Pengendalian dan menetapkan Peningkatan Standar Mutu SPMI Universitas Sumatera Selatan, dengan agenda sebagai berikut:
    </p>

    <ol style="margin-bottom: 20px;">
        <li>Tinjauan hasil Audit Mutu Internal (AMAI/EMI) periode berjalan</li>
        <li>Tinjauan hasil Pengendalian, PTK, dan Laporan Tindak Lanjut (LTL)</li>
        <li>Evaluasi capaian target Standar Mutu USS</li>
        <li>Usulan revisi/peningkatan standar (bila diperlukan)</li>
        <li>Rencana tindak lanjut & penetapan target periode berikutnya</li>
    </ol>

    <h3 class="fw-bold">DAFTAR HADIR</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 40%;">Nama</th>
                <th style="width: 35%;">Jabatan</th>
                <th style="width: 20%;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 10; $i++)
            <tr>
                <td class="text-center">{{ $i }}</td>
                <td></td>
                <td></td>
                <td style="height: 25px;"></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <div style="page-break-before: always;"></div>

    <h3 class="fw-bold">PEMBAHASAN DAN KEPUTUSAN RAPAT</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Pokok Bahasan</th>
                <th style="width: 35%;">Keputusan / Rekomendasi</th>
                <th style="width: 12%;">PIC</th>
                <th style="width: 13%;">Target Waktu</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 6; $i++)
            <tr>
                <td class="text-center">{{ $i }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td style="height: 50px;"></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <p style="text-align: justify; margin-top: 30px;">
        Demikian Berita Acara ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.
    </p>

    <table class="signature-table">
        <tr>
            <td>
                <p>Notulis<br>(Kepala LPMA)</p>
                <div class="signature-box"></div>
                <p>(________________________)<br>Nama & Tanda Tangan</p>
                <p>Tanggal: ____________________</p>
            </td>
            <td>
                <p>Pimpinan Rapat<br>(Rektor / Wakil Rektor I)</p>
                <div class="signature-box"></div>
                <p>(________________________)<br>Nama & Tanda Tangan</p>
                <p>Tanggal: ____________________</p>
            </td>
        </tr>
    </table>

    <div style="margin-top: 50px; font-size: 9pt; color: #666;">
        Sumber acuan: Manual Peningkatan SPMI USS (MM-USS-02-05), Laporan Hasil AMAI/EMI SPMI USS, Laporan Tindak Lanjut (LTL) SPMI USS.
    </div>

</body>
</html>
