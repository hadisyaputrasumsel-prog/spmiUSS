<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan AMI Checklist</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .page-break { page-break-after: always; }
        
        /* Cover Page */
        .cover { text-align: center; margin-top: 150px; }
        .cover h1 { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .cover h2 { font-size: 20px; font-weight: bold; margin-bottom: 50px; }
        .cover h3 { font-size: 18px; font-weight: normal; margin-bottom: 80px; }
        
        .footer-cover { position: absolute; bottom: 50px; width: 100%; text-align: center; font-size: 14px; font-weight: bold; }
        
        /* Titles */
        .page-title { font-size: 14px; font-weight: bold; text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .section-title { font-size: 12px; font-weight: bold; margin-bottom: 10px; margin-top: 20px; }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10px; }
        table th, table td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        
        .table-identitas { width: 100%; border-collapse: collapse; margin-bottom: 30px; border: none; }
        .table-identitas td { border: none; padding: 4px; vertical-align: top; font-size: 12px; }
        .table-identitas td:first-child { width: 35%; font-weight: normal; }
        
        .table-ttd { width: 100%; border-collapse: collapse; margin-top: 50px; text-align: center; font-size: 12px; border: none; }
        .table-ttd td { width: 33%; padding: 5px; vertical-align: bottom; height: 100px; border: none; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .standard-header { background-color: #e6e6e6; font-weight: bold; padding: 5px; font-size: 11px; margin-top: 15px; border: 1px solid #000; border-bottom: none; }
        .checklist-table { margin-top: 0; }
        .checklist-table th { background-color: #e6e6e6; }
    </style>
</head>
<body>

    <!-- PAGE 1: COVER -->
    <div class="cover">
        <h1>LAPORAN AUDIT MUTU INTERNAL (AMI)</h1>
        <h2>{{ $unit ? mb_strtoupper($unit->nama) : 'SELURUH UNIT KERJA' }}</h2>
        <br><br><br><br>
        <h3>LEMBAGA PENJAMINAN MUTU DAN AKREDITASI<br>UNIVERSITAS SUMATERA SELATAN<br>{{ $tahun }}</h3>
    </div>
    <div class="page-break"></div>
    
    <!-- PAGE 2: TIM PERUMUS -->
    <div style="margin-top: 50px;">
        <div class="page-title" style="text-align: left; text-transform:none;">Tim Auditor</div>
        <ol style="font-size: 12px; line-height: 1.8;">
            @if(count($auditors) > 0)
                @foreach($auditors as $auditor)
                    <li>{{ $auditor->name }}</li>
                @endforeach
            @else
                <li>-</li>
            @endif
        </ol>
    </div>
    
    <div class="page-break"></div>

    <!-- PAGE 3: KATA PENGANTAR -->
    <div class="page-title">KATA PENGANTAR</div>
    <div style="text-align: justify; font-size: 12px; line-height: 1.8;">
        <p>Audit Mutu Internal (AMI) merupakan kegiatan evaluasi yang dilakukan secara internal oleh Lembaga Penjaminan Mutu dan Akreditasi (LPMA) guna menjamin kelangsungan jaminan mutu. AMI merupakan suatu kegiatan untuk memastikan kesesuaian antara Standar Mutu dengan pelaksanaannya oleh unit pelaksana akademik dan non akademik yang terdiri dari audit sistem dan audit kepatuhan/kesadaran mutu.</p>
        <p>Tujuan umum AMI adalah membantu seluruh Pengelola Unit/Program Studi di lingkungan Universitas Sumatera Selatan dalam melaksanakan tugasnya untuk: 1) mencapai sasaran mutu baik akademik maupun non akademik yang telah ditetapkan secara efektif dan bertanggung jawab dalam standar mutu internal; 2) membantu pengelola mengidentifikasi lingkup perbaikan dan mengembangkannya secara berkelanjutan; dan 3) memperbaiki program dan merencanakan kegiatan di lingkungan Universitas Sumatera Selatan.</p>
        <p>Hasil AMI dapat dijadikan refleksi dan perbaikan serta peningkatan mutu secara berkelanjutan.</p>
        <p style="text-align: left; margin-top: 40px;">
            Palembang, {{ date('d F Y') }}
        </p>
    </div>
    <div class="page-break"></div>

    <!-- PAGE 4: IDENTITAS -->
    <div class="page-title" style="text-decoration: underline;">IDENTITAS TERAUDIT</div>
    <table class="table-identitas">
        <tr>
            <td>Perguruan Tinggi</td>
            <td>: Universitas Sumatera Selatan</td>
        </tr>
        <tr>
            <td>Nama Unit / Program Studi</td>
            <td>: {{ $unit ? $unit->nama : 'Seluruh Unit Kerja' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: Jalan Letnan Murod No.55 Talang Ratu KM 5 Palembang</td>
        </tr>
        <tr>
            <td>Status / Tahun Akreditasi</td>
            <td>: {{ $unit ? $unit->akreditasi ?? '-' : '-' }}</td>
        </tr>
    </table>
    
    <table class="table-ttd">
        <tr>
            <td>
                Ketua Auditor<br><br><br><br><br>
                ({{ count($auditors) > 0 ? $auditors[0]->name : '..........................' }})
            </td>
            <td>
                Auditi<br><br><br><br><br>
                ({{ $unit ? $unit->pimpinan ?? 'Pimpinan Unit' : '..........................' }})
            </td>
            <td>
                Auditor Anggota<br><br><br><br><br>
                ({{ count($auditors) > 1 ? $auditors[1]->name : '..........................' }})
            </td>
        </tr>
    </table>
    <div class="page-break"></div>

    <!-- PAGE 5: CHECKLIST STANDAR -->
    <div class="page-title">INSTRUMEN EVALUASI / DAFTAR TILIK</div>
    
    @php
        $scores = [];
        $totalStandardsWithScores = 0;
        $sumOfScores = 0;
        
        $tahapanEnum = [
            'P1' => 'Penetapan',
            'P2' => 'Pelaksanaan',
            'P3' => 'Evaluasi',
            'P4' => 'Pengendalian',
            'P5' => 'Peningkatan'
        ];
    @endphp

    @foreach($standards as $index => $standard)
        @php
            $stdScores = [];
            foreach (array_keys($tahapanEnum) as $t) {
                if ($t === 'P1') {
                    $stdScores[] = 4; // Penetapan selalu nilai maksimal (4)
                } else {
                    // Pick the most recent finding score if multiple
                    $f = $findings->where('standar_kode', $standard->kode)->where('tahap', $t)->whereNotNull('skor')->sortByDesc('created_at')->first();
                    if ($f) $stdScores[] = $f->skor;
                }
            }
            
            $stdAvg = count($stdScores) > 0 ? array_sum($stdScores) / count($stdScores) : null;
            $scores[] = [
                'nama' => $standard->nama,
                'kode' => $standard->kode,
                'skor' => $stdAvg
            ];
            
            if ($stdAvg !== null) {
                $totalStandardsWithScores++;
                $sumOfScores += $stdAvg;
            }
        @endphp

        <div class="standard-header">Standar {{ $index + 1 }}. {{ $standard->nama }} ({{ $standard->kode }})</div>
        <table class="checklist-table">
            <thead>
                <tr>
                    <th width="10%">Tahap</th>
                    <th width="75%">Butir / Indikator PPEPP</th>
                    <th width="15%">Nilai Capaian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tahapanEnum as $tahapCode => $tahapName)
                    @php
                        if ($tahapCode === 'P1') {
                            $skor = 4;
                        } else {
                            if ($unit) {
                                $f = $findings->where('standar_kode', $standard->kode)->where('tahap', $tahapCode)->whereNotNull('skor')->sortByDesc('created_at')->first();
                                $skor = $f ? $f->skor : null;
                            } else {
                                $fs = $findings->where('standar_kode', $standard->kode)->where('tahap', $tahapCode)->whereNotNull('skor');
                                $skor = $fs->count() > 0 ? round($fs->avg('skor'), 2) : null;
                            }
                        }
                        
                        $propName = 'indikator_' . strtolower($tahapCode);
                        $indikatorText = $standard->$propName ?: $standard->indikator;
                    @endphp
                    <tr>
                        <td class="text-center" style="font-weight: bold;">{{ $tahapCode }}<br><small style="font-weight: normal;">{{ $tahapName }}</small></td>
                        <td style="text-align: justify;">
                            <b>Indikator:</b><br>{!! nl2br(e($indikatorText ?? $standard->nama)) !!}
                            
                            @if($tahapCode === 'P1' && $standard->rubrik_penilaian && is_array($standard->rubrik_penilaian))
                                <div style="margin-top: 10px; font-size: 9px; line-height: 1.3;">
                                    <i>(Referensi Rubrik Penilaian):</i><br>
                                    @foreach([4,3,2,1] as $val)
                                        @if(isset($standard->rubrik_penilaian[$val]))
                                            <div><b>{{ $val }}:</b> {{ $standard->rubrik_penilaian[$val] }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="text-center" style="font-size: 14px; font-weight: bold; vertical-align: middle;">
                            {{ $skor ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    @endforeach

    <div class="page-break"></div>

    <!-- PAGE 6: REKAPITULASI -->
    <div class="page-title">REKAPITULASI RERATA PENCAPAIAN STANDAR</div>
    <div style="text-align: center; margin-bottom: 20px; font-weight: bold;">
        Unit / Prodi: {{ $unit ? $unit->nama : 'Seluruh Unit Kerja' }}<br>
        Universitas Sumatera Selatan
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="10%">NO.</th>
                <th width="70%">NAMA STANDAR</th>
                <th width="20%">SKOR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scores as $idx => $s)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $s['nama'] }}</td>
                <td class="text-center">{{ $s['skor'] === null ? '-' : number_format((float)$s['skor'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right" style="font-weight: bold; padding-right: 15px;">TOTAL RATA-RATA PENCAPAIAN:</td>
                <td class="text-center" style="font-weight: bold;">
                    {{ $totalStandardsWithScores > 0 ? number_format($sumOfScores / $totalStandardsWithScores, 2, ',', '.') : '-' }}
                </td>
            </tr>
        </tfoot>
    </table>
    
    @if(isset($chartUrl) && $chartUrl)
    <div style="text-align: center; margin-top: 40px; page-break-inside: avoid;">
        <div class="page-title">GRAFIK PENCAPAIAN STANDAR MUTU</div>
        <img src="{{ $chartUrl }}" style="max-width: 90%; height: auto; border: 1px solid #ccc; padding: 10px;">
    </div>
    @endif

</body>
</html>
