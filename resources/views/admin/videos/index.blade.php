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
  <label for="category_id" class="adm-filter-label">Kategori:</label>
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
        <th class="adm-th-w96">Pratinjau</th>
        <th>Judul Video</th>
        <th>Kategori</th>
        <th>Sumber</th>
        <th>Urutan</th>
        <th>Status</th>
        <th class="adm-td-actions">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($videos as $video)
        <tr>
          <td>
            <img src="{{ $video->thumbnail_url }}"
                 alt="{{ $video->title }}"
                 class="adm-thumb-video"
                 onerror="this.src='{{ asset('images/icons/video-placeholder.svg') }}'">
          </td>
          <td>
            <strong class="adm-entity-name">{{ $video->title }}</strong>
            @if($video->description)
              <div class="adm-entity-desc adm-entity-desc--truncate">
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
          <td class="adm-td-nowrap">
            <form action="{{ route('admin.videos.toggle', $video) }}" method="POST" class="adm-inline-form">
              @csrf
              <button type="submit"
                      class="adm-pill adm-status-toggle {{ $video->is_active ? 'adm-pill--success' : 'adm-pill--neutral' }}"
                      title="Klik untuk ubah status">
                <span class="adm-dot"></span>{{ $video->is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </form>
          </td>
          <td class="adm-td-actions">
            <div class="adm-actions">
              <a href="{{ route('admin.videos.edit', $video) }}" class="adm-btn adm-btn--secondary adm-btn--sm">Ubah</a>
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
          <td colspan="7" class="adm-table-empty">
            Belum ada video edukasi yang ditambahkan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="adm-pagination-wrap">
  {{ $videos->links() }}
</div>
@endsection
