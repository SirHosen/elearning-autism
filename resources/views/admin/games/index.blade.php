@extends('layouts.admin')

@section('title', 'Kelola Permainan Edukasi | Panel Pengelolaan Guru')
@section('page_title', 'Permainan')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Kurikulum Interaktif</p>
    <h1 class="adm-title">Kelola Permainan Edukasi</h1>
    <p class="adm-subtitle">Konfigurasi permainan Pilih Benar, Cocokkan Gambar, dan Seret &amp; Tempatkan berdasar materi kategori.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.games.create', ['category_id' => request('category_id')]) }}" class="adm-btn adm-btn--primary">+ Tambah Permainan</a>
  </div>
</div>

<form action="{{ route('admin.games.index') }}" method="GET" class="adm-filter-bar">
  <label for="category_id" class="adm-filter-label">Kategori:</label>
  <select name="category_id" id="category_id" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Kategori</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
    @endforeach
  </select>

  <label for="game_type" class="adm-filter-label">Jenis Engine:</label>
  <select name="game_type" id="game_type" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Jenis</option>
    <option value="pilih_benar" {{ request('game_type') === 'pilih_benar' ? 'selected' : '' }}>Pilih yang Benar</option>
    <option value="cocokkan" {{ request('game_type') === 'cocokkan' ? 'selected' : '' }}>Cocokkan Gambar</option>
    <option value="seret_tempatkan" {{ request('game_type') === 'seret_tempatkan' ? 'selected' : '' }}>Seret &amp; Tempatkan</option>
  </select>

  @if(request('category_id') || request('game_type'))
    <a href="{{ route('admin.games.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">Reset Filter</a>
  @endif
</form>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Judul Permainan</th>
        <th>Jenis Permainan</th>
        <th>Kategori</th>
        <th>Ronde / Opsi</th>
        <th>Dimainkan</th>
        <th>Status</th>
        <th class="adm-td-actions">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($games as $game)
        <tr>
          <td>
            <strong class="adm-entity-name">{{ $game->title }}</strong>
            @if($game->instruction_text)
              <div class="adm-entity-desc">{{ $game->instruction_text }}</div>
            @endif
          </td>
          <td>
            @if($game->game_type === 'pilih_benar')
              <span class="adm-pill adm-pill--info">Pilih Benar</span>
            @elseif($game->game_type === 'cocokkan')
              <span class="adm-pill adm-pill--warning">Cocokkan</span>
            @else
              <span class="adm-pill adm-pill--success">Seret &amp; Tempatkan</span>
            @endif
          </td>
          <td>
            <span class="adm-pill adm-pill--neutral">{{ $game->category->name }}</span>
          </td>
          <td>
            <strong>{{ $game->round_count }}</strong> ronde &bull; {{ $game->option_count }} opsi
          </td>
          <td>
            <span class="adm-pill adm-pill--neutral">{{ $game->sessions_count ?? 0 }} sesi</span>
          </td>
          <td class="adm-td-nowrap">
            <form action="{{ route('admin.games.toggle', $game) }}" method="POST" class="adm-inline-form">
              @csrf
              <button type="submit"
                      class="adm-pill adm-status-toggle {{ $game->is_active ? 'adm-pill--success' : 'adm-pill--neutral' }}"
                      title="Klik untuk ubah status">
                <span class="adm-dot"></span>{{ $game->is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </form>
          </td>
          <td class="adm-td-actions">
            <div class="adm-actions">
              <a href="{{ route('admin.games.edit', $game) }}" class="adm-btn adm-btn--secondary adm-btn--sm">Ubah</a>
              <form action="{{ route('admin.games.destroy', $game) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permainan ini?')">
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
            Belum ada permainan edukasi yang ditambahkan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="adm-pagination-wrap">
  {{ $games->links() }}
</div>
@endsection
