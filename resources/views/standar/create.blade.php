@extends('layouts.app')

@section('title', 'Tambah Standar Mutu')
@section('page_title', 'Tambah Standar Mutu Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('standar-mutu.index') }}" class="btn btn-outline" style="margin-bottom: 1rem;">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali
    </a>
</div>

<form action="{{ route('standar-mutu.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card" style="max-width: 1200px; margin: 0 auto; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <div class="card-header" style="background-color: var(--bg-tertiary); padding: 1.5rem 2rem; border-bottom: 1px solid var(--border-color);">
            <h3 style="font-size: 1.25rem; margin: 0; font-weight: 600; color: var(--text-primary);">Informasi Standar SPMI</h3>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Lengkapi detail acuan standar mutu baru ini.</p>
        </div>
        <div style="padding: 2rem;">
            
            @if($errors->any())
                <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); font-size: 0.875rem; margin-bottom: 2rem; border: 1px solid rgba(239, 68, 68, 0.2);">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Kode Standar <span style="color: var(--status-danger);">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode') }}" required class="form-control" placeholder="Cth: SM-USS-01" style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Nama Standar <span style="color: var(--status-danger);">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="form-control" placeholder="Cth: Standar Kompetensi Lulusan" style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 1rem; color: var(--text-primary); margin-bottom: 1rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Pernyataan, Indikator, dan Target Standar (Acuan Utama)</label>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Indikator (Apa yang diukur)</label>
                    <textarea name="indikator" class="form-control" rows="3" placeholder="Cth: Rata-rata Indeks Prestasi Kumulatif (IPK) lulusan sarjana..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('indikator') }}</textarea>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Target (Capaian yang harus diraih)</label>
                    <textarea name="target" class="form-control" rows="3" placeholder="Cth: Minimal 3.0" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('target') }}</textarea>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Acuan Utama (Referensi Dokumen)</label>
                    <input type="text" name="acuan" value="{{ old('acuan') }}" class="form-control" placeholder="Cth: SM-USS-03-01-01 (Revisi: 23-04-2025)" style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                
                <div style="margin-bottom: 1.5rem; background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius-md); border: 1px dashed var(--border-color);">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Unggah Template Borang / Dokumen Pendukung (Opsional)</label>
                    <input type="file" name="template_dokumen" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">Dokumen ini nantinya dapat diunduh oleh Prodi/Unit saat mengisi LED sebagai referensi atau format pengisian.</small>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 1rem; color: var(--text-primary); margin-bottom: 1rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Pertanyaan / Kriteria Audit PPEPP <span style="color: var(--text-muted); font-weight: 400; font-size: 0.875rem;">(Opsional)</span></label>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">P1 - Penetapan</label>
                    <textarea name="indikator_p1" class="form-control" rows="4" placeholder="Cth: Apakah Standar telah ditetapkan secara resmi..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('indikator_p1') }}</textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">P2 - Pelaksanaan</label>
                    <textarea name="indikator_p2" class="form-control" rows="4" placeholder="Cth: Apakah pelaksanaan Standar mengacu pada SOP..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('indikator_p2') }}</textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">P3 - Evaluasi</label>
                    <textarea name="indikator_p3" class="form-control" rows="4" placeholder="Cth: Apakah evaluasi capaian Standar dilakukan secara berkala..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('indikator_p3') }}</textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">P4 - Pengendalian</label>
                    <textarea name="indikator_p4" class="form-control" rows="4" placeholder="Cth: Apakah setiap penyimpangan ditindaklanjuti..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('indikator_p4') }}</textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">P5 - Peningkatan</label>
                    <textarea name="indikator_p5" class="form-control" rows="4" placeholder="Cth: Apakah dilakukan peningkatan/revisi Standar..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('indikator_p5') }}</textarea>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 1rem; color: var(--text-primary); margin-bottom: 1rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Matriks Penilaian Standar Mutu (Opsional)</label>
                <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem;">Digunakan sebagai pedoman bagi Auditor dalam memberikan penilaian akhir saat proses AMI.</p>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--status-success); margin-bottom: 0.5rem; font-weight: 600;">Skor 4 - Sangat Baik / Melampaui</label>
                    <textarea name="rubrik_penilaian[4]" class="form-control" rows="3" placeholder="Syarat / Deskripsi untuk Skor 4..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('rubrik_penilaian.4') }}</textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--status-info); margin-bottom: 0.5rem; font-weight: 600;">Skor 3 - Baik / Tercapai</label>
                    <textarea name="rubrik_penilaian[3]" class="form-control" rows="3" placeholder="Syarat / Deskripsi untuk Skor 3..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('rubrik_penilaian.3') }}</textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--status-warning); margin-bottom: 0.5rem; font-weight: 600;">Skor 2 - Cukup / Belum Tercapai</label>
                    <textarea name="rubrik_penilaian[2]" class="form-control" rows="3" placeholder="Syarat / Deskripsi untuk Skor 2..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('rubrik_penilaian.2') }}</textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--status-danger); margin-bottom: 0.5rem; font-weight: 600;">Skor 1 - Kurang / Menyimpang</label>
                    <textarea name="rubrik_penilaian[1]" class="form-control" rows="3" placeholder="Syarat / Deskripsi untuk Skor 1..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: var(--bg-primary); color: var(--text-primary);">{{ old('rubrik_penilaian.1') }}</textarea>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Penanggung Jawab <span style="color: var(--text-muted); font-weight: 400;">(Opsional)</span></label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" class="form-control" placeholder="Cth: Dekan, Ketua Prodi" style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 600;">Kelompok Standar <span style="color: var(--status-danger);">*</span></label>
                    <select name="kelompok" required class="form-control" style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        <option value="" disabled selected>Pilih Kelompok</option>
                        <option value="Standar Pendidikan" {{ old('kelompok') == 'Standar Pendidikan' ? 'selected' : '' }}>Standar Pendidikan</option>
                        <option value="Standar Penelitian" {{ old('kelompok') == 'Standar Penelitian' ? 'selected' : '' }}>Standar Penelitian</option>
                        <option value="Standar PkM" {{ old('kelompok') == 'Standar PkM' ? 'selected' : '' }}>Standar PkM</option>
                        <option value="Tambahan Penelitian 53/2023" {{ old('kelompok') == 'Tambahan Penelitian 53/2023' ? 'selected' : '' }}>Tambahan Penelitian 53/2023</option>
                        <option value="Tambahan PkM 53/2023" {{ old('kelompok') == 'Tambahan PkM 53/2023' ? 'selected' : '' }}>Tambahan PkM 53/2023</option>
                        <option value="Standar Non-Akademik / Lainnya" {{ old('kelompok') == 'Standar Non-Akademik / Lainnya' ? 'selected' : '' }}>Standar Non-Akademik / Lainnya</option>
                    </select>
                </div>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 2rem 0; padding-top: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.75rem; font-weight: 600;">Sifat Standar <span style="color: var(--status-danger);">*</span></label>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary); transition: all 0.2s;">
                                <input type="radio" name="is_akademik" value="1" {{ old('is_akademik') == '1' ? 'checked' : '' }} required>
                                <span><strong style="display:block; font-size: 0.875rem;">Akademik (Dikti)</strong><span style="font-size: 0.75rem; color: var(--text-muted);">Standar pendidikan, penelitian & PkM</span></span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary); transition: all 0.2s;">
                                <input type="radio" name="is_akademik" value="0" {{ old('is_akademik') == '0' ? 'checked' : '' }}>
                                <span><strong style="display:block; font-size: 0.875rem;">Non-Akademik</strong><span style="font-size: 0.75rem; color: var(--text-muted);">Standar operasional, SDM & pendukung</span></span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.75rem; font-weight: 600;">Sasaran Unit <span style="color: var(--text-muted); font-weight: 400;">(Opsional)</span></label>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: -0.5rem; margin-bottom: 1rem;">Centang unit yang wajib mengisi standar ini. Kosongkan jika berlaku universal.</p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            @php
                                $jenisUnits = ['Universitas', 'Fakultas', 'Program Studi', 'Non-Akademik'];
                            @endphp
                            @foreach($jenisUnits as $jenis)
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-primary); cursor: pointer; padding: 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); background: var(--bg-secondary);">
                                <input type="checkbox" name="sasaran_unit[]" value="{{ $jenis }}" {{ (is_array(old('sasaran_unit')) && in_array($jenis, old('sasaran_unit'))) ? 'checked' : '' }}>
                                {{ $jenis }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-weight: 500;">
                    <i data-feather="save" style="width: 16px; height: 16px; margin-right: 0.5rem;"></i> Simpan Standar
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
