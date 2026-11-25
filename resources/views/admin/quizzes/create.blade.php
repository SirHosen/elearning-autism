@extends('layouts.admin')

@section('title', 'Tambah Kuis Bergambar | Panel Pengelolaan Guru')
@section('page_title', 'Tambah Kuis')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm" style="margin-bottom: 10px;">&larr; Daftar Kuis</a>
      <h1 class="adm-title">Tambah Kuis Baru</h1>
      <p class="adm-subtitle">Tentukan judul dan kategori materi untuk penyusunan soal bergambar.</p>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.quizzes.store') }}" method="POST">
      @csrf

      <div class="adm-field">
        <label for="title" class="adm-label">Judul Kuis *</label>
        <input type="text" name="title" id="title" class="adm-input" value="{{ old('title') }}" placeholder="Contoh: Kuis Mengenal Hewan Ceria" required>
        @error('title')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="category_id" class="adm-label">Kategori Materi *</label>
        <select name="category_id" id="category_id" class="adm-select" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}>
              {{ $category->name }} ({{ $category->active_materials_count }} materi aktif)
            </option>
          @endforeach
        </select>
        @error('category_id')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="sort_order" class="adm-label">Urutan Tampil</label>
          <input type="number" name="sort_order" id="sort_order" class="adm-input" value="{{ old('sort_order', 0) }}" min="0">
        </div>
        <div class="adm-field" style="display: flex; align-items: center; padding-top: 24px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} style="width: 18px; height: 18px;">
            <span>Aktifkan Kuis</span>
          </label>
        </div>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Lanjut ke Kelola Soal &rarr;</button>
      </div>
    </form>
  </div>
</div>
@endsection
