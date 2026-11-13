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
@if(session('error'))
    <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="alert-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('error') }}
    </div>
@endif
@if($errors->any())
    <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="alert-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        <ul style="margin: 0.25rem 0 0 1.25rem; padding: 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $amiUser = auth()->user();
    $amiRoleKode = $amiUser->role->kode ?? '';
    $canVerifyInAmi = in_array($amiRoleKode, ['super_admin', 'lpma', 'auditor', 'auditor_anggota'], true) || $amiUser->isAuditor();
@endphp

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
                @php $selectedTahun = (int) request('tahun', $tahun ?? date('Y')); @endphp
                @foreach(($availableYears ?? [2027, 2026, 2025, 2024, 2023]) as $y)
                    <option value="{{ $y }}" {{ $selectedTahun === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
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
                                ->where('tahun', $selectedTahun)->pluck('unit_id');
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
                <option value="Temuan Positif (TP)" {{ $selectedKat == 'Temuan Positif (TP)' ? 'selected' : '' }}>Temuan Positif (TP)</option>
                <option value="Peluang Peningkatan (OFI)" {{ $selectedKat == 'Peluang Peningkatan (OFI)' ? 'selected' : '' }}>Peluang Peningkatan (OFI)</option>
                <option value="Observasi (OB)" {{ $selectedKat == 'Observasi (OB)' ? 'selected' : '' }}>Observasi (OB)</option>
                <option value="KTS Minor" {{ $selectedKat == 'KTS Minor' ? 'selected' : '' }}>KTS Minor</option>
                <option value="KTS Mayor" {{ $selectedKat == 'KTS Mayor' ? 'selected' : '' }}>KTS Mayor</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button type="submit" class="btn btn-outline" style="height: 42px;">Terapkan Filter</button>

            @if(auth()->user()->isAuditor() || in_array(auth()->user()->role->kode, ['super_admin', 'lpma']))
            <a href="{{ route('audit-sesi.edit', ['unit_id' => $selectedUnit !== 'all' ? $selectedUnit : null, 'tahun' => $selectedTahun]) }}" class="btn btn-outline" style="height: 42px; display: inline-flex; align-items: center; border-color: #0284c7; color: #0284c7; font-weight: 500;">
                <i data-feather="settings" style="width: 16px; height: 16px; margin-right: 4px;"></i> Data Sesi Audit
            </a>
            @endif
            
            <!-- Generate Form 4 (KKA Ringkasan Temuan) -->
            <button type="submit" formaction="{{ route('ami.kka.pdf') }}" formtarget="_blank" class="btn btn-primary" style="height: 42px; background-color: #0284c7; border-color: #0284c7; box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);">
                <i data-feather="file-text" style="width: 16px; height: 16px; margin-right: 4px;"></i> Cetak Form 4 (KKA)
            </button>

            <!-- Generate existing finding-based report -->
            <button type="submit" formaction="{{ route('ami.pdf') }}" formtarget="_blank" class="btn btn-primary" style="height: 42px; background-color: #2E75B6; border-color: #2E75B6; box-shadow: 0 4px 6px -1px rgba(46, 117, 182, 0.2);">
                <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 4px;"></i> Laporan BAP (Temuan)
            </button>
            
            <!-- Generate the new checklist report -->
            <button type="submit" formaction="{{ route('ami.checklist.pdf') }}" formtarget="_blank" class="btn btn-primary" style="height: 42px; background-color: #6366f1; border-color: #6366f1; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);">
                <i data-feather="file-text" style="width: 16px; height: 16px; margin-right: 4px;"></i> Cetak Checklist AMI
            </button>

            <!-- RTL Form 5 -->
            <button type="submit" formaction="{{ route('ami.rtl.pdf') }}" formtarget="_blank" class="btn btn-primary" style="height: 42px; background-color: #10b981; border-color: #10b981; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">
                <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 4px;"></i> Cetak Form 5 (RCA)
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
                    <th>AKSI</th>
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
                        @if($finding->kategori_temuan == 'Sesuai' || $finding->kategori_temuan == 'K')
                            <span class="badge" style="background-color: #548235; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif(str_contains($finding->kategori_temuan, 'Positif') || $finding->kategori_temuan == 'TP')
                            <span class="badge" style="background-color: #059669; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif(str_contains($finding->kategori_temuan, 'Peluang') || $finding->kategori_temuan == 'OFI')
                            <span class="badge" style="background-color: #7c3aed; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif(str_contains($finding->kategori_temuan, 'OB') || str_contains($finding->kategori_temuan, 'Observasi'))
                            <span class="badge" style="background-color: #2E75B6; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif(str_contains($finding->kategori_temuan, 'Minor'))
                            <span class="badge" style="background-color: #BF8F00; color: white;">{{ $finding->kategori_temuan }}</span>
                        @elseif(str_contains($finding->kategori_temuan, 'Mayor'))
                            <span class="badge" style="background-color: #C00000; color: white;">{{ $finding->kategori_temuan }}</span>
                        @else
                            <span class="badge" style="background-color: #64748b; color: white;">{{ $finding->kategori_temuan }}</span>
                        @endif
                    </td>
                    <td>
                        @if($finding->status_tindak_lanjut == 'Selesai')
                            <span class="badge" style="background-color: #548235; color: white;">Selesai</span>
                        @elseif(in_array($finding->status_tindak_lanjut, ['Proses PTK', 'Proses', 'Sedang Berjalan']))
                            <span class="badge" style="background-color: #BF8F00; color: white;">{{ $finding->status_tindak_lanjut }}</span>
                        @elseif($finding->status_tindak_lanjut == 'Belum' || str_contains($finding->status_tindak_lanjut, 'RTL'))
                            <span class="badge" style="background-color: #C00000; color: white;">Belum / RTL</span>
                        @else
                            <span class="badge" style="background-color: #C00000; color: white;">{{ $finding->status_tindak_lanjut ?: 'Belum' }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: inline-flex; gap: 0.35rem; align-items: center; flex-wrap: wrap; justify-content: center;">
                            <a href="{{ route('ami.pka.pdf', $finding->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="padding: 4px 8px; font-size: 0.75rem; border: 1px solid #2E75B6; color: #2E75B6; background: transparent; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; opacity: 1; transition: all 0.2s;" title="Cetak Kertas Kerja Audit (PKA)" onmouseover="this.style.backgroundColor='#2E75B6'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#2E75B6';">
                                <i data-feather="printer" style="width: 14px; height: 14px; margin-right: 4px;"></i> PKA
                            </a>

                            @php
                                $canEditDelete = ($finding->status_tindak_lanjut !== 'Selesai') && (
                                    in_array($amiRoleKode, ['super_admin', 'lpma'], true) ||
                                    $finding->auditor_id == auth()->id() ||
                                    \App\Models\AuditorAssignment::where('auditor_id', auth()->id())
                                        ->where('unit_id', $finding->unit_id)
                                        ->where('tahun', date('Y', strtotime($finding->tanggal)))
                                        ->where('peran', 'Auditor Ketua')
                                        ->exists()
                                );
                            @endphp

                            @if($canEditDelete)
                            @php
                                $editUrl = $finding->standar_kode
                                    ? route('led.audit', [
                                        'kode' => $finding->standar_kode,
                                        'unit_id' => $finding->unit_id,
                                        'tahun' => $finding->tahun ?: date('Y', strtotime($finding->tanggal))
                                    ])
                                    : route('ami.edit', $finding->id);
                            @endphp
                            <a href="{{ $editUrl }}" class="btn btn-sm btn-outline" style="padding: 4px 8px; font-size: 0.75rem; border: 1px solid #f59e0b; color: #f59e0b; background: transparent; border-radius: 4px; display: inline-flex; align-items: center;" title="Ubah Temuan">
                                <i data-feather="edit-2" style="width: 14px; height: 14px; margin-right: 4px;"></i> Ubah
                            </a>
                            <form action="{{ route('ami.destroy', $finding->id) }}" method="POST" onsubmit="return confirm('Hapus temuan ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline" style="padding: 4px 8px; font-size: 0.75rem; border: 1px solid #ef4444; color: #ef4444; background: transparent; border-radius: 4px; display: inline-flex; align-items: center;" title="Hapus Temuan">
                                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            </form>
                            @endif

                            @if($canVerifyInAmi)
                            <button
                                type="button"
                                onclick="openVerifyRtlModal(this)"
                                data-id="{{ $finding->id }}"
                                data-standar="{{ $finding->standar_kode }}"
                                data-unit="{{ $finding->unit->nama ?? '-' }}"
                                data-status="{{ $finding->status_tindak_lanjut }}"
                                data-rencana="{{ $finding->rencana_tindakan ?? '' }}"
                                data-pic="{{ $finding->pic ?? '' }}"
                                data-batas="{{ $finding->batas_waktu ?? '' }}"
                                data-catatan="{{ $finding->catatan_tindak_lanjut ?? '' }}"
                                class="btn btn-sm"
                                style="padding: 4px 8px; font-size: 0.75rem; border: 1px solid #548235; color: #548235; background: transparent; border-radius: 4px; display: inline-flex; align-items: center; cursor: pointer;"
                                title="Verifikasi Tindak Lanjut (RTL)"
                            >
                                <i data-feather="check-square" style="width: 14px; height: 14px; margin-right: 4px;"></i> Verifikasi RTL
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i data-feather="clipboard" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
                        <p>Belum ada temuan audit yang tercatat di sistem.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($canVerifyInAmi)
<div id="verifyRtlModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
    <div class="card" style="width: 100%; max-width: 540px; background: var(--bg-secondary);">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; margin: 0;">Verifikasi RTL Temuan AMI</h3>
            <button type="button" onclick="closeVerifyRtlModal()" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                <i data-feather="x"></i>
            </button>
        </div>
        <form id="verifyRtlForm" action="" method="POST" style="padding: 1.5rem;">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                <div><strong>Unit:</strong> <span id="verifyUnitName"></span></div>
                <div><strong>Standar:</strong> <span id="verifyStandarKode"></span></div>
            </div>
            <div style="background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.85rem; margin-bottom: 1rem; font-size: 0.85rem;">
                <div><strong>Rencana Tindakan:</strong> <span id="verifyRencana">-</span></div>
                <div style="margin-top: 0.35rem;"><strong>PIC:</strong> <span id="verifyPic">-</span> | <strong>Batas Waktu:</strong> <span id="verifyBatas">-</span></div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.4rem;">Status Tindak Lanjut</label>
                <select name="status_tindak_lanjut" id="verifyStatusSelect" required class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <option value="Selesai">Selesai (Terverifikasi)</option>
                    <option value="Proses PTK">Proses PTK (Perlu Revisi)</option>
                    <option value="Sedang Berjalan">Sedang Berjalan</option>
                    <option value="Belum">Belum</option>
                </select>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.4rem;">Catatan Verifikasi Auditor (Opsional)</label>
                <textarea name="catatan_tindak_lanjut" id="verifyCatatanInput" rows="3" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);" placeholder="Catatan hasil verifikasi RTL..."></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeVerifyRtlModal()" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: #548235; border-color: #548235;">Simpan Verifikasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVerifyRtlModal(button) {
        const id = button.dataset.id;
        document.getElementById('verifyUnitName').innerText = button.dataset.unit || '-';
        document.getElementById('verifyStandarKode').innerText = button.dataset.standar || '-';
        document.getElementById('verifyRencana').innerText = button.dataset.rencana || '-';
        document.getElementById('verifyPic').innerText = button.dataset.pic || '-';
        document.getElementById('verifyBatas').innerText = button.dataset.batas || '-';
        document.getElementById('verifyCatatanInput').value = button.dataset.catatan || '';

        const currentStatus = button.dataset.status || '';
        document.getElementById('verifyStatusSelect').value = currentStatus === 'Proses PTK' ? 'Proses PTK' : 'Selesai';
        document.getElementById('verifyRtlForm').action = "{{ url('/ami') }}/" + id + "/verify-rtl";
        document.getElementById('verifyRtlModal').style.display = 'flex';
    }

    function closeVerifyRtlModal() {
        document.getElementById('verifyRtlModal').style.display = 'none';
    }
</script>
@endif
@endsection
