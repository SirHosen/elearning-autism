@extends('layouts.admin')

@section('title', 'Tambah Materi | Panel Pengelolaan Guru')
@section('page_title', 'Tambah Materi')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <x-admin.back-link :href="route('admin.materials.index')" label="Daftar Materi" />
      <h1 class="adm-title">Tambah Materi Pembelajaran</h1>
      <p class="adm-subtitle">Unggah ilustrasi gambar bersih beserta rekaman suara guru (atau gunakan suara otomatis).</p>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="category_id" class="adm-label">Kategori Materi *</label>
          <select id="category_id" name="category_id" class="adm-select" required>
            <option value="">Pilih Kategori...</option>
            @foreach ($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', request('category_id')) == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          @error('category_id')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="label" class="adm-label">Nama Objek / Materi *</label>
          <input type="text" id="label" name="label" class="adm-input" value="{{ old('label') }}" required placeholder="Contoh: Kucing, Merah, Bola">
          @error('label')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-field">
        <label for="short_sentence" class="adm-label">Contoh Kalimat Sederhana (Opsional)</label>
        <input type="text" id="short_sentence" name="short_sentence" class="adm-input" value="{{ old('short_sentence') }}" placeholder="Contoh: Ini kucing berbulu lembut.">
        @error('short_sentence')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="image" class="adm-label">Foto / Ilustrasi Gambar (Maks 4 MB) *</label>
        <div class="adm-file-row">
          <img id="imagePreviewThumb" src="" alt="Pratinjau gambar" class="adm-thumb-preview adm-is-hidden">
          <input type="file" id="image" name="image" required accept="image/jpeg,image/png,image/webp" class="adm-input adm-file-input">
        </div>
        <span class="adm-help">Format yang didukung: JPG, PNG, WebP (otomatis dioptimasi ke WebP 1024px).</span>
        @error('image')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="audio" class="adm-label">Rekaman Suara Guru (Opsional, Maks 3 MB)</label>
        <input type="file" id="audio" name="audio" accept="audio/mpeg,audio/mp3,audio/ogg,audio/wav" class="adm-input adm-file-input">
        <span class="adm-help">Jika dikosongkan, sistem otomatis membacakan nama objek memakai suara bahasa Indonesia/Inggris sesuai kategori.</span>
        @error('audio')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="sort_order" class="adm-label">Urutan Tampil</label>
          <input type="number" id="sort_order" name="sort_order" class="adm-input" value="{{ old('sort_order', 0) }}" min="0">
          @error('sort_order')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field adm-check-slot">
          <div class="adm-check-row">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="adm-checkbox">
            <label for="is_active" class="adm-check-label">Aktifkan materi ini agar dapat dipelajari siswa</label>
          </div>
        </div>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.materials.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Materi</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('image')?.addEventListener('change', function(e) {
    const file = e.target.files?.[0];
    const thumb = document.getElementById('imagePreviewThumb');
    if (file && thumb) {
      thumb.src = URL.createObjectURL(file);
      thumb.style.display = 'block';
    }
  });
</script>
@endpush
