<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EVALUASI DIRI LENGKAP - {{ $unit->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        h2 {
            text-align: center;
            font-size: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        h3 {
            text-align: center;
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: normal;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .label {
            font-weight: bold;
            width: 120px;
        }
        .stage-box {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .stage-header {
            background-color: #f2f2f2;
            padding: 6px 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        .stage-content {
            padding: 10px;
        }
        .stage-grid {
            width: 100%;
        }
        .stage-grid td {
            vertical-align: top;
            padding: 3px 0;
        }
        .uraian-box {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            padding: 8px;
            margin-top: 5px;
            min-height: 30px;
        }
        .tahap-badge {
            display: inline-block;
            background-color: #4361ee;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            margin-right: 8px;
        }
        .page-break {
            page-break-after: always;
        }
        .standard-section {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

    @php
        $tahapan = [
            'P1' => 'Penetapan',
            'P2' => 'Pelaksanaan',
            'P3' => 'Evaluasi',
            'P4' => 'Pengendalian',
            'P5' => 'Peningkatan'
        ];
    @endphp

    @foreach($standards as $index => $standard)
        @php
            $entry = $entries->get($standard->kode);
            $stageData = [];
            if ($entry) {
                foreach ($entry->stages as $st) {
                    $stageData[$st->tahap] = $st;
                }
            }
        @endphp

        <div class="standard-section">
            <div style="background: #333; color: white; padding: 10px; margin-bottom: 15px; text-align: center; font-weight: bold; font-size: 13px;">
                STANDAR {{ $index + 1 }} : {{ $standard->kode }} - {{ mb_strtoupper($standard->nama) }}
            </div>

            <table class="header-table">
                <tr>
                    <td class="label">Unit Kerja</td>
                    <td>: {{ $unit->nama }}</td>
                </tr>
                <tr>
                    <td class="label">Tahun SPMI</td>
                    <td>: {{ $tahun }}</td>
                </tr>
                <tr>
                    <td class="label">Kelompok</td>
                    <td>: {{ $standard->kelompok ?? 'Non-Akademik' }}</td>
                </tr>
            </table>

            @if(!empty($standard->penanggung_jawab) || !empty($standard->indikator))
            <div style="background-color: #e1f5fe; border-left: 4px solid #039be5; padding: 10px; margin-bottom: 15px;">
                @if(!empty($standard->indikator) || !empty($standard->target))
                <div style="margin-bottom: 8px;">
                    @if(!empty($standard->indikator))
                        <div style="color: #555;"><strong>Indikator:</strong> {!! nl2br(e($standard->indikator)) !!}</div>
                    @endif
                    @if(!empty($standard->target))
                        <div style="color: #555;"><strong>Target:</strong> {!! nl2br(e($standard->target)) !!}</div>
                    @endif
                </div>
                @endif
            </div>
            @endif

            @foreach($tahapan as $tahapCode => $tahapName)
                @php
                    $data = $stageData[$tahapCode] ?? null;
                @endphp
                
                <div class="stage-box">
                    <div class="stage-header">
                        <span class="tahap-badge">{{ $tahapCode }}</span> Tahap {{ $tahapName }}
                    </div>
                    <div class="stage-content">
                        @if($data)
                            <table class="stage-grid">
                                <tr>
                                    <td style="width: 150px; font-weight: bold;">Tanggal</td>
                                    <td>: {{ $data->tanggal ? \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                @if($data->penanggung_jawab)
                                <tr>
                                    <td style="font-weight: bold;">Penanggung Jawab</td>
                                    <td>: {{ $data->penanggung_jawab }}</td>
                                </tr>
                                @endif
                            </table>
                            
                            @if($tahapCode != 'P1')
                                <div style="margin-top: 5px; font-weight: bold;">Uraian:</div>
                                <div class="uraian-box">
                                    {{ $data->uraian ?: '-' }}
                                </div>
                            @endif
                        @else
                            <div style="color: #999; font-style: italic; text-align: center; padding: 5px 0;">
                                Kosong / Belum diisi
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    @php
        $semuaLampiran = [];
        $tahapanLabels = [
            'P1' => 'Penetapan',
            'P2' => 'Pelaksanaan',
            'P3' => 'Evaluasi',
            'P4' => 'Pengendalian',
            'P5' => 'Peningkatan'
        ];
        
        foreach($standards as $standard) {
            $entry = $entries->get($standard->kode);
            if($entry && $entry->stages) {
                foreach($entry->stages as $st) {
                    if($st->bukti) {
                        $b = json_decode($st->bukti, true);
                        if(is_array($b)) {
                            // Extract files
                            if (isset($b['files']) && count($b['files']) > 0) {
                                foreach($b['files'] as $f) {
                                    $semuaLampiran[] = [
                                        'standar' => $standard->kode,
                                        'tahap_nama' => $tahapanLabels[$st->tahap] ?? $st->tahap,
                                        'type' => 'file',
                                        'file' => $f
                                    ];
                                }
                            }
                            // Extract links
                            if (isset($b['links']) && count($b['links']) > 0) {
                                foreach($b['links'] as $link) {
                                    $semuaLampiran[] = [
                                        'standar' => $standard->kode,
                                        'tahap_nama' => $tahapanLabels[$st->tahap] ?? $st->tahap,
                                        'type' => 'link',
                                        'file' => $link // file holds the URL
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
    @endphp

    @if(count($semuaLampiran) > 0)
        <!-- Lampiran has its own page -->
        <div class="page-break"></div>
        <div class="standard-section">
            <div style="background: #333; color: white; padding: 10px; margin-bottom: 20px; text-align: center; font-weight: bold; font-size: 14px;">
                LAMPIRAN BUKTI DOKUMEN EVALUASI DIRI
            </div>
            
            @foreach($semuaLampiran as $idx => $lamp)
                <div style="margin-bottom: 40px; page-break-inside: avoid;">
                    <div style="background: #e1f5fe; padding: 5px 10px; border-left: 4px solid #039be5; margin-bottom: 10px;">
                        <strong>Lampiran {{ $idx + 1 }}</strong> | Standar: {{ $lamp['standar'] }} | Tahap: {{ $lamp['tahap_nama'] }}
                    </div>
                    
                    @if($lamp['type'] == 'file')
                        @php
                            $absolutePath = public_path($lamp['file']);
                            $ext = strtolower(pathinfo($lamp['file'], PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                            <div style="border: 1px dashed #ccc; padding: 10px; text-align: center; background: white;">
                                <img src="{{ $absolutePath }}" style="max-width: 100%; max-height: 750px; object-fit: contain;">
                            </div>
                        @else
                            <div style="border: 1px dashed #ccc; padding: 20px; background: #fafafa; text-align: center;">
                                <h4 style="margin: 0; color: #555;">[Dokumen Upload Non-Gambar]</h4>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">
                                    Dokumen ini berformat <strong>{{ strtoupper($ext) }}</strong> dan tidak dapat dirender menjadi PDF.
                                </p>
                                <p style="margin: 5px 0 0 0; font-size: 10px; color: #999;">Path: {{ $lamp['file'] }}</p>
                            </div>
                        @endif
                    @else
                        <!-- It's a link -->
                        <div style="border: 1px dashed #ccc; padding: 20px; background: #fafafa; text-align: center;">
                            <h4 style="margin: 0; color: #039be5;">[Tautan Eksternal / Cloud Storage]</h4>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #333;">
                                Tautan Dokumen Publik:
                            </p>
                            <p style="margin: 5px 0 0 0; font-size: 12px; font-weight: bold;">
                                <a href="{{ $lamp['file'] }}" style="color: #039be5; text-decoration: none;">{{ $lamp['file'] }}</a>
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <!-- Fallback if no attachments -->
        <div class="page-break"></div>
        <div class="standard-section">
            <div style="background: #333; color: white; padding: 10px; margin-bottom: 20px; text-align: center; font-weight: bold; font-size: 14px;">
                LAMPIRAN BUKTI DOKUMEN EVALUASI DIRI
            </div>
            <div style="border: 1px dashed #ccc; padding: 30px; text-align: center; font-size: 14px; color: #666; background: #f9f9f9;">
                Tidak ada dokumen file maupun tautan *cloud* yang dilampirkan oleh unit pada SPMI Siklus ini.
            </div>
        </div>
    @endif

</body>
</html>
