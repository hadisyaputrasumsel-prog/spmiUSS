<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Evaluasi Diri (LED) - {{ $standard->kode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        h2 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        h3 {
            text-align: center;
            font-size: 14px;
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
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .stage-header {
            background-color: #f2f2f2;
            padding: 8px 10px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        .stage-content {
            padding: 10px;
        }
        .stage-grid {
            width: 100%;
        }
        .stage-grid td {
            vertical-align: top;
            padding: 4px 0;
        }
        .uraian-box {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            padding: 10px;
            margin-top: 5px;
            min-height: 40px;
        }
        .tahap-badge {
            display: inline-block;
            background-color: #4361ee;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            margin-right: 8px;
        }
    </style>
</head>
<body>

    <h2>LAPORAN EVALUASI DIRI (LED) - SIKLUS PPEPP</h2>
    <h3>Tahun Pelaporan: {{ $tahun }}</h3>

    <table class="header-table">
        <tr>
            <td class="label">Unit / Prodi</td>
            <td>: {{ $unit ? $unit->nama : (auth()->user()->unit ? auth()->user()->unit->nama : 'Semua Unit') }}</td>
        </tr>
        <tr>
            <td class="label">Kode Standar</td>
            <td>: {{ $standard->kode }}</td>
        </tr>
        <tr>
            <td class="label">Nama Standar</td>
            <td>: {{ $standard->nama }}</td>
        </tr>
        <tr>
            <td class="label">Kelompok</td>
            <td>: {{ $standard->kelompok ?? 'Non-Akademik' }}</td>
        </tr>
    </table>

    @if(!empty($standard->penanggung_jawab) || !empty($standard->indikator))
    <div style="background-color: #e1f5fe; border-left: 4px solid #039be5; padding: 12px; margin-bottom: 20px;">
        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #039be5; font-weight: bold; text-transform: uppercase;">Informasi Standar (Acuan Utama)</h4>
        
        @if(!empty($standard->indikator))
        <div style="margin-bottom: 12px;">
            <strong style="font-size: 12px; color: #333;">Target / Indikator Standar:</strong>
            <div style="font-size: 12px; color: #555; margin-top: 2px; line-height: 1.5;">{!! nl2br(e($standard->indikator)) !!}</div>
        </div>
        @endif
        
        @if(!empty($standard->penanggung_jawab))
        <div>
            <strong style="font-size: 12px; color: #333;">Penanggung Jawab Baku:</strong>
            <p style="margin: 2px 0 0 0; font-size: 12px; color: #555;">{{ $standard->penanggung_jawab }}</p>
        </div>
        @endif
    </div>
    @endif

    <hr style="border: 0; border-top: 1px solid #ccc; margin-bottom: 20px;">

    @php
        $tahapan = [
            'P1' => 'Penetapan',
            'P2' => 'Pelaksanaan',
            'P3' => 'Evaluasi',
            'P4' => 'Pengendalian',
            'P5' => 'Peningkatan'
        ];
    @endphp

    @foreach($tahapan as $tahapCode => $tahapName)
        @php
            $data = $stageData[$tahapCode] ?? null;
        @endphp
        
        <div class="stage-box">
            <div class="stage-header">
                <span class="tahap-badge">{{ $tahapCode }}</span> Tahap {{ $tahapName }}
            </div>
            <div class="stage-content">
                @php
                    $indikatorField = 'indikator_' . strtolower($tahapCode);
                    $indikatorText = $standard->$indikatorField;
                @endphp
                @if(!empty($indikatorText))
                <div style="background-color: #e1f5fe; border-left: 3px solid #039be5; padding: 8px 10px; margin-bottom: 15px;">
                    <strong style="color: #039be5; font-size: 12px; display: block; margin-bottom: 4px;">Target / Indikator (Acuan):</strong>
                    <div style="font-size: 12px; color: #555; line-height: 1.5;">{!! nl2br(e($indikatorText)) !!}</div>
                </div>
                @endif
                
                @if($data)
                    <table class="stage-grid">
                        @if($tahapCode == 'P1')
                            <tr>
                                <td style="width: 150px; font-weight: bold;">Tanggal Ditetapkan</td>
                                <td>: {{ $data->tanggal ? \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">No. SK / Dokumen</td>
                                <td>: {{ isset($data->data_spesifik['no_sk']) ? $data->data_spesifik['no_sk'] : '-' }}</td>
                            </tr>
                        @elseif($tahapCode == 'P2')
                            <tr>
                                <td style="width: 150px; font-weight: bold;">Periode Pelaksanaan</td>
                                <td>: {{ $data->tanggal ?: '-' }}</td>
                            </tr>
                        @elseif($tahapCode == 'P3')
                            <tr>
                                <td style="width: 150px; font-weight: bold;">Metode Evaluasi</td>
                                <td>: {{ isset($data->data_spesifik['metode']) ? $data->data_spesifik['metode'] : '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Tanggal Evaluasi</td>
                                <td>: {{ $data->tanggal ? \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Status Capaian</td>
                                <td>: {{ isset($data->data_spesifik['status_capaian']) ? $data->data_spesifik['status_capaian'] : '-' }}</td>
                            </tr>
                        @elseif($tahapCode == 'P4')
                            <tr>
                                <td style="width: 150px; font-weight: bold;">Tanggal Tindak Lanjut</td>
                                <td>: {{ $data->tanggal ? \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @elseif($tahapCode == 'P5')
                            <tr>
                                <td style="width: 180px; font-weight: bold;">Target Periode Berikutnya</td>
                                <td>: {{ isset($data->data_spesifik['target_berikutnya']) ? $data->data_spesifik['target_berikutnya'] : '-' }}</td>
                            </tr>
                        @endif
                        
                        @if($data->penanggung_jawab)
                        <tr>
                            <td style="font-weight: bold;">Penanggung Jawab</td>
                            <td>: {{ $data->penanggung_jawab }}</td>
                        </tr>
                        @endif
                    </table>
                    
                    @if($tahapCode == 'P2')
                    <div style="margin-top: 10px; font-weight: bold;">Uraian Pelaksanaan:</div>
                    @elseif($tahapCode == 'P3')
                    <div style="margin-top: 10px; font-weight: bold;">Capaian Aktual:</div>
                    @elseif($tahapCode == 'P4')
                    <div style="margin-top: 10px; font-weight: bold;">Temuan / Penyimpangan:</div>
                    @elseif($tahapCode == 'P5')
                    <div style="margin-top: 10px; font-weight: bold;">Rencana Peningkatan / Revisi Standar:</div>
                    @endif
                    
                    @if($tahapCode != 'P1')
                    <div class="uraian-box">
                        {{ $data->uraian ?: '-' }}
                    </div>
                    @endif
                    
                    @if($tahapCode == 'P4' && isset($data->data_spesifik['tindakan_korektif']))
                    <div style="margin-top: 10px; font-weight: bold;">Tindakan Korektif:</div>
                    <div class="uraian-box">
                        {{ $data->data_spesifik['tindakan_korektif'] }}
                    </div>
                    @endif
                    
                    @if($data->catatan)
                        <div style="margin-top: 10px;">
                            <strong>Catatan Tambahan:</strong><br>
                            {{ $data->catatan }}
                        </div>
                    @endif
                    
                    @if($data->bukti)
                        @php
                            $buktiObj = json_decode($data->bukti, true);
                        @endphp
                        @if(isset($buktiObj['links']) && count($buktiObj['links']) > 0)
                            <div style="margin-top: 10px;">
                                <strong>Tautan Bukti:</strong><br>
                                <ul style="margin: 0; padding-left: 20px;">
                                @foreach($buktiObj['links'] as $link)
                                    <li><a href="{{ $link }}">{{ $link }}</a></li>
                                @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                @else
                    <div style="color: #999; font-style: italic; text-align: center; padding: 10px 0;">
                        Belum ada data evaluasi untuk tahap ini.
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    <div style="margin-top: 40px; width: 100%;">
        <div style="float: right; width: 250px; text-align: center;">
            <p>Dibuat oleh,</p>
            <br><br><br><br>
            <p><strong>{{ $unit ? 'Pimpinan ' . $unit->nama : (auth()->user()->unit ? 'Pimpinan ' . auth()->user()->unit->nama : 'Penanggung Jawab') }}</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
