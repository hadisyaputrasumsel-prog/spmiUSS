@extends('layouts.app')

@section('title', 'Isi Evaluasi Diri (LED)')
@section('page_title', 'Isi LED: ' . $standard->kode)

@section('content')
@php
    $isLocked = (isset($hasSubmitted) && $hasSubmitted) || (auth()->user()->role->kode == 'auditor');
    $disabledAttr = $isLocked ? 'disabled' : '';
    
    // Mapping warna untuk tahapan PPEPP
    $stageColors = [
        'P1' => ['border' => '#8b5cf6', 'bg' => '#f5f3ff', 'text' => '#6d28d9'], // Violet (Penetapan)
        'P2' => ['border' => '#3b82f6', 'bg' => '#eff6ff', 'text' => '#1d4ed8'], // Blue (Pelaksanaan)
        'P3' => ['border' => '#f59e0b', 'bg' => '#fffbeb', 'text' => '#b45309'], // Amber (Evaluasi)
        'P4' => ['border' => '#ef4444', 'bg' => '#fef2f2', 'text' => '#b91c1c'], // Red (Pengendalian)
        'P5' => ['border' => '#10b981', 'bg' => '#f0fdf4', 'text' => '#047857'], // Emerald (Peningkatan)
    ];
@endphp

<div class="mb-5">
    <a href="{{ route('led.index') }}" class="btn btn-outline" style="margin-bottom: 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Daftar
    </a>
    
    <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04); position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; width: 6px; height: 100%; background: var(--brand-primary);"></div>
        <div style="display: flex; gap: 1.25rem; align-items: flex-start;">
            <div style="background: var(--brand-primary-light); color: var(--brand-primary); padding: 1rem; border-radius: var(--radius-md);">
                <i data-feather="check-square" style="width: 28px; height: 28px;"></i>
            </div>
            <div style="flex: 1;">
                <span class="badge" style="background: var(--brand-primary); color: white; margin-bottom: 0.75rem; padding: 4px 10px; font-weight: 700; letter-spacing: 0.5px;">{{ $standard->kode }}</span>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0; margin-bottom: 0.5rem;">{{ $standard->nama }}</h2>
                <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem; line-height: 1.5;">Lengkapi data capaian per tahapan Siklus PPEPP untuk tahun pelaporan berjalan secara cermat dan berdasarkan bukti nyata.</p>
            </div>
        </div>
        
        @if(!empty($standard->indikator) || !empty($standard->penanggung_jawab) || !empty($standard->acuan) || !empty($standard->target))
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
            <div>
                <h4 style="margin: 0 0 0.75rem 0; font-size: 0.75rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Indikator & Target Standar</h4>
                <div style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 1.25rem; border-radius: var(--radius-md);">
                    @if(!empty($standard->indikator))
                        <div style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.5;"><strong style="color:var(--text-primary); display:block; margin-bottom: 0.25rem;">Indikator Utama:</strong> {!! nl2br(e($standard->indikator)) !!}</div>
                    @endif
                    @if(!empty($standard->target))
                        <div style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.5; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;"><strong style="color:var(--text-primary); display:block; margin-bottom: 0.25rem;">Target Pengharapan:</strong> {!! nl2br(e($standard->target)) !!}</div>
                    @endif
                </div>
            </div>
            <div>
                <h4 style="margin: 0 0 0.75rem 0; font-size: 0.75rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Acuan & Tata Kelola</h4>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @if(!empty($standard->penanggung_jawab))
                    <div style="display: flex; align-items:flex-start; gap:0.75rem;">
                        <i data-feather="users" style="color: var(--text-muted); width: 16px; height: 16px; margin-top: 2px;"></i>
                        <div>
                            <strong style="color: var(--text-primary); font-size: 0.85rem; display: block; margin-bottom: 0.25rem;">Penanggung Jawab Baku:</strong>
                            <span style="font-size: 0.85rem; color: #0284c7; background: #e0f2fe; padding: 3px 10px; border-radius: 12px; font-weight: 600;">{{ $standard->penanggung_jawab }}</span>
                        </div>
                    </div>
                    @endif
                    @if(!empty($standard->acuan))
                    <div style="display: flex; align-items:flex-start; gap:0.75rem;">
                        <i data-feather="book" style="color: var(--text-muted); width: 16px; height: 16px; margin-top: 2px;"></i>
                        <div>
                            <strong style="color: var(--text-primary); font-size: 0.85rem; display: block; margin-bottom: 0.25rem;">Dokumen Acuan Terkait:</strong>
                            <span style="font-size: 0.85rem; color: var(--text-secondary);">{{ $standard->acuan }}</span>
                        </div>
                    </div>
                    @endif
                    @if(!empty($standard->template_dokumen))
                    <div style="margin-top: 0.5rem;">
                        <a href="{{ asset('storage/' . $standard->template_dokumen) }}" target="_blank" class="btn" style="background: white; border: 1px solid #10b981; color: #10b981; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; border-radius: var(--radius-md); box-shadow: 0 2px 4px rgba(16,185,129,0.1); width: 100%; justify-content: center; display: flex;">
                            <i data-feather="download-cloud" style="width: 16px; height: 16px; margin-right: 8px;"></i> Unduh Template Resmi
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if($isLocked)
<div class="alert" style="background-color: var(--status-success-bg); color: var(--status-success); border: 1px solid var(--status-success); padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    <i data-feather="lock" style="width: 24px; height: 24px; flex-shrink: 0;"></i>
    <div>
        <strong style="display: block; font-size: 1rem; margin-bottom: 0.25rem;">Mode Akses Terkunci</strong>
        <span style="font-size: 0.9rem;">Formulir Evaluasi Diri ini telah diajukan kepada Auditor atau Anda sedang bertindak sebagai Auditor. Mode saat ini hanyalah <b>Tinjauan (BACA-SAJA)</b> dan tidak ada perubahan yang dapat disimpan.</span>
    </div>
</div>
@endif

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
    @php
        $colors = $stageColors[$kode];
    @endphp
    <div class="card" style="margin-bottom: 1.5rem; overflow: hidden; border: 1px solid var(--border-color); box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-radius: var(--radius-md);">
        <div class="card-header" onclick="toggleStage('{{ $kode }}')" style="background-color: white; border-left: 6px solid {{ $colors['border'] }}; cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; transition: background-color 0.2s;">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <div style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.15rem; border: 1px solid {{ $colors['border'] }}40;">{{ $kode }}</div>
                <div>
                    <h3 style="font-size: 1.25rem; margin: 0; color: var(--text-primary); font-weight: 800; letter-spacing: -0.3px;">Tahap {{ $info['nama'] }} {!! $info['req'] ? '<span title="Wajib diisi" style="color: #ef4444; margin-left: 2px;">*</span>' : '' !!}</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0; margin-top: 0.25rem;">{{ $info['desc'] }}</p>
                </div>
            </div>
            <div>
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center;">
                    <i data-feather="chevron-down" id="icon-{{ $kode }}" style="transition: transform 0.3s; color: var(--text-secondary); width: 18px; height: 18px; transform: {{ $kode === 'P1' ? 'rotate(180deg)' : 'rotate(0deg)' }};"></i>
                </div>
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

    @if(auth()->user()->role->kode != 'auditor' && !(isset($hasSubmitted) && $hasSubmitted))
    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-bottom: 3rem;">
        <button type="submit" name="status" value="draft" class="btn btn-outline">Simpan sebagai Draft</button>
        <button type="submit" name="status" value="lengkap" class="btn btn-primary" style="box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">Simpan & Tandai Lengkap</button>
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

    // Auto-disable form if locked
    @if($isLocked)
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form');
        if (form) {
            const elements = form.querySelectorAll('input:not([name="_token"]):not([name="_method"]), textarea, select, button[type="submit"]');
            elements.forEach(el => {
                el.disabled = true;
                el.style.backgroundColor = '#f1f5f9';
                el.style.cursor = 'not-allowed';
                el.style.color = '#64748b';
            });
        }
    });
    @endif
</script>
@endsection
