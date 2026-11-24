@extends('layouts.admin')

@section('title', 'Tambah Materi | Panel Guru')

@section('content')
<div style="max-width: 680px; margin: 0 auto; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 32px;">
  <h2 style="font-size: 22px; margin-bottom: 6px;">Tambah Materi Pembelajaran</h2>
  <p style="color: var(--ink-soft); font-size: 14px; margin-bottom: 24px;">Unggah ilustrasi gambar bersih dan rekaman suara guru.</p>

  <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="margin-bottom: 18px;">
      <label for="category_id" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Kategori Materi</label>
      <select id="category_id" name="category_id" required style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
        <option value="">Pilih Kategori...</option>
        @foreach ($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      @error('category_id')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="label" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Nama Objek / Materi</label>
      <input type="text" id="label" name="label" value="{{ old('label') }}" required placeholder="Contoh: Kucing, Merah, Bola"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('label')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="short_sentence" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Contoh Kalimat Sederhana (Opsional)</label>
      <input type="text" id="short_sentence" name="short_sentence" value="{{ old('short_sentence') }}" placeholder="Contoh: Ini kucing lucu."
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('short_sentence')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="image" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Foto / Ilustrasi Gambar (Maks 4 MB)</label>
      <input type="file" id="image" name="image" required accept="image/jpeg,image/png,image/webp"
             style="width: 100%; padding: 10px; border: 1px solid var(--line); border-radius: var(--radius-sm); background: var(--surface-alt);">
      <small style="color: var(--ink-soft); display: block; margin-top: 4px;">Format yang didukung: JPG, PNG, WebP (otomatis dioptimasi ke WebP 1024px).</small>
      @error('image')<small style="color: var(--danger); font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="audio" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Rekaman Suara Guru (Opsional, Maks 3 MB)</label>
      <input type="file" id="audio" name="audio" accept="audio/mpeg,audio/mp3,audio/ogg,audio/wav"
             style="width: 100%; padding: 10px; border: 1px solid var(--line); border-radius: var(--radius-sm); background: var(--surface-alt);">
      <small style="color: var(--ink-soft); display: block; margin-top: 4px;">Jika dikosongkan, sistem otomatis menggunakan suara Text-to-Speech bahasa Indonesia.</small>
      @error('audio')<small style="color: var(--danger); font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
      <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="width: 18px; height: 18px;">
      <label for="is_active" style="font-weight: 700; font-size: 14px;">Aktifkan materi ini agar dapat dipelajari siswa</label>
    </div>

    <div style="display: flex; gap: 12px;">
      <button type="submit" class="btn btn-primary" style="min-height: 44px; padding: 0 20px;">Simpan Materi</button>
      <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary" style="min-height: 44px; padding: 0 16px;">Batal</a>
    </div>
  </form>
</div>
@endsection
