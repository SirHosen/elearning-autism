@extends('layouts.admin')

@section('title', 'Kategori Materi | Panel Pengelolaan Guru')
@section('page_title', 'Kategori')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Struktur Kurikulum</p>
    <h1 class="adm-title">Kategori Pembelajaran</h1>
    <p class="adm-subtitle">Kelompok materi yang terhubung ke halaman Belajar, Video, Permainan, dan Kuis siswa.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.categories.create') }}" class="adm-btn adm-btn--primary">+ Tambah Kategori</a>
  </div>
</div>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Warna</th>
        <th>Nama Kategori &amp; Rute</th>
        <th>Materi</th>
        <th>Video</th>
        <th>Permainan</th>
        <th>Kuis</th>
        <th>Status</th>
        <th style="text-align: right;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($categories as $cat)
        <tr>
          <td>
            <span class="tile-{{ $cat->color_key }}" style="display: inline-block; width: 28px; height: 28px; border-radius: 8px; border: 1.5px solid var(--line);"></span>
          </td>
          <td>
            <strong style="color: var(--ink); font-size: 0.9375rem;">{{ $cat->name }}</strong>
            @if ($cat->description)
              <div style="font-size: 0.8125rem; color: var(--ink-soft); margin-top: 2px;">{{ $cat->description }}</div>
            @endif
            <div style="font-size: 0.75rem; color: var(--primary-strong); margin-top: 2px;"><code>/belajar/{{ $cat->slug }}</code></div>
          </td>
          <td>
            <a href="{{ route('admin.materials.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--info">
              {{ $cat->materials_count }} materi
            </a>
          </td>
          <td>
            <a href="{{ route('admin.videos.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--purple">
              {{ $cat->videos_count }} video
            </a>
          </td>
          <td>
            <a href="{{ route('admin.games.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--success">
              {{ $cat->games_count }} game
            </a>
          </td>
          <td>
            <a href="{{ route('admin.quizzes.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--warning">
              {{ $cat->quizzes_count }} kuis
            </a>
          </td>
          <td>
            @if ($cat->is_active)
              <span class="adm-pill adm-pill--success">Aktif</span>
            @else
              <span class="adm-pill adm-pill--neutral">Nonaktif</span>
            @endif
          </td>
          <td style="text-align: right;">
            <div class="adm-actions">
              <a href="{{ route('admin.materials.create', ['category_id' => $cat->id]) }}" class="adm-btn adm-btn--secondary adm-btn--sm">+ Materi</a>
              <a href="{{ route('admin.categories.edit', $cat) }}" class="adm-btn adm-btn--secondary adm-btn--sm">Ubah</a>
              <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" style="padding: 36px; text-align: center; color: var(--ink-soft);">
            Belum ada kategori. Silakan tambahkan kategori baru.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
