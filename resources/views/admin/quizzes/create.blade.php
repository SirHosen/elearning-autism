@extends('layouts.admin')

@section('title', 'Tambah Kuis Bergambar')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: var(--space-6);">
        <a href="{{ route('admin.quizzes.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9375rem;">&larr; Kembali ke Daftar Kuis</a>
        <h1 style="margin: var(--space-2) 0 0 0; font-size: 1.5rem;">Tambah Kuis Baru</h1>
    </div>

    <div class="card">
        <form action="{{ route('admin.quizzes.store') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="title" class="form-label" style="font-weight: 600;">Judul Kuis *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Kuis Mengenal Hewan Ceria" required>
                @error('title')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="category_id" class="form-label" style="font-weight: 600;">Kategori Materi *</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->active_materials_count }} materi aktif)
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-6);">
                <div class="form-group">
                    <label for="sort_order" class="form-label" style="font-weight: 600;">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="form-group" style="display: flex; align-items: center; padding-top: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: var(--space-2); cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span style="font-weight: 600;">Aktifkan Kuis</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: var(--space-3);">
                <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Lanjut ke Kelola Soal &rarr;</button>
            </div>
        </form>
    </div>
</div>
@endsection
