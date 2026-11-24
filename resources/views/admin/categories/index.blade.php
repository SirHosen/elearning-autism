@extends('layouts.admin')

@section('title', 'Kategori Pembelajaran | Panel Guru')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px; flex-wrap: wrap;">
  <div>
    <h2 style="font-size: 24px; margin-bottom: 4px;">Kategori Materi</h2>
    <p style="color: var(--ink-soft); font-size: 14px;">Kelola kelompok materi yang dapat diakses oleh siswa.</p>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="min-height: 44px; padding: 0 18px; font-size: 15px;">
    + Tambah Kategori
  </a>
</div>

<div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); overflow-x: auto;">
  <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
    <thead>
      <tr style="background: var(--surface-alt); border-bottom: 1px solid var(--line); color: var(--ink-soft);">
        <th style="padding: 14px 16px;">Warna</th>
        <th style="padding: 14px 16px;">Nama Kategori</th>
        <th style="padding: 14px 16px;">Slug</th>
        <th style="padding: 14px 16px;">Materi</th>
        <th style="padding: 14px 16px;">Status</th>
        <th style="padding: 14px 16px; text-align: right;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($categories as $cat)
        <tr style="border-bottom: 1px solid var(--line);">
          <td style="padding: 14px 16px;">
            <span class="tile-{{ $cat->color_key }}" style="display: inline-block; width: 24px; height: 24px; border-radius: 6px; border: 1px solid var(--line);"></span>
          </td>
          <td style="padding: 14px 16px; font-weight: 700;">
            {{ $cat->name }}
            @if ($cat->description)
              <small style="display: block; font-weight: normal; color: var(--ink-soft); font-size: 12px;">{{ $cat->description }}</small>
            @endif
          </td>
          <td style="padding: 14px 16px; color: var(--ink-soft);">/belajar/{{ $cat->slug }}</td>
          <td style="padding: 14px 16px;">{{ $cat->materials_count }} materi</td>
          <td style="padding: 14px 16px;">
            @if ($cat->is_active)
              <span style="background: var(--success-bg); color: var(--success); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700;">Aktif</span>
            @else
              <span style="background: #eee; color: #777; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700;">Nonaktif</span>
            @endif
          </td>
          <td style="padding: 14px 16px; text-align: right;">
            <div style="display: inline-flex; gap: 8px;">
              <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-secondary" style="min-height: 36px; padding: 0 12px; font-size: 13px;">Ubah</a>
              <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="min-height: 36px; padding: 0 12px; font-size: 13px;">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 32px; text-align: center; color: var(--ink-soft);">Belum ada kategori. Silakan tambahkan kategori baru.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
