@extends('layouts.app')

@section('title', 'Ubah Temuan AMI')
@section('page_title', 'Ubah Temuan Audit Mutu Internal')

@section('content')
<div class="mb-4">
    <a href="{{ route('ami.index', ['unit_id' => $finding->unit_id, 'tahun' => date('Y', strtotime($finding->tanggal))]) }}" class="btn btn-outline" style="margin-bottom: 0.5rem;">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Instrumen AMI
    </a>
    <h2 style="font-size: 1.5rem; color: var(--text-primary); margin: 0;">Ubah Temuan: {{ $finding->standar_kode }}</h2>
    <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0; font-size: 0.9rem;">
        Unit: <strong>{{ $finding->unit->nama }}</strong> &mdash; Periode: {{ date('Y', strtotime($finding->tanggal)) }}
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

<form action="{{ route('ami.update', $finding->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header" style="background-color: var(--bg-tertiary);">
            <h3 style="font-size: 1.125rem; margin: 0;">Informasi Temuan Audit</h3>
        </div>
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">Tanggal Audit <span style="color: var(--status-danger);">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d', strtotime($finding->tanggal))) }}" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">Standar Terkait <span style="color: var(--status-danger);">*</span></label>
                    <select name="standar_kode" required class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                        @foreach($standards as $std)
                            <option value="{{ $std->kode }}" {{ old('standar_kode', $finding->standar_kode) == $std->kode ? 'selected' : '' }}>
                                {{ $std->kode }} - {{ substr($std->nama, 0, 45) }}...
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">Tahap PPEPP <span style="color: var(--status-danger);">*</span></label>
                    <select name="tahap" required class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">
                        <option value="Umum" {{ old('tahap', $finding->tahap) == 'Umum' ? 'selected' : '' }}>Keseluruhan Siklus (Umum)</option>
                        <option value="P1" {{ old('tahap', $finding->tahap) == 'P1' ? 'selected' : '' }}>P1 - Penetapan</option>
                        <option value="P2" {{ old('tahap', $finding->tahap) == 'P2' ? 'selected' : '' }}>P2 - Pelaksanaan</option>
                        <option value="P3" {{ old('tahap', $finding->tahap) == 'P3' ? 'selected' : '' }}>P3 - Evaluasi</option>
                        <option value="P4" {{ old('tahap', $finding->tahap) == 'P4' ? 'selected' : '' }}>P4 - Pengendalian</option>
                        <option value="P5" {{ old('tahap', $finding->tahap) == 'P5' ? 'selected' : '' }}>P5 - Peningkatan</option>
                    </select>
                </div>
            </div>

            <!-- Kategori Radio -->
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">Kategori Temuan <span style="color: var(--status-danger);">*</span></label>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    @php $currentKat = old('kategori_temuan', $finding->kategori_temuan); @endphp
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary);">
                        <input type="radio" name="kategori_temuan" value="Sesuai" {{ $currentKat == 'Sesuai' ? 'checked' : '' }} required> Sesuai
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #059669; border-radius: var(--radius-md); cursor: pointer; background: rgba(5, 150, 105, 0.1); color: #059669; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="Temuan Positif (TP)" {{ $currentKat == 'Temuan Positif (TP)' ? 'checked' : '' }}> Temuan Positif (TP)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #7c3aed; border-radius: var(--radius-md); cursor: pointer; background: rgba(124, 58, 237, 0.1); color: #7c3aed; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="Peluang Peningkatan (OFI)" {{ $currentKat == 'Peluang Peningkatan (OFI)' ? 'checked' : '' }}> Peluang Peningkatan (OFI)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #0284c7; border-radius: var(--radius-md); cursor: pointer; background: rgba(2, 132, 199, 0.1); color: #0284c7; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="Observasi (OB)" {{ $currentKat == 'Observasi (OB)' ? 'checked' : '' }}> Observasi (OB)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #d97706; border-radius: var(--radius-md); cursor: pointer; background: rgba(217, 119, 6, 0.1); color: #d97706; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="KTS Minor" {{ $currentKat == 'KTS Minor' ? 'checked' : '' }}> KTS Minor
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #dc2626; border-radius: var(--radius-md); cursor: pointer; background: rgba(220, 38, 38, 0.1); color: #dc2626; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="KTS Mayor" {{ $currentKat == 'KTS Mayor' ? 'checked' : '' }}> KTS Mayor
                    </label>
                </div>
            </div>

            <!-- Skor -->
            <div style="margin-bottom: 1.25rem; border: 1px solid var(--border-color); padding: 1rem; border-radius: var(--radius-md); background: var(--bg-secondary);">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">Skor Penilaian</label>
                <div style="display: flex; gap: 1rem;">
                    @php $currentSkor = old('skor', $finding->skor); @endphp
                    @foreach([4 => 'Skor 4 (Sangat Baik)', 3 => 'Skor 3 (Baik)', 2 => 'Skor 2 (Cukup)', 1 => 'Skor 1 (Kurang)'] as $val => $lbl)
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary);">
                            <input type="radio" name="skor" value="{{ $val }}" {{ $currentSkor == $val ? 'checked' : '' }}> {{ $lbl }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">Uraian Temuan / Deskripsi Singkat <span style="color: var(--status-danger);">*</span></label>
                <textarea name="uraian" rows="3" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-primary); color: var(--text-primary);">{{ old('uraian', $finding->uraian) }}</textarea>
            </div>

            <!-- Analisis RCA -->
            <div style="background-color: var(--bg-tertiary); padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.95rem; margin-bottom: 1rem; color: var(--text-primary); font-weight: 600;">Deskripsi Analisis Temuan (Form 5 RCA)</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Akibat</label>
                        <textarea name="akibat" rows="2" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ old('akibat', $finding->akibat) }}</textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Akar Penyebab / Masalah</label>
                        <textarea name="akar_penyebab" rows="2" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ old('akar_penyebab', $finding->akar_penyebab) }}</textarea>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Rekomendasi yang Disepakati</label>
                    <textarea name="rekomendasi" rows="2" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ old('rekomendasi', $finding->rekomendasi) }}</textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Rencana Perbaikan</label>
                        <input type="text" name="rencana_tindakan" value="{{ old('rencana_tindakan', $finding->rencana_tindakan) }}" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Penanggung Jawab (PIC)</label>
                        <input type="text" name="pic" value="{{ old('pic', $finding->pic) }}" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Jadwal Perbaikan (Target)</label>
                        <input type="date" name="batas_waktu" value="{{ old('batas_waktu', $finding->batas_waktu ? date('Y-m-d', strtotime($finding->batas_waktu)) : '') }}" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Rencana Pencegahan</label>
                        <input type="text" name="rencana_pencegahan" value="{{ old('rencana_pencegahan', $finding->rencana_pencegahan) }}" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Jadwal Pencegahan</label>
                        <input type="text" name="jadwal_pencegahan" value="{{ old('jadwal_pencegahan', $finding->jadwal_pencegahan) }}" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('ami.index', ['unit_id' => $finding->unit_id, 'tahun' => date('Y', strtotime($finding->tanggal))]) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 0.625rem 1.5rem;">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>
@endsection
