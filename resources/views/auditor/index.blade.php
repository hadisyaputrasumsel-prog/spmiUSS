@extends('layouts.app')

@section('title', 'Penugasan Auditor')
@section('page_title', 'Manajemen Penugasan Auditor')

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
    <!-- Form Tambah Penugasan -->
    <div class="card" style="height: fit-content;">
        <div class="card-header" style="background-color: var(--bg-tertiary);">
            <h3 style="font-size: 1.125rem; margin: 0;">Tambah Penugasan Baru</h3>
        </div>
        <form action="{{ route('penugasan-auditor.store') }}" method="POST" style="padding: 1.5rem;">
            @csrf
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tahun Audit</label>
                <select name="tahun" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    @for($y = date('Y') + 1; $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Pilih Auditor</label>
                <select name="auditor_id" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="">-- Pilih Auditor --</option>
                    @foreach($auditors as $auditor)
                        <option value="{{ $auditor->id }}">{{ $auditor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Peran Tugas</label>
                <select name="peran" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="Auditor Ketua">Auditor Ketua</option>
                    <option value="Auditor Anggota">Auditor Anggota</option>
                </select>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit / Auditee</label>
                <select name="unit_id" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="">-- Pilih Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->nama }} ({{ $u->jenis }})</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Tetapkan Auditor</button>
        </form>
    </div>

    <!-- Daftar Penugasan -->
    <div class="card">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; margin: 0;">Daftar Penugasan</h3>
            
            <form action="{{ route('penugasan-auditor.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <select name="unit_id" onchange="this.form.submit()" class="form-control" style="padding: 0.25rem 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary); max-width: 250px;">
                    <option value="">Semua Unit</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ (isset($filter_unit_id) && $filter_unit_id == $u->id) ? 'selected' : '' }}>{{ $u->nama }} ({{ $u->jenis }})</option>
                    @endforeach
                </select>
                <select name="tahun" onchange="this.form.submit()" class="form-control" style="padding: 0.25rem 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    @for($y = date('Y') + 1; $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>NAMA AUDITOR</th>
                        <th>UNIT AUDITEE</th>
                        <th>TAHUN</th>
                        <th>PERAN TUGAS</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td style="font-weight: 500;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background-color: var(--brand-primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">
                                    {{ substr($assignment->auditor->name, 0, 1) }}
                                </div>
                                {{ $assignment->auditor->name }}
                            </div>
                        </td>
                        <td>{{ $assignment->unit->nama }}</td>
                        <td>
                            <span class="badge" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $assignment->tahun }}</span>
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ $assignment->peran == 'Auditor Ketua' ? '#2E75B6' : '#548235' }}; color: white;">{{ $assignment->peran }}</span>
                        </td>
                        <td style="text-align: right; display: flex; justify-content: flex-end; gap: 0.25rem;">
                            <button type="button" onclick="document.getElementById('editModal_{{ $assignment->id }}').style.display='flex'" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--brand-primary);" title="Edit Penugasan">
                                <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                            </button>
                            <form action="{{ route('penugasan-auditor.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Hapus penugasan ini?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--status-danger);" title="Hapus Penugasan">
                                    <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                </button>
                            </form>

                            <!-- Edit Modal for this assignment -->
                            <div id="editModal_{{ $assignment->id }}" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; padding: 1rem; text-align: left;">
                                <div class="card" style="width: 100%; max-width: 500px; animation: modalSlideIn 0.3s ease;">
                                    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
                                        <h3 style="font-size: 1.125rem; margin: 0;">Edit Penugasan</h3>
                                        <button type="button" onclick="document.getElementById('editModal_{{ $assignment->id }}').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('penugasan-auditor.update', $assignment->id) }}" method="POST" style="padding: 1.5rem;">
                                        @csrf
                                        @method('PUT')
                                        <div style="margin-bottom: 1.25rem;">
                                            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tahun Audit</label>
                                            <select name="tahun" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                                                @for($y = date('Y') + 1; $y >= 2023; $y--)
                                                    <option value="{{ $y }}" {{ $assignment->tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        
                                        <div style="margin-bottom: 1.25rem;">
                                            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Pilih Auditor</label>
                                            <select name="auditor_id" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                                                @foreach($auditors as $auditor)
                                                    <option value="{{ $auditor->id }}" {{ $assignment->auditor_id == $auditor->id ? 'selected' : '' }}>{{ $auditor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div style="margin-bottom: 1.25rem;">
                                            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Peran Tugas</label>
                                            <select name="peran" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                                                <option value="Auditor Ketua" {{ $assignment->peran == 'Auditor Ketua' ? 'selected' : '' }}>Auditor Ketua</option>
                                                <option value="Auditor Anggota" {{ $assignment->peran == 'Auditor Anggota' ? 'selected' : '' }}>Auditor Anggota</option>
                                            </select>
                                        </div>
                                        
                                        <div style="margin-bottom: 2rem;">
                                            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit / Auditee</label>
                                            <select name="unit_id" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                                                @foreach($units as $u)
                                                    <option value="{{ $u->id }}" {{ $assignment->unit_id == $u->id ? 'selected' : '' }}>{{ $u->nama }} ({{ $u->jenis }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                                            <button type="button" onclick="document.getElementById('editModal_{{ $assignment->id }}').style.display='none'" class="btn btn-outline">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <i data-feather="users" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
                            <p>Belum ada penugasan auditor untuk tahun ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
