@extends('layouts.admin')

@section('title', 'Ubah Siswa: ' . $student->name . ' | Panel Pengelolaan Guru')
@section('page_title', 'Ubah Siswa')

@section('content')
<div class="adm-form-shell">
  <div class="adm-page-head">
    <div>
      <x-admin.back-link :href="route('admin.students.index')" label="Daftar Siswa" />
      <h1 class="adm-title">Ubah Data Siswa: {{ $student->name }}</h1>
      <p class="adm-subtitle">Perbarui identitas siswa, kelas belajar, PIN baru, atau status akses akun.</p>
    </div>
    <div class="adm-head-actions">
      <a href="{{ route('admin.reports.index', ['student_id' => $student->id]) }}" class="adm-btn adm-btn--secondary">Lihat Rapor &rarr;</a>
    </div>
  </div>

  <div class="adm-form-card">
    <form action="{{ route('admin.students.update', $student) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="name" class="adm-label">Nama Lengkap / Panggilan Siswa *</label>
          <input type="text" id="name" name="name" class="adm-input" value="{{ old('name', $student->name) }}" required>
          @error('name')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="username" class="adm-label">Nama Pengguna (Username) *</label>
          <input type="text" id="username" name="username" class="adm-input" value="{{ old('username', $student->username) }}" required>
          @error('username')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-form-grid-2">
        <div class="adm-field">
          <label for="class_name" class="adm-label">Jenjang / Kelas</label>
          <input type="text" id="class_name" name="class_name" class="adm-input" value="{{ old('class_name', $student->class_name) }}">
          @error('class_name')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="pin" class="adm-label">Ganti PIN (Opsional)</label>
          <input type="text" id="pin" name="pin" class="adm-input" placeholder="Kosongkan jika tidak ingin mengubah">
          @error('pin')<span class="adm-error">{{ $message }}</span>@enderror
        </div>
      </div>

      <div class="adm-check-row">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $student->is_active ? '1' : '0') == '1' ? 'checked' : '' }} class="adm-checkbox">
        <label for="is_active" class="adm-check-label">Akun aktif (dapat masuk ke sistem)</label>
      </div>

      <div class="adm-form-footer">
        <a href="{{ route('admin.students.index') }}" class="adm-btn adm-btn--secondary">Batal</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
@endsection
