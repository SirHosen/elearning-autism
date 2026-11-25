@extends('layouts.admin')

@section('title', 'Kelola Kuis Bergambar')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem;">Kelola Kuis Bergambar</h1>
        <p style="margin: var(--space-1) 0 0 0; color: var(--text-muted); font-size: 0.9375rem;">Kuis gambar dan suara yang sederhana serta mudah dipahami siswa.</p>
    </div>
    <div style="display: flex; gap: var(--space-3);">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('auto-quiz-modal').style.display='flex'">
            Generator Kuis Otomatis
        </button>
        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
            + Tambah Kuis Manual
        </a>
    </div>
</div>

{{-- Modal Generator Otomatis --}}
<div id="auto-quiz-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: var(--space-4);">
    <div class="card" style="max-width: 500px; width: 100%; background: var(--surface); border-radius: var(--radius-lg); padding: var(--space-6);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4);">
            <h2 style="margin: 0; font-size: 1.25rem;">Generator Kuis Otomatis</h2>
            <button type="button" onclick="document.getElementById('auto-quiz-modal').style.display='none'" style="background: none; border: 0; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form action="{{ route('admin.quizzes.auto-generate') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="modal_cat_id" class="form-label" style="font-weight: 600;">Pilih Kategori Materi *</label>
                <select name="category_id" id="modal_cat_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }} ({{ $category->active_materials_count }} materi aktif)
                        </option>
                    @endforeach
                </select>
                <small style="color: var(--text-muted); display: block; margin-top: var(--space-1);">Kategori harus memiliki minimal 3 materi aktif.</small>
            </div>
            <div class="form-group" style="margin-bottom: var(--space-6);">
                <label for="modal_q_count" class="form-label" style="font-weight: 600;">Jumlah Soal yang Dibuat *</label>
                <input type="number" name="question_count" id="modal_q_count" class="form-control" value="5" min="2" max="20" required>
                <small style="color: var(--text-muted); display: block; margin-top: var(--space-1);">Sistem akan memilih materi acak dan membuatkan pilihan pengecoh otomatis.</small>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: var(--space-3);">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('auto-quiz-modal').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">Buat Kuis Sekarang</button>
            </div>
        </form>
    </div>
</div>

{{-- Filter Kategori --}}
<div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
    <form action="{{ route('admin.quizzes.index') }}" method="GET" style="display: flex; gap: var(--space-4); align-items: center; flex-wrap: wrap;">
        <label for="category_id" style="font-weight: 500; font-size: 0.9375rem;">Kategori:</label>
        <select name="category_id" id="category_id" class="form-control" style="max-width: 240px;" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @if(request('category_id'))
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary" style="padding: var(--space-2) var(--space-3); font-size: 0.875rem;">Reset Filter</a>
        @endif
    </form>
</div>

<div class="card" style="padding: 0; overflow-x: auto;">
    <table class="table" style="margin: 0;">
        <thead>
            <tr>
                <th>Judul Kuis</th>
                <th>Kategori</th>
                <th>Jumlah Soal</th>
                <th>Dikerjakan</th>
                <th>Status</th>
                <th style="text-align: right; width: 240px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quizzes as $quiz)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text);">{{ $quiz->title }}</div>
                    </td>
                    <td>
                        @if($quiz->category)
                            <span class="badge" style="background: var(--surface-alt); border: 1px solid var(--border);">
                                {{ $quiz->category->name }}
                            </span>
                        @else
                            <span style="color: var(--text-muted); font-size: 0.8125rem;">Umum</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: 600;">{{ $quiz->questions_count }}</span> Soal
                    </td>
                    <td>
                        <span style="font-weight: 600;">{{ $quiz->attempts_count }}</span> kali
                    </td>
                    <td>
                        <form action="{{ route('admin.quizzes.toggle', $quiz) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="badge" style="cursor: pointer; border: 0; background: {{ $quiz->is_active ? 'var(--success-bg, #E3F2EA)' : '#F3F4F6' }}; color: {{ $quiz->is_active ? 'var(--success)' : '#6B7280' }}; font-weight: 600;">
                                {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="btn btn-secondary" style="padding: var(--space-1) var(--space-3); font-size: 0.8125rem;">Kelola Soal</a>
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-secondary" style="padding: var(--space-1) var(--space-3); font-size: 0.8125rem;">Edit</a>
                        <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus kuis ini beserta semua soalnya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: var(--space-1) var(--space-3); font-size: 0.8125rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: var(--space-8); color: var(--text-muted);">
                        Belum ada kuis bergambar yang ditambahkan. Gunakan generator otomatis untuk membuat kuis instan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: var(--space-4);">
    {{ $quizzes->links() }}
</div>
@endsection
