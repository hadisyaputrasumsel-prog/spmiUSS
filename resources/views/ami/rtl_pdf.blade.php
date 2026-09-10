<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FORM 5 - Deskripsi Temuan RCA</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #000; line-height: 1.5; margin: 1cm; }
        .page-break { page-break-after: always; }
        
        .header { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .header h3 { margin: 0; padding: 0; font-size: 14px; font-weight: bold; }
        
        .box-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .box-table td, .box-table th { border: 1px solid #000; padding: 6px 10px; vertical-align: top; }
        
        .section-title { font-weight: bold; text-align: left; }
        
        .cb-container { margin-top: 5px; margin-bottom: 5px; }
        .cb-container span { margin-right: 20px; font-size: 11px; }
    </style>
</head>
<body>

    @forelse($findings as $index => $finding)
    <div class="header">
        <h3>BORANG AUDIT MUTU INTERNAL</h3>
        <h3>Deskripsi Temuan Audit</h3>
    </div>
    
    <table class="box-table" style="margin-bottom: 20px;">
        <tr>
            <td width="30%"><strong>Auditi:</strong></td>
            <td>{{ $finding->unit->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Kriteria (Standar):</strong></td>
            <td>{{ $finding->standar_kode }}</td>
        </tr>
        <tr>
            <td><strong>Auditor Ketua:</strong></td>
            <td>{{ ($auditors ?? collect())->count() > 0 ? $auditors[0]->name : 'Tim Auditor' }}</td>
        </tr>
        <tr>
            <td><strong>Auditor Anggota:</strong></td>
            <td>{{ ($auditors ?? collect())->count() > 1 ? $auditors[1]->name : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Audit:</strong></td>
            <td>{{ date('d F Y', strtotime($finding->tanggal)) }}</td>
        </tr>
    </table>

    <table class="box-table">
        <tr>
            <td colspan="2" class="section-title">DESKRIPSI TEMUAN</td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>OBJEKTIF / RISIKO</strong>
                <div class="cb-container">
                    <span>[{{ str_contains(strtoupper($finding->kategori_temuan), 'KTS') || str_contains(strtoupper($finding->kategori_temuan), 'OB') ? 'X' : ' ' }}] OB / KTS</span>
                    <span>[{{ !str_contains(strtoupper($finding->kategori_temuan), 'KTS') && !str_contains(strtoupper($finding->kategori_temuan), 'OB') ? 'X' : ' ' }}] PPM</span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Deskripsi Temuan:</strong><br><br>
                {!! nl2br(e($finding->uraian)) !!}
            </td>
        </tr>
        <tr>
            <td width="20%"><strong>Akar Penyebab</strong></td>
            <td>{!! nl2br(e($finding->akar_penyebab ?: '-')) !!}</td>
        </tr>
        <tr>
            <td><strong>Akibat</strong></td>
            <td>{!! nl2br(e($finding->akibat ?: '-')) !!}</td>
        </tr>
        <tr>
            <td><strong>Rekomendasi / Permintaan Tindakan Koreksi.</strong></td>
            <td>{!! nl2br(e($finding->rekomendasi ?: '-')) !!}</td>
        </tr>
        <tr>
            <td><strong>Tanggapan Auditi</strong></td>
            <td>{!! nl2br(e($finding->tanggapan_auditi ?: 'Setuju')) !!}</td>
        </tr>
        <tr>
            <td><strong>Rencana Perbaikan</strong></td>
            <td>{!! nl2br(e($finding->rencana_tindakan ?: '-')) !!}</td>
        </tr>
        <tr>
            <td><strong>Jadwal Perbaikan</strong></td>
            <td>{{ $finding->batas_waktu ? date('d F Y', strtotime($finding->batas_waktu)) : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Penanggung Jawab</strong></td>
            <td>{{ $finding->pic ?: '-' }}</td>
        </tr>
        <tr>
            <td><strong>Rencana Pencegahan</strong></td>
            <td>{!! nl2br(e($finding->rencana_pencegahan ?: '-')) !!}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 10px; text-align: right; padding-top: 15px;">
                <em>Status RTL Formulir: {{ $finding->status_tindak_lanjut ?: 'Berjalan' }}</em>
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 30px; text-align: center;">
        <tr>
            <td width="33%">
                Auditee (Pimpinan Unit)
                <br><br><br><br>
                ( ______________________ )
            </td>
            <td width="33%">
                Auditor
                <br><br><br><br>
                ( {{ ($auditors ?? collect())->count() > 0 ? $auditors[0]->name : '______________________' }} )
            </td>
            <td width="33%">
                Reviewer / LPMA
                <br><br><br><br>
                ( ______________________ )
            </td>
        </tr>
    </table>

    @if(!$loop->last)
    <div class="page-break"></div>
    @endif
    
    @empty
    <div style="text-align: center; margin-top: 50px;">
        <h3>Belum ada data temuan untuk diekspor ke Form 5 RCA.</h3>
    </div>
    @endforelse

</body>
</html>
