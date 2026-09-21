<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PKA - {{ $finding->standar_kode }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #000; line-height: 1.4; padding: 10px; margin: 0; }
        .page-break { page-break-after: always; }
        
        table { border-collapse: collapse; width: 100%; border: 1px solid #000; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        
        .header-table td { padding: 4px; vertical-align: middle; text-align: center; }
        .header-left { width: 20%; padding: 5px; }
        .header-left img { max-width: 80px; height: auto; }
        .header-mid { width: 60%; font-weight: bold; }
        .header-right { width: 20%; font-weight: bold; }
        
        .title-bar {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            padding: 8px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
        }

        .meta-table { border: none; margin-bottom: 10px; }
        .meta-table td { border: none; padding: 3px; }
        .meta-left-col { width: 18%; font-weight: normal; }
        .meta-mid-col { width: 2%; text-align: center; }
        .meta-val-col { width: 50%; }
        
        .meta-right-container { border-left: 1px solid #000; padding-left: 10px; font-size: 11px; }

        .section-header {
            background-color: #1F4E78;
            color: #fff;
            font-weight: bold;
            font-style: italic;
            padding: 5px 8px;
            font-size: 11px;
            border: 1px solid #000;
        }
        
        .section-content {
            padding: 8px;
            text-align: justify;
            border: 1px solid #000;
            border-top: none;
            margin-bottom: 5px;
        }

        .langkah-table { margin-top: 15px; }
        .langkah-table th { background-color: #e6e6e6; text-align: center; font-weight: bold; font-size: 11px; vertical-align: middle; }
        .langkah-table td { padding: 6px; font-size: 11px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <!-- Header Box -->
    <table class="header-table" style="margin-bottom: 5px;">
        <tr>
            <td class="header-left" rowspan="2">
                @php
                    $setting = \App\Models\Setting::first();
                    // Fallback to text if img fails in some DOMPDF configs or if no logo
                @endphp
                @if($setting && $setting->logo_kampus)
                    <img src="{{ public_path('storage/' . $setting->logo_kampus) }}" alt="Logo" style="height:60px;">
                @else
                    <div style="font-size:36px; font-weight:bold; color:#1F4E78; line-height:1; margin-bottom:5px;">U</div>
                @endif
                <div style="font-size: 8px; margin-top:2px;">{{ $setting->nama_kampus ?? 'UNIVERSITAS SUMATERA SELATAN' }}</div>
            </td>
            <td class="header-mid" style="font-size: 12px; border-bottom:1px solid #000;">
                KANTOR AUDIT INTERNAL<br>
                {{ strtoupper($setting->nama_kampus ?? 'UNIVERSITAS SUMATERA SELATAN') }}
            </td>
            <td class="header-right" rowspan="2">
                <u>No</u> : PKA-{{ $finding->standar_kode }}-{{ date('y', strtotime($finding->tanggal)) }}
            </td>
        </tr>
        <tr>
            <td class="header-mid" style="font-style: italic; font-weight: normal; font-size: 10px;">
                {{ $setting->alamat_kampus ?? 'Jl. Letnan Murod No. 55, Talang Ratu, KM 5, Palembang' }}
            </td>
        </tr>
    </table>

    <!-- Title Bar -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; border-bottom: none;">
        <tr>
            <td class="title-bar" style="border-bottom: 2px solid #000;">
                PROGRAM KERJA AUDIT (PKA) — AUDIT MUTU INTERNAL BIDANG PENDIDIKAN — {{ $standard->kode }} — {{ strtoupper($unit->nama) }}
            </td>
        </tr>
    </table>
    
    <table class="meta-table" style="width: 100%; border-collapse: collapse; border: 1px solid #000; border-top: none; margin-bottom: 10px;">
        <tr>
            <td style="width: 70%; padding: 0; vertical-align: top; border-right: 1px solid #000;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="width: 20%; border:none; padding:4px;">Auditi</td>
                        <td style="width: 2%; border:none; padding:4px;">:</td>
                        <td style="width: 78%; border:none; padding:4px;">Unit Pengelola Program Studi (UPPS) {{ $unit->nama }}</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px; border-top:1px solid #000;">Ruang Lingkup</td>
                        <td style="border:none; padding:4px; border-top:1px solid #000;">:</td>
                        <td style="border:none; padding:4px; border-top:1px solid #000;">{{ $finding->tahap }} ({{ $standard->kode }})</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px; border-top:1px solid #000;">Kriteria</td>
                        <td style="border:none; padding:4px; border-top:1px solid #000;">:</td>
                        <td style="border:none; padding:4px; border-top:1px solid #000;">Matriks Penilaian LED-LKPS BAN-PT — Standar {{ $standard->kode }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 30%; padding: 0; vertical-align: top;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border:none; border-bottom: 1px solid #000; padding: 8px 6px; padding-bottom: 25px; vertical-align: top;">
                            <div style="margin-bottom: 4px;"><strong>Disusun Oleh</strong> &nbsp;: {{ count($auditors) > 0 ? $auditors[0]->name : '..........................' }}</div>
                            <div style="margin-bottom: 25px;"><strong>Tanggal</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ date('d M Y', strtotime($finding->tanggal)) }}</div>
                            <div><strong>Paraf</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding: 8px 6px; padding-bottom: 25px; vertical-align: top;">
                            <div style="margin-bottom: 4px;"><strong>Diperiksa Oleh</strong> : {{ count($auditors) > 1 ? $auditors[1]->name : 'Ketua LPMA' }}</div>
                            <div style="margin-bottom: 25px;"><strong>Tanggal</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </div>
                            <div><strong>Paraf</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Objectives -->
    <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 5px;">
        <tr>
            <td class="section-header" style="border: 1px solid #000;">Tentative Audit Objective</td>
        </tr>
        <tr>
            <td class="section-content">
                {{ $standard->indikator ? Str::limit($standard->indikator, 255) : 'Mengevaluasi keterlibatan pemangku kepentingan dan kesesuaian implementasi dengan standar yang berlaku untuk menemukan ruang perbaikan sistematis.' }}
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 5px;">
        <tr>
            <td class="section-header" style="border: 1px solid #000;">Tujuan Audit</td>
        </tr>
        <tr>
            <td class="section-content">
                Untuk meyakinkan bahwa capaian dan implementasi Standar {{ $standard->kode }} - {{ $standard->nama }} telah dilaksanakan sesuai dengan target capaian yang ditetapkan dan didokumentasikan secara berkala.
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-top: 15px; text-transform: uppercase;">LANGKAH KERJA:</div>
    <table class="langkah-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">URAIAN LANGKAH-LANGKAH KERJA</th>
                <th width="10%">Anggaran<br>Waktu</th>
                <th width="15%">Realisasi</th>
                <th width="10%">No KKA</th>
                <th width="15%">Disusun oleh</th>
            </tr>
        </thead>
        <tbody>
            @php
                $angkah = [
                    "Dapatkan dokumen kebijakan/SOP evaluasi dan pemutakhiran standar terkait pada program studi.",
                    "Dapatkan bukti pelibatan pemangku kepentingan internal (dosen, mahasiswa, tenaga kependidikan) dan eksternal (asosiasi profesi, industri, pakar, pengguna lulusan) dalam peninjauan.",
                    "Tanyakan penyebab bila dokumen/bukti pelibatan tidak ditemukan atau tidak lengkap.",
                    "Bandingkan dokumen pelaksanaan dengan profil target yang berlaku; periksa kesesuaiannya.",
                    "Telaah pemetaan matriks standar untuk menilai ketepatan pencapaian target.",
                    "Cari akar penyebab bila ditemukan ketidaksesuaian/ketidaklengkapan pada salah satu dari ketiga aspek di atas, serta perkirakan dampaknya.",
                    "Buat kesimpulan dan rekomendasi hasil analisis; tuangkan hasil pengujian dalam Kertas Kerja Audit (KKA)."
                ];
            @endphp
            @foreach($angkah as $idx => $step)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="text-align: justify; padding-right: 10px;">{{ $step }}</td>
                <td class="text-center">1 hari</td>
                <td></td>
                <td></td>
                <td class="text-center">{{ count($auditors) > 0 ? explode(',', $auditors[0]->name)[0] : 'Auditor' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
