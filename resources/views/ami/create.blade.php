@extends('layouts.app')

@section('title', 'Tambah Temuan AMI')
@section('page_title', 'Input Temuan Audit Mutu Internal')

@section('content')
<div class="mb-4">
    <a href="{{ route('ami.index') }}" class="btn btn-outline" style="margin-bottom: 1rem;">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali
    </a>
    <h2 style="font-size: 1.5rem; color: var(--text-primary); margin-bottom: 0.5rem;">Formulir Temuan Audit (AMAI / EMI)</h2>
    <p style="color: var(--text-secondary);">Gunakan form ini untuk mencatat temuan (KTS / OB / Sesuai) terhadap pelaksanaan SPMI pada auditee.</p>
</div>

<form action="{{ route('ami.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
        <div class="card-header" style="background-color: var(--bg-tertiary);">
            <h3 style="font-size: 1.125rem; margin: 0;">Informasi Audit</h3>
        </div>
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tanggal Audit <span style="color: var(--status-danger);">*</span></label>
                    <input type="date" name="tanggal" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unit / Prodi (Auditee) <span style="color: var(--status-danger);">*</span></label>
                    <select name="unit_id" id="unit_id" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }} ({{ $u->jenis }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Jenis Standar <span style="color: var(--status-danger);">*</span></label>
                    <select name="jenis" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        <option value="akademik">Akademik</option>
                        <option value="nonakademik">Non-Akademik</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Standar Terkait <span style="color: var(--status-danger);">*</span></label>
                    <select name="standar_kode" id="standar_kode" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        <option value="">-- Pilih Standar --</option>
                        @foreach($standards as $std)
                            <option value="{{ $std->kode }}" data-rubrik="{{ json_encode($std->rubrik_penilaian) }}" {{ request('standar_kode') == $std->kode ? 'selected' : '' }}>{{ $std->kode }} - {{ substr($std->nama, 0, 40) }}...</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tahap PPEPP <span style="color: var(--status-danger);">*</span></label>
                    <select name="tahap" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                        <option value="Umum">Keseluruhan Siklus (Umum)</option>
                        <option value="P1">P1 - Penetapan</option>
                        <option value="P2">P2 - Pelaksanaan</option>
                        <option value="P3">P3 - Evaluasi</option>
                        <option value="P4">P4 - Pengendalian</option>
                        <option value="P5">P5 - Peningkatan</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

<div style="display: {{ isset($ledEntry) ? 'grid' : 'block' }}; grid-template-columns: {{ isset($ledEntry) ? '1fr 1fr' : '1fr' }}; gap: 1.5rem; align-items: start;">

    @if(isset($ledEntry))
    <div class="card" style="position: sticky; top: 1rem; max-height: calc(100vh - 2rem); overflow-y: auto;">
        <div class="card-header" style="background-color: var(--bg-tertiary); position: sticky; top: 0; z-index: 10;">
            <h3 style="font-size: 1.125rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="file-text" style="width: 18px; height: 18px;"></i>
                Laporan Evaluasi Diri (Auditee)
            </h3>
        </div>
        <div style="padding: 1.5rem;">
            @php
                $tahapan = ['P1' => 'Penetapan', 'P2' => 'Pelaksanaan', 'P3' => 'Evaluasi', 'P4' => 'Pengendalian', 'P5' => 'Peningkatan'];
            @endphp

            @foreach($tahapan as $tCode => $tName)
                @php $sData = $stageData[$tCode] ?? null; @endphp
                <div style="margin-bottom: 1.5rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                    <div style="background-color: var(--bg-secondary); padding: 0.75rem 1rem; font-weight: 600; font-size: 0.875rem; border-bottom: 1px solid var(--border-color);">
                        <span style="background-color: var(--brand-primary); color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px; margin-right: 8px;">{{ $tCode }}</span> 
                        Tahap {{ $tName }}
                    </div>
                    <div style="padding: 1rem;">
                        @if($sData)
                            <table style="width: 100%; font-size: 0.875rem; margin-bottom: 0.75rem;">
                                <tr>
                                    <td style="width: 140px; font-weight: 500; color: var(--text-secondary); vertical-align: top; padding-bottom: 0.5rem;">{{ $tCode === 'P2' ? 'Periode Pelaksanaan' : 'Tanggal' }}</td>
                                    <td style="vertical-align: top; padding-bottom: 0.5rem;">: {{ $sData->formattedTanggal() }}</td>
                                </tr>
                                
                                @if(isset($sData->data_spesifik) && is_array($sData->data_spesifik))
                                    @foreach($sData->data_spesifik as $key => $val)
                                        <tr>
                                            <td style="font-weight: 500; color: var(--text-secondary); vertical-align: top; padding-bottom: 0.5rem; text-transform: capitalize;">{{ str_replace('_', ' ', $key) }}</td>
                                            <td style="vertical-align: top; padding-bottom: 0.5rem;">: {{ $val ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                                @if($sData->penanggung_jawab)
                                <tr>
                                    <td style="font-weight: 500; color: var(--text-secondary); vertical-align: top; padding-bottom: 0.5rem;">Penanggung Jawab</td>
                                    <td style="vertical-align: top; padding-bottom: 0.5rem;">: {{ $sData->penanggung_jawab }}</td>
                                </tr>
                                @endif
                            </table>

                            @if($tCode != 'P1' && $sData->uraian)
                                <div style="margin-bottom: 0.5rem; font-size: 0.875rem;">
                                    <strong style="color: var(--text-secondary); display: block; margin-bottom: 0.25rem;">Uraian / Penjelasan:</strong>
                                    <div style="background: var(--bg-tertiary); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                        {{ $sData->uraian }}
                                    </div>
                                </div>
                            @endif

                            @php
                                $stageBukti = $sData->decodedBukti();
                                $autoDocs = $autoAttachedByStage[$tCode] ?? collect();
                                $selectedDocs = collect($stageBukti['shared_document_ids'])
                                    ->map(fn ($docId) => ($sharedDocumentsMap ?? collect())->get($docId))
                                    ->filter();
                            @endphp

                            @if($autoDocs->count() > 0)
                                <div style="margin-top: 0.75rem; font-size: 0.85rem;">
                                    <strong style="color: #047857; display: block; margin-bottom: 0.25rem;">Dokumen Induk Universitas (LPMA):</strong>
                                    <ul style="margin: 0; padding-left: 1.25rem;">
                                        @foreach($autoDocs as $adoc)
                                            <li>
                                                <a href="{{ route('evidence-documents.download', $adoc) }}" target="_blank" style="color: #047857; text-decoration: none;">
                                                    [{{ $adoc->kategori }}] {{ $adoc->judul }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($selectedDocs->count() > 0)
                                <div style="margin-top: 0.75rem; font-size: 0.85rem;">
                                    <strong style="color: #6d28d9; display: block; margin-bottom: 0.25rem;">Bukti dari Pustaka Dokumen:</strong>
                                    <ul style="margin: 0; padding-left: 1.25rem;">
                                        @foreach($selectedDocs as $sdoc)
                                            <li>
                                                <a href="{{ route('evidence-documents.download', $sdoc) }}" target="_blank" style="color: #6d28d9; text-decoration: none;">
                                                    [{{ $sdoc->scope === 'global' ? 'Global' : 'Unit' }} - {{ $sdoc->kategori }}] {{ $sdoc->judul }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(count($stageBukti['files']) > 0)
                                <div style="margin-top: 0.75rem; font-size: 0.875rem;">
                                    <strong style="color: var(--text-secondary); display: block; margin-bottom: 0.25rem;">Berkas Bukti Khusus Tahap Ini:</strong>
                                    <ul style="margin: 0; padding-left: 1.25rem;">
                                    @foreach($stageBukti['files'] as $filePath)
                                        <li><a href="{{ asset($filePath) }}" target="_blank" style="color: var(--brand-primary); text-decoration: none;">{{ basename($filePath) }}</a></li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(count($stageBukti['links']) > 0)
                                <div style="margin-top: 0.75rem; font-size: 0.875rem;">
                                    <strong style="color: var(--text-secondary); display: block; margin-bottom: 0.25rem;">Tautan Bukti (Klik untuk buka):</strong>
                                    <ul style="margin: 0; padding-left: 1.25rem;">
                                    @foreach($stageBukti['links'] as $link)
                                        <li><a href="{{ $link }}" target="_blank" style="color: var(--brand-primary); text-decoration: none;">{{ parse_url($link, PHP_URL_HOST) ?? 'Buka Tautan' }}</a></li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                        @else
                            <div style="color: var(--text-muted); font-style: italic; text-align: center; padding: 1rem 0; font-size: 0.875rem;">
                                Tahap ini belum diisi oleh Auditee.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div style="flex: 1;">
        <div class="card">
            <div class="card-header" style="background-color: var(--bg-tertiary);">
                <h3 style="font-size: 1.125rem; margin: 0;">Detail Temuan</h3>
            </div>
        <div style="padding: 1.5rem;">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Kategori Temuan <span style="color: var(--status-danger);">*</span></label>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary);">
                        <input type="radio" name="kategori_temuan" value="Sesuai" required> Sesuai
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #059669; border-radius: var(--radius-md); cursor: pointer; background: rgba(5, 150, 105, 0.1); color: #059669; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="Temuan Positif (TP)"> Temuan Positif (TP)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #7c3aed; border-radius: var(--radius-md); cursor: pointer; background: rgba(124, 58, 237, 0.1); color: #7c3aed; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="Peluang Peningkatan (OFI)"> Peluang Peningkatan (OFI)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #0284c7; border-radius: var(--radius-md); cursor: pointer; background: rgba(2, 132, 199, 0.1); color: #0284c7; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="Observasi (OB)"> Observasi (OB)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #d97706; border-radius: var(--radius-md); cursor: pointer; background: rgba(217, 119, 6, 0.1); color: #d97706; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="KTS Minor"> KTS Minor
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.9rem; border: 1px solid #dc2626; border-radius: var(--radius-md); cursor: pointer; background: rgba(220, 38, 38, 0.1); color: #dc2626; font-weight: 500;">
                        <input type="radio" name="kategori_temuan" value="KTS Mayor"> KTS Mayor
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Uraian Temuan / Deskripsi Singkat</label>
                <textarea name="uraian" rows="3" class="form-control" placeholder="Jelaskan apa yang diamati/ditemukan..." style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);"></textarea>
            </div>
            
            <div style="margin-bottom: 1.5rem; border: 1px solid var(--border-color); padding: 1rem; border-radius: var(--radius-md); background: var(--bg-secondary);">
                <label style="display: block; font-size: 1rem; color: var(--text-primary); margin-bottom: 0.75rem; font-weight: 600;">Skor Penilaian Standar Mutu <span style="color: var(--status-danger);">*</span></label>
                
                <div id="rubrik-container" style="display: none; margin-bottom: 1rem; padding: 1rem; background: var(--bg-primary); border-radius: var(--radius-sm); border-left: 4px solid var(--status-info);">
                    <div style="margin-bottom: 0.5rem;"><strong style="color: var(--status-success);">Skor 4:</strong> <span id="text-rubrik-4">-</span></div>
                    <div style="margin-bottom: 0.5rem;"><strong style="color: var(--status-info);">Skor 3:</strong> <span id="text-rubrik-3">-</span></div>
                    <div style="margin-bottom: 0.5rem;"><strong style="color: var(--status-warning);">Skor 2:</strong> <span id="text-rubrik-2">-</span></div>
                    <div><strong style="color: var(--status-danger);">Skor 1:</strong> <span id="text-rubrik-1">-</span></div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary);">
                        <input type="radio" name="skor" value="4" required> Skor 4 (Sangat Baik)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary);">
                        <input type="radio" name="skor" value="3"> Skor 3 (Baik)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary);">
                        <input type="radio" name="skor" value="2"> Skor 2 (Cukup)
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; background: var(--bg-primary);">
                        <input type="radio" name="skor" value="1"> Skor 1 (Kurang)
                    </label>
                </div>
            </div>
            
            <div style="background-color: var(--bg-tertiary); padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.95rem; margin-bottom: 1rem; color: var(--text-primary); font-weight: 600;">Deskripsi Analisis Temuan (Form 5 RCA)</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Akibat</label>
                        <textarea name="akibat" rows="2" class="form-control" placeholder="Dampak/risiko dari temuan ini..." style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);"></textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Akar Penyebab / Masalah</label>
                        <textarea name="akar_penyebab" rows="2" class="form-control" placeholder="Mengapa ketidaksesuaian ini terjadi?" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);"></textarea>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Rekomendasi yang Disepakati</label>
                    <textarea name="rekomendasi" rows="2" class="form-control" placeholder="Tindakan koreksi atau peningkatan yang direkomendasikan..." style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);"></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Rencana Perbaikan</label>
                        <input type="text" name="rencana_tindakan" class="form-control" placeholder="Apa tindakan perbaikan yang akan dilakukan?" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Penanggung Jawab (PIC)</label>
                        <input type="text" name="pic" class="form-control" placeholder="Misal: Kaprodi / Ketua GKM" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Jadwal Perbaikan (Target)</label>
                        <input type="date" name="batas_waktu" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Rencana Pencegahan</label>
                        <input type="text" name="rencana_pencegahan" class="form-control" placeholder="Langkah pencegahan agar tidak berulang..." style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem; font-weight: 500;">Jadwal Pencegahan</label>
                        <input type="text" name="jadwal_pencegahan" class="form-control" placeholder="Misal: Rutin tiap awal semester" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary">Simpan Temuan Audit</button>
            </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const standarSelect = document.querySelector('select[name="standar_kode"]');
        const rubrikContainer = document.getElementById('rubrik-container');
        
        function updateRubrik() {
            if (standarSelect.selectedIndex < 0) return;
            const selectedOption = standarSelect.options[standarSelect.selectedIndex];
            const rubrikData = selectedOption.getAttribute('data-rubrik');
            
            if (rubrikData && rubrikData !== 'null' && rubrikData !== '[]') {
                const rubrik = JSON.parse(rubrikData);
                document.getElementById('text-rubrik-4').textContent = rubrik[4] || 'Tidak ada deskripsi.';
                document.getElementById('text-rubrik-3').textContent = rubrik[3] || 'Tidak ada deskripsi.';
                document.getElementById('text-rubrik-2').textContent = rubrik[2] || 'Tidak ada deskripsi.';
                document.getElementById('text-rubrik-1').textContent = rubrik[1] || 'Tidak ada deskripsi.';
                rubrikContainer.style.display = 'block';
            } else {
                rubrikContainer.style.display = 'none';
            }
        }

        function reloadWithLed() {
            const unitId = document.getElementById('unit_id').value;
            const standarKode = document.getElementById('standar_kode').value;
            
            // Only reload if the values are different from current URL params to avoid infinite loops
            const urlParams = new URLSearchParams(window.location.search);
            const currentUnit = urlParams.get('unit_id');
            const currentStandar = urlParams.get('standar_kode');
            
            if (unitId && standarKode && (unitId !== currentUnit || standarKode !== currentStandar)) {
                window.location.href = "{{ route('ami.create') }}?unit_id=" + unitId + "&standar_kode=" + standarKode;
            } else {
                updateRubrik();
            }
        }

        standarSelect.addEventListener('change', reloadWithLed);
        document.getElementById('unit_id').addEventListener('change', reloadWithLed);
        
        // Trigger on load for pre-selected value
        updateRubrik();
    });
</script>
@endpush
