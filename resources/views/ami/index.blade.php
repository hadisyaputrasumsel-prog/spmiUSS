@extends('layouts.app')

@section('title', 'Instrumen AMI')
@section('page_title', 'Daftar Temuan Audit (AMI)')

@section('content')

@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card mb-4" style="margin-bottom: 2rem;">
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
        <h2 class="card-title" style="font-size: 1rem;">Filter Temuan</h2>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                <i data-feather="upload" style="width: 16px; height: 16px;"></i> Import Excel
            </button>
            <a href="{{ route('ami.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                <i data-feather="plus" style="width: 16px; height: 16px;"></i> Tambah Temuan
            </a>
        </div>
    </div>
    
    <!-- Import Modal -->
    <div id="importModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
        <div class="card" style="width: 100%; max-width: 500px; animation: modalSlideIn 0.3s ease;">
            <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.125rem; margin: 0;">Import Data Instrumen AMI</h3>
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="{{ route('ami.import') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Pilih File Excel / CSV <span style="color: var(--status-danger);">*</span></label>
                    <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.5rem;">
                        Pastikan file Anda memiliki header (baris pertama): <b>standar_kode, tahap, unit, kategori_temuan, uraian, rencana_tindakan, pic, batas_waktu, tanggal</b>
                    </small>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('importModal').style.display='none'" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
    <form action="{{ route('ami.index') }}" method="GET" style="padding: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tahun</label>
            <select name="tahun" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary);">
                @php $selectedTahun = request('tahun', date('Y')); @endphp
                @foreach([2027, 2026, 2025, 2024, 2023] as $y)
                    <option value="{{ $y }}" {{ $selectedTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit Ter-Audit (Auditee)</label>
            <select name="unit_id" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary);">
                @if(auth()->user()->role->kode != 'auditor')
                <option value="all">Semua Unit</option>
                @endif
                @php
                    if (auth()->user()->role->kode == 'auditor') {
                        $assignedUnitIds = \App\Models\AuditorAssignment::where('auditor_id', auth()->id())
                                ->where('tahun', request('tahun', date('Y')))->pluck('unit_id');
                        $unitsList = \App\Models\Unit::whereIn('id', $assignedUnitIds)->get();
                    } else {
                        $unitsList = \App\Models\Unit::all();
                    }
                    $selectedUnit = request('unit_id', 'all');
                @endphp
                @foreach($unitsList as $u)
                    <option value="{{ $u->id }}" {{ $selectedUnit == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Kategori Temuan</label>
            <select name="kategori" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary);">
                @php $selectedKat = request('kategori', 'all'); @endphp
                <option value="all">Semua Kategori</option>
                <option value="Sesuai" {{ $selectedKat == 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                <option value="K" {{ $selectedKat == 'K' ? 'selected' : '' }}>Kesesuaian (K)</option>
                <option value="Observasi (OB)" {{ $selectedKat == 'Observasi (OB)' ? 'selected' : '' }}>Observasi (OB)</option>
                <option value="KTS Minor" {{ $selectedKat == 'KTS Minor' ? 'selected' : '' }}>KTS Minor</option>
                <option value="KTS Mayor" {{ $selectedKat == 'KTS Mayor' ? 'selected' : '' }}>KTS Mayor</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-outline" style="height: 42px;">Terapkan Filter</button>
            <button type="submit" formaction="{{ route('ami.pdf') }}" formtarget="_blank" class="btn btn-primary" style="height: 42px; background-color: #2E75B6; border-color: #2E75B6; box-shadow: 0 4px 6px -1px rgba(46, 117, 182, 0.2);">
                <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 4px;"></i> Laporan AMI
            </button>
            <button type="submit" formaction="{{ route('ami.rtl.pdf') }}" formtarget="_blank" class="btn btn-primary" style="height: 42px; background-color: #10b981; border-color: #10b981; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">
                <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 4px;"></i> Laporan RTL
            </button>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>UNIT / PRODI</th>
                    <th>NAMA STANDAR</th>
                    <th>KODE STANDAR</th>
                    <th>TAHAP</th>
                    <th>SKOR</th>
                    <th>KATEGORI</th>
                    <th>TINDAK LANJUT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($findings as $finding)
                <tr>
                    <td style="font-size: 0.875rem;">{{ $finding->tanggal }}</td>
                    <td style="font-weight: 500;">{{ $finding->unit->nama }}</td>
                    <td style="font-size: 0.875rem;">{{ $finding->standard->nama ?? '-' }}</td>
                    <td style="font-family: monospace; font-size: 0.875rem;">{{ $finding->standar_kode }}</td>
                    <td>
                        @if($finding->tahap == 'P1')
                            <span class="badge" style="background-color: #2E75B6; color: white;">1. Penetapan</span>
                        @elseif($finding->tahap == 'P2')
                            <span class="badge" style="background-color: #548235; color: white;">2. Pelaksanaan</span>
                        @elseif($finding->tahap == 'P3')
                            <span class="badge" style="background-color: #BF8F00; color: white;">3. Evaluasi</span>
                        @elseif($finding->tahap == 'P4')
                            <span class="badge" style="background-color: #C00000; color: white;">4. Pengendalian</span>
                        @elseif($finding->tahap == 'P5')
                            <span class="badge" style="background-color: #7030A0; color: white;">5. Peningkatan</span>
                        @else
                            <span class="badge" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $finding->tahap }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($finding->skor == 4)
                            <span class="badge" style="background-color: var(--status-success); color: white; padding: 4px 8px; font-weight: bold;">4</span>
                        @elseif($finding->skor == 3)
                            <span class="badge" style="background-color: var(--status-info); color: white; padding: 4px 8px; font-weight: bold;">3</span>
                        @elseif($finding->skor == 2)
                            <span class="badge" style="background-color: var(--status-warning); color: white; padding: 4px 8px; font-weight: bold;">2</span>
                        @elseif($finding->skor == 1)
                            <span class="badge" style="background-color: var(--status-danger); color: white; padding: 4px 8px; font-weight: bold;">1</span>
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($finding->kategori_temuan == 'Sesuai')
                            <span class="badge" style="background-color: #548235; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif($finding->kategori_temuan == 'Observasi (OB)')
                            <span class="badge" style="background-color: #2E75B6; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif($finding->kategori_temuan == 'KTS Minor')
                            <span class="badge" style="background-color: #BF8F00; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif($finding->kategori_temuan == 'KTS Mayor')
                            <span class="badge" style="background-color: #C00000; color: white;">{{ $finding->kategori_temuan }}</span>
                        @else
                            <span class="badge" style="background-color: #548235; color: white;">Sesuai</span>
                        @endif
                    </td>
                    <td>
                        @if($finding->status_tindak_lanjut == 'Selesai')
                            <span class="badge" style="background-color: #548235; color: white;">Selesai</span>
                        @elseif($finding->status_tindak_lanjut == 'Proses PTK' || $finding->status_tindak_lanjut == 'Proses')
                            <span class="badge" style="background-color: #BF8F00; color: white;">Proses PTK</span>
                        @elseif($finding->status_tindak_lanjut == 'Belum' || str_contains($finding->status_tindak_lanjut, 'RTL'))
                            <span class="badge" style="background-color: #C00000; color: white;">Belum / RTL</span>
                        @else
                            <span class="badge" style="background-color: #C00000; color: white;">{{ $finding->status_tindak_lanjut ?: 'Belum' }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i data-feather="clipboard" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
                        <p>Belum ada temuan audit yang tercatat di sistem.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
