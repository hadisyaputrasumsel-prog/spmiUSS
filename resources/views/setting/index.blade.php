@extends('layouts.app')

@section('title', 'Setting Kampus')
@section('page_title', 'Pengaturan Informasi Kampus')

@section('content')

@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        <i data-feather="check-circle" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        {{ $errors->first() }}
    </div>
@endif

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="background-color: var(--bg-tertiary);">
        <h3 style="font-size: 1.125rem; margin: 0;">Form Pengaturan Kampus</h3>
    </div>
    
    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            
            <!-- Logo Section -->
            <div style="grid-column: 1 / -1; display: flex; align-items: flex-start; gap: 2rem;">
                <div>
                    @if($setting->logo_kampus)
                        <div style="border: 1px solid var(--border-color); padding: 10px; border-radius: var(--radius-md); background: white; text-align: center; width: fit-content;">
                            <img src="{{ asset('storage/' . $setting->logo_kampus) }}" alt="Logo Kampus" style="max-height: 120px; max-width: 200px;">
                        </div>
                    @else
                        <div style="border: 1px dashed var(--border-color); padding: 30px; border-radius: var(--radius-md); background: var(--bg-secondary); text-align: center; color: var(--text-muted); width: 150px;">
                            <i data-feather="image" style="width: 32px; height: 32px; margin-bottom: 10px;"></i>
                            <p style="margin: 0; font-size: 0.875rem;">Belum ada logo</p>
                        </div>
                    @endif
                </div>
                
                <div style="flex-grow: 1;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Logo Kampus</label>
                    <input type="file" name="logo_kampus" accept="image/*" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">Format: JPG, PNG, SVG. Maks 2MB.</small>
                </div>
            </div>

            <!-- Basic Info -->
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Nama Kampus <span style="color: var(--status-danger);">*</span></label>
                <input type="text" name="nama_kampus" value="{{ old('nama_kampus', $setting->nama_kampus) }}" required class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Nama Rektor (Pimpinan)</label>
                <input type="text" name="nama_rektor" value="{{ old('nama_rektor', $setting->nama_rektor) }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Email Kampus</label>
                <input type="email" name="email_kampus" value="{{ old('email_kampus', $setting->email_kampus) }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Telepon</label>
                <input type="text" name="telepon_kampus" value="{{ old('telepon_kampus', $setting->telepon_kampus) }}" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>
            
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Website Utama</label>
                <input type="url" name="website_kampus" value="{{ old('website_kampus', $setting->website_kampus) }}" placeholder="https://..." class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
            </div>

            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Alamat Lengkap</label>
                <textarea name="alamat_kampus" rows="4" class="form-control" style="width: 100%; padding: 0.625rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">{{ old('alamat_kampus', $setting->alamat_kampus) }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i data-feather="save" style="width: 16px; height: 16px; margin-right: 0.5rem; display: inline-block; vertical-align: middle;"></i>
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection
