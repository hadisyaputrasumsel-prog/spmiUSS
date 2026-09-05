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
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
        <h2 class="card-title" style="font-size: 1rem;">Daftar Standar SPMI</h2>
        <a href="{{ route('standar-mutu.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
            <i data-feather="plus" style="width: 16px; height: 16px;"></i> Tambah Standar Baru
        </a>
    </div>
    @foreach($groupedStandards as $kelompok => $standards)
    <div style="background-color: var(--bg-secondary); padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <h3 style="margin: 0; font-size: 1rem; color: var(--text-primary); font-weight: 600;">{{ $kelompok }} <span class="badge" style="background-color: var(--status-info-bg); color: var(--status-info); border: none; margin-left: 0.5rem; font-size: 0.75rem;">{{ count($standards) }} Standar</span></h3>
    </div>
    <div class="table-responsive">
        <table class="table" style="margin-bottom: 0;">
            <thead>
                <tr style="background-color: transparent;">
                    <th style="width: 150px;">KODE</th>
                    <th>NAMA STANDAR</th>
                    <th>JENIS</th>
                    <th style="width: 150px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($standards as $standard)
                <tr>
                    <td style="font-family: monospace; font-size: 0.875rem;">{{ $standard->kode }}</td>
                    <td style="font-weight: 500;">{{ $standard->nama }}</td>
                    <td>
                        @if($standard->is_akademik)
                            <span class="badge badge-info">Akademik</span>
                        @else
                            <span class="badge badge-warning">Non-Akademik</span>
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
@endsection
