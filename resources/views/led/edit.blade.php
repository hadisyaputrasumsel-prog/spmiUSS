@extends('layouts.app')

@section('title', 'Isi Evaluasi Diri (LED)')
@section('page_title', 'Isi LED: ' . $standard->kode)

@section('content')
<div class="mb-4">
    <a href="{{ route('led.index') }}" class="btn btn-outline" style="margin-bottom: 1rem;">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali
    </a>
    <h2 style="font-size: 1.5rem; color: var(--text-primary); margin-bottom: 0.5rem;">{{ $standard->nama }}</h2>
    <p style="color: var(--text-secondary); margin-bottom: 1rem;">Silakan lengkapi data capaian siklus PPEPP untuk standar ini pada tahun pelaporan berjalan.</p>
    
    @if(!empty($standard->indikator) || !empty($standard->penanggung_jawab))
    <div style="background-color: var(--status-info-bg); border-left: 4px solid var(--status-info); padding: 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
        <h4 style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: var(--status-info); font-weight: 700; text-transform: uppercase;">Informasi Standar (Acuan)</h4>
        
        @if(!empty($standard->indikator) || !empty($standard->target) || !empty($standard->acuan))
        <div style="margin-bottom: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-md); padding: 1rem; border: 1px solid var(--border-color);">
            <strong style="color: var(--text-primary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">Pernyataan, Indikator, dan Target Standar (Acuan Utama):</strong>
            @if(!empty($standard->indikator))
                <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.5rem;"><strong style="color:var(--text-primary);">Indikator:</strong> {!! nl2br(e($standard->indikator)) !!}</div>
            @endif
            @if(!empty($standard->target))
                <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.5rem;"><strong style="color:var(--text-primary);">Target:</strong> {!! nl2br(e($standard->target)) !!}</div>
            @endif
            @if(!empty($standard->acuan))
                <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.5rem;"><strong style="color:var(--text-primary);">Acuan:</strong> {{ $standard->acuan }}</div>
            @endif
            @if(!empty($standard->template_dokumen))
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                    <strong style="color: var(--text-primary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">Template Borang / Dokumen Resmi:</strong>
                    <a href="{{ asset('storage/' . $standard->template_dokumen) }}" target="_blank" class="btn btn-outline" style="border: 1px solid var(--status-success); color: var(--status-success); padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                        <i data-feather="download" style="width: 16px; height: 16px;"></i> Unduh Template Dokumen
                    </a>
                </div>
            @endif
        </div>
        @endif
        
        @if(!empty($standard->penanggung_jawab))
        <div>
            <strong style="color: var(--text-primary); font-size: 0.875rem;">Penanggung Jawab Baku:</strong>
            <p style="margin: 0; font-size: 0.875rem; color: var(--text-secondary);">{{ $standard->penanggung_jawab }}</p>
        </div>
        @endif
    </div>
    @endif
</div>

<form action="{{ route('led.update', $standard->kode) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    @php
        $stages = [
            'P1' => ['nama' => 'Penetapan', 'desc' => 'Proses penetapan/pengesahan resmi standar', 'req' => true],
            'P2' => ['nama' => 'Pelaksanaan', 'desc' => 'Pelaksanaan kegiatan yang mengacu pada standar', 'req' => true],
            'P3' => ['nama' => 'Evaluasi', 'desc' => 'Metode & hasil evaluasi capaian standar', 'req' => true],
            'P4' => ['nama' => 'Pengendalian', 'desc' => 'Tindakan koreksi jika ada penyimpangan (Opsional)', 'req' => false],
            'P5' => ['nama' => 'Peningkatan', 'desc' => 'Langkah peningkatan/revisi standar', 'req' => true],
        ];
    @endphp

    @foreach($stages as $kode => $info)
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" onclick="toggleStage('{{ $kode }}')" style="background-color: var(--bg-tertiary); cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background-color 0.2s;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="background: var(--brand-gradient); color: white; width: 32px; height: 32px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-weight: bold;">{{ $kode }}</div>
                <div>
                    <h3 style="font-size: 1.125rem; margin: 0;">Tahap {{ $info['nama'] }} {!! $info['req'] ? '<span style="color: var(--status-danger);">*</span>' : '' !!}</h3>
                    <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0;">{{ $info['desc'] }}</p>
                </div>
            </div>
            <div>
                <i data-feather="chevron-down" id="icon-{{ $kode }}" style="transition: transform 0.3s; color: var(--text-secondary); transform: {{ $kode === 'P1' ? 'rotate(180deg)' : 'rotate(0deg)' }};"></i>
            </div>
        </div>
        <div id="content-{{ $kode }}" style="display: {{ $kode === 'P1' ? 'block' : 'none' }};">
            <div style="padding: 1.5rem;">
            @php
                $indikatorField = 'indikator_' . strtolower($kode);
                $indikatorText = $standard->$indikatorField;
            @endphp
            @if(!empty($indikatorText))
            <div style="background-color: var(--status-info-bg); border-left: 3px solid var(--status-info); padding: 0.75rem 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
                <strong style="color: var(--status-info); font-size: 0.875rem; display: block; margin-bottom: 0.25rem;">Target / Indikator (Acuan):</strong>
                <div style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">{!! nl2br(e($indikatorText)) !!}</div>
            </div>
            @endif

            @if($kode == 'P1')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tanggal Ditetapkan {!! $info['req'] ? '<span style="color: var(--status-danger);">*</span>' : '' !!}</label>
                    <input type="date" name="stages[{{ $kode }}][tanggal]" value="{{ isset($stageData[$kode]) ? $stageData[$kode]->tanggal : '' }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">No. SK / Dokumen Penetapan</label>
                    <input type="text" name="stages[{{ $kode }}][data_spesifik][no_sk]" value="{{ isset($stageData[$kode]->data_spesifik['no_sk']) ? $stageData[$kode]->data_spesifik['no_sk'] : '' }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Uraian Penetapan {!! $info['req'] ? '<span style="color: var(--status-danger);">*</span>' : '' !!}</label>
                <textarea name="stages[{{ $kode }}][uraian]" rows="3" class="form-control" placeholder="Jelaskan secara singkat proses atau hasil penetapan standar ini..." style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ isset($stageData[$kode]) ? $stageData[$kode]->uraian : '' }}</textarea>
            </div>
            @elseif($kode == 'P2')
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Periode Pelaksanaan {!! $info['req'] ? '<span style="color: var(--status-danger);">*</span>' : '' !!}</label>
                <input type="text" name="stages[{{ $kode }}][tanggal]" value="{{ isset($stageData[$kode]) ? $stageData[$kode]->tanggal : '' }}" placeholder="Cth: Semester Ganjil 2026/2027" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Uraian Pelaksanaan {!! $info['req'] ? '<span style="color: var(--status-danger);">*</span>' : '' !!}</label>
                <textarea name="stages[{{ $kode }}][uraian]" rows="3" class="form-control" placeholder="Jelaskan secara singkat bagaimana standar ini dilaksanakan di lapangan..." style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ isset($stageData[$kode]) ? $stageData[$kode]->uraian : '' }}</textarea>
            </div>
            @elseif($kode == 'P3')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Metode Evaluasi</label>
                    <input type="text" name="stages[{{ $kode }}][data_spesifik][metode]" value="{{ isset($stageData[$kode]->data_spesifik['metode']) ? $stageData[$kode]->data_spesifik['metode'] : '' }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tanggal Evaluasi</label>
                    <input type="date" name="stages[{{ $kode }}][tanggal]" value="{{ isset($stageData[$kode]) ? $stageData[$kode]->tanggal : '' }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Capaian Aktual {!! $info['req'] ? '<span style="color: var(--status-danger);">*</span>' : '' !!}</label>
                <textarea name="stages[{{ $kode }}][uraian]" rows="3" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ isset($stageData[$kode]) ? $stageData[$kode]->uraian : '' }}</textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Status Capaian</label>
                <select name="stages[{{ $kode }}][data_spesifik][status_capaian]" class="form-select" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <option value="">-- Pilih Status --</option>
                    @php $statusCap = isset($stageData[$kode]->data_spesifik['status_capaian']) ? $stageData[$kode]->data_spesifik['status_capaian'] : ''; @endphp
                    <option value="Melampaui" {{ $statusCap == 'Melampaui' ? 'selected' : '' }}>Melampaui (Tercapai > 100%)</option>
                    <option value="Tercapai" {{ $statusCap == 'Tercapai' ? 'selected' : '' }}>Tercapai (Sesuai Target)</option>
                    <option value="Belum Tercapai" {{ $statusCap == 'Belum Tercapai' ? 'selected' : '' }}>Belum Tercapai / Menyimpang</option>
                </select>
            </div>
            @elseif($kode == 'P4')
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Temuan / Penyimpangan</label>
                <textarea name="stages[{{ $kode }}][uraian]" rows="3" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ isset($stageData[$kode]) ? $stageData[$kode]->uraian : '' }}</textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tindakan Korektif</label>
                    <textarea name="stages[{{ $kode }}][data_spesifik][tindakan_korektif]" rows="3" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ isset($stageData[$kode]->data_spesifik['tindakan_korektif']) ? $stageData[$kode]->data_spesifik['tindakan_korektif'] : '' }}</textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tanggal Tindak Lanjut</label>
                    <input type="date" name="stages[{{ $kode }}][tanggal]" value="{{ isset($stageData[$kode]) ? $stageData[$kode]->tanggal : '' }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>
            @elseif($kode == 'P5')
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Rencana Peningkatan / Revisi Standar {!! $info['req'] ? '<span style="color: var(--status-danger);">*</span>' : '' !!}</label>
                <textarea name="stages[{{ $kode }}][uraian]" rows="3" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ isset($stageData[$kode]) ? $stageData[$kode]->uraian : '' }}</textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Target Periode Berikutnya</label>
                <textarea name="stages[{{ $kode }}][data_spesifik][target_berikutnya]" rows="2" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ isset($stageData[$kode]->data_spesifik['target_berikutnya']) ? $stageData[$kode]->data_spesifik['target_berikutnya'] : '' }}</textarea>
            </div>
            @endif

            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                @php
                    $existingLinks = '';
                    if (isset($stageData[$kode]) && $stageData[$kode]->bukti) {
                        $buktiObj = json_decode($stageData[$kode]->bukti, true);
                        if (isset($buktiObj['links'])) {
                            $existingLinks = implode("\n", $buktiObj['links']);
                        }
                    }
                @endphp
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Tautan Dokumen / Google Drive (Opsional)</label>
                    <textarea name="stages[{{ $kode }}][links]" rows="2" placeholder="Masukkan link (pisahkan dengan koma/baris baru)..." class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ $existingLinks }}</textarea>
                    
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-top: 1rem; margin-bottom: 0.5rem; font-weight: 500;">Penanggung Jawab / Pihak Terlibat</label>
                    <input type="text" name="stages[{{ $kode }}][penanggung_jawab]" value="{{ isset($stageData[$kode]) ? $stageData[$kode]->penanggung_jawab : '' }}" placeholder="Cth: Kaprodi, Dosen..." class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Unggah Dokumen Bukti (Opsional - Bisa pilih banyak file)</label>
                    <input type="file" multiple name="stages[{{ $kode }}][dokumen][]" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png" style="width: 100%; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 0.5rem;">Gunakan Ctrl (Windows) / Cmd (Mac) saat memilih file untuk mengunggah lebih dari satu dokumen sekaligus.</small>
                    
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-top: 1rem; margin-bottom: 0.5rem; font-weight: 500;">Catatan Tambahan (Opsional)</label>
                    <input type="text" name="stages[{{ $kode }}][catatan]" value="{{ isset($stageData[$kode]) ? $stageData[$kode]->catatan : '' }}" placeholder="Catatan penting lainnya..." class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
            </div>
        </div>
        </div> <!-- End collapsible wrapper -->
    </div>
    @endforeach

    @if(auth()->user()->role->kode != 'auditor')
    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-bottom: 3rem;">
        <button type="submit" name="status" value="draft" class="btn btn-outline">Simpan sebagai Draft</button>
        <button type="submit" name="status" value="lengkap" class="btn btn-primary">Simpan & Tandai Lengkap</button>
    </div>
    @endif
</form>
<script>
    function toggleStage(kode) {
        const content = document.getElementById('content-' + kode);
        const icon = document.getElementById('icon-' + kode);
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }
</script>
@endsection
