<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Permintaan Tindakan Korektif (PTK) - AMI {{ $tahun }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 9.5pt; 
            color: #000; 
            line-height: 1.35; 
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-justify { text-align: justify; }
        
        /* Header Kop Surat */
        .header { margin-bottom: 8px; }
        .header-title { font-size: 12pt; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .header-sub { font-size: 9pt; color: #222; margin-top: 2px; }
        .border-kop { border-bottom: 3px double #000; margin-bottom: 10px; padding-bottom: 5px; }
        
        .letter-number { 
            text-align: center; 
            font-weight: bold; 
            text-decoration: underline; 
            font-size: 11pt; 
            letter-spacing: 0.5px;
        }
        
        .content { 
            margin-top: 8px; 
            margin-bottom: 10px; 
        }
        
        table { border-collapse: collapse; width: 100%; }
        table.table-bordered { 
            margin-top: 8px; 
            margin-bottom: 10px; 
            font-size: 8.5pt; 
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
        
        /* Box Form PTK */
        .box-title {
            background-color: #f1f5f9;
            border: 1px solid #000;
            padding: 3px 6px;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
            border-bottom: none;
        }
        table.ptk-grid {
            border: 1px solid #000;
            margin-bottom: 8px;
            font-size: 8.5pt;
        }
        table.ptk-grid td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        .label-cell {
            width: 25%;
            font-weight: bold;
            background-color: #f8fafc;
        }
        
        /* Checkbox Box Styling */
        .checkbox-square {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1.2px solid #000;
            margin-right: 4px;
            vertical-align: middle;
            text-align: center;
            line-height: 10px;
            font-size: 8pt;
            font-weight: bold;
            background-color: #fff;
        }
        
        .signature-box { 
            width: 100%; 
            margin-top: 15px; 
            page-break-inside: avoid; 
        }
        .signature-box td { 
            border: none; 
            padding: 2px 4px; 
            vertical-align: top; 
            text-align: center;
        }
        .signature-space { height: 48px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- HALAMAN 1: REKAPITULASI PENERBITAN PTK SIKLUS AMI -->
    <div class="header text-center border-kop">
        <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
        <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
        <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan | Email: lpma@universitassumsel.ac.id</div>
    </div>

    <div class="letter-number">REKAPITULASI PENERBITAN PERMINTAAN TINDAKAN KOREKTIF (PTK)</div>
    <div class="text-center" style="margin-top: 3px; margin-bottom: 6px; font-size: 10pt;">
        Nomor: {{ $nomorSurat }}
    </div>

    <div class="text-center text-bold" style="font-size: 10pt; margin-bottom: 10px; text-transform: uppercase;">
        DAFTAR TEMUAN AUDIT MUTU INTERNAL & PERMINTAAN TINDAKAN KOREKTIF (PTK)<br>
        SIKLUS BULAN MUTU TAHUN {{ $tahun }}
    </div>

    <div class="content">
        <p style="margin-top: 0; margin-bottom: 6px;">
            Berdasarkan hasil pelaksanaan visitasi Audit Mutu Akademik Internal (AMAI) dan Audit Mutu Non-Akademik (EMI) Tahun <strong>{{ $tahun }}</strong>, bersama ini Lembaga Penjaminan Mutu Akademik (LPMA) menerbitkan <strong>Formulir Permintaan Tindakan Korektif (PTK)</strong> kepada unit-unit kerja yang memiliki temuan ketidaksesuaian (KTS Mayor, KTS Minor, dan Observasi) untuk ditindaklanjuti sesuai siklus PPEPP:
        </p>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Unit Kerja / Auditee</th>
                    <th style="width: 22%;">Kode Standar SPMI</th>
                    <th style="width: 14%;">Kategori</th>
                    <th style="width: 20%;">Batas Waktu</th>
                    <th style="width: 14%;">Status PTK</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($detailFindings as $f)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>{{ $f->unit?->nama ?? 'Unit' }}</strong></td>
                    <td>{{ $f->standar_kode }}</td>
                    <td class="text-center">
                        <span style="font-weight: bold; color: {{ str_contains($f->kategori_temuan, 'Mayor') ? '#dc2626' : (str_contains($f->kategori_temuan, 'Minor') ? '#d97706' : '#2563eb') }};">
                            {{ $f->kategori_temuan }}
                        </span>
                    </td>
                    <td class="text-center">{{ $f->batas_waktu ? \Carbon\Carbon::parse($f->batas_waktu)->translatedFormat('d M Y') : '30 Hari Kerja' }}</td>
                    <td class="text-center">
                        <strong>{{ in_array($f->status_tindak_lanjut, ['Selesai', 'Sudah']) ? 'Selesai' : ($f->status_tindak_lanjut ?: 'Proses PTK') }}</strong>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 10px; font-style: italic;">
                        Seluruh standar yang diaudit telah memenuhi kriteria Sesuai (Tidak ada penerbitan PTK KTS Mayor/Minor).
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($findings->count() > $detailFindings->count())
            <div style="font-size: 8pt; font-style: italic; color: #475569; margin-top: 2px;">
                * Menampilkan ringkasan {{ $detailFindings->count() }} dari total {{ $findings->count() }} temuan PTK universitas. Untuk mengunduh PTK unit spesifik, gunakan filter unit_id.
            </div>
        @endif

        <div style="font-size: 8.5pt; font-style: italic; color: #444; margin-top: 4px;">
            * Setiap butir temuan ketidaksesuaian di atas wajib dilengkapi dengan analisis akar masalah (RCA), rencana perbaikan, dan verifikasi penyelesaian pada lembar Formulir PTK terlampir.
        </div>
    </div>

    <!-- TANDA TANGAN PENGESAHAN REKAPITULASI -->
    <table class="signature-box">
        <tr>
            <td style="width: 50%;">
                <div>Mengetahui,</div>
                <div><strong>Ketua Tim Auditor Mutu Internal,</strong></div>
                <div class="signature-space"></div>
                <div><strong><u>{{ $ketuaAuditorNama ?? 'Dr. Hadi Syaputra, S.Kom., M.Kom.' }}</u></strong></div>
                <div>NIDN: 0214057801</div>
            </td>
            <td style="width: 50%;">
                <div>Palembang, {{ $tanggalSurat }}</div>
                <div><strong>Kepala Lembaga Penjaminan Mutu Akademik,</strong></div>
                <div class="signature-space"></div>
                <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
                <div>NIDN / NIP: ............................................</div>
            </td>
        </tr>
    </table>

    <!-- HALAMAN BERIKUTNYA: DETAIL FORMULIR PTK PER TEMUAN (FORM 5 CAR) -->
    @if($detailFindings->isNotEmpty())
        @foreach($detailFindings as $idx => $f)
            <div class="page-break"></div>

            <div class="header text-center border-kop">
                <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
                <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
                <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
            </div>

            <div class="letter-number" style="font-size: 10.5pt;">FORMULIR PERMINTAAN TINDAKAN KOREKTIF (PTK) / FORM 5 RCA</div>
            <div class="text-center" style="margin-top: 2px; margin-bottom: 8px; font-size: 9pt;">
                No. PTK: PTK/{{ strtoupper(str_replace(['Prodi ', 'Fakultas ', ' '], ['', '', ''], $f->unit?->nama ?? 'USS')) }}/{{ $tahun }}/{{ str_pad($idx + 1, 3, '0', STR_PAD_LEFT) }}
            </div>

            <!-- IDENTITAS AUDIT & PTK -->
            <table class="ptk-grid">
                <tr>
                    <td class="label-cell">Unit Kerja / Auditee</td>
                    <td style="width: 35%;"><strong>{{ $f->unit?->nama }}</strong></td>
                    <td class="label-cell">Tanggal Audit</td>
                    <td style="width: 25%;">{{ $f->tanggal ? \Carbon\Carbon::parse($f->tanggal)->translatedFormat('d F Y') : $tanggalSurat }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Standar SPMI Acuan</td>
                    <td colspan="3">
                        <strong>{{ $f->standar_kode }}</strong> — {{ $f->standard->nama ?? 'Standar Mutu SPMI USS' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Kategori Temuan</td>
                    <td>
                        <span style="font-weight: bold; color: {{ str_contains($f->kategori_temuan, 'Mayor') ? '#dc2626' : (str_contains($f->kategori_temuan, 'Minor') ? '#d97706' : '#2563eb') }};">
                            [{{ $f->kategori_temuan }}]
                        </span>
                    </td>
                    <td class="label-cell">Auditor Ketua</td>
                    <td>Dr. Hadi Syaputra, S.Kom., M.Kom.</td>
                </tr>
            </table>

            <!-- BAGIAN I: URAIAN TEMUAN KETIDAKSESUAIAN -->
            <div class="box-title">I. Uraian Temuan Ketidaksesuaian (KTS / Kondisi Nyata)</div>
            <table class="ptk-grid">
                <tr>
                    <td class="label-cell">Deskripsi Temuan</td>
                    <td>{{ $f->uraian }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Akibat / Dampak</td>
                    <td>{{ $f->akibat ?: 'Berpotensi menurunkan capaian mutu akreditasi dan kepuasan pemangku kepentingan bila tidak segera diselaraskan.' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Rekomendasi Auditor</td>
                    <td>{{ $f->rekomendasi ?: 'Unit kerja wajib menyusun SOP perbaikan, melengkapi dokumen regulasi pendukung, dan melaporkan bukti realisasi ke LPMA.' }}</td>
                </tr>
            </table>

            <!-- BAGIAN II: ANALISIS AKAR MASALAH (ROOT CAUSE ANALYSIS) -->
            <div class="box-title">II. Analisis Akar Masalah (Root Cause Analysis - RCA 5-Whys)</div>
            <table class="ptk-grid">
                <tr>
                    <td class="label-cell">Akar Masalah (Why 1 s.d. 5)</td>
                    <td>
                        {{ $f->akar_penyebab ?: 'Belum adanya SOP terpadu dan keterbatasan sosialisasi pedoman operasional pelaksanaan standar kepada tim pelaksana unit.' }}
                    </td>
                </tr>
            </table>

            <!-- BAGIAN III: RENCANA TINDAKAN KOREKTIF (RTL / KOREKSI) -->
            <div class="box-title">III. Rencana Tindakan Korektif (RTL Perbaikan)</div>
            <table class="ptk-grid">
                <tr>
                    <td class="label-cell">Tindakan Koreksi</td>
                    <td>{{ $f->rencana_tindakan ?: 'Menerbitkan instrumen panduan, menyelenggarakan rapat koordinasi internal, dan melengkapi berkas portofolio fisik.' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Penanggung Jawab (PIC)</td>
                    <td>{{ $f->pic ?: ($f->unit?->pimpinan ?: 'Pimpinan Unit / Tim Pengelola Standar') }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Target Batas Waktu</td>
                    <td><strong>{{ $f->batas_waktu ? \Carbon\Carbon::parse($f->batas_waktu)->translatedFormat('d F Y') : '30 Hari Kalender' }}</strong></td>
                </tr>
            </table>

            <!-- BAGIAN IV: RENCANA TINDAKAN PENCEGAHAN -->
            <div class="box-title">IV. Rencana Tindakan Pencegahan (Preventive Action)</div>
            <table class="ptk-grid">
                <tr>
                    <td class="label-cell">Tindakan Pencegahan</td>
                    <td>{{ $f->rencana_pencegahan ?: 'Melakukan monitoring berkala setiap semester dan memasukkan indikator pemenuhan standar ke dalam Rencana Operasional Unit.' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Jadwal Pencegahan</td>
                    <td>{{ $f->jadwal_pencegahan ?: 'Setiap Awal Semester Genap & Ganjil' }}</td>
                </tr>
            </table>

            <!-- BAGIAN V: VERIFIKASI TINDAK LANJUT OLEH AUDITOR -->
            <div class="box-title">V. Verifikasi Tindak Lanjut oleh Tim Auditor (Closing PTK)</div>
            <table class="ptk-grid">
                <tr>
                    <td class="label-cell">Status Penutupan PTK</td>
                    <td>
                        <span class="checkbox-square">{{ in_array($f->status_tindak_lanjut, ['Selesai', 'Sudah']) ? '✓' : '' }}</span> <strong>Selesai (Closed)</strong> &nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="checkbox-square">{{ $f->status_tindak_lanjut === 'Proses PTK' ? '✓' : '' }}</span> <strong>Proses PTK (Perlu Revisi)</strong> &nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="checkbox-square">{{ $f->status_tindak_lanjut === 'Sedang Berjalan' ? '✓' : '' }}</span> <strong>Sedang Berjalan</strong>
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Catatan Verifikasi Auditor</td>
                    <td>
                        {{ $f->catatan_tindak_lanjut ?: 'Telah diverifikasi oleh auditor dan dinyatakan Selesai sesuai bukti fisik dokumen yang dilampirkan.' }}
                    </td>
                </tr>
            </table>

            <!-- TANDA TANGAN 3 PIHAK -->
            <table class="signature-box" style="margin-top: 10px;">
                <tr>
                    <td style="width: 33%;">
                        <div>Pihak Auditee,</div>
                        <div style="font-weight: bold;">{{ $f->unit?->nama }}</div>
                        <div class="signature-space" style="height: 42px;"></div>
                        <div><strong><u>( ........................................ )</u></strong></div>
                        <div>Pimpinan Unit / Auditee</div>
                    </td>
                    <td style="width: 34%;">
                        <div>Ketua Tim Auditor,</div>
                        <div style="font-weight: bold;">Auditor Mutu Internal</div>
                        <div class="signature-space" style="height: 42px;"></div>
                        <div><strong><u>Dr. Hadi Syaputra, S.Kom., M.Kom.</u></strong></div>
                        <div>NIDN. 0214057801</div>
                    </td>
                    <td style="width: 33%;">
                        <div>Mengetahui,</div>
                        <div style="font-weight: bold;">Kepala LPMA USS</div>
                        <div class="signature-space" style="height: 42px;"></div>
                        <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
                        <div>Kepala LPMA</div>
                    </td>
                </tr>
            </table>
        @endforeach
    @else
        <!-- FORMAT KOSONG / BLANK TEMPLATE JIKA BELUM ADA TEMUAN -->
        <div class="page-break"></div>

        <div class="header text-center border-kop">
            <div class="header-title">UNIVERSITAS SUMATERA SELATAN</div>
            <div class="header-title">LEMBAGA PENJAMINAN MUTU AKADEMIK (LPMA)</div>
            <div class="header-sub">Alamat: Jl. Letnan Murod, KM. 5, Palembang, Sumatera Selatan</div>
        </div>

        <div class="letter-number" style="font-size: 10.5pt;">FORMULIR PERMINTAAN TINDAKAN KOREKTIF (PTK) — TEMPLATE KOSONG</div>
        <div class="text-center" style="margin-top: 2px; margin-bottom: 8px; font-size: 9pt;">
            No. PTK: PTK/................./{{ $tahun }}/001
        </div>

        <table class="ptk-grid">
            <tr>
                <td class="label-cell">Unit Kerja / Auditee</td>
                <td style="width: 35%;">....................................................................</td>
                <td class="label-cell">Tanggal Audit</td>
                <td style="width: 25%;">....................................</td>
            </tr>
            <tr>
                <td class="label-cell">Standar SPMI Acuan</td>
                <td colspan="3">................................................................................................................................................................</td>
            </tr>
            <tr>
                <td class="label-cell">Kategori Temuan</td>
                <td>
                    <span class="checkbox-square"></span> KTS Mayor &nbsp;&nbsp;
                    <span class="checkbox-square"></span> KTS Minor &nbsp;&nbsp;
                    <span class="checkbox-square"></span> Observasi
                </td>
                <td class="label-cell">Auditor Ketua</td>
                <td>....................................</td>
            </tr>
        </table>

        <div class="box-title">I. Uraian Temuan Ketidaksesuaian (KTS / Kondisi Nyata)</div>
        <table class="ptk-grid">
            <tr><td class="label-cell">Deskripsi Temuan</td><td style="height: 45px;"></td></tr>
            <tr><td class="label-cell">Akibat / Dampak</td><td style="height: 35px;"></td></tr>
            <tr><td class="label-cell">Rekomendasi Auditor</td><td style="height: 35px;"></td></tr>
        </table>

        <div class="box-title">II. Analisis Akar Masalah (Root Cause Analysis - RCA 5-Whys)</div>
        <table class="ptk-grid">
            <tr><td class="label-cell">Akar Penyebab</td><td style="height: 45px;"></td></tr>
        </table>

        <div class="box-title">III. Rencana Tindakan Korektif (RTL Perbaikan)</div>
        <table class="ptk-grid">
            <tr><td class="label-cell">Tindakan Koreksi</td><td style="height: 40px;"></td></tr>
            <tr><td class="label-cell">Penanggung Jawab (PIC)</td><td>....................................................................</td></tr>
            <tr><td class="label-cell">Target Batas Waktu</td><td>....................................................................</td></tr>
        </table>

        <div class="box-title">IV. Rencana Tindakan Pencegahan (Preventive Action)</div>
        <table class="ptk-grid">
            <tr><td class="label-cell">Tindakan Pencegahan</td><td style="height: 40px;"></td></tr>
            <tr><td class="label-cell">Jadwal Pencegahan</td><td>....................................................................</td></tr>
        </table>

        <div class="box-title">V. Verifikasi Tindak Lanjut oleh Tim Auditor (Closing PTK)</div>
        <table class="ptk-grid">
            <tr>
                <td class="label-cell">Status Penutupan PTK</td>
                <td>
                    <span class="checkbox-square"></span> Selesai (Closed) &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="checkbox-square"></span> Proses PTK (Revisi) &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="checkbox-square"></span> Belum
                </td>
            </tr>
            <tr><td class="label-cell">Catatan Verifikasi</td><td style="height: 35px;"></td></tr>
        </table>

        <table class="signature-box" style="margin-top: 10px;">
            <tr>
                <td style="width: 33%;">
                    <div>Pihak Auditee,</div>
                    <div class="signature-space" style="height: 42px;"></div>
                    <div><strong><u>( ........................................ )</u></strong></div>
                    <div>Pimpinan Unit / Auditee</div>
                </td>
                <td style="width: 34%;">
                    <div>Ketua Tim Auditor,</div>
                    <div class="signature-space" style="height: 42px;"></div>
                    <div><strong><u>( ........................................ )</u></strong></div>
                    <div>Auditor Ketua</div>
                </td>
                <td style="width: 33%;">
                    <div>Mengetahui,</div>
                    <div class="signature-space" style="height: 42px;"></div>
                    <div><strong><u>{{ $kepalaLpmaNama }}</u></strong></div>
                    <div>Kepala LPMA</div>
                </td>
            </tr>
        </table>
    @endif

</body>
</html>
