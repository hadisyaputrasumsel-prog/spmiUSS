@extends('layouts.app')

@section('page_title', 'Buku Laporan Audit Mutu Internal (AMI)')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 3rem;">

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i data-feather="check-circle" style="width: 20px; height: 20px; color: #10b981;"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #065f46;">&times;</button>
        </div>
    @endif

    <!-- Header & Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0 0 0.25rem 0;">
                Buku Laporan Audit Mutu Internal (AMI)
            </h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                Penyusunan naratif, pengelolaan susunan buku, pratinjau, dan pencetakan PDF resmi satu buku utuh per tahun untuk Rapat Tinjauan Manajemen (RTM).
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <!-- Tombol Pratinjau -->
            <a href="{{ route('laporan_ami.preview', ['tahun' => $tahun]) }}" target="_blank" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; border-color: #0284c7; color: #0284c7; padding: 0.55rem 1.1rem; font-weight: 600;">
                <i data-feather="eye" style="width: 16px; height: 16px;"></i> Pratinjau Buku
            </a>

            <!-- Tombol Cetak PDF -->
            <a href="{{ route('laporan_ami.pdf', ['tahun' => $tahun]) }}" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #0284c7; border-color: #0284c7; padding: 0.55rem 1.1rem; font-weight: 600;">
                <i data-feather="printer" style="width: 16px; height: 16px;"></i> Cetak PDF (A4)
            </a>

            <!-- Tombol Arsip ke Bank Dokumen -->
            <form action="{{ route('laporan_ami.arsip') }}" method="POST" style="margin: 0;" onsubmit="return confirm('Arsipkan Buku Laporan AMI Tahun {{ $tahun }} ke Bank Dokumen sebagai Dokumen Induk Universitas?')">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; border-color: #16a34a; color: #16a34a; padding: 0.55rem 1.1rem; font-weight: 600;">
                    <i data-feather="archive" style="width: 16px; height: 16px;"></i> Arsipkan di Bank Dokumen
                </button>
            </form>
        </div>
    </div>

    <!-- Filter & Scope Box -->
    <div class="card" style="margin-bottom: 2rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.25rem 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <!-- Tahun Pelaporan -->
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <label for="select-tahun" style="font-weight: 600; font-size: 0.95rem; color: var(--text-main); margin: 0;">
                    Tahun Laporan:
                </label>
                <select id="select-tahun" onchange="window.location.href='{{ route('laporan_ami.index') }}?tahun=' + this.value" class="laporan-select" style="width: 160px; font-weight: bold; font-size: 1rem; border-color: #0284c7; color: #0284c7;">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Dokumen -->
            <div style="display: flex; align-items: center; gap: 1.25rem; font-size: 0.85rem; color: var(--text-muted); flex-wrap: wrap;">
                <span><b>No. Dokumen:</b> {{ $laporan->nomor_dokumen ?: '-' }}</span>
                <span><b>Versi:</b> {{ $laporan->versi ?: '1.0' }}</span>
                <span><b>Tgl Terbit:</b> {{ $laporan->tanggal_terbit ? $laporan->tanggal_terbit->translatedFormat('d F Y') : '-' }}</span>
                @if($laporan->file_path)
                    <span class="badge" style="background: #dcfce7; color: #15803d; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                        <i data-feather="check" style="width: 12px; height: 12px; vertical-align: middle;"></i> Terarsip
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Isian Naratif Laporan AMI -->
    <form id="form-laporan-ami" action="{{ route('laporan_ami.update') }}" method="POST">
        @csrf
        <input type="hidden" name="tahun" value="{{ $tahun }}">

        <!-- Card 1: Identitas & Format Dokumen -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="file-text" style="width: 18px; height: 18px; color: #0284c7;"></i>
                Identitas Dokumen & Cakupan Cetakan
            </h3>

            <div style="display: grid; grid-template-columns: 2fr 1.2fr 0.8fr; gap: 1.25rem; margin-bottom: 1.25rem;" class="grid-doc-identity">
                <div>
                    <label class="laporan-label">Nomor Dokumen Resmi</label>
                    <input type="text" name="nomor_dokumen" value="{{ old('nomor_dokumen', $laporan->nomor_dokumen) }}" class="laporan-input" placeholder="Contoh: 01/LPMA-USS/AMI/XII/{{ $tahun }}" required>
                </div>

                <div>
                    <label class="laporan-label">Tanggal Terbit Resmi</label>
                    <input type="date" name="tanggal_terbit" value="{{ old('tanggal_terbit', $laporan->tanggal_terbit ? $laporan->tanggal_terbit->format('Y-m-d') : '') }}" class="laporan-input" required>
                </div>

                <div>
                    <label class="laporan-label">Versi Dokumen</label>
                    <input type="text" name="versi" value="{{ old('versi', $laporan->versi ?: '1.0') }}" class="laporan-input" placeholder="1.0">
                </div>
            </div>

            <!-- Pilihan Unit Kerja yang Dicakup -->
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                    <label style="font-weight: 600; font-size: 0.85rem; color: var(--text-main); margin: 0;">
                        Cakupan Unit Kerja / Program Studi dalam Laporan:
                    </label>
                    <label style="font-size: 0.82rem; color: #0284c7; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                        <input type="checkbox" id="check-all-units" onchange="toggleAllUnits(this)">
                        <b>Pilih Semua Unit</b>
                    </label>
                </div>
                @php
                    $selectedUnits = (array) ($laporan->selected_unit_ids ?: ['all']);
                    $isAllUnits = empty($selectedUnits) || in_array('all', $selectedUnits);
                @endphp
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 0.6rem; max-height: 200px; overflow-y: auto; background: var(--bg-hover); padding: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    @foreach($units as $u)
                        <label style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; cursor: pointer;">
                            <input type="checkbox" name="selected_unit_ids[]" value="{{ $u->id }}" class="unit-checkbox" {{ ($isAllUnits || in_array($u->id, $selectedUnits)) ? 'checked' : '' }}>
                            <span>{{ $u->nama }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Opsi Lampiran -->
            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                <label style="font-weight: 600; font-size: 0.85rem; color: var(--text-main); display: block; margin-bottom: 0.6rem;">
                    Lampiran yang Dilibatkan dalam Buku Laporan:
                </label>
                @php
                    $lampOptions = (array) ($laporan->lampiran_options ?: ['form4', 'form5', 'daftar_hadir', 'standar']);
                @endphp
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0.75rem;">
                    <label class="lampiran-pill">
                        <input type="checkbox" name="lampiran_options[]" value="form4" {{ in_array('form4', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 1:</b> Form 4 Ringkasan Temuan Audit (KKA)</span>
                    </label>
                    <label class="lampiran-pill">
                        <input type="checkbox" name="lampiran_options[]" value="form5" {{ in_array('form5', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 2:</b> Form 5 Deskripsi Temuan Audit (RTL)</span>
                    </label>
                    <label class="lampiran-pill">
                        <input type="checkbox" name="lampiran_options[]" value="daftar_hadir" {{ in_array('daftar_hadir', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 3:</b> Daftar Hadir & Berita Acara RTM</span>
                    </label>
                    <label class="lampiran-pill">
                        <input type="checkbox" name="lampiran_options[]" value="standar" {{ in_array('standar', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 4:</b> Daftar Standar Mutu SPMI</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Card 2: Kata Pengantar -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-feather="message-square" style="width: 18px; height: 18px; color: #0284c7;"></i>
                    Kata Pengantar
                </h3>
                <span style="font-size: 0.75rem; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; font-weight: 600;">Rich Text / TinyMCE</span>
            </div>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 0.85rem 0;">
                Tuliskan kata sambutan pimpinan LPMA. Mendukung format paragraf, cetak tebal, miring, dan daftar butir.
            </p>
            <textarea name="kata_pengantar" rows="8" class="tinymce-editor laporan-textarea" placeholder="Tuliskan kata pengantar pimpinan LPMA...">{{ old('kata_pengantar', $laporan->kata_pengantar) }}</textarea>
        </div>

        <!-- Card 3: Bab I Pendahuluan (Naratif) -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-feather="layers" style="width: 18px; height: 18px; color: #0284c7;"></i>
                    Bab I: Pendahuluan
                </h3>
                <span style="font-size: 0.75rem; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; font-weight: 600;">Rich Text / TinyMCE</span>
            </div>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 1.25rem 0;">
                Kelola narasi pendahuluan buku AMI, meliputi dasar hukum regulasi, tujuan kegiatan, ruang lingkup audit, dan metodologi.
            </p>

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div>
                    <label class="laporan-label">1.2 Dasar Hukum Penyelenggaraan</label>
                    <textarea name="dasar_hukum" rows="6" class="tinymce-editor laporan-textarea">{{ old('dasar_hukum', $laporan->dasar_hukum) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;" class="grid-two-cols">
                    <div>
                        <label class="laporan-label">1.3 Tujuan AMI</label>
                        <textarea name="tujuan" rows="6" class="tinymce-editor laporan-textarea">{{ old('tujuan', $laporan->tujuan) }}</textarea>
                    </div>

                    <div>
                        <label class="laporan-label">1.4 Ruang Lingkup Audit</label>
                        <textarea name="ruang_lingkup" rows="6" class="tinymce-editor laporan-textarea">{{ old('ruang_lingkup', $laporan->ruang_lingkup) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="laporan-label">1.5 Metode dan Mekanisme Audit (Desk Evaluation, Visitasi, Verifikasi RTL)</label>
                    <textarea name="metode" rows="6" class="tinymce-editor laporan-textarea">{{ old('metode', $laporan->metode) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 4: Bab II Jadwal Pelaksanaan AMI -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-feather="calendar" style="width: 18px; height: 18px; color: #0284c7;"></i>
                    Bab II: Jadwal Pelaksanaan AMI
                </h3>

                <!-- Tombol Tarik dari Bulan Mutu -->
                <button type="button" onclick="document.getElementById('form-import-jadwal').submit();" class="btn btn-outline" style="font-size: 0.8rem; padding: 5px 12px; border-color: #0284c7; color: #0284c7; display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 600;">
                    <i data-feather="download-cloud" style="width: 14px; height: 14px;"></i> Tarik Jadwal dari Bulan Mutu
                </button>
            </div>

            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
                Tabel tahapan kegiatan audit mutu internal, rentang tanggal pelaksanaan, dan penanggung jawab. Baris dapat ditambah atau dihapus.
            </p>

            <div style="overflow-x: auto;">
                <table class="table" id="table-jadwal" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-hover); text-align: left;">
                            <th style="width: 44%; padding: 8px 10px; font-size: 0.85rem;">Nama Kegiatan</th>
                            <th style="width: 28%; padding: 8px 10px; font-size: 0.85rem;">Rentang Tanggal Pelaksanaan</th>
                            <th style="width: 22%; padding: 8px 10px; font-size: 0.85rem;">Penanggung Jawab</th>
                            <th style="width: 6%; text-align: center; padding: 8px 10px; font-size: 0.85rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jadwal-tbody">
                        @php
                            $jadwalItems = (array) ($laporan->jadwal_ami ?: []);
                        @endphp
                        @forelse($jadwalItems as $idx => $j)
                            <tr>
                                <td style="padding: 6px;">
                                    <input type="text" name="jadwal_kegiatan[]" value="{{ $j['kegiatan'] ?? '' }}" class="laporan-input" required>
                                </td>
                                <td style="padding: 6px;">
                                    <input type="text" name="jadwal_tanggal[]" value="{{ $j['tanggal'] ?? '' }}" class="laporan-input" required>
                                </td>
                                <td style="padding: 6px;">
                                    <input type="text" name="jadwal_pic[]" value="{{ $j['penanggung_jawab'] ?? 'LPMA' }}" class="laporan-input" required>
                                </td>
                                <td style="text-align: center; padding: 6px;">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();" style="padding: 4px 8px; font-weight: bold; border-radius: 6px;">&times;</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td style="padding: 6px;">
                                    <input type="text" name="jadwal_kegiatan[]" value="Desk Evaluation LED" class="laporan-input" required>
                                </td>
                                <td style="padding: 6px;">
                                    <input type="text" name="jadwal_tanggal[]" value="{{ $tahun }}-09-15 s.d. {{ $tahun }}-09-30" class="laporan-input" required>
                                </td>
                                <td style="padding: 6px;">
                                    <input type="text" name="jadwal_pic[]" value="Tim Auditor" class="laporan-input" required>
                                </td>
                                <td style="text-align: center; padding: 6px;">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();" style="padding: 4px 8px; font-weight: bold; border-radius: 6px;">&times;</button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <button type="button" onclick="addJadwalRow()" class="btn btn-outline" style="margin-top: 0.75rem; font-size: 0.85rem; padding: 6px 14px; font-weight: 600; border-color: #cbd5e1; color: var(--text-main);">
                + Tambah Baris Jadwal
            </button>
        </div>

        <!-- Card 5: Bab V Kesimpulan & Rekomendasi Pimpinan -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-feather="check-circle" style="width: 18px; height: 18px; color: #0284c7;"></i>
                    Bab V: Kesimpulan & Rekomendasi Pimpinan
                </h3>
                <span style="font-size: 0.75rem; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; font-weight: 600;">Rich Text / TinyMCE</span>
            </div>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 1.25rem 0;">
                Kesimpulan akhir pelaksanaan audit dan butir-butir rekomendasi strategis yang diajukan ke Rapat Tinjauan Manajemen (RTM).
            </p>

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div>
                    <label class="laporan-label">5.1 Kesimpulan Umum (Di luar kalkulasi statistik otomatis)</label>
                    <textarea name="kesimpulan" rows="6" class="tinymce-editor laporan-textarea">{{ old('kesimpulan', $laporan->kesimpulan) }}</textarea>
                </div>

                <div>
                    <label class="laporan-label">5.2 Rekomendasi untuk Rapat Tinjauan Manajemen (RTM) dan Pimpinan Universitas</label>
                    <textarea name="rekomendasi_pimpinan" rows="7" class="tinymce-editor laporan-textarea">{{ old('rekomendasi_pimpinan', $laporan->rekomendasi_pimpinan) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 6: Lembar Pengesahan & Penandatangan -->
        <div class="card" style="margin-bottom: 2rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="edit-3" style="width: 18px; height: 18px; color: #0284c7;"></i>
                Lembar Pengesahan: Pejabat Penandatangan
            </h3>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 1.25rem 0;">
                Nama pejabat penandatangan yang tercantum pada Lembar Pengesahan resmi buku laporan.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="grid-two-cols">
                <!-- Kepala LPMA -->
                <div style="background: var(--bg-hover); padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #0284c7; margin: 0 0 1rem 0;">Pihak Pertama: Kepala LPMA</h4>
                    
                    <div style="margin-bottom: 0.85rem;">
                        <label class="laporan-label">Nama Lengkap & Gelar</label>
                        <input type="text" name="penandatangan_lpma_nama" value="{{ old('penandatangan_lpma_nama', $laporan->penandatangan_lpma_nama) }}" class="laporan-input" placeholder="Dr. H. Ahmad Fauzi, M.Pd" required>
                    </div>

                    <div style="margin-bottom: 0.85rem;">
                        <label class="laporan-label">NIDN / NIP</label>
                        <input type="text" name="penandatangan_lpma_nidn" value="{{ old('penandatangan_lpma_nidn', $laporan->penandatangan_lpma_nidn) }}" class="laporan-input" placeholder="0214057801">
                    </div>

                    <div>
                        <label class="laporan-label">Jabatan</label>
                        <input type="text" name="penandatangan_lpma_jabatan" value="{{ old('penandatangan_lpma_jabatan', $laporan->penandatangan_lpma_jabatan ?: 'Kepala LPMA') }}" class="laporan-input" required>
                    </div>
                </div>

                <!-- Rektor USS -->
                <div style="background: var(--bg-hover); padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #16a34a; margin: 0 0 1rem 0;">Pihak Kedua: Mengetahui / Mengesahkan (Rektor)</h4>
                    
                    <div style="margin-bottom: 0.85rem;">
                        <label class="laporan-label">Nama Lengkap & Gelar</label>
                        <input type="text" name="penandatangan_rektor_nama" value="{{ old('penandatangan_rektor_nama', $laporan->penandatangan_rektor_nama) }}" class="laporan-input" placeholder="Prof. Dr. Ir. H. M. Syahril, M.S." required>
                    </div>

                    <div style="margin-bottom: 0.85rem;">
                        <label class="laporan-label">NIDN / NIP</label>
                        <input type="text" name="penandatangan_rektor_nidn" value="{{ old('penandatangan_rektor_nidn', $laporan->penandatangan_rektor_nidn) }}" class="laporan-input" placeholder="0015086002">
                    </div>

                    <div>
                        <label class="laporan-label">Jabatan</label>
                        <input type="text" name="penandatangan_rektor_jabatan" value="{{ old('penandatangan_rektor_jabatan', $laporan->penandatangan_rektor_jabatan ?: 'Rektor Universitas Sumatera Selatan') }}" class="laporan-input" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; align-items: center;">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem; font-weight: 700; background: #0284c7; border-color: #0284c7; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);">
                <i data-feather="save" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 6px;"></i> Simpan Isian Naratif
            </button>
        </div>
    </form>

    <!-- Hidden form for Import Jadwal Bulan Mutu -->
    <form id="form-import-jadwal" action="{{ route('laporan_ami.import-jadwal') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="tahun" value="{{ $tahun }}">
    </form>
</div>

<style>
    /* Styling Komprehensif Form Laporan AMI */
    .laporan-label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-main, #1e293b);
        margin-bottom: 0.4rem;
    }
    .laporan-input,
    .laporan-select,
    .laporan-textarea {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        display: block !important;
        padding: 0.65rem 0.85rem !important;
        font-family: inherit !important;
        font-size: 0.9rem !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        line-height: 1.5 !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    }
    .laporan-input:focus,
    .laporan-select:focus,
    .laporan-textarea:focus {
        border-color: #0284c7 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
        background-color: #ffffff !important;
    }
    .lampiran-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--bg-hover, #f8fafc);
        border: 1px solid var(--border-color, #e2e8f0);
        padding: 0.65rem 0.85rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    .lampiran-pill:hover {
        border-color: #0284c7;
        background: #f0f9ff;
    }
    /* TinyMCE Editor styling */
    .tox-tinymce {
        border-radius: 8px !important;
        border: 1.5px solid #cbd5e1 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    @media (max-width: 768px) {
        .grid-doc-identity,
        .grid-two-cols {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<!-- Load TinyMCE 6.8.3 dari CDN Resmi -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.tinymce-editor',
                height: 250,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                    'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'table', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic underline | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | removeformat code fullscreen',
                toolbar_mode: 'sliding',
                branding: false,
                promotion: false,
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #1e293b; padding: 10px; }',
                setup: function (editor) {
                    editor.on('change keyup', function () {
                        editor.save();
                    });
                }
            });
        }
    });

    // Pastikan konten TinyMCE tersinkronisasi saat tombol simpan diklik
    const reportForm = document.getElementById('form-laporan-ami');
    if (reportForm) {
        reportForm.addEventListener('submit', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    }

    function toggleAllUnits(source) {
        const checkboxes = document.querySelectorAll('.unit-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
    }

    function addJadwalRow() {
        const tbody = document.getElementById('jadwal-tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="padding: 6px;">
                <input type="text" name="jadwal_kegiatan[]" class="laporan-input" placeholder="Nama Kegiatan..." required>
            </td>
            <td style="padding: 6px;">
                <input type="text" name="jadwal_tanggal[]" class="laporan-input" placeholder="{{ $tahun }}-10-01 s.d. {{ $tahun }}-10-15" required>
            </td>
            <td style="padding: 6px;">
                <input type="text" name="jadwal_pic[]" class="laporan-input" value="LPMA / Auditor" required>
            </td>
            <td style="text-align: center; padding: 6px;">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();" style="padding: 4px 8px; font-weight: bold; border-radius: 6px;">&times;</button>
            </td>
        `;
        tbody.appendChild(tr);
    }
</script>
@endsection
