@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('page_title', 'Akun & Peran Pengguna')

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
    <!-- Form Tambah Akun -->
    <div class="card" style="height: fit-content;">
        <div class="card-header" style="background-color: var(--bg-tertiary);">
            <h3 style="font-size: 1.125rem; margin: 0;">Tambah Akun Baru</h3>
        </div>
        <form action="{{ route('akun.store') }}" method="POST" style="padding: 1.5rem;">
            @csrf
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Nama Lengkap <span style="color: var(--status-danger);">*</span></label>
                <input type="text" name="name" required placeholder="Cth: Budi Santoso" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Alamat Email <span style="color: var(--status-danger);">*</span></label>
                <input type="email" name="email" required placeholder="Cth: budi@uss.ac.id" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Kata Sandi <span style="color: var(--status-danger);">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Peran (Role) <span style="color: var(--status-danger);">*</span></label>
                <select name="role_id" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="">-- Pilih Peran --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nama }} ({{ $role->kode }})</option>
                    @endforeach
                </select>
                <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.25rem;">Catatan: Pilih "Auditor Internal" untuk membuat akun Auditor.</small>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit / Prodi (Opsional)</label>
                <select name="unit_id" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="">-- Tidak Terikat Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                    @endforeach
                </select>
                <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.25rem;">Wajib diisi jika peran adalah Dekan/Kaprodi/Unit.</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Buat Akun</button>
        </form>
    </div>

    <!-- Daftar Akun -->
    <div class="card">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; margin: 0;">Daftar Pengguna Terdaftar</h3>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>PENGGUNA</th>
                        <th>PERAN</th>
                        <th>UNIT/PRODI</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="font-weight: 500; color: var(--text-primary);">{{ $user->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">
                                {{ $user->role->nama ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            @if($user->unit)
                                <span style="font-size: 0.875rem;">{{ $user->unit->nama }}</span>
                            @else
                                <span style="font-size: 0.875rem; color: var(--text-muted); font-style: italic;">-</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <button type="button" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--text-primary);" title="Edit Akun" onclick="editUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role_id }}', '{{ $user->unit_id }}')">
                                    <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('akun.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--status-danger);" title="Hapus Akun">
                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <i data-feather="users" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
                            <p>Belum ada pengguna yang terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Akun -->
<div id="modalEdit" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 500px; margin: 1rem; animation: slideIn 0.3s ease-out;">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; margin: 0;">Edit Akun Pengguna</h3>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                <i data-feather="x"></i>
            </button>
        </div>
        <form id="formEdit" method="POST" style="padding: 1.5rem;">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Nama Lengkap <span style="color: var(--status-danger);">*</span></label>
                <input type="text" name="name" id="edit_name" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Alamat Email <span style="color: var(--status-danger);">*</span></label>
                <input type="email" name="email" id="edit_email" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Kata Sandi Baru</label>
                <input type="password" name="password" id="edit_password" placeholder="Kosongkan jika tidak ingin mengubah sandi" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Peran (Role) <span style="color: var(--status-danger);">*</span></label>
                <select name="role_id" id="edit_role_id" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="">-- Pilih Peran --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nama }} ({{ $role->kode }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit / Prodi (Opsional)</label>
                <select name="unit_id" id="edit_unit_id" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="">-- Tidak Terikat Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editUser(id, name, email, roleId, unitId) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_role_id').value = roleId;
        document.getElementById('edit_unit_id').value = unitId || '';
        
        document.getElementById('formEdit').action = `/akun/${id}`;
        
        let modal = document.getElementById('modalEdit');
        modal.style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('modalEdit').style.display = 'none';
        document.getElementById('formEdit').reset();
    }
</script>

@endsection
