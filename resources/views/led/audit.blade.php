@extends('layouts.app')

@section('title', 'Audit Standar: ' . $standard->kode)
@section('page_title', 'Audit Standar: ' . $standard->kode)

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding-bottom: 3rem;">

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i data-feather="check-circle" style="width: 20px; height: 20px; color: #10b981;"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #065f46;">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i data-feather="alert-circle" style="width: 20px; height: 20px; color: #ef4444;"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #991b1b;">&times;</button>
        </div>
    @endif

    <!-- Top Navigation & Progress Bar -->
    <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1rem 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <!-- Back to LED List -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('led.index', ['tahun' => $tahun, 'unit_id' => $unit_id]) }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 6px 12px; font-size: 0.85rem;">
                    <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Daftar LED
                </a>

                <div style="font-size: 0.9rem; color: var(--text-muted);">
                    Unit: <b style="color: var(--text-main);">{{ $unit->nama }}</b> &bull; Tahun: <b style="color: var(--text-main);">{{ $tahun }}</b>
                </div>
            </div>

            <!-- Progress Text & Pagination Buttons -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 0.85rem; color: var(--text-muted); text-align: right;">
                    <div>Standar <b>{{ $position }}</b> dari <b>{{ $totalStandards }}</b></div>
                    <small style="color: #0284c7;"><b>{{ $countAudited }}</b> sudah dinilai &bull; <b>{{ $countDraft }}</b> draft</small>
                </div>

                <div style="display: flex; gap: 0.4rem;">
                    @if($prevStandard)
                        <a href="{{ route('led.audit', ['kode' => $prevStandard->kode, 'tahun' => $tahun, 'unit_id' => $unit_id]) }}" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.8rem;" title="Sebelumnya: {{ $prevStandard->kode }}">
                            &larr; Sebelumnya
                        </a>
                    @else
                        <button class="btn btn-outline" disabled style="padding: 6px 10px; font-size: 0.8rem; opacity: 0.5;">&larr; Sebelumnya</button>
                    @endif

                    @if($nextStandard)
                        <a href="{{ route('led.audit', ['kode' => $nextStandard->kode, 'tahun' => $tahun, 'unit_id' => $unit_id]) }}" class="btn btn-outline" style="padding: 6px 10px; font-size: 0.8rem;" title="Berikutnya: {{ $nextStandard->kode }}">
                            Berikutnya &rarr;
                        </a>
                    @else
                        <button class="btn btn-outline" disabled style="padding: 6px 10px; font-size: 0.8rem; opacity: 0.5;">Berikutnya &rarr;</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Progress Bar Track -->
        @php
            $pctProgress = $totalStandards > 0 ? round(($countAudited / $totalStandards) * 100) : 0;
        @endphp
        <div style="background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden; margin-top: 12px;">
            <div style="width: {{ $pctProgress }}%; height: 100%; background: #0284c7; transition: width 0.3s;"></div>
        </div>
    </div>

    <!-- Alert Status LED / Lock -->
    @if(!$hasSubmitted)
        <div style="background: #fffbeb; border: 1px solid #f59e0b; color: #92400e; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i data-feather="clock" style="width: 22px; height: 22px; color: #f59e0b;"></i>
            <div>
                <b>Perhatian:</b> Laporan Evaluasi Diri (LED) unit ini belum diajukan oleh auditee. Isian LED di bawah berstatus draf awal dan tombol penyimpanan penilaian dinonaktifkan sementara hingga auditee menyelesaikan pengajuan.
            </div>
        </div>
    @endif

    @if($isLocked)
        <div style="background: #f0fdf4; border: 1px solid #22c55e; color: #15803d; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i data-feather="lock" style="width: 22px; height: 22px; color: #22c55e;"></i>
            <div>
                <b>Penilaian Terkunci:</b> Tindak lanjut (RTL) untuk temuan pada standar ini telah diverifikasi <b>Selesai</b>. Seluruh panel penilaian telah dikunci menjadi baca-saja untuk menjaga integritas data audit.
            </div>
        </div>
    @endif

    <!-- Two-Column Layout -->
    <div style="display: grid; grid-template-columns: minmax(0, 1fr) 480px; gap: 1.5rem; align-items: flex-start;" class="audit-grid">

        <!-- ================================================================= -->
        <!-- KOLOM KIRI: LED Auditee (Baca-saja)                               -->
        <!-- ================================================================= -->
        <div>
            <!-- Kartu Identitas Standar -->
            <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 0.75rem;">
                    <div>
                        <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 0.8rem; font-weight: bold; margin-bottom: 0.4rem;">
                            {{ $standard->kode }} &bull; {{ $standard->kelompok }}
                        </span>
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">
                            {{ $standard->nama }}
                        </h2>
                    </div>

                    <a href="{{ route('led.pdf', ['kode' => $standard->kode, 'tahun' => $tahun, 'unit_id' => $unit_id]) }}" target="_blank" class="btn btn-outline" style="font-size: 0.75rem; padding: 4px 10px; border-color: #dc2626; color: #dc2626; display: inline-flex; align-items: center; gap: 0.3rem;">
                        <i data-feather="printer" style="width: 14px; height: 14px;"></i> Unduh PDF LED
                    </a>
                </div>

                <!-- Rubrik & Target Acuan -->
                <div style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1rem;">
                    <b>Pernyataan Standar / Indikator:</b><br>
                    {!! nl2br(e($standard->indikator ?: $standard->nama)) !!}
                </div>

                @if($standard->rubrik_penilaian && is_array($standard->rubrik_penilaian))
                    <div style="background: var(--bg-hover); padding: 1rem; border-radius: 6px; font-size: 0.8rem; border: 1px solid var(--border-color);">
                        <b style="color: var(--text-main); display: block; margin-bottom: 0.4rem;">Panduan Rubrik Penilaian Standar:</b>
                        @foreach([4, 3, 2, 1] as $sk)
                            @if(isset($standard->rubrik_penilaian[$sk]))
                                <div style="margin-bottom: 3px;">
                                    <span style="font-weight: bold; color: #0284c7;">Skor {{ $sk }}:</span> {{ $standard->rubrik_penilaian[$sk] }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Kartu Isian Siklus PPEPP (P1 s.d. P5) -->
            @php
                $stagesInfo = [
                    'P1' => ['title' => 'P1 - Penetapan Standar', 'color' => '#0284c7', 'icon' => 'file-plus'],
                    'P2' => ['title' => 'P2 - Pelaksanaan Standar', 'color' => '#16a34a', 'icon' => 'play-circle'],
                    'P3' => ['title' => 'P3 - Evaluasi Pelaksanaan Standar (Monev / Pengukuran)', 'color' => '#d97706', 'icon' => 'bar-chart-2'],
                    'P4' => ['title' => 'P4 - Pengendalian Standar (RTM / Tindakan Koreksi)', 'color' => '#dc2626', 'icon' => 'shield-alert'],
                    'P5' => ['title' => 'P5 - Peningkatan Standar (Benchmarking / Upgrading)', 'color' => '#7c3aed', 'icon' => 'trending-up'],
                ];
            @endphp

            @foreach($stagesInfo as $stCode => $stMeta)
                @php
                    $stObj = $stageData[$stCode] ?? null;
                    $propIndikator = 'indikator_' . strtolower($stCode);
                    $butirIndikator = $standard->$propIndikator ?: $standard->indikator;
                @endphp
                <div class="card" style="margin-bottom: 1.25rem; background: var(--bg-card); border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden;">
                    <!-- Stage Header -->
                    <div style="background: var(--bg-hover); padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-weight: 700; font-size: 0.95rem; color: {{ $stMeta['color'] }}; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-feather="{{ $stMeta['icon'] }}" style="width: 18px; height: 18px;"></i>
                            {{ $stMeta['title'] }}
                        </div>

                        @if($stObj && !empty($stObj->uraian))
                            <span class="badge" style="background: #dcfce7; color: #15803d; font-size: 0.75rem;">Terisi</span>
                        @else
                            <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">Belum Diisi</span>
                        @endif
                    </div>

                    <!-- Stage Body -->
                    <div style="padding: 1.25rem; font-size: 0.85rem;">
                        <div style="margin-bottom: 0.75rem; color: var(--text-muted);">
                            <b>Indikator Spesifik Tahap:</b><br>
                            {!! nl2br(e($butirIndikator ?? '-')) !!}
                        </div>

                        <div style="background: var(--bg-hover); padding: 1rem; border-radius: 6px; border: 1px dashed var(--border-color); margin-bottom: 0.75rem;">
                            <div style="font-weight: bold; margin-bottom: 0.25rem; color: var(--text-main);">Deskripsi / Uraian Pelaksanaan oleh Auditi:</div>
                            <div style="line-height: 1.6; text-align: justify; color: var(--text-primary);">
                                {!! nl2br(e($stObj?->uraian ?: 'Tidak ada deskripsi isian yang dilaporkan oleh auditi.')) !!}
                            </div>
                        </div>

                        <div style="display: flex; gap: 1.5rem; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                            <div><b>Penanggung Jawab:</b> {{ $stObj?->penanggung_jawab ?: '-' }}</div>
                            <div><b>Tanggal:</b> {{ $stObj?->tanggal ? \Carbon\Carbon::parse($stObj->tanggal)->translatedFormat('d F Y') : '-' }}</div>
                        </div>

                        <!-- Bukti Sahih & Dokumen Pustaka -->
                        @php
                            $decodedBukti = $stObj ? $stObj->decodedBukti() : ['files' => [], 'shared_document_ids' => [], 'links' => []];
                            $stageAutoDocs = $autoAttachedByStage[$stCode] ?? collect();
                        @endphp

                        @if(!empty($decodedBukti['files']) || !empty($decodedBukti['shared_document_ids']) || !empty($decodedBukti['links']) || $stageAutoDocs->isNotEmpty())
                            <div style="border-top: 1px dashed var(--border-color); padding-top: 0.75rem; margin-top: 0.75rem;">
                                <div style="font-weight: bold; font-size: 0.8rem; color: var(--text-main); margin-bottom: 0.4rem;">Bukti Sahih & Tautan Dokumen:</div>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <!-- Unggahan File -->
                                    @foreach($decodedBukti['files'] as $fPath)
                                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                                            <i data-feather="file" style="width: 14px; height: 14px; color: #0284c7;"></i>
                                            <a href="{{ Storage::url($fPath) }}" target="_blank" style="color: #0284c7; text-decoration: underline; word-break: break-all;">
                                                {{ basename($fPath) }}
                                            </a>
                                        </div>
                                    @endforeach

                                    <!-- Dokumen Bank Dokumen -->
                                    @foreach($decodedBukti['shared_document_ids'] as $docId)
                                        @if(isset($sharedDocumentsMap[$docId]))
                                            @php $sDoc = $sharedDocumentsMap[$docId]; @endphp
                                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                                <i data-feather="folder" style="width: 14px; height: 14px; color: #16a34a;"></i>
                                                <a href="{{ route('evidence-documents.download', $sDoc->id) }}" target="_blank" style="color: #16a34a; text-decoration: underline;">
                                                    [Pustaka] {{ $sDoc->judul }}
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach

                                    <!-- Auto Attached Docs -->
                                    @foreach($stageAutoDocs as $aDoc)
                                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                                            <i data-feather="check-circle" style="width: 14px; height: 14px; color: #059669;"></i>
                                            <a href="{{ route('evidence-documents.download', $aDoc->id) }}" target="_blank" style="color: #059669; text-decoration: underline;">
                                                [Wajib] {{ $aDoc->judul }}
                                            </a>
                                        </div>
                                    @endforeach

                                    <!-- Tautan Eksternal -->
                                    @foreach($decodedBukti['links'] as $extLink)
                                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                                            <i data-feather="external-link" style="width: 14px; height: 14px; color: #d97706;"></i>
                                            <a href="{{ $extLink }}" target="_blank" rel="noopener noreferrer" style="color: #d97706; text-decoration: underline; word-break: break-all;">
                                                {{ $extLink }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div style="color: #94a3b8; font-style: italic; font-size: 0.8rem;">Tidak ada berkas bukti yang dilampirkan.</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ================================================================= -->
        <!-- KOLOM KANAN: Panel Penilaian Auditor (Sticky)                     -->
        <!-- ================================================================= -->
        <div style="position: sticky; top: 80px;">
            <div class="card" style="background: var(--bg-card); border-radius: 10px; border: 2px solid #0284c7; box-shadow: 0 4px 15px rgba(2, 132, 199, 0.1); padding: 1.5rem;">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #0284c7; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-feather="check-square" style="width: 20px; height: 20px;"></i>
                        Panel Penilaian Audit
                    </h3>

                    @if($finding)
                        <span class="badge" style="background: {{ $finding->status_audit == 'Sudah Diaudit' ? '#dcfce7' : '#fef3c7' }}; color: {{ $finding->status_audit == 'Sudah Diaudit' ? '#15803d' : '#92400e' }}; font-weight: bold; font-size: 0.75rem;">
                            {{ $finding->status_audit ?: 'Sudah Diaudit' }}
                        </span>
                    @else
                        <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">Belum Dinilai</span>
                    @endif
                </div>

                <form id="form-audit-standar" action="{{ route('led.audit.update', $standard->kode) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <input type="hidden" name="unit_id" value="{{ $unit_id }}">
                    <input type="hidden" name="action_type" id="input-action-type" value="save">

                    <!-- Fieldset jika terkunci atau belum diajukan -->
                    <fieldset {{ ($isLocked || !$hasSubmitted) ? 'disabled' : '' }} style="border: none; padding: 0; margin: 0;">

                        <!-- Tanggal Audit -->
                        <div style="margin-bottom: 1rem;">
                            <label class="form-label" style="font-weight: 600; font-size: 0.8rem; margin-bottom: 0.25rem;">Tanggal Pelaksanaan Audit</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $defaultTanggal) }}" class="form-control" required style="font-size: 0.85rem;">
                        </div>

                        <!-- Penilaian per Tahap (P1 s.d. P5) -->
                        <div style="background: var(--bg-hover); padding: 0.85rem; border-radius: 6px; margin-bottom: 1rem; border: 1px solid var(--border-color);">
                            <label style="font-weight: 700; font-size: 0.8rem; color: var(--text-main); display: block; margin-bottom: 0.5rem;">
                                Nilai Capaian per Tahap (1 - 4):
                            </label>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                @foreach(['P1', 'P2', 'P3', 'P4', 'P5'] as $st)
                                    @php
                                        $currSkor = $tahapScores[$st] ?? ($finding?->skor ?? 3);
                                        $currNote = $tahapNotes[$st] ?? '';
                                    @endphp
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; font-size: 0.8rem;">
                                        <b style="width: 35px;">{{ $st }}:</b>
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            @foreach([1, 2, 3, 4] as $val)
                                                <label style="display: inline-flex; align-items: center; gap: 2px; cursor: pointer; margin: 0; font-weight: normal;">
                                                    <input type="radio" name="skor_{{ strtolower($st) }}" value="{{ $val }}" {{ $currSkor == $val ? 'checked' : '' }} onchange="calculateAutoScore()">
                                                    <span>{{ $val }}</span>
                                                </label>
                                            @endforeach
                                            @if($st === 'P4')
                                                <label style="display: inline-flex; align-items: center; gap: 2px; cursor: pointer; margin: 0; font-size: 0.75rem; color: #64748b;">
                                                    <input type="radio" name="skor_p4" value="" {{ empty($currSkor) ? 'checked' : '' }} onchange="calculateAutoScore()">
                                                    <span>N/A</span>
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Skor Akhir Standar & Alasan Override -->
                        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.85rem; border-radius: 6px; margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <label style="font-weight: 700; font-size: 0.85rem; color: #1e40af; margin: 0; display: block;">
                                        Skor Akhir Standar:
                                    </label>
                                    <small style="font-size: 0.75rem; color: #3b82f6;">Rata-rata terhitung: <b id="auto-score-display">-</b></small>
                                </div>
                                <input type="number" step="1" min="1" max="4" name="skor" id="input-final-score" value="{{ old('skor', $finding?->skor ?? 3) }}" class="form-control" style="width: 80px; text-align: center; font-size: 1.1rem; font-weight: bold; color: #1e40af; border-color: #3b82f6;" required oninput="checkScoreOverride()">
                            </div>

                            <div id="box-alasan-override" style="display: none; margin-top: 0.75rem; border-top: 1px dashed #bfdbfe; padding-top: 0.5rem;">
                                <label style="font-size: 0.75rem; font-weight: 600; color: #991b1b; display: block; margin-bottom: 2px;">
                                    * Wajib isi Alasan jika mengubah skor berbeda dari rata-rata:
                                </label>
                                <textarea name="alasan_ubah_skor" id="input-alasan-override" rows="2" class="form-control" style="font-size: 0.75rem;" placeholder="Alasan pertimbangan auditor...">{{ old('alasan_ubah_skor', $finding?->alasan_ubah_skor) }}</textarea>
                            </div>
                        </div>

                        <!-- Kategori Temuan -->
                        <div style="margin-bottom: 1rem;">
                            <label class="form-label" style="font-weight: 600; font-size: 0.8rem; margin-bottom: 0.4rem;">
                                Kategori Temuan Audit:
                            </label>
                            @php
                                $selectedKat = old('kategori_temuan', $finding?->kategori_temuan ?? 'Sesuai');
                            @endphp
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; font-size: 0.75rem;">
                                <label style="display: flex; align-items: center; gap: 0.3rem; background: #dcfce7; padding: 4px 6px; border-radius: 4px; cursor: pointer; border: 1px solid #86efac;">
                                    <input type="radio" name="kategori_temuan" value="Sesuai" {{ $selectedKat == 'Sesuai' ? 'checked' : '' }} onchange="toggleCategoryFields()">
                                    <span style="color: #166534; font-weight: bold;">Sesuai</span>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.3rem; background: #e0f2fe; padding: 4px 6px; border-radius: 4px; cursor: pointer; border: 1px solid #7dd3fc;">
                                    <input type="radio" name="kategori_temuan" value="Observasi (OB)" {{ $selectedKat == 'Observasi (OB)' ? 'checked' : '' }} onchange="toggleCategoryFields()">
                                    <span style="color: #0369a1; font-weight: bold;">Observasi (OB)</span>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.3rem; background: #fef3c7; padding: 4px 6px; border-radius: 4px; cursor: pointer; border: 1px solid #fde047;">
                                    <input type="radio" name="kategori_temuan" value="KTS Minor" {{ $selectedKat == 'KTS Minor' ? 'checked' : '' }} onchange="toggleCategoryFields()">
                                    <span style="color: #854d0e; font-weight: bold;">KTS Minor</span>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.3rem; background: #fee2e2; padding: 4px 6px; border-radius: 4px; cursor: pointer; border: 1px solid #fca5a5;">
                                    <input type="radio" name="kategori_temuan" value="KTS Mayor" {{ $selectedKat == 'KTS Mayor' ? 'checked' : '' }} onchange="toggleCategoryFields()">
                                    <span style="color: #991b1b; font-weight: bold;">KTS Mayor</span>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.3rem; background: #f3e8ff; padding: 4px 6px; border-radius: 4px; cursor: pointer; border: 1px solid #d8b4fe;">
                                    <input type="radio" name="kategori_temuan" value="Peluang Peningkatan (OFI)" {{ $selectedKat == 'Peluang Peningkatan (OFI)' ? 'checked' : '' }} onchange="toggleCategoryFields()">
                                    <span style="color: #6b21a8; font-weight: bold;">OFI</span>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.3rem; background: #ccfbf1; padding: 4px 6px; border-radius: 4px; cursor: pointer; border: 1px solid #5eead4;">
                                    <input type="radio" name="kategori_temuan" value="Temuan Positif (TP)" {{ $selectedKat == 'Temuan Positif (TP)' ? 'checked' : '' }} onchange="toggleCategoryFields()">
                                    <span style="color: #115e59; font-weight: bold;">Temuan Positif</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tahap Munculnya Temuan -->
                        <div style="margin-bottom: 1rem;">
                            <label class="form-label" style="font-weight: 600; font-size: 0.8rem; margin-bottom: 0.25rem;">Tahap PPEPP Temuan</label>
                            @php $selectedTahap = old('tahap', $finding?->tahap ?? 'Umum'); @endphp
                            <select name="tahap" class="form-control" style="font-size: 0.85rem;" required>
                                <option value="Umum" {{ $selectedTahap == 'Umum' ? 'selected' : '' }}>Umum (Keseluruhan Siklus)</option>
                                <option value="P1" {{ $selectedTahap == 'P1' ? 'selected' : '' }}>P1 - Penetapan</option>
                                <option value="P2" {{ $selectedTahap == 'P2' ? 'selected' : '' }}>P2 - Pelaksanaan</option>
                                <option value="P3" {{ $selectedTahap == 'P3' ? 'selected' : '' }}>P3 - Evaluasi (Monev)</option>
                                <option value="P4" {{ $selectedTahap == 'P4' ? 'selected' : '' }}>P4 - Pengendalian (RTM)</option>
                                <option value="P5" {{ $selectedTahap == 'P5' ? 'selected' : '' }}>P5 - Peningkatan</option>
                            </select>
                        </div>

                        <!-- Uraian Temuan -->
                        <div style="margin-bottom: 1rem;">
                            <label class="form-label" style="font-weight: 600; font-size: 0.8rem; margin-bottom: 0.25rem;">
                                Deskripsi / Uraian Temuan <span id="label-uraian-req" style="color: #dc2626;">*</span>
                            </label>
                            <textarea name="uraian" id="input-uraian" rows="3" class="form-control" style="font-size: 0.85rem;" placeholder="Catatan temuan audit...">{{ old('uraian', $finding?->uraian) }}</textarea>
                        </div>

                        <!-- Detail Temuan Non-Sesuai (RCA, Akibat, RTL) -->
                        <div id="section-detail-kts" style="display: none; border-top: 1px dashed var(--border-color); padding-top: 0.75rem; margin-top: 0.75rem;">
                            <div style="font-weight: 700; font-size: 0.8rem; color: #dc2626; margin-bottom: 0.5rem;">
                                Detail Ketidaksesuaian & Rencana Tindak Lanjut:
                            </div>

                            <div style="margin-bottom: 0.5rem;">
                                <label style="font-size: 0.75rem; font-weight: 600; display: block; margin-bottom: 2px;">Akar Masalah (RCA):</label>
                                <input type="text" name="akar_penyebab" value="{{ old('akar_penyebab', $finding?->akar_penyebab) }}" class="form-control" style="font-size: 0.8rem;" placeholder="Akar penyebab...">
                            </div>

                            <div style="margin-bottom: 0.5rem;">
                                <label style="font-size: 0.75rem; font-weight: 600; display: block; margin-bottom: 2px;">Akibat / Dampak Risiko:</label>
                                <input type="text" name="akibat" value="{{ old('akibat', $finding?->akibat) }}" class="form-control" style="font-size: 0.8rem;" placeholder="Akibat...">
                            </div>

                            <div style="margin-bottom: 0.5rem;">
                                <label style="font-size: 0.75rem; font-weight: 600; display: block; margin-bottom: 2px;">Rekomendasi Auditor:</label>
                                <input type="text" name="rekomendasi" value="{{ old('rekomendasi', $finding?->rekomendasi) }}" class="form-control" style="font-size: 0.8rem;" placeholder="Rekomendasi...">
                            </div>

                            <div style="margin-bottom: 0.5rem;">
                                <label style="font-size: 0.75rem; font-weight: 600; display: block; margin-bottom: 2px;">Rencana Perbaikan (RTL):</label>
                                <textarea name="rencana_tindakan" rows="2" class="form-control" style="font-size: 0.8rem;" placeholder="Tindakan perbaikan...">{{ old('rencana_tindakan', $finding?->rencana_tindakan) }}</textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <div>
                                    <label style="font-size: 0.75rem; font-weight: 600; display: block; margin-bottom: 2px;">PIC:</label>
                                    <input type="text" name="pic" value="{{ old('pic', $finding?->pic) }}" class="form-control" style="font-size: 0.8rem;" placeholder="Kaprodi / GPM">
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; font-weight: 600; display: block; margin-bottom: 2px;">Batas Waktu:</label>
                                    <input type="date" name="batas_waktu" value="{{ old('batas_waktu', $finding?->batas_waktu) }}" class="form-control" style="font-size: 0.8rem;">
                                </div>
                            </div>

                            <div style="margin-bottom: 0.5rem;">
                                <label style="font-size: 0.75rem; font-weight: 600; display: block; margin-bottom: 2px;">Jadwal Pencegahan:</label>
                                <input type="text" name="jadwal_pencegahan" value="{{ old('jadwal_pencegahan', $finding?->jadwal_pencegahan) }}" class="form-control" style="font-size: 0.8rem;" placeholder="Jadwal pencegahan...">
                            </div>
                        </div>

                    </fieldset>

                    <!-- Tombol Aksi Penyimpanan -->
                    @if(!$isLocked && $hasSubmitted)
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1.25rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                            <!-- Simpan & Lanjut (Primary) -->
                            <button type="button" onclick="submitAudit('save_next')" class="btn btn-primary" style="width: 100%; padding: 0.6rem; font-size: 0.9rem; font-weight: 700; background: #0284c7; display: flex; align-items: center; justify-content: center; gap: 0.4rem;">
                                <i data-feather="arrow-right-circle" style="width: 16px; height: 16px;"></i> Simpan & Lanjut
                            </button>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                <!-- Simpan Saja -->
                                <button type="button" onclick="submitAudit('save')" class="btn btn-outline" style="padding: 0.5rem; font-size: 0.8rem; font-weight: 600; border-color: #16a34a; color: #16a34a;">
                                    <i data-feather="save" style="width: 14px; height: 14px; vertical-align: middle;"></i> Simpan
                                </button>

                                <!-- Simpan Draft -->
                                <button type="button" onclick="submitAudit('draft')" class="btn btn-outline" style="padding: 0.5rem; font-size: 0.8rem; font-weight: 600; border-color: #f59e0b; color: #f59e0b;">
                                    <i data-feather="clock" style="width: 14px; height: 14px; vertical-align: middle;"></i> Simpan Draft
                                </button>
                            </div>
                        </div>
                    @endif
                </form>

            </div>
        </div>

    </div>

</div>

<script>
    // Hitung rata-rata skor tahap otomatis
    function calculateAutoScore() {
        const stages = ['p1', 'p2', 'p3', 'p4', 'p5'];
        let sum = 0;
        let count = 0;

        stages.forEach(st => {
            const checked = document.querySelector(`input[name="skor_${st}"]:checked`);
            if (checked && checked.value !== '') {
                const val = parseInt(checked.value);
                if (!isNaN(val)) {
                    sum += val;
                    count++;
                }
            }
        });

        const autoDisplay = document.getElementById('auto-score-display');
        const finalInput = document.getElementById('input-final-score');

        if (count > 0) {
            const avg = sum / count;
            const rounded = Math.round(avg);
            autoDisplay.innerText = avg.toFixed(2) + ' (Dibulatkan: ' + rounded + ')';

            // Set final score input if not manually overridden
            if (!finalInput.dataset.manuallyChanged) {
                finalInput.value = rounded;
            }
        } else {
            autoDisplay.innerText = '-';
        }
        checkScoreOverride();
    }

    // Deteksi jika auditor mengubah skor manual berbeda dari rata-rata
    function checkScoreOverride() {
        const finalInput = document.getElementById('input-final-score');
        const boxOverride = document.getElementById('box-alasan-override');
        const inputAlasan = document.getElementById('input-alasan-override');

        const autoText = document.getElementById('auto-score-display').innerText;
        let rounded = null;
        if (autoText.includes('Dibulatkan: ')) {
            rounded = parseInt(autoText.split('Dibulatkan: ')[1].replace(')', ''));
        }

        const currVal = parseInt(finalInput.value);
        if (rounded !== null && !isNaN(currVal) && currVal !== rounded) {
            boxOverride.style.display = 'block';
            inputAlasan.required = true;
        } else {
            boxOverride.style.display = 'none';
            inputAlasan.required = false;
        }
    }

    document.getElementById('input-final-score').addEventListener('change', function() {
        this.dataset.manuallyChanged = 'true';
        checkScoreOverride();
    });

    // Toggle tampilan field detail temuan sesuai kategori
    function toggleCategoryFields() {
        const checkedKat = document.querySelector('input[name="kategori_temuan"]:checked');
        const detailKts = document.getElementById('section-detail-kts');
        const reqStar = document.getElementById('label-uraian-req');
        const inputUraian = document.getElementById('input-uraian');

        if (!checkedKat) return;

        const val = checkedKat.value;
        if (val === 'Sesuai' || val === 'Temuan Positif (TP)') {
            detailKts.style.display = 'none';
            reqStar.style.display = 'none';
            inputUraian.required = false;
            if (!inputUraian.value.trim()) {
                inputUraian.value = val === 'Sesuai' 
                    ? 'Pelaksanaan standar {{ $standard->nama }} telah memenuhi ketentuan dan terdokumentasi sesuai siklus PPEPP.'
                    : 'Ditemukan praktik baik (best practice) pada standar {{ $standard->nama }} yang patut dipertahankan.';
            }
        } else {
            detailKts.style.display = 'block';
            reqStar.style.display = 'inline';
            inputUraian.required = true;
            if (inputUraian.value.includes('telah memenuhi ketentuan')) {
                inputUraian.value = '';
            }
        }
    }

    function submitAudit(type) {
        document.getElementById('input-action-type').value = type;
        document.getElementById('form-audit-standar').submit();
    }

    // Inisialisasi awal
    document.addEventListener('DOMContentLoaded', function() {
        calculateAutoScore();
        toggleCategoryFields();
    });
</script>

<style>
    @media (max-width: 1024px) {
        .audit-grid {
            grid-template-columns: 1fr !important;
        }
        .audit-grid > div:last-child {
            position: static !important;
        }
    }
</style>
@endsection
