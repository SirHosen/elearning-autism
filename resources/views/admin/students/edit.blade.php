@extends('layouts.admin')

@section('title', 'Ubah Siswa | Panel Guru')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 32px;">
  <h2 style="font-size: 22px; margin-bottom: 6px;">Ubah Data Siswa: {{ $student->name }}</h2>
  <p style="color: var(--ink-soft); font-size: 14px; margin-bottom: 24px;">Perbarui data identitas atau ubah status keaktifan.</p>

  <form action="{{ route('admin.students.update', $student) }}" method="POST">
    @csrf
    @method('PUT')
    <div style="margin-bottom: 18px;">
      <label for="name" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Nama Lengkap / Panggilan Siswa</label>
      <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('name')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="username" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Nama Pengguna (Username)</label>
      <input type="text" id="username" name="username" value="{{ old('username', $student->username) }}" required
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('username')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="class_name" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Jenjang / Kelas</label>
      <input type="text" id="class_name" name="class_name" value="{{ old('class_name', $student->class_name) }}"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('class_name')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="pin" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Ganti PIN (Kosongkan jika tidak ingin mengubah)</label>
      <input type="text" id="pin" name="pin" placeholder="Masukkan PIN baru 4-6 angka"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('pin')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
      <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $student->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px;">
      <label for="is_active" style="font-weight: 700; font-size: 14px;">Akun aktif (dapat masuk ke sistem)</label>
    </div>

    <div style="display: flex; gap: 12px;">
      <button type="submit" class="btn btn-primary" style="min-height: 44px; padding: 0 20px;">Simpan Perubahan</button>
      <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" style="min-height: 44px; padding: 0 16px;">Batal</a>
    </div>
  </form>
</div>
@endsection
