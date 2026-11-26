@extends('layouts.admin')

@section('title', 'Edit Kuis: ' . $quiz->title . ' | Panel Pengelolaan Guru')
@section('page_title', 'Edit Kuis')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <x-admin.back-link :href="route('admin.quizzes.index')" label="Daftar Kuis" />
      <h1 class="adm-title">Ubah Kuis: {{ $quiz->title }}</h1>
      <p class="adm-subtitle">Perbarui judul, kategori, atau kelola butir soal pada kuis ini.</p>
    </div>
    <div class="adm-head-actions">
      <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="adm-btn adm-btn--blue">Kelola Soal &rarr;</a>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="adm-field">
        <label for="title" class="adm-label">Judul Kuis *</label>
        <input type="text" name="title" id="title" class="adm-input" value="{{ old('title', $quiz->title) }}" required>
        @error('title')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="category_id" class="adm-label">Kategori Materi *</label>
        <select name="category_id" id="category_id" class="adm-select" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $quiz->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }} ({{ $category->active_materials_count }} materi aktif)
            </option>
          @endforeach
        </select>
        @error('category_id')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="sort_order" class="adm-label">Urutan Tampil</label>
          <input type="number" name="sort_order" id="sort_order" class="adm-input" value="{{ old('sort_order', $quiz->sort_order) }}" min="0">
        </div>
        <div class="adm-field adm-check-slot">
          <label class="adm-check-row">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $quiz->is_active ? '1' : '0') == '1' ? 'checked' : '' }} class="adm-checkbox">
            <span class="adm-check-label">Aktifkan Kuis</span>
          </label>
        </div>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection
