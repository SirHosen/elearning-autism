@extends('layouts.admin')

@section('title', 'Kelola Permainan Edukasi')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem;">Kelola Permainan Edukasi</h1>
        <p style="margin: var(--space-1) 0 0 0; color: var(--text-muted); font-size: 0.9375rem;">Konfigurasi 3 engine permainan: Pilih Benar, Cocokkan, dan Seret & Tempatkan.</p>
    </div>
    <div>
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
            + Tambah Permainan
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
    <form action="{{ route('admin.games.index') }}" method="GET" style="display: flex; gap: var(--space-4); align-items: center; flex-wrap: wrap;">
        <label for="category_id" style="font-weight: 500; font-size: 0.9375rem;">Kategori:</label>
        <select name="category_id" id="category_id" class="form-control" style="max-width: 200px;" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <label for="game_type" style="font-weight: 500; font-size: 0.9375rem;">Jenis Engine:</label>
        <select name="game_type" id="game_type" class="form-control" style="max-width: 220px;" onchange="this.form.submit()">
            <option value="">Semua Jenis</option>
            <option value="pilih_benar" {{ request('game_type') === 'pilih_benar' ? 'selected' : '' }}>Pilih yang Benar</option>
            <option value="cocokkan" {{ request('game_type') === 'cocokkan' ? 'selected' : '' }}>Cocokkan Gambar</option>
            <option value="seret_tempatkan" {{ request('game_type') === 'seret_tempatkan' ? 'selected' : '' }}>Seret & Tempatkan</option>
        </select>

        @if(request('category_id') || request('game_type'))
            <a href="{{ route('admin.games.index') }}" class="btn btn-secondary" style="padding: var(--space-2) var(--space-3); font-size: 0.875rem;">Reset Filter</a>
        @endif
    </form>
</div>

<div class="card" style="padding: 0; overflow-x: auto;">
    <table class="table" style="margin: 0;">
        <thead>
            <tr>
                <th>Judul Permainan</th>
                <th>Jenis Permainan</th>
                <th>Kategori</th>
                <th>Ronde</th>
                <th>Opsi</th>
                <th>Status</th>
                <th style="text-align: right; width: 180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($games as $game)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text);">{{ $game->title }}</div>
                        <div style="font-size: 0.8125rem; color: var(--text-muted);">{{ $game->instruction_text }}</div>
                    </td>
                    <td>
                        @if($game->game_type === 'pilih_benar')
                            <span class="badge" style="background: #E0E7FF; color: #3730A3;">Pilih Benar</span>
                        @elseif($game->game_type === 'cocokkan')
                            <span class="badge" style="background: #FEF3C7; color: #92400E;">Cocokkan</span>
                        @else
                            <span class="badge" style="background: #E3F2EA; color: #166534;">Seret & Tempatkan</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background: var(--surface-alt); border: 1px solid var(--border);">
                            {{ $game->category->name }}
                        </span>
                    </td>
                    <td>{{ $game->round_count }}</td>
                    <td>{{ $game->option_count }}</td>
                    <td>
                        <form action="{{ route('admin.games.toggle', $game) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="badge" style="cursor: pointer; border: 0; background: {{ $game->is_active ? 'var(--success-bg, #E3F2EA)' : '#F3F4F6' }}; color: {{ $game->is_active ? 'var(--success)' : '#6B7280' }}; font-weight: 600;">
                                {{ $game->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.games.edit', $game) }}" class="btn btn-secondary" style="padding: var(--space-1) var(--space-3); font-size: 0.8125rem;">Edit</a>
                        <form action="{{ route('admin.games.destroy', $game) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus permainan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: var(--space-1) var(--space-3); font-size: 0.8125rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: var(--space-8); color: var(--text-muted);">
                        Belum ada permainan edukasi yang ditambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: var(--space-4);">
    {{ $games->links() }}
</div>
@endsection
