@extends('layouts.app')

@section('title', 'Manajemen Auditee')
@section('page_title', 'Daftar Unit / Auditee')

@section('content')

@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        {{ $errors->first() }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Form Tambah Unit -->
    <div class="card" style="height: fit-content;">
        <div class="card-header" style="background-color: var(--bg-tertiary);">
            <h3 style="font-size: 1.125rem; margin: 0;">Tambah Auditee Baru</h3>
        </div>
        <form action="{{ route('manajemen-auditee.store') }}" method="POST" style="padding: 1.5rem;">
            @csrf
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Nama Unit / Auditee <span style="color: var(--status-danger);">*</span></label>
                <input type="text" name="nama" required placeholder="Cth: Program Studi Sistem Informasi" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Jenis Unit <span style="color: var(--status-danger);">*</span></label>
                <select name="jenis" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="Program Studi">Program Studi (Prodi)</option>
                    <option value="Fakultas">Fakultas</option>
                    <option value="Universitas">Universitas</option>
                    <option value="Non-Akademik">Biro / Lembaga (Non-Akademik)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Auditee</button>
        </form>
    </div>

    <!-- Daftar Unit -->
    <div class="card">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; margin: 0;">Daftar Terdaftar</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>NAMA UNIT (AUDITEE)</th>
                        <th>KATEGORI</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td style="font-family: monospace; font-size: 0.875rem;">{{ $unit->id }}</td>
                        <td style="font-weight: 500;">{{ $unit->nama }}</td>
                        <td><span class="badge" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $unit->jenis }}</span></td>
                        <td style="text-align: right;">
                            <form action="{{ route('manajemen-auditee.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus unit ini? Data yang terkait mungkin akan hilang.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--status-danger);" title="Hapus Auditee">
                                    <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <i data-feather="home" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
                            <p>Belum ada unit auditee yang terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
