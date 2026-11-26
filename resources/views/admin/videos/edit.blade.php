@extends('layouts.admin')

@section('title', 'Edit Video: ' . $video->title . ' | Panel Pengelolaan Guru')
@section('page_title', 'Edit Video')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <x-admin.back-link :href="route('admin.videos.index')" label="Daftar Video" />
      <h1 class="adm-title">Ubah Video: {{ $video->title }}</h1>
      <p class="adm-subtitle">Perbarui rincian, sumber video, atau status tayangnya bagi siswa.</p>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="adm-field">
        <label for="title" class="adm-label">Judul Video *</label>
        <input type="text" name="title" id="title" class="adm-input" value="{{ old('title', $video->title) }}" required>
        @error('title')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="category_id" class="adm-label">Kategori</label>
        <select name="category_id" id="category_id" class="adm-select">
          <option value="">-- Pilih Kategori (Opsional) --</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('category_id')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label class="adm-label">Tipe Sumber Video *</label>
        <div class="adm-radio-grid">
          <label class="adm-radio-card">
            <input type="radio" name="source_type" value="upload" id="source_upload" {{ old('source_type', $video->source_type) === 'upload' ? 'checked' : '' }} onchange="toggleSourceType()">
            <span>Unggah Berkas MP4</span>
          </label>
          <label class="adm-radio-card">
            <input type="radio" name="source_type" value="youtube" id="source_youtube" {{ old('source_type', $video->source_type) === 'youtube' ? 'checked' : '' }} onchange="toggleSourceType()">
            <span>Tautan YouTube</span>
          </label>
        </div>
      </div>

      <div id="section_upload" class="adm-field">
        <label for="video_file" class="adm-label">Ganti Berkas Video MP4 (Opsional, Maks. 50MB)</label>
        <input type="file" name="video_file" id="video_file" class="adm-input adm-file-input" accept="video/mp4">
        @if($video->source_type === 'upload' && $video->video_path)
          <span class="adm-help">Berkas saat ini: <code>{{ basename($video->video_path) }}</code></span>
        @endif
        @error('video_file')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div id="section_youtube" class="adm-field adm-is-hidden">
        <label for="youtube_url" class="adm-label">URL Video YouTube *</label>
        <input type="text" name="youtube_url" id="youtube_url" class="adm-input" value="{{ old('youtube_url', $video->youtube_id ? 'https://www.youtube.com/watch?v='.$video->youtube_id : '') }}" placeholder="https://www.youtube.com/watch?v=...">
        @error('youtube_url')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="description" class="adm-label">Deskripsi Singkat (Opsional)</label>
        <textarea name="description" id="description" rows="3" class="adm-textarea">{{ old('description', $video->description) }}</textarea>
        @error('description')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="sort_order" class="adm-label">Urutan Tampil</label>
          <input type="number" name="sort_order" id="sort_order" class="adm-input" value="{{ old('sort_order', $video->sort_order) }}" min="0">
        </div>
        <div class="adm-field adm-check-slot">
          <label class="adm-check-row">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active ? '1' : '0') == '1' ? 'checked' : '' }} class="adm-checkbox">
            <span class="adm-check-label">Aktifkan Video</span>
          </label>
        </div>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.videos.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Perubahan</button>
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
