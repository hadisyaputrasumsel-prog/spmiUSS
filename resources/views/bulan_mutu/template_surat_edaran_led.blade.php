<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Edaran Pengisian Evaluasi Diri (LED) - Siklus SPMI {{ $tahun }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 10.5pt; 
            color: #000; 
            line-height: 1.35; 
            margin: 20px 30px; 
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-justify { text-align: justify; }
        
        /* Header Kop Surat */
        .header { margin-bottom: 10px; }
        .header-title { font-size: 12.5pt; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .header-sub { font-size: 9.5pt; color: #222; margin-top: 2px; }
        .border-kop { border-bottom: 3px double #000; margin-bottom: 12px; padding-bottom: 6px; }
        
        .letter-number { 
            text-align: center; 
            font-weight: bold; 
            text-decoration: underline; 
            font-size: 11.5pt; 
            letter-spacing: 0.5px;
        }
        
        .content { 
            margin-top: 10px; 
            margin-bottom: 12px; 
            text-align: justify; 
        }
        
        table { border-collapse: collapse; }
        table.table-bordered { 
            width: 100%; 
            margin-top: 10px; 
            margin-bottom: 12px; 
            font-size: 9.5pt; 
        }
        table.table-bordered th, table.table-bordered td { 
            border: 1px solid #000; 
            padding: 4px 6px; 
        }
        table.table-bordered th { 
            background-color: #f2f2f2; 
            text-align: center; 
            font-weight: bold; 
        }
        
        /* Checkbox Box Styling */
        .checkbox-square {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1.2px solid #000;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 10px;
            font-size: 8.5pt;
            font-weight: bold;
            background-color: #fff;
        }
        .table-checkbox {
            width: 13px;
            height: 13px;
            border: 1.3px solid #000;
            margin: 0 auto;
            text-align: center;
            line-height: 12px;
            font-size: 9pt;
            font-weight: bold;
            background-color: #fff;
        }
        
        .signature-box { 
            width: 100%; 
            margin-top: 20px; 
            page-break-inside: avoid; 
        }
        .signature-box td { 
            border: none; 
            padding: 3px; 
            vertical-align: top; 
        }
        .signature-space { height: 60px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- KOP SURAT LPMA USS -->
    <div class="header text-center border-kop">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan | Email: lpma@universitassumsel.ac.id</div>
    </div>

    <!-- NOMOR DAN JUDUL SURAT EDARAN -->
    <div class="letter-number">SURAT EDARAN</div>
    <div class="text-center" style="margin-top: 3px; margin-bottom: 8px; font-size: 10.5pt;">
        Nomor: {{ $nomorSurat }}
    </div>

    <div class="text-center text-bold" style="font-size: 10.5pt; margin-bottom: 12px; text-transform: uppercase; line-height: 1.25;">
        TENTANG<br>
        PENGISIAN LAPORAN EVALUASI DIRI (LED) DAN PENGUNGGAHAN DOKUMEN BUKTI SIKLUS PPEPP<br>
        DALAM RANGKA AUDIT MUTU INTERNAL (AMI) SIKLUS TAHUN {{ $tahun }}
    </div>

    <!-- TUJUAN SURAT DAN KOTAK CEKLIS SASARAN PRODI / UNIT -->
    <table style="width: 100%; margin-bottom: 6px;">
        <tr>
            <td style="width: 80px; vertical-align: top;"><strong>Kepada Yth.</strong></td>
            <td style="width: 10px; vertical-align: top;">:</td>
            <td style="vertical-align: top;">
                <strong>Pimpinan Program Studi & Unit Kerja Sasaran</strong> <em>(Beri tanda centang &check; pada kotak unit tujuan):</em>
            </td>
        </tr>
    </table>

    <!-- KOTAK CEKLIS PRODI & UNIT SASARAN -->
    <div style="border: 1px solid #000; padding: 6px 10px; margin-bottom: 10px; background-color: #fafafa; font-size: 8.5pt;">
        <table style="width: 100%; border: none;">
            <tr>
                <!-- Kolom 1: Program Studi & Fakultas -->
                <td style="width: 52%; vertical-align: top; padding-right: 8px;">
                    <div style="font-weight: bold; border-bottom: 1px dashed #666; margin-bottom: 4px; padding-bottom: 2px;">
                        A. PROGRAM STUDI & FAKULTAS (AKADEMIK):
                    </div>
                    @foreach($prodiUnits as $pu)
                        <div style="margin-bottom: 2.5px;">
                            <span class="checkbox-square">{{ in_array($pu->id, $selectedUnitIds) ? '✓' : '' }}</span>
                            {{ $pu->nama }}
                        </div>
                    @endforeach
                    @foreach($fakultasUnits as $fu)
                        <div style="margin-bottom: 2.5px;">
                            <span class="checkbox-square">{{ in_array($fu->id, $selectedUnitIds) ? '✓' : '' }}</span>
                            {{ $fu->nama }}
                        </div>
                    @endforeach
                </td>

                <!-- Kolom 2: Lembaga, Biro & Unit Penunjang -->
                <td style="width: 48%; vertical-align: top; padding-left: 8px; border-left: 1px solid #ccc;">
                    <div style="font-weight: bold; border-bottom: 1px dashed #666; margin-bottom: 4px; padding-bottom: 2px;">
                        B. LEMBAGA, BIRO & UNIT (NON-AKADEMIK):
                    </div>
                    @foreach($nonAkademikUnits as $nu)
                        <div style="margin-bottom: 2.5px;">
                            <span class="checkbox-square">{{ in_array($nu->id, $selectedUnitIds) ? '✓' : '' }}</span>
                            {{ $nu->nama }}
                        </div>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

    <!-- ISI SURAT EDARAN -->
    <div class="content">
        <p style="margin-top: 0; margin-bottom: 6px;">Dengan hormat,</p>
        <p style="margin-top: 0; margin-bottom: 6px;">
            Dalam rangka penjaminan mutu berkelanjutan (<em>Continuous Quality Improvement</em>) serta pelaksanaan Siklus Sistem Penjaminan Mutu Internal (SPMI) berbasis <strong>PPEPP</strong> (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan) sesuai agenda <strong>Bulan Mutu Universitas Sumatera Selatan Tahun {{ $tahun }}</strong>, bersama ini Lembaga Penjaminan Mutu Akademik (LPMA) menyampaikan hal-hal sebagai berikut:
        </p>

        <ol style="margin-top: 4px; margin-bottom: 8px; padding-left: 20px; line-height: 1.35;">
            <li style="margin-bottom: 4px;">
                <strong>Kewajiban Pengisian LED:</strong> Seluruh Unit Kerja / Program Studi wajib melaksanakan evaluasi diri secara objektif dan komprehensif atas seluruh kriteria Standar SPMI USS yang berlaku pada siklus tahun {{ $tahun }}.
            </li>
            <li style="margin-bottom: 4px;">
                <strong>Penggunaan Aplikasi AMIRA:</strong> Pengisian instrumen LED dan penilaian mandiri dilakukan secara daring melalui sistem <strong>AMIRA</strong> (<em>http://202.146.181.85:8083</em>) pada modul <em>Laporan Evaluasi Diri (LED)</em>.
            </li>
            <li style="margin-bottom: 4px;">
                <strong>Kelengkapan Bukti Sahih (Evidence):</strong> Setiap pernyataan standar wajib disertai tautan berkas digital atau dokumen bukti fisik mencakup penetapan, realisasi pelaksanaan, hasil evaluasi, dan tindak lanjut peningkatan.
            </li>
            <li style="margin-bottom: 4px;">
                <strong>Jadwal & Batas Waktu Pengisian:</strong> Pengisian LED dan unggah bukti dimulai tanggal <strong>{{ $tanggalMulai }}</strong> dan batas akhir (<em>cut-off</em>) adalah tanggal <strong>{{ $tanggalSelesai }}</strong> pukul 23.59 WIB.
            </li>
            <li style="margin-bottom: 4px;">
                <strong>Desk Evaluation & Visitasi:</strong> Berkas LED yang telah difinalisasi akan dinilai oleh Tim Auditor dalam tahap Evaluasi Dokumen sebelum visitasi audit lapangan.
            </li>
        </ol>

        <p style="margin-top: 0; margin-bottom: 6px;">
            Demikian Surat Edaran ini disampaikan untuk dipedomani dan dilaksanakan dengan penuh rasa tanggung jawab demi budaya mutu yang unggul di Universitas Sumatera Selatan. Atas perhatian dan kerja sama Bapak/Ibu, diucapkan terima kasih.
        </p>
    </div>

    <!-- TANDA TANGAN & TEMBUSAN -->
    <table class="signature-box">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 9.5pt; line-height: 1.3;">
                    <strong>Tembusan Yth.:</strong><br>
                    1. Rektor Universitas Sumatera Selatan (sebagai laporan)<br>
                    2. Para Wakil Rektor Universitas Sumatera Selatan<br>
                    3. Para Pimpinan Unit Kerja Terkait<br>
                    4. Arsip LPMA
                </div>
            </td>
            <td style="width: 50%; text-align: right; padding-right: 15px;">
                <div>Ditetapkan di : Palembang</div>
                <div>Pada tanggal : {{ $tanggalSurat }}</div>
                <div style="height: 6px;"></div>
                <div><strong>Kepala Lembaga Penjaminan Mutu Akademik</strong></div>
                <div><strong>Universitas Sumatera Selatan,</strong></div>
                <div class="signature-space"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
                <div>NIDN / NIP: ............................................</div>
            </td>
        </tr>
    </table>

    <!-- HALAMAN 2: LAMPIRAN TABEL CEKLIS PRODI & UNIT KERJA SASARAN -->
    <div class="page-break"></div>

    <div class="header text-center border-kop">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
    </div>

    <div style="font-size: 9.5pt; margin-bottom: 8px;">
        <strong>LAMPIRAN SURAT EDARAN KEPALA LPMA UNIVERSITAS SUMATERA SELATAN</strong><br>
        Nomor &nbsp;: {{ $nomorSurat }}<br>
        Tanggal : {{ $tanggalSurat }}<br>
        Perihal : Lembar Ceklis Distribusi & Sasaran Pengisian Laporan Evaluasi Diri (LED) Tahun {{ $tahun }}
    </div>

    <div class="text-center text-bold" style="font-size: 10.5pt; margin-top: 10px; margin-bottom: 10px; text-transform: uppercase;">
        LEMBAR CEKLIS PROGRAM STUDI & UNIT KERJA SASARAN PENGISIAN LED<br>
        SIKLUS BULAN MUTU TAHUN {{ $tahun }}
    </div>

    <table class="table-bordered">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 8%;">Ceklis (&check;)</th>
                <th style="width: 35%;">Nama Program Studi / Unit Kerja</th>
                <th style="width: 17%;">Kategori Unit</th>
                <th style="width: 20%;">Penanggung Jawab (Auditee)</th>
                <th style="width: 15%;">Paraf / Tanda Terima</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp

            <!-- Kelompok 1: Program Studi -->
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="6" style="padding: 4px 6px;">I. KELOMPOK PROGRAM STUDI (PRODI)</td>
            </tr>
            @foreach($prodiUnits as $u)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">
                    <div class="table-checkbox">{{ in_array($u->id, $selectedUnitIds) ? '✓' : '' }}</div>
                </td>
                <td><strong>{{ $u->nama }}</strong></td>
                <td class="text-center">Program Studi</td>
                <td>Kaprodi / Tim LED Prodi</td>
                <td></td>
            </tr>
            @endforeach

            <!-- Kelompok 2: Fakultas -->
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="6" style="padding: 4px 6px;">II. KELOMPOK FAKULTAS (UPPS)</td>
            </tr>
            @foreach($fakultasUnits as $u)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">
                    <div class="table-checkbox">{{ in_array($u->id, $selectedUnitIds) ? '✓' : '' }}</div>
                </td>
                <td><strong>{{ $u->nama }}</strong></td>
                <td class="text-center">Fakultas (UPPS)</td>
                <td>Dekan / Wakil Dekan</td>
                <td></td>
            </tr>
            @endforeach

            <!-- Kelompok 3: Lembaga, Biro & Unit Penunjang -->
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="6" style="padding: 4px 6px;">III. KELOMPOK BIRO, LEMBAGA & UNIT PENUNJANG</td>
            </tr>
            @foreach($nonAkademikUnits as $u)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">
                    <div class="table-checkbox">{{ in_array($u->id, $selectedUnitIds) ? '✓' : '' }}</div>
                </td>
                <td><strong>{{ $u->nama }}</strong></td>
                <td class="text-center">{{ ucfirst($u->jenis ?? 'Non-Akademik') }}</td>
                <td>Kepala Biro / Lembaga</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-box" style="margin-top: 15px;">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 8.5pt; font-style: italic; color: #444;">
                    * Lembar ini berfungsi sebagai lembar ekspedisi, tanda terima surat edaran, serta monitoring kepatuhan pengisian LED.
                </div>
            </td>
            <td style="width: 50%; text-align: right; padding-right: 15px;">
                <div>Palembang, {{ $tanggalSurat }}</div>
                <div><strong>Kepala LPMA USS,</strong></div>
                <div class="signature-space" style="height: 50px;"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
            </td>
        </tr>
    </table>

</body>
</html>
