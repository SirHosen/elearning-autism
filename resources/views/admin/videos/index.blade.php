@extends('layouts.admin')

@section('title', 'Kelola Video Edukasi')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem;">Kelola Video Edukasi</h1>
        <p style="margin: var(--space-1) 0 0 0; color: var(--text-muted); font-size: 0.9375rem;">Daftar materi video visual-audio untuk siswa.</p>
    </div>
    <div>
        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
            + Tambah Video
        </a>
    </div>
</div>

{{-- Filter Kategori --}}
<div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
    <form action="{{ route('admin.videos.index') }}" method="GET" style="display: flex; gap: var(--space-4); align-items: center; flex-wrap: wrap;">
        <label for="category_id" style="font-weight: 500; font-size: 0.9375rem;">Kategori:</label>
        <select name="category_id" id="category_id" class="form-control" style="max-width: 260px;" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @if(request('category_id'))
            <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary" style="padding: var(--space-2) var(--space-3); font-size: 0.875rem;">Reset Filter</a>
        @endif
    </form>
</div>

<div class="card" style="padding: 0; overflow-x: auto;">
    <table class="table" style="margin: 0;">
        <thead>
            <tr>
                <th style="width: 80px;">Pratinjau</th>
                <th>Judul Video</th>
                <th>Kategori</th>
                <th>Tipe Sumber</th>
                <th>Urutan</th>
                <th>Status</th>
                <th style="text-align: right; width: 220px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($videos as $video)
                <tr>
                    <td>
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" style="width: 70px; height: 46px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border);" onerror="this.src='{{ asset('images/icons/video-placeholder.svg') }}'">
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text);">{{ $video->title }}</div>
                        @if($video->description)
                            <div style="font-size: 0.8125rem; color: var(--text-muted); max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $video->description }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($video->category)
                            <span class="badge" style="background: var(--surface-alt); border: 1px solid var(--border);">
                                {{ $video->category->name }}
                            </span>
                        @else
                            <span style="color: var(--text-muted); font-size: 0.8125rem;">Umum</span>
                        @endif
                    </td>
                    <td>
                        @if($video->source_type === 'youtube')
                            <span class="badge" style="background: #FEE2E2; color: #991B1B;">YouTube</span>
                        @else
                            <span class="badge" style="background: #E0E7FF; color: #3730A3;">Berkas MP4</span>
                        @endif
                    </td>
                    <td>{{ $video->sort_order }}</td>
                    <td>
                        <form action="{{ route('admin.videos.toggle', $video) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="badge" style="cursor: pointer; border: 0; background: {{ $video->is_active ? 'var(--success-bg, #E3F2EA)' : '#F3F4F6' }}; color: {{ $video->is_active ? 'var(--success)' : '#6B7280' }}; font-weight: 600;">
                                {{ $video->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-secondary" style="padding: var(--space-1) var(--space-3); font-size: 0.8125rem;">Edit</a>
                        <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus video ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: var(--space-1) var(--space-3); font-size: 0.8125rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: var(--space-8); color: var(--text-muted);">
                        Belum ada video edukasi yang ditambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: var(--space-4);">
    {{ $videos->links() }}
</div>
@endsection
