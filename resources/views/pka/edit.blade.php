@extends('layouts.app')

@section('title', 'Ubah Program Kerja Audit (PKA)')
@section('page_title', 'Ubah PKA — ' . $pka->nomor)

@section('content')
<div class="mb-4">
    <a href="{{ route('pka.index', ['unit_id' => $pka->unit_id, 'tahun' => $pka->tahun]) }}" class="btn btn-outline" style="margin-bottom: 0.5rem;">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Daftar PKA
    </a>
    <h2 style="font-size: 1.5rem; color: var(--text-primary); margin: 0;">Ubah Dokumen PKA: {{ $pka->nomor }}</h2>
    <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem;">
        Perbarui identitas, tujuan audit, atau daftar langkah kerja PKA.
    </p>
</div>

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

<form action="{{ route('pka.update', $pka->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- SECTION 1: HEADER & IDENTITAS -->
    <div class="card mb-4" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background-color: var(--bg-tertiary);">
            <h3 style="font-size: 1.125rem; margin: 0;">1. Identitas Dokumen PKA</h3>
        </div>
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Tahun Pelaksanaan <span style="color: var(--status-danger);">*</span>
                    </label>
                    <input type="number" name="tahun" value="{{ old('tahun', $pka->tahun) }}" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Unit Teraudit (Auditi) <span style="color: var(--status-danger);">*</span>
                    </label>
                    <select name="unit_id" required class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('unit_id', $pka->unit_id) == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Nomor PKA <span style="color: var(--status-danger);">*</span>
                    </label>
                    <input type="text" name="nomor" value="{{ old('nomor', $pka->nomor) }}" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Ruang Lingkup <span style="color: var(--status-danger);">*</span>
                    </label>
                    <input type="text" name="ruang_lingkup" value="{{ old('ruang_lingkup', $pka->ruang_lingkup) }}" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Kriteria Acuan <span style="color: var(--status-danger);">*</span>
                    </label>
                    <input type="text" name="kriteria" value="{{ old('kriteria', $pka->kriteria) }}" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Disusun Oleh
                    </label>
                    <select name="disusun_oleh" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                        @foreach($auditors as $aud)
                            <option value="{{ $aud->id }}" {{ old('disusun_oleh', $pka->disusun_oleh) == $aud->id ? 'selected' : '' }}>
                                {{ $aud->nama_lengkap ?: $aud->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Tanggal Disusun
                    </label>
                    <input type="date" name="tanggal_disusun" value="{{ old('tanggal_disusun', $pka->tanggal_disusun ? $pka->tanggal_disusun->format('Y-m-d') : '') }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Diperiksa Oleh
                    </label>
                    <select name="diperiksa_oleh" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                        <option value="">-- Pilih Pemeriksa --</option>
                        @foreach($auditors as $aud)
                            <option value="{{ $aud->id }}" {{ old('diperiksa_oleh', $pka->diperiksa_oleh) == $aud->id ? 'selected' : '' }}>
                                {{ $aud->nama_lengkap ?: $aud->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                        Tanggal Diperiksa
                    </label>
                    <input type="date" name="tanggal_diperiksa" value="{{ old('tanggal_diperiksa', $pka->tanggal_diperiksa ? $pka->tanggal_diperiksa->format('Y-m-d') : '') }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: OBJECTIVES -->
    <div class="card mb-4" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background-color: var(--bg-tertiary);">
            <h3 style="font-size: 1.125rem; margin: 0;">2. Tujuan Audit</h3>
        </div>
        <div style="padding: 1.5rem;">
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                    Tentative Audit Objective
                </label>
                <textarea name="tentative_audit_objective" rows="3" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">{{ old('tentative_audit_objective', $pka->tentative_audit_objective) }}</textarea>
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">
                    Tujuan Audit (Audit Objective)
                </label>
                <textarea name="tujuan_audit" rows="3" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">{{ old('tujuan_audit', $pka->tujuan_audit) }}</textarea>
            </div>
        </div>
    </div>

    <!-- SECTION 3: LANGKAH KERJA DINAMIS -->
    <div class="card mb-4" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; margin: 0;">3. Langkah Kerja Audit</h3>
            <button type="button" onclick="tambahBarisLangkah()" class="btn btn-outline" style="border-color: #0284c7; color: #0284c7; font-size: 0.85rem; padding: 4px 10px;">
                <i data-feather="plus" style="width: 14px; height: 14px; margin-right: 4px;"></i> Tambah Baris Langkah
            </button>
        </div>
        <div style="padding: 1.5rem;">
            <div class="table-responsive">
                <table class="table" id="tabelLangkah" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">NO</th>
                            <th>URAIAN LANGKAH-LANGKAH KERJA <span style="color: var(--status-danger);">*</span></th>
                            <th style="width: 130px;">ANGGARAN WAKTU</th>
                            <th style="width: 120px;">REALISASI</th>
                            <th style="width: 110px;">NO KKA</th>
                            <th style="width: 160px;">DISUSUN OLEH</th>
                            <th style="width: 60px; text-align: center;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyLangkah">
                        @php
                            $langkahList = old('langkah', $pka->langkah->toArray());
                        @endphp

                        @foreach($langkahList as $idx => $step)
                        <tr class="baris-langkah">
                            <td class="nomor-urut" style="text-align: center; font-weight: bold; vertical-align: middle;">{{ $idx + 1 }}</td>
                            <td>
                                <textarea name="langkah[{{ $idx }}][uraian]" rows="2" required class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">{{ $step['uraian'] ?? '' }}</textarea>
                            </td>
                            <td>
                                <input type="text" name="langkah[{{ $idx }}][anggaran_waktu]" value="{{ $step['anggaran_waktu'] ?? '' }}" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                            </td>
                            <td>
                                <input type="text" name="langkah[{{ $idx }}][realisasi]" value="{{ $step['realisasi'] ?? '' }}" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                            </td>
                            <td>
                                <input type="text" name="langkah[{{ $idx }}][no_kka]" value="{{ $step['no_kka'] ?? '' }}" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                            </td>
                            <td>
                                <input type="text" name="langkah[{{ $idx }}][disusun_oleh]" value="{{ $step['disusun_oleh'] ?? ($pka->penyusun ? ($pka->penyusun->nama_lengkap ?: $pka->penyusun->name) : auth()->user()->name) }}" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <button type="button" onclick="hapusBaris(this)" class="btn btn-outline" style="padding: 4px 6px; color: #ef4444; border-color: #ef4444;" title="Hapus Baris">
                                    <i data-feather="trash" style="width: 14px; height: 14px;"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                <button type="button" onclick="tambahBarisLangkah()" class="btn btn-outline" style="border-color: #0284c7; color: #0284c7; font-size: 0.85rem;">
                    <i data-feather="plus" style="width: 14px; height: 14px; margin-right: 4px;"></i> Tambah Baris Langkah
                </button>
            </div>
        </div>
    </div>

    <!-- SUBMIT BAR -->
    <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-bottom: 3rem;">
        <a href="{{ route('pka.index', ['unit_id' => $pka->unit_id, 'tahun' => $pka->tahun]) }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
            <i data-feather="save" style="width: 16px; height: 16px; margin-right: 4px;"></i> Simpan Perubahan PKA
        </button>
    </div>
</form>

<script>
    let stepCount = {{ count($langkahList) }};
    const defaultAuthor = "{{ $pka->penyusun ? ($pka->penyusun->nama_lengkap ?: $pka->penyusun->name) : auth()->user()->name }}";

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#tbodyLangkah .baris-langkah');
        rows.forEach((row, index) => {
            row.querySelector('.nomor-urut').innerText = index + 1;
        });
    }

    function hapusBaris(btn) {
        const rows = document.querySelectorAll('#tbodyLangkah .baris-langkah');
        if (rows.length <= 1) {
            alert('PKA harus memiliki minimal 1 langkah kerja.');
            return;
        }
        btn.closest('tr').remove();
        updateRowNumbers();
    }

    function tambahBarisLangkah() {
        stepCount++;
        const tbody = document.getElementById('tbodyLangkah');
        const tr = document.createElement('tr');
        tr.className = 'baris-langkah';
        tr.innerHTML = `
            <td class="nomor-urut" style="text-align: center; font-weight: bold; vertical-align: middle;"></td>
            <td>
                <textarea name="langkah[${stepCount}][uraian]" rows="2" required class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);"></textarea>
            </td>
            <td>
                <input type="text" name="langkah[${stepCount}][anggaran_waktu]" value="1 hari" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
            </td>
            <td>
                <input type="text" name="langkah[${stepCount}][realisasi]" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
            </td>
            <td>
                <input type="text" name="langkah[${stepCount}][no_kka]" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
            </td>
            <td>
                <input type="text" name="langkah[${stepCount}][disusun_oleh]" value="${defaultAuthor}" class="form-control" style="width: 100%; padding: 0.4rem; font-size: 0.85rem; border-radius: 4px; border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <button type="button" onclick="hapusBaris(this)" class="btn btn-outline" style="padding: 4px 6px; color: #ef4444; border-color: #ef4444;" title="Hapus Baris">
                    <i data-feather="trash" style="width: 14px; height: 14px;"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        feather.replace();
        updateRowNumbers();
    }
</script>
@endsection
