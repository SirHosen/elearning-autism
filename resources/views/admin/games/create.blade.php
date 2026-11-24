@extends('layouts.admin')

@section('title', 'Tambah Permainan Edukasi')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="margin-bottom: var(--space-6);">
        <a href="{{ route('admin.games.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9375rem;">&larr; Kembali ke Daftar Permainan</a>
        <h1 style="margin: var(--space-2) 0 0 0; font-size: 1.5rem;">Tambah Permainan Edukasi</h1>
    </div>

    <div class="card">
        <form action="{{ route('admin.games.store') }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="title" class="form-label" style="font-weight: 600;">Judul Permainan *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Tebak Nama Hewan Ceria" required>
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

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="game_type" class="form-label" style="font-weight: 600;">Jenis Engine Permainan *</label>
                <select name="game_type" id="game_type" class="form-control" required>
                    <option value="pilih_benar" {{ old('game_type', 'pilih_benar') === 'pilih_benar' ? 'selected' : '' }}>
                        1. Pilih yang Benar (Receptive Identification)
                    </option>
                    <option value="cocokkan" {{ old('game_type') === 'cocokkan' ? 'selected' : '' }}>
                        2. Cocokkan Gambar (Matching Pairs)
                    </option>
                    <option value="seret_tempatkan" {{ old('game_type') === 'seret_tempatkan' ? 'selected' : '' }}>
                        3. Seret & Tempatkan (Drag & Drop + Tap Alternative)
                    </option>
                </select>
                @error('game_type')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-4);">
                <div class="form-group">
                    <label for="option_count" class="form-label" style="font-weight: 600;">Pilihan per Ronde (2 - 4) *</label>
                    <input type="number" name="option_count" id="option_count" class="form-control" value="{{ old('option_count', 3) }}" min="2" max="4" required>
                    @error('option_count')
                        <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="round_count" class="form-label" style="font-weight: 600;">Jumlah Ronde (1 - 10) *</label>
                    <input type="number" name="round_count" id="round_count" class="form-control" value="{{ old('round_count', 5) }}" min="1" max="10" required>
                    @error('round_count')
                        <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="instruction_text" class="form-label" style="font-weight: 600;">Teks Instruksi Suara / Layar (Opsional)</label>
                <input type="text" name="instruction_text" id="instruction_text" class="form-control" value="{{ old('instruction_text') }}" placeholder="Contoh: Pilih gambar yang sesuai">
                <small style="color: var(--text-muted); display: block; margin-top: var(--space-1);">Jika dikosongkan, sistem akan menggunakan instruksi standar yang ramah autis.</small>
                @error('instruction_text')
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
                        <span style="font-weight: 600;">Aktifkan Permainan</span>
                    </label>
                </div>
            </div>
            @error('is_active')
                <div style="color: var(--danger); font-size: 0.8125rem; margin-bottom: var(--space-4);">{{ $message }}</div>
            @enderror

            <div style="display: flex; justify-content: flex-end; gap: var(--space-3);">
                <a href="{{ route('admin.games.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Permainan</button>
            </div>
        </form>
    </div>
</div>
@endsection
