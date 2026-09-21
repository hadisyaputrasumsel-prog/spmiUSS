@extends('layouts.app')

@section('title', 'Standar Mutu')
@section('page_title', 'Manajemen Standar Mutu')

@section('content')

@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card mb-4">
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding: 1.25rem 1.5rem;">
        <h2 class="card-title" style="font-size: 1.125rem; margin-top: 0; margin-bottom: 0;">Daftar Standar SPMI</h2>
        <form action="{{ route('spmi-period.store') }}" method="POST" style="display: flex; gap: 0.5rem; margin: 0;">
            @csrf
            <input type="text" name="nama" class="form-control" placeholder="Nama Instrumen Baru..." required style="width: 210px; padding: 0.4rem 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary); font-size: 0.875rem;">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.875rem; color: var(--primary-color); border-color: var(--primary-color);">+ Tambah Instrumen</button>
        </form>
    </div>
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); background-color: var(--bg-primary); display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="margin-bottom: 0.5rem; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">Pilih Instrumen Standar:</div>
            <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                <select class="form-control" onchange="window.location.href='?periode_id='+this.value" style="width: 350px; max-width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary); font-weight: 500;">
                    @foreach($periodes as $p)
                        <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} {{ $p->is_active ? '(Aktif Saat ini)' : '' }}
                        </option>
                    @endforeach
                </select>
                
                @if($selectedPeriodeId)
                <button type="button" class="btn btn-outline" onclick="editPeriode()" style="padding: 0.5rem; border: 1px solid var(--border-color); color: var(--text-secondary); background: transparent; border-radius: var(--radius-md);" title="Ubah Nama Instrumen">
                    <i data-feather="edit-2" style="width: 18px; height: 18px;"></i>
                </button>
                <form id="editPeriodeForm" action="{{ route('spmi-period.update', $selectedPeriodeId) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="nama" id="editPeriodeNama">
                </form>
                @endif
                
                @if($selectedPeriodeId && $selectedPeriodeId != $activePeriode->id)
                <form action="{{ route('spmi-period.activate', $selectedPeriodeId) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="padding: 0.5rem 1rem; border: 1px solid var(--status-success); color: var(--status-success); font-weight: 600; background: transparent;">Aktifkan Instrumen Ini</button>
                </form>
                @endif
            </div>
        </div>
        
        <div>
            <a href="{{ route('standar-mutu.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                <i data-feather="plus" style="width: 16px; height: 16px;"></i> Tambah Standar Baru
            </a>
        </div>
    </div>

    @foreach($groupedStandards as $kelompok => $standards)
    @php $groupId = 'group-' . md5($kelompok); @endphp
    <div style="background-color: var(--bg-secondary); padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); border-top: 1px solid var(--border-color); cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleGroup('{{ $groupId }}', this)">
        <h3 style="margin: 0; font-size: 1rem; color: var(--text-primary); font-weight: 600;">
            {{ $kelompok }} 
            <span class="badge" style="background-color: var(--status-info-bg); color: var(--status-info); border: none; margin-left: 0.5rem; font-size: 0.75rem;">{{ count($standards) }} Standar</span>
        </h3>
        <i data-feather="chevron-down" class="toggle-icon" style="width: 20px; height: 20px; color: var(--text-muted); transition: transform 0.3s ease; transform: rotate(-180deg);"></i>
    </div>
    <div class="table-responsive" id="{{ $groupId }}" style="display: none; transition: all 0.3s ease;">
        <table class="table" style="margin-bottom: 0;">
            <thead>
                <tr style="background-color: transparent;">
                    <th style="width: 150px;">KODE</th>
                    <th>NAMA STANDAR</th>
                    <th>STATUS</th>
                    <th style="width: 150px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($standards as $standard)
                <tr>
                    <td style="font-family: monospace; font-size: 0.875rem;">{{ $standard->kode }}</td>
                    <td style="font-weight: 500;">
                        {{ $standard->nama }}
                        <div style="margin-top: 4px;">
                        @if($standard->is_akademik)
                            <span class="badge badge-info" style="font-size: 0.65rem;">Akademik</span>
                        @else
                            <span class="badge badge-warning" style="font-size: 0.65rem;">Non-Akademik</span>
                        @endif
                        </div>
                    </td>
                    <td>
                        @if($standard->is_active)
                            <span class="badge" style="background-color: var(--status-success-bg); color: var(--status-success);">Aktif</span>
                        @else
                            <span class="badge" style="background-color: var(--status-danger-bg); color: var(--status-danger);">Tidak Aktif</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('standar-mutu.edit', $standard->id) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--text-secondary);" title="Edit">
                            <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                        </a>
                        <form action="{{ route('standar-mutu.destroy', $standard->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus standar ini? Data LED terkait mungkin akan terhapus.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--status-danger);" title="Hapus">
                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                            </button>
                        </form>
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
    function editPeriode() {
        let oldName = "{{ $periodes->firstWhere('id', $selectedPeriodeId)->nama ?? '' }}";
        let newName = prompt("Ubah Nama Instrumen Standar Mutu:", oldName);
        if (newName && newName.trim() !== '' && newName !== oldName) {
            document.getElementById('editPeriodeNama').value = newName.trim();
            document.getElementById('editPeriodeForm').submit();
        }
    }

    function toggleGroup(groupId, headerElement) {
        let el = document.getElementById(groupId);
        let icon = headerElement.querySelector('.toggle-icon');
        if (el.style.display === 'none' || el.style.display === '') {
            el.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
            localStorage.setItem('spmi_group_' + groupId, 'open');
        } else {
            el.style.display = 'none';
            icon.style.transform = 'rotate(-180deg)';
            localStorage.setItem('spmi_group_' + groupId, 'closed');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.table-responsive').forEach(function(el) {
            if (el.id && el.id.startsWith('group-')) {
                let state = localStorage.getItem('spmi_group_' + el.id);
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
