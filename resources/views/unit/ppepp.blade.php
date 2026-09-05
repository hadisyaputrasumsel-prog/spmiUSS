@extends('layouts.app')

@section('title', 'Detail PPEPP Unit')
@section('page_title', 'Detail PPEPP - ' . $unit->nama)

@section('content')
<div class="card mb-4" style="margin-bottom: 2rem;">
    <div class="card-header" style="display: flex; justify-content: space-between;">
        <h2 class="card-title">Siklus PPEPP: {{ $unit->nama }} (Tahun {{ $tahun }})</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline" style="font-size: 0.875rem;">Kembali ke Dashboard</a>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        <p>Halaman ini merupakan tampilan detail (drill-down) pelaksanaan siklus Penjaminan Mutu (PPEPP) khusus untuk <strong>{{ $unit->nama }}</strong> pada periode tahun berjalan.</p>
        
        <div class="micro-timeline" style="display: flex; justify-content: space-between; margin-top: 2rem; margin-bottom: 2rem; position: relative;">
            <div style="position: absolute; top: 15px; left: 5%; width: 90%; height: 2px; background-color: var(--border-color); z-index: 1;"></div>
            
            @php
                $steps = [
                    'ed' => 'Evaluasi Diri',
                    'verifikasi_evidence' => 'Verifikasi Bukti',
                    'desk_evaluation' => 'Desk Eval',
                    'visitasi' => 'Visitasi',
                    'closing' => 'Closing',
                    'rtl' => 'RTL',
                    'verifikasi_rtl' => 'Verifikasi RTL'
                ];
            @endphp
        
            @foreach($steps as $key => $label)
                @php
                    $status = $microTimeline[$key] ?? 'pending';
                    $bgColor = $status == 'done' ? '#548235' : ($status == 'active' ? '#1F4E78' : 'var(--bg-tertiary)');
                    $textColor = $status == 'pending' ? 'var(--text-muted)' : 'white';
                    $icon = $status == 'done' ? 'check' : ($status == 'active' ? 'play' : 'circle');
                    $fontWeight = $status == 'active' ? '700' : '500';
                @endphp
                <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 14%;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background-color: {{ $bgColor }}; color: {{ $textColor }}; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 4px var(--bg-secondary); border: 2px solid {{ $status == 'pending' ? 'var(--border-color)' : 'transparent' }};">
                        <i data-feather="{{ $icon }}" style="width: {{ $status == 'pending' ? '12px' : '16px' }}; height: {{ $status == 'pending' ? '12px' : '16px' }};"></i>
                    </div>
                    <div style="font-size: 0.75rem; font-weight: {{ $fontWeight }}; margin-top: 0.5rem; text-align: center; color: {{ $status == 'pending' ? 'var(--text-muted)' : 'var(--text-primary)' }};">
                        {{ $label }}
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="stat-grid" style="margin-top: 1.5rem; grid-template-columns: repeat(3, 1fr);">
            <div class="stat-card" style="padding: 1rem;">
                <div class="stat-title">Total Evaluasi Diri (LED)</div>
                <div class="stat-value">{{ $leds->count() }}</div>
            </div>
            <div class="stat-card" style="padding: 1rem;">
                <div class="stat-title">Total Temuan AMI</div>
                <div class="stat-value">{{ $findings->count() }}</div>
            </div>
            <div class="stat-card" style="padding: 1rem;">
                <div class="stat-title">Temuan Terbuka (Belum Selesai)</div>
                <div class="stat-value" style="color: var(--status-danger);">
                    {{ $findings->where('status_tindak_lanjut', '!=', 'Selesai')->count() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Temuan AMI yang Memerlukan Tindak Lanjut</h2>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>STANDAR</th>
                    <th>KATEGORI</th>
                    <th>URAIAN TEMUAN</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($findings as $finding)
                <tr>
                    <td>{{ $finding->tanggal }}</td>
                    <td>{{ $finding->standar_kode }}</td>
                    <td>
                        <span class="badge {{ str_contains($finding->kategori_temuan, 'Mayor') ? 'badge-danger' : 'badge-warning' }}">
                            {{ $finding->kategori_temuan }}
                        </span>
                    </td>
                    <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $finding->uraian }}">
                        {{ $finding->uraian }}
                    </td>
                    <td>
                        @if($finding->status_tindak_lanjut == 'Selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">RTL Terbuka</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline" onclick="document.getElementById('rtl-form-{{ $finding->id }}').style.display = document.getElementById('rtl-form-{{ $finding->id }}').style.display === 'none' ? 'table-row' : 'none';" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                            <i data-feather="edit-3" style="width: 14px; height: 14px;"></i> Tindak Lanjut
                        </button>
                    </td>
                </tr>
                <tr id="rtl-form-{{ $finding->id }}" style="display: none; background-color: var(--bg-secondary);">
                    <td colspan="6" style="padding: 1.5rem; border-bottom: 2px solid var(--border-color);">
                        <form action="{{ route('ami.update-rtl', $finding->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                                <div>
                                    <h4 style="margin: 0 0 1rem 0; font-size: 0.875rem; color: var(--text-primary);">Detail Temuan Auditor</h4>
                                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;"><strong>Auditor:</strong> {{ $finding->auditor->name ?? 'Tim Auditor' }}</p>
                                    <div style="background-color: var(--bg-primary); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); font-size: 0.875rem; color: var(--text-primary); margin-bottom: 1rem;">
                                        {!! nl2br(e($finding->uraian)) !!}
                                    </div>
                                    @if($finding->catatan_tindak_lanjut)
                                    <p style="font-size: 0.875rem; color: var(--status-warning); margin-bottom: 0.5rem;"><strong>Catatan Verifikasi Auditor:</strong></p>
                                    <div style="background-color: var(--status-warning-bg); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--status-warning); font-size: 0.875rem; color: var(--text-primary);">
                                        {!! nl2br(e($finding->catatan_tindak_lanjut)) !!}
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 style="margin: 0 0 1rem 0; font-size: 0.875rem; color: var(--text-primary);">Form Rencana Tindak Lanjut (RTL)</h4>
                                    
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Rencana Tindakan / Perbaikan <span style="color: var(--status-danger);">*</span></label>
                                        <textarea name="rencana_tindakan" rows="3" required class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">{{ $finding->rencana_tindakan }}</textarea>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Penanggung Jawab (PIC) <span style="color: var(--status-danger);">*</span></label>
                                            <input type="text" name="pic" required value="{{ $finding->pic }}" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Target Batas Waktu <span style="color: var(--status-danger);">*</span></label>
                                            <input type="date" name="batas_waktu" required value="{{ $finding->batas_waktu }}" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                        </div>
                                    </div>
                                    
                                    <div style="text-align: right;">
                                        @if(in_array(auth()->user()->role->kode, ['auditee_upps', 'auditee_prodi', 'auditee_unit', 'super_admin']))
                                            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Simpan RTL</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">Tidak ada data temuan untuk unit ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
