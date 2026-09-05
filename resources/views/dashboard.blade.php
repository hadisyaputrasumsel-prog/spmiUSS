@extends('layouts.app')

@section('title', 'Ringkasan Dashboard')
@section('page_title', 'Siklus SPMI Control Center')

@section('content')
<!-- Top Summary Cards -->
<div class="stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
    <div class="stat-card" style="padding: 1rem;">
        <div class="stat-title" style="font-size: 0.75rem;">Total Unit</div>
        <div class="stat-value" style="font-size: 1.5rem;">37</div>
    </div>
    <div class="stat-card" style="padding: 1rem;">
        <div class="stat-title" style="font-size: 0.75rem;">Pelaksanaan Selesai</div>
        <div class="stat-value" style="font-size: 1.5rem; color: var(--status-success);">29</div>
    </div>
    <div class="stat-card" style="padding: 1rem;">
        <div class="stat-title" style="font-size: 0.75rem;">AMI Selesai</div>
        <div class="stat-value" style="font-size: 1.5rem; color: var(--status-info);">24</div>
    </div>
    <div class="stat-card" style="padding: 1rem;">
        <div class="stat-title" style="font-size: 0.75rem;">RTL Terbuka</div>
        <div class="stat-value" style="font-size: 1.5rem; color: var(--status-warning);">18</div>
    </div>
    <div class="stat-card" style="padding: 1rem;">
        <div class="stat-title" style="font-size: 0.75rem;">KTS Mayor</div>
        <div class="stat-value" style="font-size: 1.5rem; color: var(--status-danger);">6</div>
    </div>
    <div class="stat-card" style="padding: 1rem;">
        <div class="stat-title" style="font-size: 0.75rem;">KTS Minor</div>
        <div class="stat-value" style="font-size: 1.5rem; color: var(--status-warning);">12</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
    
    <!-- Left Panel: Units Needing Action -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Unit yang Memerlukan Tindak Lanjut</h2>
            <a href="#" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Lihat Semua Unit</a>
        </div>
        <div class="table-responsive">
            <table class="table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>UNIT</th>
                        <th>TEMUAN TERBUKA</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitsAttention as $u)
                    <tr>
                        <td style="font-weight: 600;">{{ $u->nama }}</td>
                        <td style="color: var(--text-muted);">{{ $u->ami_findings_count }} RTL Belum Selesai</td>
                        <td><span class="badge badge-danger" style="background-color: transparent; border: 1px solid var(--status-danger); color: var(--status-danger);">🔴 TINDAK LANJUT</span></td>
                        <td><a href="{{ route('unit.ppepp', $u->id) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: var(--radius-sm);"><i data-feather="external-link" style="width: 14px; height: 14px;"></i> Detail</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">Tidak ada unit yang memerlukan tindak lanjut khusus.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Panel: SPMI Cycle Timeline -->
    <div class="card">
        <div class="card-header" style="flex-direction: column; align-items: flex-start; gap: 0.5rem;">
            <h2 class="card-title" style="font-size: 1rem;">Siklus SPMI Universitas Sumatera Selatan &mdash; Tahun {{ $tahun }}</h2>
            <div style="font-size: 0.875rem; color: var(--text-secondary);">
                Sedang berjalan:<br>
                <span style="font-weight: 600; color: var(--brand-primary);">Tahap {{ substr($activeTahap, 1) }}: {{ $tahapLabels[$activeTahap] }}</span>
            </div>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            
            @foreach($tahapProgress as $t => $data)
                @php
                    $isActive = ($t == $activeTahap);
                    $isSelesai = ($data['status'] == 'Selesai');
                    $isBelum = ($data['status'] == 'Belum dimulai');
                    
                    if($isSelesai) {
                        $color = 'var(--status-success)';
                        $icon = 'check';
                        $bg = 'var(--status-success)';
                        $border = 'none';
                        $iconColor = 'white';
                        $badgeClass = 'badge-success';
                        $badgeText = 'SELESAI';
                    } elseif ($isActive) {
                        $color = 'var(--brand-primary)';
                        $icon = 'play';
                        $bg = 'var(--brand-primary)';
                        $border = 'none';
                        $iconColor = 'white';
                        $badgeClass = 'badge-warning';
                        $badgeText = 'BERJALAN';
                    } else {
                        $color = 'var(--text-muted)';
                        $icon = '';
                        $bg = 'transparent';
                        $border = '2px solid var(--border-color)';
                        $iconColor = 'var(--text-muted)';
                        $badgeClass = '';
                        $badgeText = 'Belum dimulai';
                    }
                @endphp
                <div style="display: flex; gap: 1rem; {{ $loop->last ? '' : 'margin-bottom: 1.5rem;' }} position: relative;">
                    @if(!$loop->last)
                        <div style="position: absolute; left: 11px; top: 24px; bottom: -24px; width: 2px; background-color: {{ $isSelesai ? 'var(--status-success)' : 'var(--border-color)' }};"></div>
                    @endif
                    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: {{ $bg }}; {{ $border != 'none' ? 'border: '.$border.';' : '' }} color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; z-index: 1;">
                        @if($icon)
                            <i data-feather="{{ $icon }}" style="width: {{ $icon == 'play' ? '12px' : '14px' }}; height: {{ $icon == 'play' ? '12px' : '14px' }}; {{ $icon == 'play' ? 'margin-left: 2px;' : '' }}"></i>
                        @endif
                    </div>
                    
                    @if($isActive)
                        <div style="flex-grow: 1; background-color: var(--bg-tertiary); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem; color: {{ $color }};">Tahap {{ substr($t, 1) }} &mdash; {{ $tahapLabels[$t] }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Sedang Berjalan</div>
                                    @if($t == 'P5' && in_array(auth()->user()->role->kode, ['lpma', 'super_admin']))
                                        <div style="margin-top: 0.75rem;">
                                            <a href="{{ route('rtm.cetak') }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: var(--radius-sm);">
                                                <i data-feather="printer" style="width: 12px; height: 12px; margin-right: 2px;"></i> Cetak Berita Acara RTM
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <span class="badge {{ $badgeClass }}" style="font-size: 0.65rem; {{ $isActive ? 'background-color: var(--status-warning-bg); color: var(--status-warning);' : '' }}">{{ $badgeText }}</span>
                            </div>
                            <div style="margin-top: 1rem; font-size: 0.8rem; color: var(--text-secondary);">
                                @foreach($data['kegiatan'] as $keg)
                                    @php
                                        $kegDone = str_contains($keg->status, 'Terlaksana');
                                        $kegActive = ($keg->status == 'Belum Dilaksanakan' && !$kegDone); // Simple heuristic
                                    @endphp
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem; {{ $kegDone ? 'color: var(--status-success);' : 'color: var(--text-muted);' }}">
                                        @if($kegDone)
                                            <i data-feather="check-circle" style="width: 12px; height: 12px;"></i>
                                        @else
                                            <i data-feather="circle" style="width: 12px; height: 12px;"></i>
                                        @endif
                                        <span style="{{ $kegDone ? '' : '' }}">{{ $keg->kegiatan_nama }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div style="flex-grow: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <div style="font-weight: {{ $isSelesai ? '600' : '500' }}; font-size: 0.875rem; color: {{ $isSelesai ? 'var(--text-primary)' : 'var(--text-muted)' }};">Tahap {{ substr($t, 1) }} &mdash; {{ $tahapLabels[$t] }}</div>
                                    @if($t == 'P5' && in_array(auth()->user()->role->kode, ['lpma', 'super_admin']))
                                        <div style="margin-top: 0.5rem;">
                                            <a href="{{ route('rtm.cetak') }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: var(--radius-sm);">
                                                <i data-feather="printer" style="width: 12px; height: 12px; margin-right: 2px;"></i> Cetak Berita Acara RTM
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <span class="badge {{ $badgeClass }}" style="font-size: 0.65rem; {{ $isBelum ? 'background-color: var(--bg-tertiary); color: var(--text-muted);' : '' }}">{{ $badgeText }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</div>

@if(in_array(auth()->user()->role->kode, ['auditor', 'lpma', 'super_admin']) && count($matriksPenilaian) > 0)
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h2 class="card-title">Matriks Penilaian Standar Mutu (Pedoman Auditor)</h2>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1rem;">
            Berikut adalah rincian rubrik penilaian yang dapat digunakan sebagai acuan dasar (P1-P5) dalam memberikan skor (1-4) saat mengevaluasi Laporan Evaluasi Diri (LED).
        </p>
        
        <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
            @foreach($matriksPenilaian as $kelompok => $standards)
                <div style="background-color: var(--bg-tertiary); padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-color); font-weight: 700; color: var(--text-primary); font-size: 0.875rem;">
                    {{ $kelompok ?: 'Non-Akademik' }}
                </div>
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.75rem; margin-bottom: 0;">
                        <thead>
                            <tr style="background-color: var(--bg-secondary);">
                                <th style="width: 15%;">Standar</th>
                                <th style="width: 20%;">Indikator Utama</th>
                                <th style="width: 65%;">Rubrik Penilaian (Skor)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($standards as $std)
                            <tr>
                                <td style="font-weight: 600; vertical-align: top;">{{ $std->kode }}<br><span style="color: var(--brand-primary);">{{ $std->nama }}</span></td>
                                <td style="vertical-align: top; color: var(--text-secondary);">{!! nl2br(e(Str::limit($std->indikator, 150))) !!}</td>
                                <td style="vertical-align: top;">
                                    @if($std->rubrik_penilaian)
                                        @php $rubrik = is_string($std->rubrik_penilaian) ? json_decode($std->rubrik_penilaian, true) : $std->rubrik_penilaian; @endphp
                                        @if(is_array($rubrik))
                                            <ul style="margin: 0; padding-left: 1rem; list-style-type: none;">
                                                <li style="margin-bottom: 0.5rem;"><strong>Skor 4 (Sangat Baik):</strong> {{ $rubrik['skor_4'] ?? '-' }}</li>
                                                <li style="margin-bottom: 0.5rem;"><strong>Skor 3 (Baik):</strong> {{ $rubrik['skor_3'] ?? '-' }}</li>
                                                <li style="margin-bottom: 0.5rem;"><strong>Skor 2 (Cukup):</strong> {{ $rubrik['skor_2'] ?? '-' }}</li>
                                                <li><strong>Skor 1 (Kurang):</strong> {{ $rubrik['skor_1'] ?? '-' }}</li>
                                            </ul>
                                        @else
                                            <span style="color: var(--text-muted);">-</span>
                                        @endif
                                    @else
                                        <span style="color: var(--text-muted);">Belum ada rubrik</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
