@extends('layouts.app')

@section('title', 'Bulan Mutu')
@section('page_title', 'Pemantauan Kegiatan Bulan Mutu')

@section('content')

@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card mb-4" style="margin-bottom: 2rem;">
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
        <h2 class="card-title" style="font-size: 1rem;">Filter Tahun Pelaksanaan</h2>
    </div>
    <div style="padding: 1.5rem; display: flex; align-items: flex-end; gap: 1rem;">
        <form action="{{ route('bulan-mutu.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex: 1;">
            <div style="flex: 1; max-width: 250px;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Pilih Tahun</label>
                <select name="tahun" id="tahunSelect" onchange="this.form.submit()" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary);">
                    @php
                        $userRole = auth()->user()->role->kode;
                        $dropdownYears = in_array($userRole, ['super_admin', 'lpma']) ? empty($availableYears) ? [date('Y')] : $availableYears : [\App\Models\BulanMutuConfig::where('is_active', true)->value('tahun') ?? date('Y')];
                    @endphp
                    @foreach($dropdownYears as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        
        @php
            $isActiveYear = \App\Models\BulanMutuConfig::where('tahun', $tahun)->where('is_active', true)->exists();
        @endphp
        
        <div style="padding-bottom: 0.25rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
            @if(in_array(auth()->user()->role->kode, ['super_admin', 'lpma']))
                <button onclick="document.getElementById('generateModal').style.display = 'flex'" class="btn" style="height: 42px; background: #10b981; color: white; border: none; padding: 0 1rem; border-radius: var(--radius-md); font-weight: 500; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(16,185,129,0.3);">
                    <i data-feather="plus-circle" style="width: 16px; height: 16px; margin-right: 4px;"></i> Generate Siklus Baru
                </button>
            @endif

            @if($isActiveYear)
                <div style="display: inline-flex; align-items: center; height: 42px;">
                    <span class="badge badge-success" style="font-size: 0.8rem; padding: 0.5rem 0.75rem;"><i data-feather="check-circle" style="width: 14px; height: 14px; margin-right: 4px;"></i> Tahun Pelaksanaan Aktif</span>
                </div>
            @elseif(in_array(auth()->user()->role->kode, ['super_admin', 'lpma']))
                <form action="{{ route('bulan-mutu.set-active') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button type="submit" class="btn btn-outline" onclick="return confirm('Jadikan {{ $tahun }} sebagai tahun pelaksanaan SPMI yang aktif secara global?')" style="height: 42px; border-color: var(--brand-primary); color: var(--brand-primary);">
                        <i data-feather="star" style="width: 16px; height: 16px; margin-right: 4px;"></i> Jadikan Tahun Aktif
                    </button>
                </form>
            @endif
            
            <a href="{{ route('bulan-mutu.pdf', ['tahun' => $tahun]) }}" target="_blank" class="btn btn-primary" style="height: 42px; display: inline-flex; align-items: center; justify-content: center;">
                <i data-feather="printer" style="width: 16px; height: 16px; margin-right: 4px;"></i> Cetak PDF
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th>NAMA KEGIATAN</th>
                    <th>TAHAP (PPEPP)</th>
                    <th>PENANGGUNG JAWAB</th>
                    <th>STATUS PELAKSANAAN</th>
                    @if(in_array(auth()->user()->role->kode, ['super_admin', 'lpma']))
                    <th>AKSI</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                @php
                    $statusRecord = $statuses->get($activity->id);
                    $status = $statusRecord ? $statusRecord->status : 'Belum Dilaksanakan';
                @endphp
                <tr>
                    <td style="font-weight: 600; text-align: center;">{{ $activity->index_kegiatan }}</td>
                    <td style="font-weight: 500;">
                        {{ $activity->nama }}
                        @if($statusRecord)
                            @if($statusRecord->tanggal_pelaksanaan)
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                <i data-feather="calendar" style="width: 12px; height: 12px; margin-right: 2px;"></i>
                                Tgl: {{ $statusRecord->tanggal_pelaksanaan }}
                            </div>
                            @endif
                            @if($statusRecord->dokumen_bukti)
                            <div style="margin-top: 0.5rem;">
                                <a href="{{ asset($statusRecord->dokumen_bukti) }}" target="_blank" style="font-size: 0.75rem; text-decoration: none; color: var(--brand-primary); display: inline-flex; align-items: center; background: var(--bg-primary); padding: 0.15rem 0.5rem; border-radius: 4px; border: 1px solid var(--brand-primary);">
                                    <i data-feather="file" style="width: 12px; height: 12px; margin-right: 4px;"></i> Bukti Dokumen
                                </a>
                            </div>
                            @endif
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $activity->tahap }}</span>
                    </td>
                    <td style="font-size: 0.875rem; color: var(--text-secondary);">{{ $activity->pic }}</td>
                    <td>
                        @if($status == 'Terlaksana Sesuai Rencana')
                            <span class="badge badge-success">Terlaksana</span>
                        @elseif($status == 'Terlaksana - Tertunda')
                            <span class="badge badge-warning">Terlaksana (Tertunda)</span>
                        @elseif($status == 'Dibatalkan')
                            <span class="badge badge-danger">Dibatalkan</span>
                        @else
                            <span class="badge" style="background-color: var(--border-color); color: var(--text-secondary);">Belum Dilaksanakan</span>
                        @endif
                    </td>
                    @if(in_array(auth()->user()->role->kode, ['super_admin', 'lpma']))
                    <td>
                        @if (str_contains(strtolower($activity->nama), 'evaluasi diri'))
                            <a href="{{ route('bulan-mutu.pantau-led', ['kegiatan_id' => $activity->id, 'tahun' => $tahun]) }}" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: var(--brand-primary); color: var(--brand-primary); margin-right: 0.25rem;">
                                <i data-feather="monitor" style="width: 12px; height: 12px; margin-right: 2px;"></i> Pantau LED
                            </a>
                        @elseif (str_contains(strtolower($activity->nama), 'pelaksanaan audit mutu'))
                            <a href="{{ route('bulan-mutu.pantau-audit', ['kegiatan_id' => $activity->id, 'tahun' => $tahun]) }}" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: var(--status-warning); color: var(--status-warning-dark); margin-right: 0.25rem;">
                                <i data-feather="monitor" style="width: 12px; height: 12px; margin-right: 2px;"></i> Pantau Audit
                            </a>
                        @endif
                        <button onclick="openStatusModal({{ $activity->id }}, '{{ addslashes($activity->nama) }}', '{{ $status }}')" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                            Update
                        </button>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Update Status (Simple Implementation using hidden form mechanism for demonstration) -->
<div id="statusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 500px; background: var(--bg-secondary);">
        <div class="card-header" style="display: flex; justify-content: space-between;">
            <h3 style="margin: 0; font-size: 1.125rem;">Update Status Kegiatan</h3>
            <button onclick="closeStatusModal()" style="background: none; border: none; cursor: pointer; color: var(--text-muted);"><i data-feather="x"></i></button>
        </div>
        <form id="updateStatusForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <div style="padding: 1.5rem;">
                <p id="modalActivityName" style="font-weight: 500; margin-bottom: 0.5rem; color: var(--text-primary);"></p>
                
                <div style="margin-bottom: 1.5rem;">
                    <a id="btnCetakTemplate" href="#" target="_blank" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.75rem; color: var(--brand-primary); border-color: var(--brand-primary);">
                        <i data-feather="printer" style="width: 14px; height: 14px; margin-right: 4px;"></i> Cetak Template Bukti Kegiatan (Kosong)
                    </a>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Status</label>
                    <select name="status" id="modalStatus" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md);">
                        <option value="Belum Dilaksanakan">Belum Dilaksanakan</option>
                        <option value="Terlaksana Sesuai Rencana">Terlaksana Sesuai Rencana</option>
                        <option value="Terlaksana - Tertunda">Terlaksana - Tertunda</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_pelaksanaan" id="modalTanggal" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Upload Evidence / Dokumen Bukti (Opsional)</label>
                    <input type="file" name="dokumen_bukti" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary);">
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Maks 5MB. Upload dokumen terkait rapat/kegiatan ini.</small>
                </div>

                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Catatan</label>
                    <textarea name="catatan" rows="3" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md);"></textarea>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <button type="button" onclick="closeStatusModal()" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openStatusModal(id, name, currentStatus) {
        document.getElementById('modalActivityName').innerText = name;
        document.getElementById('modalStatus').value = currentStatus;
        document.getElementById('updateStatusForm').action = "/bulan-mutu/" + id + "/status";
        document.getElementById('btnCetakTemplate').href = "/bulan-mutu/" + id + "/template?tahun={{ $tahun }}";
        document.getElementById('statusModal').style.display = 'flex';
    }
    function closeStatusModal() {
        document.getElementById('statusModal').style.display = 'none';
    }
</script>

<!-- Modal Generate Siklus Baru -->
<div id="generateModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; background: var(--bg-secondary);">
        <div class="card-header" style="display: flex; justify-content: space-between;">
            <h3 style="margin: 0; font-size: 1.125rem;">Generate Siklus Baru</h3>
            <button onclick="document.getElementById('generateModal').style.display = 'none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);"><i data-feather="x"></i></button>
        </div>
        <form action="{{ route('bulan-mutu.generate') }}" method="POST">
            @csrf
            <div style="padding: 1.5rem;">
                <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Buka perencaan siklus SPMI untuk tahun baru. Ini akan menyalin seluruh urutan kegiatan.
                </p>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Tahun Siklus (Format: YYYY)</label>
                    <input type="number" name="tahun_generate" class="form-control" value="{{ max(!empty($availableYears) ? $availableYears : [date('Y')]) + 1 }}" required style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary);">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <button type="button" onclick="document.getElementById('generateModal').style.display = 'none'" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--success); border-color: var(--success);">Generate</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
