@extends('layouts.app')

@section('title', 'Bank Dokumen & Evidence')
@section('page_title', 'Bank Dokumen & Evidence Mutu')

@section('content')

@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        <strong style="display: block; margin-bottom: 0.25rem;">Terjadi kesalahan:</strong>
        <ul style="margin: 0; padding-left: 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1.75rem;">
    <div class="card" style="padding: 1.15rem; border-left: 4px solid var(--brand-primary);">
        <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; font-size: 0.95rem;">1. Dokumen Induk Universitas (Global)</div>
        <p style="margin: 0; font-size: 0.82rem; color: var(--text-secondary);">Dikelola LPMA (Kebijakan Mutu, Manual Mutu, Statuta). Bisa dipakai semua unit atau otomatis tampil di LED.</p>
    </div>
    <div class="card" style="padding: 1.15rem; border-left: 4px solid #8b5cf6;">
        <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; font-size: 0.95rem;">2. Pustaka Internal Unit (Privat Unit)</div>
        <p style="margin: 0; font-size: 0.82rem; color: var(--text-secondary);">Untuk SOP Prodi, Kurikulum, dll. Aman terisolasi per unit—tidak bisa dilihat prodi lain, tapi bisa dipakai ulang di banyak standar.</p>
    </div>
    <div class="card" style="padding: 1.15rem; border-left: 4px solid var(--status-success);">
        <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; font-size: 0.95rem;">3. Bukti Khusus Per-Tahap</div>
        <p style="margin: 0; font-size: 0.82rem; color: var(--text-secondary);">Dokumen insidental/rahasia (daftar hadir, nilai, transaksi) tetap diunggah langsung pada form standar & tahap terkait.</p>
    </div>
</div>

@if($canManageGlobal || $canManageUnit)
<div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
    <button type="button" onclick="document.getElementById('addDocModal').style.display='flex'" class="btn btn-primary">
        <i data-feather="plus" style="width: 16px; height: 16px;"></i> Tambah Dokumen ke Pustaka
    </button>
</div>
@endif

<!-- Section 1: Dokumen Induk Universitas (Global) -->
<div class="card mb-4" style="margin-bottom: 2rem;">
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <h2 class="card-title" style="font-size: 1.05rem; margin: 0;">Dokumen Induk Universitas (Global - LPMA)</h2>
            <small style="color: var(--text-secondary);">Tersedia untuk seluruh unit sebagai referensi maupun bukti bersama.</small>
        </div>
        <span class="badge badge-info">{{ $globalDocs->count() }} Dokumen</span>
    </div>
    <div class="table-responsive">
        <table class="table" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <th>JUDUL DOKUMEN</th>
                    <th>KATEGORI</th>
                    <th>CAKUPAN OTOMATIS</th>
                    <th>TAHUN</th>
                    <th>BERKAS / TAUTAN</th>
                    @if($canManageGlobal)
                    <th style="text-align: right;">AKSI</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($globalDocs as $doc)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $doc->judul }}</div>
                        @if($doc->keterangan)
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">{{ $doc->keterangan }}</div>
                        @endif
                    </td>
                    <td><span class="badge" style="background: var(--bg-tertiary); color: var(--text-secondary);">{{ $doc->kategori }}</span></td>
                    <td style="font-size: 0.82rem;">
                        @if($doc->auto_attach)
                            <span class="badge" style="background-color: var(--status-success-bg); color: var(--status-success);">
                                Auto-Attach ({{ $doc->standar_kode ?: 'Semua Standar' }} / {{ $doc->tahap ?: 'Semua Tahap' }})
                            </span>
                        @else
                            <span style="color: var(--text-muted);">Manual (Dipilih di LED)</span>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem;">{{ $doc->tahun ?: 'Semua Tahun' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @if($doc->file_path)
                                <a href="{{ route('evidence-documents.download', $doc) }}" target="_blank" class="btn btn-outline" style="padding: 0.3rem 0.65rem; font-size: 0.8rem;">
                                    <i data-feather="file" style="width: 14px; height: 14px;"></i> Buka File
                                </a>
                            @endif
                            @if($doc->external_url)
                                <a href="{{ $doc->external_url }}" target="_blank" class="btn btn-outline" style="padding: 0.3rem 0.65rem; font-size: 0.8rem; border-color: var(--brand-primary); color: var(--brand-primary);">
                                    <i data-feather="external-link" style="width: 14px; height: 14px;"></i> Buka Tautan
                                </a>
                            @endif
                        </div>
                    </td>
                    @if($canManageGlobal)
                    <td style="text-align: right;">
                        <form action="{{ route('evidence-documents.destroy', $doc) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus dokumen global ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--status-danger);" title="Hapus">
                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $canManageGlobal ? 6 : 5 }}" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                        Belum ada dokumen induk universitas yang didaftarkan oleh LPMA.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Section 2: Pustaka Dokumen Internal Unit -->
<div class="card">
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 class="card-title" style="font-size: 1.05rem; margin: 0;">Pustaka Dokumen Internal Unit (Privat per Unit/Prodi)</h2>
            <small style="color: var(--text-secondary);">SOP Prodi, Kurikulum, dan dokumen unit yang terisolasi—hanya dapat diakses oleh unit pemilik, LPMA, dan Auditor terkait.</small>
        </div>
        @if($units->count() > 1)
        <form method="GET" action="{{ route('evidence-documents.index') }}" style="display: flex; gap: 0.5rem; align-items: center; margin: 0;">
            <select name="unit_id" onchange="this.form.submit()" class="form-select" style="padding: 0.4rem 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary); font-size: 0.85rem;">
                <option value="all">-- Semua Unit yang Diizinkan --</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ (string) $selectedUnitId === (string) $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <th>JUDUL DOKUMEN</th>
                    <th>UNIT PEMILIK</th>
                    <th>KATEGORI</th>
                    <th>TAHUN</th>
                    <th>BERKAS / TAUTAN</th>
                    <th style="text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unitDocs as $doc)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $doc->judul }}</div>
                        @if($doc->keterangan)
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">{{ $doc->keterangan }}</div>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem; font-weight: 500;">{{ $doc->unit->nama ?? '-' }}</td>
                    <td><span class="badge" style="background: #f5f3ff; color: #6d28d9;">{{ $doc->kategori }}</span></td>
                    <td style="font-size: 0.85rem;">{{ $doc->tahun ?: 'Lintas Tahun' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @if($doc->file_path)
                                <a href="{{ route('evidence-documents.download', $doc) }}" target="_blank" class="btn btn-outline" style="padding: 0.3rem 0.65rem; font-size: 0.8rem;">
                                    <i data-feather="lock" style="width: 13px; height: 13px;"></i> Buka File Privat
                                </a>
                            @endif
                            @if($doc->external_url)
                                <a href="{{ $doc->external_url }}" target="_blank" class="btn btn-outline" style="padding: 0.3rem 0.65rem; font-size: 0.8rem; border-color: var(--brand-primary); color: var(--brand-primary);">
                                    <i data-feather="external-link" style="width: 14px; height: 14px;"></i> Buka Tautan
                                </a>
                            @endif
                        </div>
                    </td>
                    <td style="text-align: right;">
                        @if($doc->canBeManagedBy(auth()->user()))
                        <form action="{{ route('evidence-documents.destroy', $doc) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus dokumen ini dari pustaka unit?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; border: none; color: var(--status-danger);" title="Hapus">
                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                            </button>
                        </form>
                        @else
                            <span style="font-size: 0.78rem; color: var(--text-muted);">Baca-saja</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                        Belum ada dokumen di Pustaka Internal Unit.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($canManageGlobal || $canManageUnit)
<!-- Add Document Modal -->
<div id="addDocModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
    <div class="card" style="width: 100%; max-width: 620px; max-height: 92vh; overflow-y: auto;">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.1rem; margin: 0;">Tambah Dokumen ke Pustaka</h3>
            <button type="button" onclick="document.getElementById('addDocModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                <i data-feather="x"></i>
            </button>
        </div>
        <form action="{{ route('evidence-documents.store') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Judul Dokumen <span style="color: var(--status-danger);">*</span></label>
                <input type="text" name="judul" required placeholder="Cth: Kebijakan Mutu USS 2026 / SOP Perwalian Prodi" class="form-control" style="width: 100%; padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Kategori <span style="color: var(--status-danger);">*</span></label>
                    <select name="kategori" required class="form-select" style="width: 100%; padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        <option value="Kebijakan Mutu">Kebijakan Mutu</option>
                        <option value="Manual Mutu">Manual Mutu</option>
                        <option value="SOP">SOP / Prosedur</option>
                        <option value="SK / Pedoman">SK / Pedoman</option>
                        <option value="Kurikulum">Kurikulum</option>
                        <option value="Laporan / Monev">Laporan / Monev</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Tahun (Opsional)</label>
                    <input type="number" name="tahun" value="{{ $activeYear }}" placeholder="Kosongkan jika berlaku lintas tahun" class="form-control" style="width: 100%; padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <small style="color: var(--text-muted); font-size: 0.72rem;">Kosongkan jika dokumen berlaku di semua tahun.</small>
                </div>
            </div>

            @if($canManageGlobal)
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Cakupan (Scope) <span style="color: var(--status-danger);">*</span></label>
                    <select name="scope" id="docScopeSelect" onchange="toggleScopeFields()" class="form-select" style="width: 100%; padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        <option value="global">Global (Semua Unit - LPMA)</option>
                        <option value="unit">Privat Unit Tertentu</option>
                    </select>
                </div>
                <div id="unitSelectWrapper" style="display: none;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Unit Pemilik</label>
                    <select name="unit_id" class="form-select" style="width: 100%; padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="globalAutoAttachWrapper" style="background: var(--bg-tertiary); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; border: 1px solid var(--border-color);">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.85rem; cursor: pointer; margin-bottom: 0.75rem;">
                    <input type="checkbox" name="auto_attach" value="1">
                    Otomatis tampilkan di LED & Auditor (tanpa perlu dipilih ulang oleh unit)
                </label>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Filter Standar (Opsional)</label>
                        <select name="standar_kode" class="form-select" style="width: 100%; padding: 0.45rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary); font-size: 0.82rem;">
                            <option value="">-- Semua Standar --</option>
                            @foreach($standards as $std)
                                <option value="{{ $std->kode }}">{{ $std->kode }} - {{ $std->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Filter Tahap (Opsional)</label>
                        <select name="tahap" class="form-select" style="width: 100%; padding: 0.45rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-secondary); color: var(--text-primary); font-size: 0.82rem;">
                            <option value="">-- Semua Tahap --</option>
                            <option value="P1">P1 - Penetapan</option>
                            <option value="P2">P2 - Pelaksanaan</option>
                            <option value="P3">P3 - Evaluasi</option>
                            <option value="P4">P4 - Pengendalian</option>
                            <option value="P5">P5 - Peningkatan</option>
                        </select>
                    </div>
                </div>
            </div>
            @else
                <input type="hidden" name="scope" value="unit">
                <input type="hidden" name="unit_id" value="{{ auth()->user()->unit_id }}">
                <div style="background: #f5f3ff; color: #5b21b6; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.82rem;">
                    Dokumen ini akan disimpan secara privat untuk unit <strong>{{ auth()->user()->unit->nama ?? '-' }}</strong> dan tidak dapat dilihat oleh unit lain.
                </div>
            @endif

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Unggah Berkas Dokumen (Maks. 15 MB)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Atau Tautan Google Drive / URL Eksternal</label>
                <input type="url" name="external_url" placeholder="https://drive.google.com/..." class="form-control" style="width: 100%; padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem;">Keterangan (Opsional)</label>
                <input type="text" name="keterangan" placeholder="Catatan versi atau ringkasan dokumen..." class="form-control" style="width: 100%; padding: 0.6rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('addDocModal').style.display='none'" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    function toggleScopeFields() {
        const select = document.getElementById('docScopeSelect');
        if (!select) return;
        const unitWrapper = document.getElementById('unitSelectWrapper');
        const autoWrapper = document.getElementById('globalAutoAttachWrapper');
        if (select.value === 'unit') {
            unitWrapper.style.display = 'block';
            autoWrapper.style.display = 'none';
        } else {
            unitWrapper.style.display = 'none';
            autoWrapper.style.display = 'block';
        }
    }
</script>
@endsection
