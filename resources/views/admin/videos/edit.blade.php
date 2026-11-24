@extends('layouts.admin')

@section('title', 'Edit Video Edukasi')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="margin-bottom: var(--space-6);">
        <a href="{{ route('admin.videos.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9375rem;">&larr; Kembali ke Daftar Video</a>
        <h1 style="margin: var(--space-2) 0 0 0; font-size: 1.5rem;">Edit Video: {{ $video->title }}</h1>
    </div>

    <div class="card">
        <form action="{{ route('admin.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="title" class="form-label" style="font-weight: 600;">Judul Video *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $video->title) }}" required>
                @error('title')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="category_id" class="form-label" style="font-weight: 600;">Kategori</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">-- Pilih Kategori (Opsional) --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label class="form-label" style="font-weight: 600;">Tipe Sumber Video *</label>
                <div style="display: flex; gap: var(--space-6); margin-top: var(--space-2);">
                    <label style="display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                        <input type="radio" name="source_type" value="upload" id="source_upload" {{ old('source_type', $video->source_type) === 'upload' ? 'checked' : '' }} onchange="toggleSourceType()">
                        <span>Unggah Berkas MP4</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                        <input type="radio" name="source_type" value="youtube" id="source_youtube" {{ old('source_type', $video->source_type) === 'youtube' ? 'checked' : '' }} onchange="toggleSourceType()">
                        <span>Tautan YouTube</span>
                    </label>
                </div>
            </div>

            <div id="section_upload" class="form-group" style="margin-bottom: var(--space-4);">
                <label for="video_file" class="form-label" style="font-weight: 600;">Ganti Berkas Video MP4 (Opsional, Maks. 50MB)</label>
                <input type="file" name="video_file" id="video_file" class="form-control" accept="video/mp4">
                @if($video->source_type === 'upload' && $video->video_path)
                    <small style="color: var(--text-muted); display: block; margin-top: var(--space-1);">Berkas saat ini: <code>{{ basename($video->video_path) }}</code></small>
                @endif
                @error('video_file')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div id="section_youtube" class="form-group" style="margin-bottom: var(--space-4); display: none;">
                <label for="youtube_url" class="form-label" style="font-weight: 600;">URL Video YouTube *</label>
                <input type="text" name="youtube_url" id="youtube_url" class="form-control" value="{{ old('youtube_url', $video->youtube_id ? 'https://www.youtube.com/watch?v='.$video->youtube_id : '') }}" placeholder="https://www.youtube.com/watch?v=...">
                @error('youtube_url')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="description" class="form-label" style="font-weight: 600;">Deskripsi Singkat (Opsional)</label>
                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $video->description) }}</textarea>
                @error('description')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-6);">
                <div class="form-group">
                    <label for="sort_order" class="form-label" style="font-weight: 600;">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $video->sort_order) }}" min="0">
                </div>
                <div class="form-group" style="display: flex; align-items: center; padding-top: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                        <span style="font-weight: 600;">Aktifkan Video</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: var(--space-3);">
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
