@extends('layouts.app')

@section('title', 'Data Sesi Audit')
@section('page_title', 'Data Sesi Audit — ' . $unit->nama)

@section('content')
<div class="mb-4" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <a href="{{ route('ami.index', ['unit_id' => $unit->id, 'tahun' => $tahun]) }}" class="btn btn-outline" style="margin-bottom: 0.5rem;">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Instrumen AMI
        </a>
        <h2 style="font-size: 1.5rem; color: var(--text-primary); margin: 0;">Data Sesi Audit Mutu Internal</h2>
        <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem;">
            Data ini menjadi rujukan resmi identitas, kop, dan distribusi untuk PKA, Form 4 (KKA), dan Form 5 (Deskripsi Temuan).
        </p>
    </div>

    <!-- Quick Switcher Unit & Tahun -->
    <form method="GET" action="{{ route('audit-sesi.edit') }}" style="display: flex; gap: 0.5rem; align-items: center;">
        <select name="unit_id" class="form-select" onchange="this.form.submit()" style="min-width: 320px; max-width: 450px; width: auto; padding: 0.5rem 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); font-size: 0.875rem; font-weight: 500; cursor: pointer;">
            @foreach($units as $u)
                <option value="{{ $u->id }}" {{ $unit->id == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
            @endforeach
        </select>
        <select name="tahun" class="form-select" onchange="this.form.submit()" style="min-width: 95px; width: auto; padding: 0.5rem 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); font-size: 0.875rem; font-weight: 500; cursor: pointer;">
            @foreach([2027, 2026, 2025, 2024, 2023] as $y)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

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

@if(session('warning'))
    <div style="background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="alert-triangle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('warning') }}
    </div>
@endif

@if($errors->any())
    <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="alert-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        Terdapat kesalahan isian:
        <ul style="margin: 0.5rem 0 0 1.25rem;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif



<form action="{{ route('audit-sesi.update') }}" method="POST">
    @csrf
    <input type="hidden" name="unit_id" value="{{ $unit->id }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
        
        <!-- Left: Form Fields -->
        <div class="card">
            <div class="card-header" style="background-color: var(--bg-tertiary);">
                <h3 style="font-size: 1.125rem; margin: 0;">Identitas Dokumen & Pelaksanaan Audit</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            No. KKA (Kertas Kerja Audit) <span style="color: var(--status-danger);">*</span>
                        </label>
                        <input type="text" name="no_kka" value="{{ old('no_kka', $auditSesi->no_kka) }}" placeholder="Contoh: KKA-ILKOM-C6-01" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                        <small style="color: var(--text-muted); font-size: 0.75rem;">Nomor dokumen resmi KKA yang dicetak pada kop Form 4 & Form 5.</small>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            Tanggal Pelaksanaan Audit
                        </label>
                        <input type="date" name="tanggal_audit" value="{{ old('tanggal_audit', $auditSesi->tanggal_audit ? $auditSesi->tanggal_audit->format('Y-m-d') : '') }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                        <small style="color: var(--text-muted); font-size: 0.75rem;">Digunakan sebagai tanggal resmi di kop BAP, Checklist, Form 4 & 5.</small>
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Kriteria Standar Acuan
                    </label>
                    <textarea name="kriteria" rows="2" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">{{ old('kriteria', $auditSesi->kriteria) }}</textarea>
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Contoh: Kriteria C.6 Pendidikan — IAPS 4.0 BAN-PT / Standar SPMI USS</small>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            Lokasi Audit
                        </label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $auditSesi->lokasi) }}" placeholder="Contoh: Kampus USS Talang Ratu KM 5 Palembang" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            Wakil Auditi (Pejabat Teraudit)
                        </label>
                        <input type="text" name="wakil_auditi" value="{{ old('wakil_auditi', $auditSesi->wakil_auditi ?: $unit->pimpinan) }}" placeholder="Nama & Gelar Pejabat Auditi" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                    </div>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Ruang Lingkup Audit
                    </label>
                    <textarea name="ruang_lingkup" rows="2" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">{{ old('ruang_lingkup', $auditSesi->ruang_lingkup) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            Penjamin Mutu Audit (Reviewer LPMA)
                        </label>
                        <input type="text" name="penjamin_mutu" value="{{ old('penjamin_mutu', $auditSesi->penjamin_mutu) }}" placeholder="Contoh: Dr. Hadi Syaputra, S.Kom., M.Kom" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                            Distribusi Laporan
                        </label>
                        @php
                            $currentDist = $auditSesi->distribusi ?: ['Auditi', 'Auditor', 'LPMA', 'Arsip'];
                        @endphp
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap; padding-top: 0.5rem;">
                            @foreach(['Auditi', 'Auditor', 'LPMA', 'Arsip'] as $dst)
                                <label style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.875rem; color: var(--text-primary); cursor: pointer;">
                                    <input type="checkbox" name="distribusi[]" value="{{ $dst }}" {{ in_array($dst, $currentDist) ? 'checked' : '' }}>
                                    {{ $dst }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Catatan Penutup dan Tindak Lanjut (Opsional — dicetak di akhir Form 5)
                    </label>
                    <textarea name="catatan_penutup" rows="4" placeholder="Tuliskan simpulan, prioritas temuan KTS Major, atau rekomendasi strategis untuk pembahasan RTM..." class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">{{ old('catatan_penutup', $auditSesi->catatan_penutup) }}</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('ami.index', ['unit_id' => $unit->id, 'tahun' => $tahun]) }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary" style="padding: 0.625rem 1.5rem;">
                        <i data-feather="save" style="width: 16px; height: 16px; margin-right: 4px;"></i> Simpan Data Sesi
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: Auditor Team Info Card (Readonly from Penugasan) -->
        <div>
            <div class="card mb-4">
                <div class="card-header" style="background-color: var(--bg-tertiary);">
                    <h3 style="font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-feather="users" style="width: 16px; height: 16px;"></i>
                        Tim Auditor (Otomatis)
                    </h3>
                </div>
                <div style="padding: 1.25rem;">
                    <small style="color: var(--text-muted); display: block; margin-bottom: 1rem;">
                        Nama auditor diambil otomatis dari <b>Penugasan Auditor</b> dengan nama lengkap bergelar.
                    </small>

                    <div style="margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color);">
                        <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--brand-primary); display: block; margin-bottom: 0.25rem;">Auditor Ketua</span>
                        <div style="font-weight: 600; color: var(--text-primary);">
                            {{ $ketua ? ($ketua->nama_lengkap ?: $ketua->name) : 'Belum Ditugaskan' }}
                        </div>
                    </div>

                    <div>
                        <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-secondary); display: block; margin-bottom: 0.25rem;">Auditor Anggota</span>
                        @if($anggota->count() > 0)
                            <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem; color: var(--text-primary);">
                                @foreach($anggota as $ang)
                                    <li style="margin-bottom: 0.25rem;">{{ $ang->nama_lengkap ?: $ang->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div style="font-size: 0.85rem; color: var(--text-muted); font-style: italic;">
                                Tidak ada auditor anggota tambahan (tunggal).
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cetakan Preview Link -->
            <div class="card">
                <div class="card-header" style="background-color: var(--bg-tertiary);">
                    <h3 style="font-size: 1rem; margin: 0;">Aksi Cetak Cepat</h3>
                </div>
                <div style="padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('ami.kka.pdf', ['unit_id' => $unit->id, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-outline" style="justify-content: flex-start; border-color: #0284c7; color: #0284c7;">
                        <i data-feather="printer" style="width: 14px; height: 14px; margin-right: 6px;"></i> Cetak Form 4 (KKA Ringkasan)
                    </a>
                    <a href="{{ route('ami.rtl.pdf', ['unit_id' => $unit->id, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-outline" style="justify-content: flex-start; border-color: #10b981; color: #10b981;">
                        <i data-feather="printer" style="width: 14px; height: 14px; margin-right: 6px;"></i> Cetak Form 5 (Deskripsi Temuan)
                    </a>
                    <a href="{{ route('pka.export-unit-pdf', ['unit_id' => $unit->id, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-outline" style="justify-content: flex-start; border-color: #6366f1; color: #6366f1;">
                        <i data-feather="book" style="width: 14px; height: 14px; margin-right: 6px;"></i> Cetak Semua PKA Unit Ini
                    </a>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
