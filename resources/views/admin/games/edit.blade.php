@extends('layouts.admin')

@section('title', 'Edit Permainan: ' . $game->title . ' | Panel Pengelolaan Guru')
@section('page_title', 'Edit Permainan')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <a href="{{ route('admin.games.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm" style="margin-bottom: 10px;">&larr; Daftar Permainan</a>
      <h1 class="adm-title">Edit Permainan: {{ $game->title }}</h1>
      <p class="adm-subtitle">Sesuaikan konfigurasi kategori, engine permainan, dan jumlah ronde.</p>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.games.update', $game) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="adm-field">
        <label for="title" class="adm-label">Judul Permainan *</label>
        <input type="text" name="title" id="title" class="adm-input" value="{{ old('title', $game->title) }}" required>
        @error('title')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="category_id" class="adm-label">Kategori Materi *</label>
          <select name="category_id" id="category_id" class="adm-select" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id', $game->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }} ({{ $category->active_materials_count }} materi aktif)
              </option>
            @endforeach
          </select>
          @error('category_id')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="game_type" class="adm-label">Jenis Engine Permainan *</label>
          <select name="game_type" id="game_type" class="adm-select" required>
            <option value="pilih_benar" {{ old('game_type', $game->game_type) === 'pilih_benar' ? 'selected' : '' }}>1. Pilih yang Benar</option>
            <option value="cocokkan" {{ old('game_type', $game->game_type) === 'cocokkan' ? 'selected' : '' }}>2. Cocokkan Gambar &amp; Kata</option>
            <option value="seret_tempatkan" {{ old('game_type', $game->game_type) === 'seret_tempatkan' ? 'selected' : '' }}>3. Seret &amp; Tempatkan</option>
          </select>
          @error('game_type')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="option_count" class="adm-label">Pilihan per Ronde (2 - 4) *</label>
          <input type="number" name="option_count" id="option_count" class="adm-input" value="{{ old('option_count', $game->option_count) }}" min="2" max="4" required>
          @error('option_count')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
        <div class="adm-field">
          <label for="round_count" class="adm-label">Jumlah Ronde (1 - 10) *</label>
          <input type="number" name="round_count" id="round_count" class="adm-input" value="{{ old('round_count', $game->round_count) }}" min="1" max="10" required>
          @error('round_count')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-field">
        <label for="instruction_text" class="adm-label">Teks Instruksi Suara / Layar (Opsional)</label>
        <input type="text" name="instruction_text" id="instruction_text" class="adm-input" value="{{ old('instruction_text', $game->instruction_text) }}">
        @error('instruction_text')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="sort_order" class="adm-label">Urutan Tampil</label>
          <input type="number" name="sort_order" id="sort_order" class="adm-input" value="{{ old('sort_order', $game->sort_order) }}" min="0">
        </div>
        <div class="adm-field" style="display: flex; align-items: center; padding-top: 24px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 700;">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $game->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px;">
            <span>Aktifkan Permainan</span>
          </label>
        </div>
      </div>
      @error('is_active')<span class="adm-error" style="margin-bottom: 12px;">{{ $message }}</span>@enderror

      <div class="adm-form-footer">
        <a href="{{ route('admin.games.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection
