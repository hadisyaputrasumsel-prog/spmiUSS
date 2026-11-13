@extends('layouts.app')

@section('title', 'Program Kerja Audit')
@section('page_title', 'Program Kerja Audit (PKA)')

@section('content')
<div class="mb-4" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; color: var(--text-primary); margin: 0;">Program Kerja Audit (PKA)</h2>
        <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem;">
            Daftar PKA yang disusun auditor sebelum pelaksanaan audit mutu internal sesuai format baku LPMA.
        </p>
    </div>

    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        @if($unit_id && $unit_id !== 'all')
        <a href="{{ route('pka.export-unit-pdf', ['unit_id' => $unit_id, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-outline" style="border-color: #6366f1; color: #6366f1;">
            <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 4px;"></i> Cetak Semua PKA Unit Ini (PDF)
        </a>
        @endif
        <a href="{{ route('pka.create', ['unit_id' => $unit_id !== 'all' ? $unit_id : null, 'tahun' => $tahun]) }}" class="btn btn-primary">
            <i data-feather="plus" style="width: 16px; height: 16px; margin-right: 4px;"></i> Tambah PKA Baru
        </a>
    </div>
</div>

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

<!-- Filter Bar -->
<div class="card mb-4" style="margin-bottom: 1.5rem;">
    <form action="{{ route('pka.index') }}" method="GET" style="padding: 1.25rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tahun Pelaksanaan</label>
            <select name="tahun" class="form-select" onchange="this.form.submit()" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary);">
                @foreach(($availableYears ?? [2027, 2026, 2025, 2024, 2023]) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit Kerja / Program Studi</label>
            <select name="unit_id" class="form-select" onchange="this.form.submit()" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary);">
                <option value="all">Semua Unit</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ $unit_id == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <a href="{{ route('pka.index') }}" class="btn btn-outline" style="height: 42px; display: inline-flex; align-items: center;">Reset</a>
        </div>
    </form>
</div>

<!-- Table of PKA -->
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 120px;">NOMOR PKA</th>
                    <th style="width: 200px;">UNIT / PRODI</th>
                    <th>RUANG LINGKUP & KRITERIA</th>
                    <th style="width: 80px; text-align: center;">LANGKAH</th>
                    <th style="width: 180px;">TIM AUDITOR</th>
                    <th style="width: 160px; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pkas as $pka)
                <tr>
                    <td>
                        <span style="font-weight: 700; color: #0284c7; background: rgba(2, 132, 199, 0.1); padding: 4px 8px; border-radius: 4px; display: inline-block;">
                            {{ $pka->nomor }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $pka->unit->nama }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Tahun {{ $pka->tahun }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 2px;">{{ $pka->ruang_lingkup }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">{!! nl2br(e($pka->kriteria)) !!}</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-info" style="font-size: 0.85rem; padding: 4px 8px;">
                            {{ $pka->langkah->count() }} butir
                        </span>
                    </td>
                    <td style="font-size: 0.85rem;">
                        <div><small style="color: var(--text-muted);">Disusun:</small> <b>{{ $pka->penyusun ? ($pka->penyusun->nama_lengkap ?: $pka->penyusun->name) : '-' }}</b></div>
                        <div><small style="color: var(--text-muted);">Diperiksa:</small> <b>{{ $pka->pemeriksa ? ($pka->pemeriksa->nama_lengkap ?: $pka->pemeriksa->name) : '-' }}</b></div>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: center;">
                            <a href="{{ route('pka.export-pdf', $pka->id) }}" target="_blank" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem; color: #6366f1; border-color: #6366f1;" title="Cetak PDF">
                                <i data-feather="printer" style="width: 14px; height: 14px;"></i>
                            </a>
                            <a href="{{ route('pka.edit', $pka->id) }}" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem; color: #f59e0b; border-color: #f59e0b;" title="Ubah PKA">
                                <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                            </a>
                            <form action="{{ route('pka.destroy', $pka->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen PKA {{ $pka->nomor }}? Data langkah kerja terkait juga akan dihapus.')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem; color: #ef4444; border-color: #ef4444;" title="Hapus PKA">
                                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                        Belum ada dokumen PKA yang disusun untuk filter tahun/unit ini.<br>
                        <a href="{{ route('pka.create', ['unit_id' => $unit_id !== 'all' ? $unit_id : null, 'tahun' => $tahun]) }}" class="btn btn-primary" style="margin-top: 1rem;">
                            <i data-feather="plus" style="width: 16px; height: 16px; margin-right: 4px;"></i> Susun PKA Baru Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
