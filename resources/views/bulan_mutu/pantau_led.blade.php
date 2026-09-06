@extends('layouts.app')

@section('header', 'Pantau Progres Pengisian Evaluasi Diri (LED)')

@section('content')
<div class="row" style="margin-bottom: 2rem;">
    <div class="col-12" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary);">Rekapitulasi LED Sistem Penjaminan Mutu</h2>
        <div>
            <a href="{{ route('bulan-mutu.index') }}" class="btn btn-outline" style="margin-right: 0.5rem;"><i data-feather="arrow-left" style="width: 16px; height: 16px; margin-right: 8px;"></i> Kembali ke Jadwal</a>
            @if($hasSubmissions)
                <a href="{{ route('bulan-mutu.template', ['id' => $kegiatan_id, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-primary">
                    <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 8px;"></i> Cetak Laporan Penuh (PDF)
                </a>
            @else
                <button class="btn btn-primary" onclick="alert('Belum ada satu pun unit yang Vervaldan mengajukan LED. Dokumen PDF belum bisa dicetak.')" style="opacity: 0.5; cursor: not-allowed;">
                    <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 8px;"></i> Cetak Laporan Penuh (PDF)
                </button>
            @endif
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 style="margin: 0; font-size: 1.125rem;">Pemantauan Tahun Pelaksanaan: <span style="color: var(--brand-primary);">{{ $tahun }}</span></h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama Unit Kerja</th>
                    <th>Kategori / Jenis</th>
                    <th>Waktu Pengajuan (Verval)</th>
                    <th>Status Pengumpulan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1;
                    $terkumpulCount = 0;
                @endphp
                @foreach($units as $unit)
                    @php
                        $sub = $submissions->get($unit->id);
                        if($sub) $terkumpulCount++;
                    @endphp
                <tr>
                    <td style="font-weight: 600; text-align: center;">{{ $no++ }}</td>
                    <td style="font-weight: 500;">{{ $unit->nama }}</td>
                    <td><span class="badge" style="background-color: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color);">{{ $unit->jenis }}</span></td>
                    <td>
                        @if($sub)
                            {{ \Carbon\Carbon::parse($sub->submitted_at)->format('d M Y - H:i') }}
                        @else
                            <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($sub)
                            <span class="badge" style="background: var(--success); color: white;"><i data-feather="check-circle" style="width: 12px; height: 12px; margin-right: 4px;"></i> Ajuan Diterima</span>
                        @else
                            <span class="badge" style="background: var(--danger); color: white;"><i data-feather="x-circle" style="width: 12px; height: 12px; margin-right: 4px;"></i> Belum Diajukan</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('led.pdf_full', ['unit_id' => $unit->id, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-outline" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; color: var(--brand-primary); border-color: var(--brand-primary);">
                            <i data-feather="printer" style="width: 12px; height: 12px; margin-right: 4px;"></i> Cetak Isi LED
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row" style="margin-bottom: 2rem;">
    <div class="col-md-12">
        <div class="card" style="padding: 1.5rem; background-color: var(--bg-secondary);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="font-weight: 600;">Ringkasan Pengumpulan LED Tahun {{ $tahun }}</div>
                <div style="font-size: 1.2rem;">
                    <span style="font-weight: 700; color: var(--brand-primary);">{{ $terkumpulCount }}</span> dari <span style="font-weight: 700;">{{ count($units) }}</span> Unit Kerja
                </div>
            </div>
            
            <div style="width: 100%; height: 8px; background: #e0e0e0; border-radius: 4px; margin-top: 1rem; overflow: hidden;">
                @php
                    $percentage = count($units) > 0 ? round(($terkumpulCount / count($units)) * 100) : 0;
                @endphp
                <div style="height: 100%; background: var(--brand-primary); width: {{ $percentage }}%;"></div>
            </div>
            <div style="text-align: right; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Tingkat Kepatuhan: {{ $percentage }}%</div>
        </div>
    </div>
</div>
@endsection
