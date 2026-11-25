@extends('layouts.admin')

@section('title', 'Kelola Video Edukasi | Panel Pengelolaan Guru')
@section('page_title', 'Video Edukasi')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Kurikulum Visual &amp; Audio</p>
    <h1 class="adm-title">Kelola Video Edukasi</h1>
    <p class="adm-subtitle">Kurasi video MP4 lokal atau tautan YouTube bebas distraksi untuk siswa.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.videos.create', ['category_id' => request('category_id')]) }}" class="adm-btn adm-btn--primary">+ Tambah Video</a>
  </div>
</div>

<form action="{{ route('admin.videos.index') }}" method="GET" class="adm-filter-bar">
  <label for="category_id" style="font-weight: 700; font-size: 0.875rem;">Kategori:</label>
  <select name="category_id" id="category_id" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Kategori</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
    @endforeach
  </select>
  @if(request('category_id'))
    <a href="{{ route('admin.videos.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">Reset Filter</a>
  @endif
</form>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead>
      <tr>
        <th style="width: 96px;">Pratinjau</th>
        <th>Judul Video</th>
        <th>Kategori</th>
        <th>Sumber</th>
        <th>Urutan</th>
        <th>Status</th>
        <th style="text-align: right;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($videos as $video)
        <tr>
          <td>
            <img src="{{ $video->thumbnail_url }}"
                 alt="{{ $video->title }}"
                 style="width: 80px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid var(--line);"
                 onerror="this.src='{{ asset('images/icons/video-placeholder.svg') }}'">
          </td>
          <td>
            <strong style="color: var(--ink);">{{ $video->title }}</strong>
            @if($video->description)
              <div style="font-size: 0.8125rem; color: var(--ink-soft); max-width: 340px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">
                {{ $video->description }}
              </div>
            @endif
          </td>
          <td>
            @if($video->category)
              <span class="adm-pill adm-pill--info">{{ $video->category->name }}</span>
            @else
              <span class="adm-pill adm-pill--neutral">Umum</span>
            @endif
          </td>
          <td>
            @if($video->source_type === 'youtube')
              <span class="adm-pill adm-pill--danger">YouTube</span>
            @else
              <span class="adm-pill adm-pill--purple">Berkas MP4</span>
            @endif
          </td>
          <td>{{ $video->sort_order }}</td>
          <td>
            <form action="{{ route('admin.videos.toggle', $video) }}" method="POST" style="display: inline;">
              @csrf
              <button type="submit"
                      class="adm-pill {{ $video->is_active ? 'adm-pill--success' : 'adm-pill--neutral' }}"
                      style="border: 0; cursor: pointer;">
                {{ $video->is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </form>
          </td>
          <td style="text-align: right;">
            <div class="adm-actions">
              <a href="{{ route('admin.videos.edit', $video) }}" class="adm-btn adm-btn--secondary adm-btn--sm">Edit</a>
              <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus video ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="text-align: center; padding: 36px; color: var(--ink-soft);">
            Belum ada video edukasi yang ditambahkan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top: 20px;">
  {{ $videos->links() }}
</div>
@endsection
