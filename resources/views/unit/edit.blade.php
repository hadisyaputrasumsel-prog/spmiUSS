@extends('layouts.app')

@section('title', 'Edit Auditee')
@section('page_title', 'Edit Unit / Auditee : ' . $unit->nama)

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

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1.125rem; margin: 0;">Form Edit Auditee</h3>
        <a href="{{ route('manajemen-auditee.index') }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem;">Kembali</a>
    </div>
    <form action="{{ route('manajemen-auditee.update', $unit->id) }}" method="POST" style="padding: 1.5rem;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Nama Unit / Auditee <span style="color: var(--status-danger);">*</span></label>
            <input type="text" name="nama" value="{{ old('nama', $unit->nama) }}" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
        </div>
        
        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Jenis Unit <span style="color: var(--status-danger);">*</span></label>
            <select name="jenis" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                <option value="Program Studi" {{ $unit->jenis == 'Program Studi' ? 'selected' : '' }}>Program Studi (Prodi)</option>
                <option value="Fakultas" {{ $unit->jenis == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                <option value="Universitas" {{ $unit->jenis == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                <option value="Non-Akademik" {{ $unit->jenis == 'Non-Akademik' ? 'selected' : '' }}>Biro / Lembaga (Non-Akademik)</option>
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Pimpinan Unit (Opsional)</label>
            <input type="text" name="pimpinan" value="{{ old('pimpinan', $unit->pimpinan) }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">NUPTK Pimpinan (Opsional)</label>
            <input type="text" name="nuptk_pimpinan" value="{{ old('nuptk_pimpinan', $unit->nuptk_pimpinan) }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Perbarui Auditee</button>
    </form>
</div>
@endsection
