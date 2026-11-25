@extends('layouts.admin')

@section('title', 'Tambah Materi | Panel Pengelolaan Guru')
@section('page_title', 'Tambah Materi')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <a href="{{ route('admin.materials.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm" style="margin-bottom: 10px;">&larr; Daftar Materi</a>
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
        <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
          <img id="imagePreviewThumb" src="" alt="Pratinjau gambar" style="display: none; width: 72px; height: 72px; object-fit: contain; border-radius: 10px; border: 1.5px solid var(--line); background: var(--surface-alt); padding: 4px;">
          <input type="file" id="image" name="image" required accept="image/jpeg,image/png,image/webp" class="adm-input" style="flex: 1; padding: 9px 12px; background: var(--surface-alt);">
        </div>
        <span class="adm-help">Format yang didukung: JPG, PNG, WebP (otomatis dioptimasi ke WebP 1024px).</span>
        @error('image')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="audio" class="adm-label">Rekaman Suara Guru (Opsional, Maks 3 MB)</label>
        <input type="file" id="audio" name="audio" accept="audio/mpeg,audio/mp3,audio/ogg,audio/wav" class="adm-input" style="padding: 9px 12px; background: var(--surface-alt);">
        <span class="adm-help">Jika dikosongkan, sistem otomatis membacakan nama objek memakai suara bahasa Indonesia.</span>
        @error('audio')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px;">
        <label for="is_active" style="font-weight: 700; font-size: 0.875rem; color: var(--ink);">Aktifkan materi ini agar dapat dipelajari siswa</label>
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
