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

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Pimpinan Unit (Opsional)</label>
                <input type="text" name="pimpinan" placeholder="Cth: Dr. Jhon Doe, M.Kom" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">NUPTK Pimpinan (Opsional)</label>
                <input type="text" name="nuptk_pimpinan" placeholder="000000000" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Auditee</button>
        </form>
    </div>

    <!-- Daftar Unit -->
    <div class="card">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <h3 style="font-size: 1.125rem; margin: 0;">Daftar Terdaftar</h3>
            <form method="GET" action="{{ route('manajemen-auditee.index') }}" style="display: flex; align-items: center; gap: 0.5rem;">
                <label for="unitTahunSelect" style="font-size: 0.8rem; color: var(--text-secondary); margin: 0;">Tahun Siklus:</label>
                <select id="unitTahunSelect" name="tahun" onchange="this.form.submit()" class="form-control" style="padding: 0.35rem 0.6rem; font-size: 0.8rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary); width: auto;">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ (int) $tahun === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>NAMA UNIT (AUDITEE)</th>
                        <th>PIMPINAN</th>
                        <th>KATEGORI</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td style="font-family: monospace; font-size: 0.875rem;">{{ $unit->id }}</td>
                        <td style="font-weight: 500;">{{ $unit->nama }}</td>
                        <td>{{ $unit->pimpinan ?? '-' }}</td>
                        <td><span class="badge" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $unit->jenis }}</span></td>
                        <td style="text-align: right; display: flex; justify-content: flex-end; align-items: center; gap: 0.5rem;">
                            <a href="{{ route('unit.ppepp', ['id' => $unit->id, 'tahun' => $tahun]) }}" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: var(--brand-primary); color: var(--brand-primary);" title="Lihat Siklus PPEPP Unit">
                                <i data-feather="activity" style="width: 14px; height: 14px; margin-right: 4px;"></i> Siklus PPEPP
                            </a>
                            <a href="{{ route('manajemen-auditee.edit', $unit->id) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--text-primary);" title="Edit Auditee">
                                <i data-feather="edit" style="width: 16px; height: 16px;"></i>
                            </a>
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
                        <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
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
