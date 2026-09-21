@extends('layouts.app')

@section('title', 'Evaluasi Diri (LED)')
@section('page_title', 'Evaluasi Diri (LED) - Siklus PPEPP')

@section('content')

@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="alert-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('error') }}
    </div>
@endif

@if(!in_array(auth()->user()->role->kode, ['auditor', 'lpma']) && (auth()->user()->role->kode != 'super_admin' || (isset($unit_id) && $unit_id != 'all')))
    @if(!$hasSubmitted)
    <div class="card mb-4" style="border: 2px solid var(--brand-primary); background-color: var(--bg-primary);">
        <div class="card-header" style="background-color: var(--brand-primary); color: white;">
            <h2 class="card-title" style="color: white; margin:0;"><i data-feather="send" style="width: 18px; margin-right: 8px; vertical-align: bottom;"></i> Ajukan Penilaian LED</h2>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <p style="margin-bottom: 1rem; color: var(--text-secondary);">Seluruh pengisian daftar standar mutu ini merupakan <strong>kesatuan utuh dari unit</strong>. Auditor tidak akan memproses atau melakukan penilaian AMI sebelum Anda memverifikasi & mengajukan LED secara keseluruhan.</p>
            
            <form action="{{ route('led.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                @if(auth()->user()->role->kode == 'super_admin')
                    <input type="hidden" name="unit_id" value="{{ $unit_id }}">
                @endif
                <div style="margin-bottom: 1.5rem; background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius-md); border: 1px dashed var(--border-color);">
                    <label style="display: flex; gap: 1rem; cursor: pointer; align-items: flex-start; margin: 0;">
                        <input type="checkbox" name="verifikasi" value="1" required style="margin-top: 0.25rem; width: 1.25rem; height: 1.25rem;">
                        <span style="font-size: 0.875rem; color: var(--text-primary); line-height: 1.5;">
                            <strong>Verifikasi Kelengkapan:</strong> Saya menyatakan bahwa isian LED ini telah diisi sesuai keadaan sebenarnya. Apabila ada standar yang belum diisi (kosong), saya telah memeriksa dan mengkonfirmasi bahwa dokumen untuk standar tersebut memang tidak ada pada periode ini. Saya yakin untuk mengajukan LED ini.
                        </span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-weight: 500;">
                    <i data-feather="check-square" style="width: 18px; margin-right: 8px; vertical-align: bottom;"></i> Ajukan Penilaian LED Sekarang
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="alert" style="background-color: var(--status-success-bg); color: var(--status-success); border: 1px solid var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
        <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
        <div>
            <strong style="display:block; margin-bottom: 0.25rem;">Telah Diverifikasi & Diajukan!</strong> Laporan Evaluasi Diri Unit {{ auth()->user()->role->kode == 'super_admin' ? 'ini' : 'Anda' }} untuk siklus ini (Tahun {{ $tahun }}) telah dikunci dan diserahkan ke Auditor untuk dinilai dalam tahapan AMI. Anda sudah tidak perlu dan tidak bisa merubah isiannya lagi.
        </div>
    </div>
    @endif
@endif

<div class="card mb-4" style="margin-bottom: 2rem;">
    <div class="card-header" style="background-color: var(--bg-tertiary);">
        <h2 class="card-title" style="font-size: 1rem;">Filter Pencarian</h2>
    </div>
    <form method="GET" action="{{ route('led.index') }}" style="padding: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tahun Pelaporan</label>
            <select name="tahun" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary);">
                @php
                    $activeYear = \App\Models\BulanMutuConfig::where('is_active', true)->value('tahun') ?? date('Y');
                    $userRole = auth()->user()->role->kode;
                    $availableYears = in_array($userRole, ['super_admin', 'lpma']) 
                                        ? [2027, 2026, 2025, 2024, 2023] 
                                        : [$activeYear];
                @endphp
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ (isset($tahun) && $tahun == $y) ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        @if(in_array(auth()->user()->role->kode, ['super_admin', 'lpma', 'auditor']))
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit Kerja / Prodi</label>
            <select name="unit_id" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary);">
                @if(auth()->user()->role->kode != 'auditor')
                <option value="all">-- Semua Unit --</option>
                @endif
                @php
                    if (auth()->user()->role->kode == 'auditor') {
                        $assignedUnitIds = \App\Models\AuditorAssignment::where('auditor_id', auth()->id())
                                ->where('tahun', $tahun ?? date('Y'))->pluck('unit_id');
                        $unitsList = \App\Models\Unit::whereIn('id', $assignedUnitIds)->get();
                    } else {
                        $unitsList = \App\Models\Unit::all();
                    }
                @endphp
                @foreach($unitsList as $u)
                    <option value="{{ $u->id }}" {{ (isset($unit_id) && $unit_id == $u->id) ? 'selected' : '' }}>{{ $u->nama }} ({{ $u->jenis }})</option>
                @endforeach
            </select>
        </div>
        @else
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit Kerja / Prodi</label>
            <input type="text" class="form-control" value="{{ auth()->user()->unit->nama ?? 'Unit Tidak Diketahui' }}" disabled style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-muted); cursor: not-allowed;">
            <input type="hidden" name="unit_id" value="{{ auth()->user()->unit_id }}">
        </div>
        @endif
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Status Kelengkapan</label>
            <select name="status" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary);">
                <option value="all" {{ (isset($status) && $status == 'all') ? 'selected' : '' }}>Semua Status</option>
                <option value="lengkap" {{ (isset($status) && $status == 'lengkap') ? 'selected' : '' }}>Lengkap</option>
                <option value="draft" {{ (isset($status) && $status == 'draft') ? 'selected' : '' }}>Draft / Belum Mengisi</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="height: 42px;">Terapkan Filter</button>
            @if(isset($unit_id) && $unit_id != 'all' && $unit_id != -1)
            <a href="{{ route('led.pdf_full', ['unit_id' => $unit_id, 'tahun' => $tahun ?? date('Y')]) }}" target="_blank" class="btn btn-outline" style="height: 42px; display: inline-flex; align-items: center; justify-content: center; border-color: #6366f1; color: #6366f1; font-weight: 500;">
                <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 4px;"></i> Cetak Borang Pertanyaan
            </a>
            @endif
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Standar Mutu</h2>
    </div>
    @php
        $groupedStandards = $standards->groupBy('kelompok');
    @endphp
    @foreach($groupedStandards as $kelompok => $groupStandards)
    @php 
        $namaKelompok = $kelompok ?: 'Non-Akademik';
        $groupId = 'led-group-' . md5($namaKelompok); 
    @endphp
    <div style="background-color: var(--bg-secondary); padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); border-top: 1px solid var(--border-color); cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleLedGroup('{{ $groupId }}', this)">
        <h3 style="margin: 0; font-size: 1rem; color: var(--text-primary); font-weight: 600;">
            {{ $namaKelompok }} 
            <span class="badge" style="background-color: var(--status-info-bg); color: var(--status-info); border: none; margin-left: 0.5rem; font-size: 0.75rem;">{{ count($groupStandards) }} Standar</span>
        </h3>
        <i data-feather="chevron-down" class="toggle-icon" style="width: 20px; height: 20px; color: var(--text-muted); transition: transform 0.3s ease; transform: rotate(-180deg);"></i>
    </div>
    
    <div class="table-responsive" id="{{ $groupId }}" style="display: none; transition: all 0.3s ease;">
        <table class="table" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <th style="width: 150px;">KODE</th>
                    <th>NAMA STANDAR / KATEGORI</th>
                    <th>UNIT KERJA</th>
                    <th style="width: 120px;">STATUS LED</th>
                    @if(auth()->user()->role->kode == 'auditor')
                    <th style="width: 150px;">STATUS AUDIT</th>
                    @endif
                    <th style="width: 210px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupStandards as $standard)
                @php
                    $entryQuery = \App\Models\LedEntry::with('unit')->where('objek_kode', $standard->kode)
                                        ->where('tahun', $tahun ?? date('Y'));
                    if (isset($unit_id) && $unit_id != 'all') {
                        $entryQuery->where('unit_id', $unit_id);
                    }
                    $entries = $entryQuery->get();
                    $entry = $entries->first();
                    $isLengkap = false;
                    if($entry) {
                        $isLengkap = ($entry->status_pengisian === 'lengkap');
                    }
                    
                    $unitNames = [];
                    foreach($entries as $e) {
                        if($e->unit) $unitNames[] = $e->unit->nama;
                    }
                @endphp
                <tr>
                    <td style="font-family: monospace; font-size: 0.875rem; padding-left: 1.5rem;">{{ $standard->kode }}</td>
                    <td style="font-weight: 500; color: var(--brand-primary);">{{ $standard->nama }}</td>
                    <td style="font-size: 0.875rem;">
                        @if(count($unitNames) > 0)
                            {{ implode(', ', $unitNames) }}
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($isLengkap)
                            <span class="badge badge-success">Lengkap</span>
                        @elseif($entry)
                            <span class="badge badge-warning">Draft</span>
                        @else
                            <span class="badge" style="background-color: var(--border-color); color: var(--text-muted);">Belum Ada</span>
                        @endif
                    </td>
                    @if(auth()->user()->role->kode == 'auditor')
                    @php
                        $isAudited = false;
                        $auditScore = null;
                        if(isset($unit_id) && $unit_id != 'all') {
                            $finding = \App\Models\AmiFinding::where('standar_kode', $standard->kode)
                                            ->where('unit_id', $unit_id)
                                            ->whereYear('tanggal', $tahun ?? date('Y'))
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                            if ($finding) {
                                $isAudited = true;
                                $auditScore = $finding->skor;
                            }
                        }
                    @endphp
                    <td>
                        @if($isAudited)
                            <span class="badge" style="background-color: var(--status-success-bg); color: var(--status-success);">
                                <i data-feather="check-circle" style="width: 12px; height: 12px;"></i> Sudah Diaudit
                            </span>
                            @if($auditScore)
                                <div style="font-size: 0.75rem; margin-top: 4px; color: var(--text-muted);">Skor: {{ $auditScore }}</div>
                            @endif
                        @else
                            <span class="badge" style="background-color: var(--status-warning-bg); color: var(--status-warning);">
                                <i data-feather="clock" style="width: 12px; height: 12px;"></i> Belum Diaudit
                            </span>
                        @endif
                    </td>
                    @endif
                    <td>
                        <div style="display: flex; gap: 0.25rem; justify-content: flex-end;">
                            @if(auth()->user()->role->kode == 'auditor')
                                @if(!$hasSubmitted && !$isAudited)
                                    <span class="badge" style="background-color: var(--bg-tertiary); color: var(--text-muted); padding: 0.4rem 0.75rem; font-size: 0.7rem; border: 1px dashed var(--border-color);">
                                        ⏳ Menunggu Unit Mengajukan LED
                                    </span>
                                @else
                                    <a href="{{ route('ami.create', ['standar_kode' => $standard->kode, 'unit_id' => $unit_id != 'all' ? $unit_id : '']) }}" class="btn {{ $isAudited ? 'btn-outline' : 'btn-primary' }}" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                        <i data-feather="{{ $isAudited ? 'edit' : 'check-square' }}" style="width: 14px; height: 14px;"></i> {{ $isAudited ? 'Edit Audit' : 'Audit LED' }}
                                    </a>
                                @endif
                            @else
                                @if($hasSubmitted)
                                <a href="{{ route('led.edit', ['kode' => $standard->kode, 'tahun' => $tahun ?? date('Y'), 'unit_id' => $unit_id ?? 'all']) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                    <i data-feather="eye" style="width: 14px; height: 14px;"></i> Lihat
                                </a>
                                @else
                                <a href="{{ route('led.edit', ['kode' => $standard->kode, 'tahun' => $tahun ?? date('Y'), 'unit_id' => $unit_id ?? 'all']) }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                    <i data-feather="edit-2" style="width: 14px; height: 14px;"></i> Isi LED
                                </a>
                                @endif
                            @endif
                            @if($entry)
                            <a href="{{ route('led.pdf', ['kode' => $standard->kode, 'tahun' => $tahun ?? date('Y'), 'unit_id' => $unit_id ?? 'all']) }}" target="_blank" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; color: #dc3545; border-color: #dc3545;">
                                <i data-feather="printer" style="width: 14px; height: 14px;"></i> PDF
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>

@section('scripts')
<script>
    function toggleLedGroup(groupId, headerElement) {
        let el = document.getElementById(groupId);
        let icon = headerElement.querySelector('.toggle-icon');
        if (el.style.display === 'none' || el.style.display === '') {
            el.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
            localStorage.setItem(groupId, 'open');
        } else {
            el.style.display = 'none';
            icon.style.transform = 'rotate(-180deg)';
            localStorage.setItem(groupId, 'closed');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.table-responsive').forEach(function(el) {
            if (el.id && el.id.startsWith('led-group-')) {
                let state = localStorage.getItem(el.id);
                if (state === 'open') {
                    let headerElement = el.previousElementSibling;
                    let icon = headerElement ? headerElement.querySelector('.toggle-icon') : null;
                    el.style.display = 'block';
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            }
        });
    });
</script>
@endsection
@endsection
