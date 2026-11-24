@extends('layouts.admin')

@section('title', 'Tambah Kategori | Panel Guru')

@section('content')
<div style="max-width: 640px; margin: 0 auto; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 32px;">
  <h2 style="font-size: 22px; margin-bottom: 6px;">Tambah Kategori Baru</h2>
  <p style="color: var(--ink-soft); font-size: 14px; margin-bottom: 24px;">Buat kelompok materi pembelajaran visual dan audio.</p>

  <form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div style="margin-bottom: 18px;">
      <label for="name" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Nama Kategori</label>
      <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Hewan, Warna, Benda"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('name')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="slug" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Slug URL (Opsional)</label>
      <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Otomatis dibuat jika dikosongkan"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('slug')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="color_key" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Warna Kartu Tile</label>
      <select id="color_key" name="color_key" required style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
        <option value="blue" {{ old('color_key') === 'blue' ? 'selected' : '' }}>Biru Tenang (Blue)</option>
        <option value="green" {{ old('color_key') === 'green' ? 'selected' : '' }}>Hijau Daun (Green)</option>
        <option value="yellow" {{ old('color_key') === 'yellow' ? 'selected' : '' }}>Kuning Hangat (Yellow)</option>
        <option value="lavender" {{ old('color_key') === 'lavender' ? 'selected' : '' }}>Lavender Lembut</option>
        <option value="peach" {{ old('color_key') === 'peach' ? 'selected' : '' }}>Peach Cerah</option>
      </select>
      @error('color_key')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="description" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Deskripsi Singkat</label>
      <textarea id="description" name="description" rows="3" placeholder="Keterangan singkat materi kategori ini"
                style="width: 100%; padding: 10px 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">{{ old('description') }}</textarea>
    </div>

    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
      <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px;">
      <label for="is_active" style="font-weight: 700; font-size: 14px;">Aktifkan kategori ini agar terlihat oleh siswa</label>
    </div>

    <div style="display: flex; gap: 12px;">
      <button type="submit" class="btn btn-primary" style="min-height: 44px; padding: 0 20px;">Simpan Kategori</button>
      <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="min-height: 44px; padding: 0 16px;">Batal</a>
    </div>
  </form>
</div>
@endsection
