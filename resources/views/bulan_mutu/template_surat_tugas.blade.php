<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Tugas Tim Auditor Mutu Internal - {{ $tahun }}</title>
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

    <!-- NOMOR DAN JUDUL SURAT TUGAS -->
    <div class="letter-number">SURAT TUGAS TIM AUDITOR MUTU INTERNAL</div>
    <div class="text-center" style="margin-top: 3px; margin-bottom: 8px; font-size: 10.5pt;">
        Nomor: {{ $nomorSurat }}
    </div>

    <div class="text-center text-bold" style="font-size: 10.5pt; margin-bottom: 12px; text-transform: uppercase; line-height: 1.25;">
        TENTANG<br>
        {{ $judulTentang ?? 'PENUGASAN TIM AUDITOR PELAKSANAAN AUDIT MUTU INTERNAL (AMI)' }}<br>
        SIKLUS BULAN MUTU TAHUN {{ $tahun }}
    </div>

    <!-- PENGANTAR PENUGASAN -->
    <div class="content">
        <p style="margin-top: 0; margin-bottom: 6px;">
            Berdasarkan Peraturan Rektor Universitas Sumatera Selatan tentang Sistem Penjaminan Mutu Internal (SPMI) dan Program Kerja Lembaga Penjaminan Mutu Akademik (LPMA) mengenai Pelaksanaan Siklus SPMI Tahun <strong>{{ $tahun }}</strong>, dengan ini Ketua LPMA Universitas Sumatera Selatan memberikan penugasan kepada Tim Auditor Mutu Internal untuk melaksanakan tahapan <strong>{{ $namaTahapan ?? 'Audit Mutu Internal' }}</strong>.
        </p>

        <!-- KOTAK CEKLIS SASARAN AUDIT PER PRODI / UNIT -->
        <div style="font-size: 9pt; font-weight: bold; margin-top: 8px; margin-bottom: 4px;">
            Sasaran Audit Kepatuhan (Beri tanda centang &check; pada kotak Program Studi / Unit yang ditugaskan):
        </div>
        
        <div style="border: 1px solid #000; padding: 6px 10px; margin-bottom: 10px; background-color: #fafafa; font-size: 8.5pt;">
            <table style="width: 100%; border: none;">
                <tr>
                    <!-- Kolom 1: Program Studi & Fakultas (Akademik) -->
                    <td style="width: 52%; vertical-align: top; padding-right: 8px;">
                        <div style="font-weight: bold; border-bottom: 1px dashed #666; margin-bottom: 4px; padding-bottom: 2px;">
                            A. PROGRAM STUDI & FAKULTAS ({{ !empty($isAkademik) ? 'SASARAN UTAMA AMAI' : 'AKADEMIK' }}):
                        </div>
                        @foreach($prodiUnits as $pu)
                            @php
                                $puChecked = !empty($selectedUnitIds) 
                                    ? in_array($pu->id, $selectedUnitIds) 
                                    : (!empty($isAkademik) || !empty($isGeneral));
                            @endphp
                            <div style="margin-bottom: 2.5px;">
                                <span class="checkbox-square">{{ $puChecked ? '✓' : '' }}</span>
                                <strong>{{ $pu->nama }}</strong>
                            </div>
                        @endforeach
                        @foreach($fakultasUnits as $fu)
                            @php
                                $fuChecked = !empty($selectedUnitIds) 
                                    ? in_array($fu->id, $selectedUnitIds) 
                                    : (!empty($isAkademik) || !empty($isGeneral));
                            @endphp
                            <div style="margin-bottom: 2.5px;">
                                <span class="checkbox-square">{{ $fuChecked ? '✓' : '' }}</span>
                                {{ $fu->nama }}
                            </div>
                        @endforeach
                    </td>

                    <!-- Kolom 2: Lembaga & Unit Non-Akademik -->
                    <td style="width: 48%; vertical-align: top; padding-left: 8px; border-left: 1px solid #ccc;">
                        <div style="font-weight: bold; border-bottom: 1px dashed #666; margin-bottom: 4px; padding-bottom: 2px;">
                            B. LEMBAGA, BIRO & UNIT ({{ !empty($isNonAkademik) ? 'SASARAN UTAMA AUDIT NON-AKADEMIK' : 'NON-AKADEMIK' }}):
                        </div>
                        @foreach($nonAkademikUnits as $nu)
                            @php
                                $nuChecked = !empty($selectedUnitIds) 
                                    ? in_array($nu->id, $selectedUnitIds) 
                                    : (!empty($isNonAkademik) || !empty($isGeneral));
                            @endphp
                            <div style="margin-bottom: 2.5px;">
                                <span class="checkbox-square">{{ $nuChecked ? '✓' : '' }}</span>
                                {{ $nu->nama }}
                            </div>
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>

        <p style="margin-top: 0; margin-bottom: 6px;">
            <strong>Ketentuan dan Ruang Lingkup Penugasan:</strong>
        </p>
        <ol style="margin-top: 4px; margin-bottom: 8px; padding-left: 20px; line-height: 1.35;">
            <li style="margin-bottom: 4px;">
                Melaksanakan penelaahan berkas dokumen Laporan Evaluasi Diri (LED) dan bukti fisik pelaksanaan PPEPP (<em>Desk Evaluation</em>) pada sistem AMIRA.
            </li>
            <li style="margin-bottom: 4px;">
                Melaksanakan audit kepatuhan lapangan (<em>Visitasi Lapangan</em>) ke Program Studi / Unit Kerja auditee sesuai jadwal pelaksanaan audit.
            </li>
            <li style="margin-bottom: 4px;">
                Mengisi dan memverifikasi Kertas Kerja Audit (Form 4 / KKA) serta merumuskan Deskripsi Temuan & Rekomendasi Tindak Lanjut (Form 5 / RTL).
            </li>
            <li style="margin-bottom: 4px;">
                Menyampaikan Berita Acara dan Ringkasan Temuan Audit kepada LPMA selambat-lambatnya pada akhir periode pelaksanaan audit.
            </li>
        </ol>

        <p style="margin-top: 0; margin-bottom: 6px;">
            Demikian Surat Tugas ini diterbitkan untuk dilaksanakan dengan penuh integritas, profesionalisme, dan rasa tanggung jawab.
        </p>
    </div>

    <!-- TANDA TANGAN & PENGESAHAN -->
    <table class="signature-box">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 9.5pt; line-height: 1.3;">
                    <strong>Mengetahui / Menyetujui:</strong><br>
                    Rektor Universitas Sumatera Selatan,
                    <div class="signature-space"></div>
                    <div><strong><u>{{ $rektorNama }}</u></strong></div>
                    <div>NIP / NIDN: ............................................</div>
                </div>
            </td>
            <td style="width: 50%; text-align: right; padding-right: 15px;">
                <div>Ditetapkan di : Palembang</div>
                <div>Pada tanggal : {{ $tanggalSurat }}</div>
                <div style="height: 4px;"></div>
                <div><strong>Ketua Lembaga Penjaminan Mutu Akademik,</strong></div>
                <div class="signature-space"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
                <div>NIP / NIDN: ............................................</div>
            </td>
        </tr>
    </table>

    <!-- HALAMAN 2: LAMPIRAN MATRIKS PENUGASAN & CEKLIS PER PRODI/UNIT -->
    <div class="page-break"></div>

    <div class="header text-center border-kop">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
    </div>

    <div style="font-size: 9.5pt; margin-bottom: 8px;">
        <strong>LAMPIRAN SURAT TUGAS TIM AUDITOR MUTU INTERNAL</strong><br>
        Nomor &nbsp;: {{ $nomorSurat }}<br>
        Tanggal : {{ $tanggalSurat }}<br>
        Perihal : Matriks Penugasan Tim Auditor & Lembar Ceklis Visitasi Program Studi / Unit Kerja
    </div>

    <div class="text-center text-bold" style="font-size: 10.5pt; margin-top: 10px; margin-bottom: 10px; text-transform: uppercase;">
        MATRIKS PENUGASAN TIM AUDITOR & LEMBAR CEKLIS VISITASI AUDIT TAHUN {{ $tahun }}
    </div>

    <table class="table-bordered">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 8%;">Ceklis (&check;)</th>
                <th style="width: 30%;">Program Studi / Unit Kerja</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 22%;">Auditor Ketua</th>
                <th style="width: 20%;">Auditor Anggota</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp

            @if(!empty($isNonAkademik))
                <!-- Jika Non-Akademik, Kelompok Biro & Lembaga Ditampilkan Pertama Sebagai Prioritas -->
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td colspan="6" style="padding: 4px 6px;">I. KELOMPOK BIRO, LEMBAGA & UNIT PENUNJANG (SASARAN UTAMA AUDIT)</td>
                </tr>
                @foreach($nonAkademikUnits as $u)
                @php 
                    $assign = $unitAssignments[$u->id] ?? null; 
                    $rowChecked = !empty($selectedUnitIds) ? in_array($u->id, $selectedUnitIds) : true;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">
                        <div class="table-checkbox">{{ $rowChecked ? '✓' : '' }}</div>
                    </td>
                    <td><strong>{{ $u->nama }}</strong></td>
                    <td class="text-center">{{ ucfirst($u->jenis ?? 'Non-Akademik') }}</td>
                    <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                    <td>{{ $assign['anggota'] ?? '-' }}</td>
                </tr>
                @endforeach

                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td colspan="6" style="padding: 4px 6px;">II. KELOMPOK PROGRAM STUDI & FAKULTAS (AKADEMIK)</td>
                </tr>
                @foreach($prodiUnits as $u)
                @php 
                    $assign = $unitAssignments[$u->id] ?? null; 
                    $rowChecked = !empty($selectedUnitIds) && in_array($u->id, $selectedUnitIds);
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">
                        <div class="table-checkbox">{{ $rowChecked ? '✓' : '' }}</div>
                    </td>
                    <td><strong>{{ $u->nama }}</strong></td>
                    <td class="text-center">Program Studi</td>
                    <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                    <td>{{ $assign['anggota'] ?? '-' }}</td>
                </tr>
                @endforeach
                @foreach($fakultasUnits as $u)
                @php 
                    $assign = $unitAssignments[$u->id] ?? null; 
                    $rowChecked = !empty($selectedUnitIds) && in_array($u->id, $selectedUnitIds);
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">
                        <div class="table-checkbox">{{ $rowChecked ? '✓' : '' }}</div>
                    </td>
                    <td><strong>{{ $u->nama }}</strong></td>
                    <td class="text-center">Fakultas</td>
                    <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                    <td>{{ $assign['anggota'] ?? '-' }}</td>
                </tr>
                @endforeach

            @else
                <!-- Kelompok 1: Program Studi -->
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td colspan="6" style="padding: 4px 6px;">I. KELOMPOK PROGRAM STUDI (AMAI)</td>
                </tr>
                @foreach($prodiUnits as $u)
                @php 
                    $assign = $unitAssignments[$u->id] ?? null; 
                    $rowChecked = !empty($selectedUnitIds) ? in_array($u->id, $selectedUnitIds) : (!empty($isAkademik) || !empty($isGeneral));
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">
                        <div class="table-checkbox">{{ $rowChecked ? '✓' : '' }}</div>
                    </td>
                    <td><strong>{{ $u->nama }}</strong></td>
                    <td class="text-center">Program Studi</td>
                    <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                    <td>{{ $assign['anggota'] ?? '-' }}</td>
                </tr>
                @endforeach

                <!-- Kelompok 2: Fakultas (UPPS) -->
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td colspan="6" style="padding: 4px 6px;">II. KELOMPOK FAKULTAS (UPPS)</td>
                </tr>
                @foreach($fakultasUnits as $u)
                @php 
                    $assign = $unitAssignments[$u->id] ?? null; 
                    $rowChecked = !empty($selectedUnitIds) ? in_array($u->id, $selectedUnitIds) : (!empty($isAkademik) || !empty($isGeneral));
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">
                        <div class="table-checkbox">{{ $rowChecked ? '✓' : '' }}</div>
                    </td>
                    <td><strong>{{ $u->nama }}</strong></td>
                    <td class="text-center">Fakultas</td>
                    <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                    <td>{{ $assign['anggota'] ?? '-' }}</td>
                </tr>
                @endforeach

                <!-- Kelompok 3: Lembaga, Biro & Unit Penunjang -->
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td colspan="6" style="padding: 4px 6px;">III. KELOMPOK BIRO, LEMBAGA & UNIT PENUNJANG</td>
                </tr>
                @foreach($nonAkademikUnits as $u)
                @php 
                    $assign = $unitAssignments[$u->id] ?? null; 
                    $rowChecked = !empty($selectedUnitIds) ? in_array($u->id, $selectedUnitIds) : (!empty($isGeneral));
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">
                        <div class="table-checkbox">{{ $rowChecked ? '✓' : '' }}</div>
                    </td>
                    <td><strong>{{ $u->nama }}</strong></td>
                    <td class="text-center">{{ ucfirst($u->jenis ?? 'Non-Akademik') }}</td>
                    <td>{{ $assign['ketua'] ?? 'Tim Auditor LPMA' }}</td>
                    <td>{{ $assign['anggota'] ?? '-' }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <table class="signature-box" style="margin-top: 15px;">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 8.5pt; font-style: italic; color: #444;">
                    * Lembar ini berfungsi sebagai lembar bukti penugasan resmi, presensi visitasi lapangan, dan dasar verifikasi Kertas Kerja Audit (KKA).
                </div>
            </td>
            <td style="width: 50%; text-align: right; padding-right: 15px;">
                <div>Palembang, {{ $tanggalSurat }}</div>
                <div><strong>Ketua LPMA USS,</strong></div>
                <div class="signature-space" style="height: 50px;"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
            </td>
        </tr>
    </table>

</body>
</html>
