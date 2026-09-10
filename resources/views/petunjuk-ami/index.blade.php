@extends('layouts.app')

@section('title', 'Petunjuk Pelaksanaan AMI')
@section('page_title', 'Petunjuk Pelaksanaan Audit Mutu Internal (AMI)')

@section('content')
@if(session('success'))
    <div style="background-color: var(--status-success-bg); color: var(--status-success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1.125rem; margin: 0;">Dokumen Petunjuk AMI</h3>
        @if(in_array(auth()->user()->role->kode, ['admin', 'super_admin', 'lpma']))
            <button type="button" onclick="openPetunjukModal()" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="edit-2" style="width: 16px; height: 16px;"></i> Update Petunjuk
            </button>
        @endif
    </div>
    <div class="card-body" style="padding: 2rem;">
        @if(empty($setting->petunjuk_ami))
            <div style="text-align: center; color: var(--text-muted); padding: 3rem 0;">
                <i data-feather="file-text" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 1rem;"></i>
                <p>Dokumen Petunjuk Pelaksanaan AMI belum tersedia.</p>
            </div>
        @else
            <div class="prose" style="max-width: none; color: var(--text-primary); line-height: 1.6;">
                {!! $setting->petunjuk_ami !!}
            </div>
        @endif
    </div>
</div>

@if(in_array(auth()->user()->role->kode, ['admin', 'super_admin', 'lpma']))
<!-- Edit Modal -->
<div id="editPetunjukModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; padding: 1rem; text-align: left;">
    <div class="card" style="width: 100%; max-width: 800px; animation: modalSlideIn 0.3s ease; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="card-header" style="background-color: var(--bg-tertiary); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; margin: 0;">Update Petunjuk Pelaksanaan AMI</h3>
            <button type="button" onclick="document.getElementById('editPetunjukModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-muted);">
                <i data-feather="x"></i>
            </button>
        </div>
        <form action="{{ route('petunjuk-ami.update') }}" method="POST" style="padding: 1.5rem; overflow-y: auto; flex-grow: 1;">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Isi Petunjuk</label>
                <textarea id="petunjuk-editor" name="petunjuk_ami" class="form-control" style="width: 100%; padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary); font-family: inherit;">{{ $setting->petunjuk_ami }}</textarea>
                <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">Gunakan *toolbar* di atas untuk memformat teks (tebal, tabel, perataan, warna, dsb). Hasil akan disimpan sebagai format HTML.</small>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('editPetunjukModal').style.display='none'" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@if(in_array(auth()->user()->role->kode, ['admin', 'super_admin', 'lpma']))
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    let editorInitialized = false;
    function openPetunjukModal() {
        document.getElementById('editPetunjukModal').style.display = 'flex';
        if (!editorInitialized) {
            editorInitialized = true;
            tinymce.init({
                selector: '#petunjuk-editor',
                height: 500,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                    'bold italic underline strikethrough forecolor backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | table link image | ' +
                    'removeformat fullscreen help',
                toolbar_mode: 'sliding',
                promotion: false,
                branding: false,
                skin: (document.documentElement.getAttribute('data-theme') === 'dark' ? 'oxide-dark' : 'oxide'),
                content_css: (document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'default'),
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
        }
    }
</script>
<style>
/* Reset z-index untuk modal TinyMCE agar tampil utuh di atas element lain */
.tox-tinymce {
    border-radius: var(--radius-md) !important;
    border-color: var(--border-color) !important;
}
.tox-tinymce-aux {
    z-index: 1050 !important;
}
/* Menghindari modal card bertabrakan overflow ketika editor membesar */
#editPetunjukModal .card {
    max-height: 95vh;
}
</style>
@endsection
@endif
