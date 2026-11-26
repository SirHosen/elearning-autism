@extends('layouts.admin')

@section('title', 'Kelola Kuis Bergambar | Panel Pengelolaan Guru')
@section('page_title', 'Kuis Bergambar')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Evaluasi Visual Mandiri</p>
    <h1 class="adm-title">Kelola Kuis Bergambar</h1>
    <p class="adm-subtitle">Buat kuis secara manual atau gunakan generator otomatis berbasis bank materi kategori.</p>
  </div>
  <div class="adm-head-actions">
    <button type="button" id="btnOpenAutoQuiz" class="adm-btn adm-btn--secondary">
      Generator Kuis Otomatis
    </button>
    <a href="{{ route('admin.quizzes.create', ['category_id' => request('category_id')]) }}" class="adm-btn adm-btn--primary">+ Tambah Kuis Manual</a>
  </div>
</div>

{{-- Modal Generator Otomatis --}}
@php
  $autoModalOpen = $errors->has('count') || $errors->has('category_id') || $errors->has('question_count');
@endphp
<div id="auto-quiz-modal" class="adm-modal-backdrop {{ $autoModalOpen ? 'is-open' : '' }}">
  <div class="adm-modal" role="dialog" aria-modal="true" aria-labelledby="autoQuizTitle">
    <div class="adm-modal__head">
      <h2 id="autoQuizTitle" class="adm-modal__title">Generator Kuis Otomatis</h2>
      <button type="button"
              id="btnCloseAutoQuizX"
              aria-label="Tutup modal"
              class="adm-modal__close">
        &times;
      </button>
    </div>
    <form action="{{ route('admin.quizzes.auto-generate') }}" method="POST">
      @csrf
      <div class="adm-field">
        <label for="modal_cat_id" class="adm-label">Pilih Kategori Materi *</label>
        <select name="category_id" id="modal_cat_id" class="adm-select" required>
          <option value="">-- Pilih Kategori --</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }} ({{ $category->active_materials_count }} materi aktif)
            </option>
          @endforeach
        </select>
        @error('category_id')<span class="adm-error">{{ $message }}</span>@enderror
        <span class="adm-help">Kategori wajib memiliki minimal 3 materi aktif agar pengecoh acak dapat disusun.</span>
      </div>

      <div class="adm-field">
        <label for="modal_q_count" class="adm-label">Jumlah Soal yang Dibuat *</label>
        <input type="number" name="question_count" id="modal_q_count" class="adm-input" value="{{ old('question_count', 5) }}" min="2" max="20" required>
        @error('question_count')<span class="adm-error">{{ $message }}</span>@enderror
        @error('count')<span class="adm-error">{{ $message }}</span>@enderror
        <span class="adm-help">Sistem memilih target &amp; opsi pengecoh gambar otomatis per soal.</span>
      </div>

      <div class="adm-form-footer">
        <button type="button" id="btnCloseAutoQuizCancel" class="adm-btn adm-btn--secondary">Batal</button>
        <button type="submit" class="adm-btn adm-btn--primary">Buat Kuis Sekarang</button>
      </div>
    </form>
  </div>
</div>

<form action="{{ route('admin.quizzes.index') }}" method="GET" class="adm-filter-bar">
  <label for="category_id" class="adm-filter-label">Kategori:</label>
  <select name="category_id" id="category_id" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Kategori</option>
    @foreach($categories as $cat)
      <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
    @endforeach
  </select>
  @if(request('category_id'))
    <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">Reset Filter</a>
  @endif
</form>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Judul Kuis</th>
        <th>Kategori</th>
        <th>Jumlah Soal</th>
        <th>Dikerjakan</th>
        <th>Status</th>
        <th class="adm-td-actions">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($quizzes as $quiz)
        <tr>
          <td>
            <strong class="adm-entity-name">{{ $quiz->title }}</strong>
          </td>
          <td>
            @if($quiz->category)
              <span class="adm-pill adm-pill--info">{{ $quiz->category->name }}</span>
            @else
              <span class="adm-pill adm-pill--neutral">Umum</span>
            @endif
          </td>
          <td><strong>{{ $quiz->questions_count }}</strong> soal</td>
          <td><span class="adm-pill adm-pill--neutral">{{ $quiz->attempts_count }} kali</span></td>
          <td class="adm-td-nowrap">
            <form action="{{ route('admin.quizzes.toggle', $quiz) }}" method="POST" class="adm-inline-form">
              @csrf
              <button type="submit"
                      class="adm-pill adm-status-toggle {{ $quiz->is_active ? 'adm-pill--success' : 'adm-pill--neutral' }}"
                      title="Klik untuk ubah status">
                <span class="adm-dot"></span>{{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </form>
          </td>
          <td class="adm-td-actions">
            <div class="adm-actions">
              <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="adm-btn adm-btn--blue adm-btn--xs">Kelola Soal</a>
              <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="adm-btn adm-btn--secondary adm-btn--xs">Ubah</a>
              <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kuis ini beserta semua soalnya?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--xs">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="adm-table-empty">
            Belum ada kuis bergambar. Gunakan Generator Kuis Otomatis untuk membuat kuis instan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="adm-pagination-wrap">
  {{ $quizzes->links() }}
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('auto-quiz-modal');
    const openBtn = document.getElementById('btnOpenAutoQuiz');
    const closeX = document.getElementById('btnCloseAutoQuizX');
    const closeCancel = document.getElementById('btnCloseAutoQuizCancel');
    if (!modal) return;

    const setOpen = (open) => {
      modal.classList.toggle('is-open', open);
    };

    if (openBtn) openBtn.addEventListener('click', () => setOpen(true));
    if (closeX) closeX.addEventListener('click', () => setOpen(false));
    if (closeCancel) closeCancel.addEventListener('click', () => setOpen(false));
    modal.addEventListener('click', (e) => {
      if (e.target === modal) setOpen(false);
    });
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) {
        setOpen(false);
      }
    });
  });
</script>
@endpush
@endsection
