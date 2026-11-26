@extends('layouts.admin')

@section('title', 'Tambah Kategori | Panel Pengelolaan Guru')
@section('page_title', 'Tambah Kategori')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <x-admin.back-link :href="route('admin.categories.index')" label="Daftar Kategori" />
      <h1 class="adm-title">Tambah Kategori Baru</h1>
      <p class="adm-subtitle">Buat kelompok materi pembelajaran visual, video, kuis, dan permainan.</p>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.categories.store') }}" method="POST">
      @csrf
      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="name" class="adm-label">Nama Kategori *</label>
          <input type="text" id="name" name="name" class="adm-input" value="{{ old('name') }}" required placeholder="Contoh: Hewan, Warna, Benda">
          @error('name')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="slug" class="adm-label">Slug URL (Opsional)</label>
          <input type="text" id="slug" name="slug" class="adm-input" value="{{ old('slug') }}" placeholder="Otomatis dari nama kategori">
          @error('slug')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="color_key" class="adm-label">Warna Kartu Tile Siswa *</label>
          <select id="color_key" name="color_key" class="adm-select" required>
            <option value="blue" {{ old('color_key') === 'blue' ? 'selected' : '' }}>Biru Tenang (Blue)</option>
            <option value="green" {{ old('color_key') === 'green' ? 'selected' : '' }}>Hijau Daun (Green)</option>
            <option value="yellow" {{ old('color_key') === 'yellow' ? 'selected' : '' }}>Kuning Hangat (Yellow)</option>
            <option value="lavender" {{ old('color_key') === 'lavender' ? 'selected' : '' }}>Lavender Lembut</option>
            <option value="peach" {{ old('color_key') === 'peach' ? 'selected' : '' }}>Peach Cerah</option>
          </select>
          @error('color_key')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="sort_order" class="adm-label">Urutan Tampil</label>
          <input type="number" id="sort_order" name="sort_order" class="adm-input" value="{{ old('sort_order', 0) }}" min="0">
          @error('sort_order')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-field">
        <label for="description" class="adm-label">Deskripsi Singkat</label>
        <textarea id="description" name="description" class="adm-textarea" rows="3" placeholder="Keterangan singkat materi kategori ini">{{ old('description') }}</textarea>
      </div>

      <div class="adm-check-row">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="adm-checkbox">
        <label for="is_active" class="adm-check-label">Aktifkan kategori ini agar terlihat oleh siswa</label>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.categories.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Kategori</button>
      </div>
    </form>
  </div>
</div>
@endsection
