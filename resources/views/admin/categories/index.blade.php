@extends('layouts.admin')

@section('title', 'Kategori Materi | Panel Pengelolaan Guru')
@section('page_title', 'Kategori')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Struktur Kurikulum</p>
    <h1 class="adm-title">Kategori Pembelajaran</h1>
    <p class="adm-subtitle">Kelompok materi yang terhubung ke modul Belajar, Video, Permainan, dan Kuis siswa.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.categories.create') }}" class="adm-btn adm-btn--primary">+ Tambah Kategori</a>
  </div>
</div>

@php
  $totalCats = $categories->count();
  $activeCats = $categories->where('is_active', true)->count();
  $totalMaterialsInCats = $categories->sum('materials_count');
@endphp

<div class="adm-kpi-grid" style="margin-bottom: 20px;">
  <div class="adm-kpi-card adm-kpi-card--blue">
    <span class="adm-kpi-card__label">Total Kategori</span>
    <strong class="adm-kpi-card__val">{{ $totalCats }}</strong>
    <span class="adm-kpi-card__sub">Kelompok topik kurikulum</span>
  </div>
  <div class="adm-kpi-card adm-kpi-card--green">
    <span class="adm-kpi-card__label">Aktif di Ruang Siswa</span>
    <strong class="adm-kpi-card__val">{{ $activeCats }}</strong>
    <span class="adm-kpi-card__sub">{{ max(0, $totalCats - $activeCats) }} menunggu aktivasi</span>
  </div>
  <div class="adm-kpi-card adm-kpi-card--forest">
    <span class="adm-kpi-card__label">Total Kartu Materi</span>
    <strong class="adm-kpi-card__val">{{ $totalMaterialsInCats }}</strong>
    <span class="adm-kpi-card__sub">Tersebar di seluruh kategori</span>
  </div>
</div>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Kategori &amp; Rute</th>
        <th>Konten Terhubung</th>
        <th>Status</th>
        <th class="adm-td-actions">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($categories as $cat)
        <tr>
          <td>
            <div style="display: flex; align-items: flex-start; gap: 12px;">
              <span class="adm-swatch adm-swatch--{{ $cat->color_key ?? 'blue' }}" style="margin-top: 2px;"></span>
              <div>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                  <strong style="color: var(--ink); font-size: 0.9375rem;">{{ $cat->name }}</strong>
                  <code style="font-size: 0.75rem; color: var(--primary-strong); background: var(--tint-blue); padding: 1px 7px; border-radius: 6px;">/belajar/{{ $cat->slug }}</code>
                </div>
                @if ($cat->description)
                  <div style="font-size: 0.8125rem; color: var(--ink-soft); margin-top: 3px; line-height: 1.4;">{{ $cat->description }}</div>
                @endif
              </div>
            </div>
          </td>
          <td>
            <div class="adm-link-group">
              <a href="{{ route('admin.materials.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->materials_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot" style="color: var(--primary);"></span>
                {{ $cat->materials_count }} Materi
              </a>
              <a href="{{ route('admin.videos.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->videos_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot" style="color: #6D28D9;"></span>
                {{ $cat->videos_count }} Video
              </a>
              <a href="{{ route('admin.games.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->games_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot" style="color: var(--success);"></span>
                {{ $cat->games_count }} Game
              </a>
              <a href="{{ route('admin.quizzes.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->quizzes_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot" style="color: #D97706;"></span>
                {{ $cat->quizzes_count }} Kuis
              </a>
            </div>
          </td>
          <td style="white-space: nowrap;">
            @if ($cat->is_active)
              <span class="adm-pill adm-pill--success"><span class="adm-dot"></span>Aktif</span>
            @else
              <span class="adm-pill adm-pill--neutral"><span class="adm-dot"></span>Nonaktif</span>
            @endif
          </td>
          <td class="adm-td-actions">
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
          <td colspan="4" style="padding: 36px; text-align: center; color: var(--ink-soft);">
            Belum ada kategori. Silakan tambahkan kategori baru.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
