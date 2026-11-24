@extends('layouts.admin')

@section('title', 'Materi Pembelajaran | Panel Guru')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px; flex-wrap: wrap;">
  <div>
    <h2 style="font-size: 24px; margin-bottom: 4px;">Materi Belajar Visual & Audio</h2>
    <p style="color: var(--ink-soft); font-size: 14px;">Daftar kartu objek pengenalan hewan, warna, dan benda.</p>
  </div>
  <a href="{{ route('admin.materials.create') }}" class="btn btn-primary" style="min-height: 44px; padding: 0 18px; font-size: 15px;">
    + Tambah Materi
  </a>
</div>

<!-- Filter & Pencarian -->
<div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
  <form action="{{ route('admin.materials.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap;">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama materi..."
           style="flex: 1; min-width: 220px; min-height: 42px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
    <select name="category_id" style="min-height: 42px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
      <option value="">Semua Kategori</option>
      @foreach ($categories as $c)
        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-secondary" style="min-height: 42px; padding: 0 16px;">Filter</button>
    @if (request()->hasAny(['q', 'category_id']))
      <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary" style="min-height: 42px; padding: 0 12px;">Reset</a>
    @endif
  </form>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 18px;">
  @forelse ($materials as $m)
    <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 16px; display: flex; flex-direction: column;">
      <div style="height: 140px; background: var(--surface-alt); border-radius: 8px; display: grid; place-items: center; overflow: hidden; margin-bottom: 12px;">
        <img src="/storage/{{ $m->image_path }}" alt="{{ $m->label }}" style="max-height: 120px; max-width: 100%; object-fit: contain;">
      </div>
      <h3 style="font-size: 18px; margin-bottom: 4px;">{{ $m->label }}</h3>
      <span style="font-size: 13px; color: var(--primary); font-weight: 700; margin-bottom: 8px;">
        {{ $m->category->name }}
      </span>
      <p style="font-size: 13px; color: var(--ink-soft); flex: 1; margin-bottom: 12px;">
        {{ $m->short_sentence ?? '-' }}
      </p>

      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        @if ($m->audio_path)
          <span style="font-size: 11px; font-weight: 700; background: var(--success-bg); color: var(--success); padding: 3px 8px; border-radius: 10px;">
            🎙 Rekaman Guru
          </span>
        @else
          <span style="font-size: 11px; font-weight: 700; background: var(--tint-blue); color: var(--primary); padding: 3px 8px; border-radius: 10px;">
            🤖 Suara Otomatis
          </span>
        @endif

        @if ($m->is_active)
          <span style="font-size: 11px; font-weight: 700; color: var(--success);">Aktif</span>
        @else
          <span style="font-size: 11px; font-weight: 700; color: #888;">Nonaktif</span>
        @endif
      </div>

      <div style="display: flex; gap: 8px; border-top: 1px solid var(--line); padding-top: 12px;">
        <a href="{{ route('admin.materials.edit', $m) }}" class="btn btn-secondary" style="flex: 1; min-height: 36px; font-size: 13px;">Ubah</a>
        <form action="{{ route('admin.materials.destroy', $m) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus materi {{ $m->label }}?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger" style="width: 100%; min-height: 36px; font-size: 13px;">Hapus</button>
        </form>
      </div>
    </div>
  @empty
    <div style="grid-column: 1/-1; background: var(--surface); padding: 48px; border: 1px dashed var(--line); border-radius: var(--radius-sm); text-align: center; color: var(--ink-soft);">
      Materi tidak ditemukan. Silakan tambahkan materi baru.
    </div>
  @endforelse
</div>

<div style="margin-top: 24px;">
  {{ $materials->links() }}
</div>
@endsection
