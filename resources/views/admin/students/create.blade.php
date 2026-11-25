@extends('layouts.admin')

@section('title', 'Tambah Siswa | Panel Pengelolaan Guru')
@section('page_title', 'Tambah Siswa')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <a href="{{ route('admin.students.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm" style="margin-bottom: 10px;">&larr; Daftar Siswa</a>
      <h1 class="adm-title">Tambah Akun Siswa</h1>
      <p class="adm-subtitle">Buat akun masuk sederhana berbasis nama pengguna dan PIN angka 4&ndash;6 digit.</p>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.students.store') }}" method="POST">
      @csrf
      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="name" class="adm-label">Nama Lengkap / Panggilan Siswa *</label>
          <input type="text" id="name" name="name" class="adm-input" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
          @error('name')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="username" class="adm-label">Nama Pengguna (Username Login) *</label>
          <input type="text" id="username" name="username" class="adm-input" value="{{ old('username') }}" required placeholder="Contoh: budi">
          <span class="adm-help">Huruf kecil tanpa spasi, unik untuk setiap anak.</span>
          @error('username')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="class_name" class="adm-label">Jenjang / Kelas (Opsional)</label>
          <input type="text" id="class_name" name="class_name" class="adm-input" value="{{ old('class_name') }}" placeholder="Contoh: Kelas 1, SDLB B, SDLB C">
          @error('class_name')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="pin" class="adm-label">PIN Angka (4&ndash;6 Digit, Opsional)</label>
          <input type="text" id="pin" name="pin" class="adm-input" value="{{ old('pin') }}" placeholder="Kosongkan untuk PIN acak otomatis">
          <span class="adm-help">Jika dikosongkan, sistem membuat PIN 4 digit yang siap dicetak.</span>
          @error('pin')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px;">
        <label for="is_active" style="font-weight: 700; font-size: 0.875rem; color: var(--ink);">Akun aktif (dapat langsung masuk ke ruang belajar)</label>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.students.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Buat Akun Siswa</button>
      </div>
    </form>
  </div>
</div>
@endsection
