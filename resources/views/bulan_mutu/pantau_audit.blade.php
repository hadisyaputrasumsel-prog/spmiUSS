@extends('layouts.app')

@section('header', 'Pantau Pelaksanaan Audit')

@section('content')
<div class="row" style="margin-bottom: 2rem;">
    <div class="col-12" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary);">Pemantauan Audit Mutu Internal (AMI)</h2>
        <div>
            <a href="{{ route('bulan-mutu.index', ['tahun' => $tahun]) }}" class="btn btn-outline" style="margin-right: 0.5rem;"><i data-feather="arrow-left" style="width: 16px; height: 16px; margin-right: 8px;"></i> Kembali ke Jadwal</a>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 style="margin: 0; font-size: 1.125rem;">{{ $activity->nama }} - <span style="color: var(--brand-primary);">Tahun {{ $tahun }}</span></h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama Unit Kerja</th>
                    <th>Kategori / Jenis</th>
                    <th>Tim Auditor Bertugas</th>
                    <th>Jumlah Temuan</th>
                    <th>Status Audit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1;
                    $terauditCount = 0;
                @endphp
                @foreach($units as $unit)
                    @php
                        $unitAssignments = $assignments->get($unit->id);
                        $unitFindings = $findings->get($unit->id);
                        $auditorNames = [];
                        if ($unitAssignments) {
                            foreach($unitAssignments as $ua) {
                                if($ua->auditor) {
                                    $auditorNames[] = $ua->auditor->name;
                                }
                            }
                        }
                        $countFindings = $unitFindings ? $unitFindings->count() : 0;
                        if ($countFindings > 0) $terauditCount++;
                    @endphp
                <tr>
                    <td style="font-weight: 600; text-align: center;">{{ $no++ }}</td>
                    <td style="font-weight: 500;">{{ $unit->nama }}</td>
                    <td><span class="badge" style="background-color: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color);">{{ $unit->jenis }}</span></td>
                    <td>
                        @if(count($auditorNames) > 0)
                            {{ implode(', ', $auditorNames) }}
                        @else
                            <span style="color: var(--text-muted); font-style: italic; font-size: 0.8rem;">Belum ditugaskan</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ $countFindings }} Temuan
                    </td>
                    <td>
                        @if($countFindings > 0)
                            <span class="badge" style="background: var(--success); color: white;"><i data-feather="check-circle" style="width: 12px; height: 12px; margin-right: 4px;"></i> Telah Diaudit</span>
                        @else
                            <span class="badge" style="background: var(--warning); color: white;"><i data-feather="clock" style="width: 12px; height: 12px; margin-right: 4px;"></i> Menunggu Audit</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ami.index', ['tahun' => $tahun, 'unit_id' => $unit->id]) }}" target="_blank" class="btn btn-outline" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; color: var(--brand-primary); border-color: var(--brand-primary);">
                            <i data-feather="search" style="width: 12px; height: 12px; margin-right: 4px;"></i> Tindak Lanjut
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
                <div style="font-weight: 600;">Ringkasan Cakupan Audit {{ str_contains(strtolower($activity->nama), 'akademik') && !str_contains(strtolower($activity->nama), 'non') ? 'Akademik' : 'Non-Akademik' }} Tahun {{ $tahun }}</div>
                <div style="font-size: 1.2rem;">
                    <span style="font-weight: 700; color: var(--brand-primary);">{{ $terauditCount }}</span> dari <span style="font-weight: 700;">{{ count($units) }}</span> Unit Kerja
                </div>
            </div>
            
            <div style="width: 100%; height: 8px; background: #e0e0e0; border-radius: 4px; margin-top: 1rem; overflow: hidden;">
                @php
                    $percentage = count($units) > 0 ? round(($terauditCount / count($units)) * 100) : 0;
                @endphp
                <div style="height: 100%; background: var(--brand-primary); width: {{ $percentage }}%;"></div>
            </div>
            <div style="text-align: right; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Tingkat Ketercapaian Audit: {{ $percentage }}%</div>
        </div>
    </div>
</div>
@endsection
