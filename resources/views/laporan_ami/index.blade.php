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
            <a href="{{ route('laporan_ami.preview', ['tahun' => $tahun]) }}" target="_blank" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; border-color: #0284c7; color: #0284c7;">
                <i data-feather="eye" style="width: 16px; height: 16px;"></i> Pratinjau Buku
            </a>

            <!-- Tombol Cetak PDF -->
            <a href="{{ route('laporan_ami.pdf', ['tahun' => $tahun]) }}" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #0284c7;">
                <i data-feather="printer" style="width: 16px; height: 16px;"></i> Cetak PDF (A4)
            </a>

            <!-- Tombol Arsip ke Bank Dokumen -->
            <form action="{{ route('laporan_ami.arsip') }}" method="POST" style="margin: 0;" onsubmit="return confirm('Arsipkan Buku Laporan AMI Tahun {{ $tahun }} ke Bank Dokumen sebagai Dokumen Induk Universitas?')">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; border-color: #16a34a; color: #16a34a;">
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
                <select id="select-tahun" onchange="window.location.href='{{ route('laporan_ami.index') }}?tahun=' + this.value" class="form-control" style="width: 140px; font-weight: bold; font-size: 1rem; border-color: #0284c7; color: #0284c7;">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Dokumen -->
            <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.85rem; color: var(--text-muted);">
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
    <form action="{{ route('laporan_ami.update') }}" method="POST">
        @csrf
        <input type="hidden" name="tahun" value="{{ $tahun }}">

        <!-- Card 1: Identitas & Format Dokumen -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="file-text" style="width: 18px; height: 18px; color: #0284c7;"></i>
                Identitas Dokumen & Cakupan Cetakan
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Nomor Dokumen</label>
                    <input type="text" name="nomor_dokumen" value="{{ old('nomor_dokumen', $laporan->nomor_dokumen) }}" class="form-control" placeholder="Contoh: 01/LPMA-USS/AMI/XII/{{ $tahun }}" required>
                </div>

                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Tanggal Terbit Resmi</label>
                    <input type="date" name="tanggal_terbit" value="{{ old('tanggal_terbit', $laporan->tanggal_terbit ? $laporan->tanggal_terbit->format('Y-m-d') : '') }}" class="form-control" required>
                </div>

                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Versi Dokumen</label>
                    <input type="text" name="versi" value="{{ old('versi', $laporan->versi ?: '1.0') }}" class="form-control" placeholder="1.0">
                </div>
            </div>

            <!-- Pilihan Unit Kerja yang Dicakup -->
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="font-weight: 600; font-size: 0.85rem; color: var(--text-main); margin: 0;">
                        Cakupan Unit Kerja / Program Studi dalam Laporan:
                    </label>
                    <label style="font-size: 0.8rem; color: #0284c7; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                        <input type="checkbox" id="check-all-units" onchange="toggleAllUnits(this)">
                        <b>Pilih Semua Unit</b>
                    </label>
                </div>
                @php
                    $selectedUnits = (array) ($laporan->selected_unit_ids ?: ['all']);
                    $isAllUnits = empty($selectedUnits) || in_array('all', $selectedUnits);
                @endphp
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.5rem; max-height: 180px; overflow-y: auto; background: var(--bg-hover); padding: 0.75rem; border-radius: 6px; border: 1px solid var(--border-color);">
                    @foreach($units as $u)
                        <label style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; cursor: pointer;">
                            <input type="checkbox" name="selected_unit_ids[]" value="{{ $u->id }}" class="unit-checkbox" {{ ($isAllUnits || in_array($u->id, $selectedUnits)) ? 'checked' : '' }}>
                            <span>{{ $u->nama }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Opsi Lampiran -->
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                <label style="font-weight: 600; font-size: 0.85rem; color: var(--text-main); display: block; margin-bottom: 0.5rem;">
                    Lampiran yang Dilibatkan dalam Buku Laporan:
                </label>
                @php
                    $lampOptions = (array) ($laporan->lampiran_options ?: ['form4', 'form5', 'daftar_hadir', 'standar']);
                @endphp
                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.9rem;">
                    <label style="display: inline-flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                        <input type="checkbox" name="lampiran_options[]" value="form4" {{ in_array('form4', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 1:</b> Form 4 Ringkasan Temuan Audit (KKA)</span>
                    </label>
                    <label style="display: inline-flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                        <input type="checkbox" name="lampiran_options[]" value="form5" {{ in_array('form5', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 2:</b> Form 5 Deskripsi Temuan Audit (RTL)</span>
                    </label>
                    <label style="display: inline-flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                        <input type="checkbox" name="lampiran_options[]" value="daftar_hadir" {{ in_array('daftar_hadir', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 3:</b> Daftar Hadir & Berita Acara RTM</span>
                    </label>
                    <label style="display: inline-flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                        <input type="checkbox" name="lampiran_options[]" value="standar" {{ in_array('standar', $lampOptions) ? 'checked' : '' }}>
                        <span><b>Lampiran 4:</b> Daftar Standar Mutu SPMI</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Card 2: Kata Pengantar -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="message-square" style="width: 18px; height: 18px; color: #0284c7;"></i>
                Kata Pengantar
            </h3>
            <textarea name="kata_pengantar" rows="8" class="form-control" style="font-size: 0.9rem; line-height: 1.6; min-height: 180px; resize: vertical;" placeholder="Tuliskan kata pengantar pimpinan LPMA...">{{ old('kata_pengantar', $laporan->kata_pengantar) }}</textarea>
        </div>

        <!-- Card 3: Bab I Pendahuluan (Naratif) -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="layers" style="width: 18px; height: 18px; color: #0284c7;"></i>
                Bab I: Pendahuluan
            </h3>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">1.2 Dasar Hukum (Satu per baris)</label>
                    <textarea name="dasar_hukum" rows="6" class="form-control" style="font-size: 0.85rem; line-height: 1.6; min-height: 140px; resize: vertical;">{{ old('dasar_hukum', $laporan->dasar_hukum) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1rem;">
                    <div>
                        <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">1.3 Tujuan AMI</label>
                        <textarea name="tujuan" rows="6" class="form-control" style="font-size: 0.85rem; line-height: 1.6; min-height: 140px; resize: vertical;">{{ old('tujuan', $laporan->tujuan) }}</textarea>
                    </div>

                    <div>
                        <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">1.4 Ruang Lingkup</label>
                        <textarea name="ruang_lingkup" rows="6" class="form-control" style="font-size: 0.85rem; line-height: 1.6; min-height: 140px; resize: vertical;">{{ old('ruang_lingkup', $laporan->ruang_lingkup) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">1.5 Metode Audit (Desk Evaluation, Visitasi, Verifikasi RTL)</label>
                    <textarea name="metode" rows="6" class="form-control" style="font-size: 0.85rem; line-height: 1.6; min-height: 140px; resize: vertical;">{{ old('metode', $laporan->metode) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 4: Bab II Jadwal Pelaksanaan AMI -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-feather="calendar" style="width: 18px; height: 18px; color: #0284c7;"></i>
                    Bab II: Jadwal Pelaksanaan AMI
                </h3>

                <!-- Tombol Tarik dari Bulan Mutu -->
                <button type="button" onclick="document.getElementById('form-import-jadwal').submit();" class="btn btn-outline" style="font-size: 0.8rem; padding: 4px 10px; border-color: #0284c7; color: #0284c7; display: inline-flex; align-items: center; gap: 0.4rem;">
                    <i data-feather="download-cloud" style="width: 14px; height: 14px;"></i> Tarik Jadwal dari Bulan Mutu
                </button>
            </div>

            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                Tabel tahapan kegiatan audit mutu internal, rentang tanggal pelaksanaan, dan penanggung jawab. Baris dapat ditambah atau disesuaikan.
            </p>

            <table class="table" id="table-jadwal" style="width: 100%; font-size: 0.85rem;">
                <thead>
                    <tr style="background: var(--bg-hover);">
                        <th style="width: 45%;">Nama Kegiatan</th>
                        <th style="width: 30%;">Rentang Tanggal Pelaksanaan</th>
                        <th style="width: 20%;">Penanggung Jawab</th>
                        <th style="width: 5%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="jadwal-tbody">
                    @php
                        $jadwalItems = (array) ($laporan->jadwal_ami ?: []);
                    @endphp
                    @forelse($jadwalItems as $idx => $j)
                        <tr>
                            <td>
                                <input type="text" name="jadwal_kegiatan[]" value="{{ $j['kegiatan'] ?? '' }}" class="form-control" style="font-size: 0.85rem;" required>
                            </td>
                            <td>
                                <input type="text" name="jadwal_tanggal[]" value="{{ $j['tanggal'] ?? '' }}" class="form-control" style="font-size: 0.85rem;" required>
                            </td>
                            <td>
                                <input type="text" name="jadwal_pic[]" value="{{ $j['penanggung_jawab'] ?? 'LPMA' }}" class="form-control" style="font-size: 0.85rem;" required>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();" style="padding: 2px 6px;">&times;</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>
                                <input type="text" name="jadwal_kegiatan[]" value="Desk Evaluation LED" class="form-control" style="font-size: 0.85rem;" required>
                            </td>
                            <td>
                                <input type="text" name="jadwal_tanggal[]" value="{{ $tahun }}-09-15 s.d. {{ $tahun }}-09-30" class="form-control" style="font-size: 0.85rem;" required>
                            </td>
                            <td>
                                <input type="text" name="jadwal_pic[]" value="Tim Auditor" class="form-control" style="font-size: 0.85rem;" required>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();" style="padding: 2px 6px;">&times;</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <button type="button" onclick="addJadwalRow()" class="btn btn-outline" style="margin-top: 0.5rem; font-size: 0.8rem; padding: 4px 10px;">
                + Tambah Baris Jadwal
            </button>
        </div>

        <!-- Card 5: Bab V Kesimpulan & Rekomendasi Pimpinan -->
        <div class="card" style="margin-bottom: 1.5rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="check-circle" style="width: 18px; height: 18px; color: #0284c7;"></i>
                Bab V: Kesimpulan & Rekomendasi Pimpinan
            </h3>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">5.1 Kesimpulan Umum (Di luar kalkulasi statistik otomatis)</label>
                    <textarea name="kesimpulan" rows="6" class="form-control" style="font-size: 0.85rem; line-height: 1.6; min-height: 140px; resize: vertical;">{{ old('kesimpulan', $laporan->kesimpulan) }}</textarea>
                </div>

                <div>
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">5.2 Rekomendasi untuk Rapat Tinjauan Manajemen (RTM) dan Pimpinan</label>
                    <textarea name="rekomendasi_pimpinan" rows="7" class="form-control" style="font-size: 0.85rem; line-height: 1.6; min-height: 160px; resize: vertical;">{{ old('rekomendasi_pimpinan', $laporan->rekomendasi_pimpinan) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Card 6: Lembar Pengesahan & Penandatangan -->
        <div class="card" style="margin-bottom: 2rem; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="edit-3" style="width: 18px; height: 18px; color: #0284c7;"></i>
                Lembar Pengesahan: Pejabat Penandatangan
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
                <!-- Kepala LPMA -->
                <div style="background: var(--bg-hover); padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #0284c7; margin: 0 0 1rem 0;">Pihak Pertama: Kepala LPMA</h4>
                    
                    <div style="margin-bottom: 0.75rem;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Nama Lengkap & Gelar</label>
                        <input type="text" name="penandatangan_lpma_nama" value="{{ old('penandatangan_lpma_nama', $laporan->penandatangan_lpma_nama) }}" class="form-control" placeholder="Dr. H. Ahmad Fauzi, M.Pd" required>
                    </div>

                    <div style="margin-bottom: 0.75rem;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">NIDN / NIP</label>
                        <input type="text" name="penandatangan_lpma_nidn" value="{{ old('penandatangan_lpma_nidn', $laporan->penandatangan_lpma_nidn) }}" class="form-control" placeholder="0214057801">
                    </div>

                    <div>
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Jabatan</label>
                        <input type="text" name="penandatangan_lpma_jabatan" value="{{ old('penandatangan_lpma_jabatan', $laporan->penandatangan_lpma_jabatan ?: 'Kepala LPMA') }}" class="form-control" required>
                    </div>
                </div>

                <!-- Rektor USS -->
                <div style="background: var(--bg-hover); padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #16a34a; margin: 0 0 1rem 0;">Pihak Kedua: Mengetahui / Mengesahkan (Rektor)</h4>
                    
                    <div style="margin-bottom: 0.75rem;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Nama Lengkap & Gelar</label>
                        <input type="text" name="penandatangan_rektor_nama" value="{{ old('penandatangan_rektor_nama', $laporan->penandatangan_rektor_nama) }}" class="form-control" placeholder="Prof. Dr. Ir. H. M. Syahril, M.S." required>
                    </div>

                    <div style="margin-bottom: 0.75rem;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">NIDN / NIP</label>
                        <input type="text" name="penandatangan_rektor_nidn" value="{{ old('penandatangan_rektor_nidn', $laporan->penandatangan_rektor_nidn) }}" class="form-control" placeholder="0015086002">
                    </div>

                    <div>
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 600;">Jabatan</label>
                        <input type="text" name="penandatangan_rektor_jabatan" value="{{ old('penandatangan_rektor_jabatan', $laporan->penandatangan_rektor_jabatan ?: 'Rektor Universitas Sumatera Selatan') }}" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; align-items: center;">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.75rem; font-size: 1rem; font-weight: 600; background: #0284c7;">
                <i data-feather="save" style="width: 16px; height: 16px; vertical-align: middle;"></i> Simpan Isian Naratif
            </button>
        </div>
    </form>

    <!-- Hidden form for Import Jadwal Bulan Mutu -->
    <form id="form-import-jadwal" action="{{ route('laporan_ami.import-jadwal') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="tahun" value="{{ $tahun }}">
    </form>
</div>

<script>
    function toggleAllUnits(source) {
        const checkboxes = document.querySelectorAll('.unit-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
    }

    function addJadwalRow() {
        const tbody = document.getElementById('jadwal-tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <input type="text" name="jadwal_kegiatan[]" class="form-control" style="font-size: 0.85rem;" placeholder="Nama Kegiatan..." required>
            </td>
            <td>
                <input type="text" name="jadwal_tanggal[]" class="form-control" style="font-size: 0.85rem;" placeholder="{{ $tahun }}-10-01 s.d. {{ $tahun }}-10-15" required>
            </td>
            <td>
                <input type="text" name="jadwal_pic[]" class="form-control" style="font-size: 0.85rem;" value="LPMA / Auditor" required>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();" style="padding: 2px 6px;">&times;</button>
            </td>
        `;
        tbody.appendChild(tr);
    }
</script>
@endsection
