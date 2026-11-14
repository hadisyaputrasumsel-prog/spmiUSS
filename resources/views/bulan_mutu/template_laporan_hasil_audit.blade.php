<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara & Lembar Ceklis Penyusunan Laporan Hasil AMAI/EMI - {{ $tahun }}</title>
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
        .signature-space { height: 55px; }
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

    <!-- NOMOR DAN JUDUL BERITA ACARA -->
    <div class="letter-number">BERITA ACARA & LEMBAR KENDALI PENYUSUNAN LAPORAN HASIL AUDIT</div>
    <div class="text-center" style="margin-top: 3px; margin-bottom: 8px; font-size: 10.5pt;">
        Nomor: {{ $nomorSurat }}
    </div>

    <div class="text-center text-bold" style="font-size: 10.5pt; margin-bottom: 12px; text-transform: uppercase; line-height: 1.25;">
        TENTANG<br>
        REKAPITULASI DAN CEKLIS KELENGKAPAN LAPORAN HASIL AUDIT MUTU AKADEMIK INTERNAL (AMAI)<br>
        DAN EVALUASI MUTU INTERNAL (EMI) SIKLUS TAHUN {{ $tahun }}
    </div>

    <!-- PENGANTAR BERITA ACARA -->
    <div class="content">
        <p style="margin-top: 0; margin-bottom: 6px;">
            Pada hari ini, <strong>{{ $tanggalSuratFormatted }}</strong>, bertempat di Kantor Lembaga Penjaminan Mutu Akademik (LPMA) Universitas Sumatera Selatan, telah dilaksanakan koordinasi dan finalisasi penyusunan berkas <strong>Laporan Hasil Audit Mutu Akademik Internal (AMAI)</strong> dan <strong>Audit Mutu Internal Unit Non-Akademik (EMI)</strong> Tahun Pelaksanaan <strong>{{ $tahun }}</strong>.
        </p>

        <!-- KOTAK CEKLIS REKAPITULASI PER PRODI / UNIT -->
        <div style="font-size: 9pt; font-weight: bold; margin-top: 8px; margin-bottom: 4px;">
            Status Ceklis Penyerahan Laporan Hasil Audit Per Program Studi & Unit Kerja:
        </div>
        
        <div style="border: 1px solid #000; padding: 6px 10px; margin-bottom: 10px; background-color: #fafafa; font-size: 8.5pt;">
            <table style="width: 100%; border: none;">
                <tr>
                    <!-- Kolom 1: Program Studi & Fakultas (AMAI) -->
                    <td style="width: 52%; vertical-align: top; padding-right: 8px;">
                        <div style="font-weight: bold; border-bottom: 1px dashed #666; margin-bottom: 4px; padding-bottom: 2px;">
                            A. PROGRAM STUDI & FAKULTAS (AMAI):
                        </div>
                        @foreach($prodiUnits as $pu)
                            @php
                                $puChecked = !empty($selectedUnitIds) ? in_array($pu->id, $selectedUnitIds) : true;
                            @endphp
                            <div style="margin-bottom: 2.5px;">
                                <span class="checkbox-square">{{ $puChecked ? '&#10003;' : '' }}</span>
                                <strong>{{ $pu->nama }}</strong>
                            </div>
                        @endforeach
                        @foreach($fakultasUnits as $fu)
                            @php
                                $fuChecked = !empty($selectedUnitIds) ? in_array($fu->id, $selectedUnitIds) : true;
                            @endphp
                            <div style="margin-bottom: 2.5px;">
                                <span class="checkbox-square">{{ $fuChecked ? '&#10003;' : '' }}</span>
                                {{ $fu->nama }}
                            </div>
                        @endforeach
                    </td>

                    <!-- Kolom 2: Lembaga & Unit Non-Akademik (EMI) -->
                    <td style="width: 48%; vertical-align: top; padding-left: 8px; border-left: 1px solid #ccc;">
                        <div style="font-weight: bold; border-bottom: 1px dashed #666; margin-bottom: 4px; padding-bottom: 2px;">
                            B. LEMBAGA, BIRO & UNIT (EMI):
                        </div>
                        @foreach($nonAkademikUnits as $nu)
                            @php
                                $nuChecked = !empty($selectedUnitIds) ? in_array($nu->id, $selectedUnitIds) : true;
                            @endphp
                            <div style="margin-bottom: 2.5px;">
                                <span class="checkbox-square">{{ $nuChecked ? '&#10003;' : '' }}</span>
                                {{ $nu->nama }}
                            </div>
                        @endforeach
                    </td>
                </tr>
            </table>
            <div style="margin-top: 5px; font-size: 8pt; font-style: italic; color: #444;">
                * Tanda centang (&check;) menandakan bahwa berkas laporan hasil audit unit kerja bersangkutan telah lengkap dan diserahkan ke LPMA.
            </div>
        </div>

        <p style="margin-top: 0; margin-bottom: 6px;">
            <strong>Pernyataan Hasil Pemeriksaan Dokumen Audit:</strong>
        </p>
        <ol style="margin-top: 4px; margin-bottom: 8px; padding-left: 20px; line-height: 1.35;">
            <li style="margin-bottom: 4px;">
                Seluruh Kertas Kerja Audit (Form 4 / KKA) telah ditandatangani oleh Auditi, Auditor Ketua, dan Penjamin Mutu LPMA.
            </li>
            <li style="margin-bottom: 4px;">
                Matriks Deskripsi Temuan & Rencana Tindak Lanjut (Form 5 / RTL) telah memuat analisis akar penyebab masalah (RCA) dan jadwal perbaikan yang disepakati.
            </li>
            <li style="margin-bottom: 4px;">
                Rekapitulasi nilai capaian rata-rata standar per unit kerja dan kelompok standar SPMI telah terangkum secara valid dalam Buku Laporan Hasil AMI.
            </li>
            <li style="margin-bottom: 4px;">
                Laporan Hasil Audit ini disahkan untuk selanjutnya diajukan ke forum <strong>Rapat Tinjauan Manajemen (RTM)</strong> bersama Rektor dan Pimpinan Universitas.
            </li>
        </ol>

        <p style="margin-top: 0; margin-bottom: 6px;">
            Demikian Berita Acara & Lembar Kendali ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagai dokumen pertanggungjawaban penjaminan mutu internal.
        </p>
    </div>

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="signature-box">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 9.5pt; line-height: 1.3;">
                    <strong>Ketua Tim Auditor Mutu Internal,</strong>
                    <div class="signature-space"></div>
                    <div><strong><u>{{ $ketuaAuditorNama ?? 'Dr. Hadi Syaputra, S.Kom., M.Kom.' }}</u></strong></div>
                    <div>NIP / NIDN: ............................................</div>
                </div>
            </td>
            <td style="width: 50%; text-align: right; padding-right: 15px;">
                <div>Palembang, {{ $tanggalSurat }}</div>
                <div style="height: 4px;"></div>
                <div><strong>Mengetahui & Menerima:</strong></div>
                <div><strong>Kepala LPMA USS,</strong></div>
                <div class="signature-space"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
                <div>NIP / NIDN: ............................................</div>
            </td>
        </tr>
    </table>

    <!-- HALAMAN 2: LAMPIRAN CEKLIS KELENGKAPAN BERKAS AUDIT PER PRODI/UNIT -->
    <div class="page-break"></div>

    <div class="header text-center border-kop">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
    </div>

    <div style="font-size: 9.5pt; margin-bottom: 8px;">
        <strong>LAMPIRAN BERITA ACARA PENYUSUNAN LAPORAN HASIL AUDIT</strong><br>
        Nomor &nbsp;: {{ $nomorSurat }}<br>
        Tanggal : {{ $tanggalSurat }}<br>
        Perihal : Lembar Ceklis Rincian Penyerahan Berkas Laporan Hasil Audit Per Program Studi & Unit Kerja
    </div>

    <div class="text-center text-bold" style="font-size: 10.5pt; margin-top: 10px; margin-bottom: 10px; text-transform: uppercase;">
        MATRIKS CEKLIS KELENGKAPAN BERKAS HASIL AUDIT TAHUN {{ $tahun }}
    </div>

    <table class="table-bordered">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 8%;">Ceklis (&check;)</th>
                <th style="width: 32%;">Program Studi / Unit Kerja</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 25%;">Auditor Ketua</th>
                <th style="width: 15%;">Status Berkas</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp

            <!-- Kelompok 1: Program Studi -->
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="6" style="padding: 4px 6px;">I. KELOMPOK PROGRAM STUDI (AMAI)</td>
            </tr>
            @foreach($prodiUnits as $u)
            @php 
                $assign = $unitAssignments[$u->id] ?? null; 
                $rowChecked = !empty($selectedUnitIds) ? in_array($u->id, $selectedUnitIds) : true;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">
                    <div class="table-checkbox">{{ $rowChecked ? '&#10003;' : '' }}</div>
                </td>
                <td><strong>{{ $u->nama }}</strong></td>
                <td class="text-center">Program Studi</td>
                <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                <td class="text-center" style="font-size: 8.5pt;">Lengkap (Form 4 & 5)</td>
            </tr>
            @endforeach

            <!-- Kelompok 2: Fakultas (UPPS) -->
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="6" style="padding: 4px 6px;">II. KELOMPOK FAKULTAS (UPPS)</td>
            </tr>
            @foreach($fakultasUnits as $u)
            @php 
                $assign = $unitAssignments[$u->id] ?? null; 
                $rowChecked = !empty($selectedUnitIds) ? in_array($u->id, $selectedUnitIds) : true;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">
                    <div class="table-checkbox">{{ $rowChecked ? '&#10003;' : '' }}</div>
                </td>
                <td><strong>{{ $u->nama }}</strong></td>
                <td class="text-center">Fakultas</td>
                <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                <td class="text-center" style="font-size: 8.5pt;">Lengkap (Form 4 & 5)</td>
            </tr>
            @endforeach

            <!-- Kelompok 3: Lembaga, Biro & Unit Penunjang -->
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="6" style="padding: 4px 6px;">III. KELOMPOK BIRO, LEMBAGA & UNIT PENUNJANG (EMI)</td>
            </tr>
            @foreach($nonAkademikUnits as $u)
            @php 
                $assign = $unitAssignments[$u->id] ?? null; 
                $rowChecked = !empty($selectedUnitIds) ? in_array($u->id, $selectedUnitIds) : true;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">
                    <div class="table-checkbox">{{ $rowChecked ? '&#10003;' : '' }}</div>
                </td>
                <td><strong>{{ $u->nama }}</strong></td>
                <td class="text-center">{{ ucfirst($u->jenis ?? 'Non-Akademik') }}</td>
                <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                <td class="text-center" style="font-size: 8.5pt;">Lengkap (Form 4 & 5)</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-box" style="margin-top: 15px;">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 8.5pt; font-style: italic; color: #444;">
                    * Lembar kendali ini diserahkan kepada LPMA sebagai bukti penyelesaian tahapan audit lapangan sebelum pelaksanaan RTM.
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
