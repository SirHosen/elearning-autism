@extends('layouts.admin')

@section('title', 'Tambah Video Edukasi | Panel Pengelolaan Guru')
@section('page_title', 'Tambah Video')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <a href="{{ route('admin.videos.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm" style="margin-bottom: 10px;">&larr; Daftar Video</a>
      <h1 class="adm-title">Tambah Video Edukasi</h1>
      <p class="adm-subtitle">Unggah video MP4 tenang atau sisipkan tautan YouTube dengan mode aman anak.</p>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="adm-field">
        <label for="title" class="adm-label">Judul Video *</label>
        <input type="text" name="title" id="title" class="adm-input" value="{{ old('title') }}" placeholder="Contoh: Belajar Mengenal Suara Hewan" required>
        @error('title')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="category_id" class="adm-label">Kategori</label>
        <select name="category_id" id="category_id" class="adm-select">
          <option value="">-- Pilih Kategori (Opsional) --</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('category_id')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label class="adm-label">Tipe Sumber Video *</label>
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 6px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600;">
            <input type="radio" name="source_type" value="upload" id="source_upload" {{ old('source_type', 'upload') === 'upload' ? 'checked' : '' }} onchange="toggleSourceType()">
            <span>Unggah Berkas MP4</span>
          </label>
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600;">
            <input type="radio" name="source_type" value="youtube" id="source_youtube" {{ old('source_type') === 'youtube' ? 'checked' : '' }} onchange="toggleSourceType()">
            <span>Tautan YouTube</span>
          </label>
        </div>
      </div>

      <div id="section_upload" class="adm-field">
        <label for="video_file" class="adm-label">Berkas Video MP4 (Maks. 50MB) *</label>
        <input type="file" name="video_file" id="video_file" class="adm-input" accept="video/mp4" style="padding: 9px 12px; background: var(--surface-alt);">
        <span class="adm-help">Format harus .mp4, aman dan tanpa kedipan visual cepat.</span>
        @error('video_file')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div id="section_youtube" class="adm-field" style="display: none;">
        <label for="youtube_url" class="adm-label">URL Video YouTube *</label>
        <input type="text" name="youtube_url" id="youtube_url" class="adm-input" value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/...">
        <span class="adm-help">Diputar melalui youtube-nocookie tanpa pemutaran otomatis atau distraksi iklan.</span>
        @error('youtube_url')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="description" class="adm-label">Deskripsi Singkat (Opsional)</label>
        <textarea name="description" id="description" rows="3" class="adm-textarea" placeholder="Panduan ringkas atau penjelasan materi video...">{{ old('description') }}</textarea>
        @error('description')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="sort_order" class="adm-label">Urutan Tampil</label>
          <input type="number" name="sort_order" id="sort_order" class="adm-input" value="{{ old('sort_order', 0) }}" min="0">
        </div>
        <div class="adm-field" style="display: flex; align-items: center; padding-top: 24px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} style="width: 18px; height: 18px;">
            <span>Aktifkan Video</span>
          </label>
        </div>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.videos.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Video</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleSourceType() {
    const isUpload = document.getElementById('source_upload').checked;
    document.getElementById('section_upload').style.display = isUpload ? 'block' : 'none';
    document.getElementById('section_youtube').style.display = isUpload ? 'none' : 'block';
}
toggleSourceType();
</script>
@endsection
