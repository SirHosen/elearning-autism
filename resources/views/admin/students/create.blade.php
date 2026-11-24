@extends('layouts.admin')

@section('title', 'Tambah Siswa | Panel Guru')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 32px;">
  <h2 style="font-size: 22px; margin-bottom: 6px;">Tambah Akun Siswa</h2>
  <p style="color: var(--ink-soft); font-size: 14px; margin-bottom: 24px;">Buat akun siswa agar dapat mengakses ruang belajar.</p>

  <form action="{{ route('admin.students.store') }}" method="POST">
    @csrf
    <div style="margin-bottom: 18px;">
      <label for="name" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Nama Lengkap / Panggilan Siswa</label>
      <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('name')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="username" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Nama Pengguna (Username Login)</label>
      <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="Contoh: budi"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      <small style="color: var(--ink-soft); display: block; margin-top: 4px;">Huruf kecil tanpa spasi, unik untuk tiap anak.</small>
      @error('username')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="class_name" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Jenjang / Kelas (Opsional)</label>
      <input type="text" id="class_name" name="class_name" value="{{ old('class_name') }}" placeholder="Contoh: Kelas 1, SDLB B, SDLB C"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('class_name')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="pin" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">PIN Angka (4-6 Digit, Opsional)</label>
      <input type="text" id="pin" name="pin" value="{{ old('pin') }}" placeholder="Kosongkan untuk membuat PIN acak otomatis"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      <small style="color: var(--ink-soft); display: block; margin-top: 4px;">Jika dikosongkan, sistem akan membuat 4 digit angka acak yang bisa dicetak.</small>
      @error('pin')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
      <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px;">
      <label for="is_active" style="font-weight: 700; font-size: 14px;">Akun aktif (dapat langsung masuk ke sistem)</label>
    </div>

    <div style="display: flex; gap: 12px;">
      <button type="submit" class="btn btn-primary" style="min-height: 44px; padding: 0 20px;">Buat Akun Siswa</button>
      <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" style="min-height: 44px; padding: 0 16px;">Batal</a>
    </div>
  </form>
</div>
@endsection
