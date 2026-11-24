@extends('layouts.admin')

@section('title', 'Ubah Materi | Panel Guru')

@section('content')
<div style="max-width: 680px; margin: 0 auto; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 32px;">
  <h2 style="font-size: 22px; margin-bottom: 6px;">Ubah Materi: {{ $material->label }}</h2>
  <p style="color: var(--ink-soft); font-size: 14px; margin-bottom: 24px;">Perbarui data atau ganti berkas gambar dan suara.</p>

  <form action="{{ route('admin.materials.update', $material) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div style="margin-bottom: 18px;">
      <label for="category_id" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Kategori Materi</label>
      <select id="category_id" name="category_id" required style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
        @foreach ($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id', $material->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      @error('category_id')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="label" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Nama Objek / Materi</label>
      <input type="text" id="label" name="label" value="{{ old('label', $material->label) }}" required
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('label')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="short_sentence" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Contoh Kalimat Sederhana</label>
      <input type="text" id="short_sentence" name="short_sentence" value="{{ old('short_sentence', $material->short_sentence) }}"
             style="width: 100%; min-height: 44px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      @error('short_sentence')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="image" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Ganti Foto / Gambar (Kosongkan jika tidak diubah)</label>
      <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 8px;">
        <img src="/storage/{{ $material->image_path }}" alt="{{ $material->label }}" style="height: 64px; width: 64px; object-fit: contain; border-radius: 8px; border: 1px solid var(--line);">
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
               style="flex: 1; padding: 8px; border: 1px solid var(--line); border-radius: var(--radius-sm); background: var(--surface-alt);">
      </div>
      @error('image')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 18px;">
      <label for="audio" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px;">Ganti Audio Suara Guru (Kosongkan jika tidak diubah)</label>
      @if ($material->audio_path)
        <div style="margin-bottom: 8px;">
          <audio controls src="/storage/{{ $material->audio_path }}" style="height: 36px; width: 100%;"></audio>
        </div>
      @endif
      <input type="file" id="audio" name="audio" accept="audio/mpeg,audio/mp3,audio/ogg,audio/wav"
             style="width: 100%; padding: 8px; border: 1px solid var(--line); border-radius: var(--radius-sm); background: var(--surface-alt);">
      @error('audio')<small style="color: var(--danger); font-weight: 700;">{{ $message }}</small>@enderror
    </div>

    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
      <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $material->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px;">
      <label for="is_active" style="font-weight: 700; font-size: 14px;">Aktifkan materi ini agar dapat dipelajari siswa</label>
    </div>

    <div style="display: flex; gap: 12px;">
      <button type="submit" class="btn btn-primary" style="min-height: 44px; padding: 0 20px;">Perbarui Materi</button>
      <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary" style="min-height: 44px; padding: 0 16px;">Batal</a>
    </div>
  </form>
</div>
@endsection
