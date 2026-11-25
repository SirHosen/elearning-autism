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
    <button type="button" class="adm-btn adm-btn--secondary" onclick="document.getElementById('auto-quiz-modal').style.display='flex'">
      Generator Kuis Otomatis
    </button>
    <a href="{{ route('admin.quizzes.create', ['category_id' => request('category_id')]) }}" class="adm-btn adm-btn--primary">+ Tambah Kuis Manual</a>
  </div>
</div>

{{-- Modal Generator Otomatis --}}
<div id="auto-quiz-modal" class="adm-modal-backdrop">
  <div class="adm-modal" role="dialog" aria-modal="true" aria-labelledby="autoQuizTitle">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h2 id="autoQuizTitle" style="margin: 0; font-size: 1.25rem; color: var(--ink);">Generator Kuis Otomatis</h2>
      <button type="button"
              onclick="document.getElementById('auto-quiz-modal').style.display='none'"
              aria-label="Tutup modal"
              style="background: none; border: 0; font-size: 1.5rem; cursor: pointer; color: var(--ink-soft);">
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
            <option value="{{ $category->id }}">
              {{ $category->name }} ({{ $category->active_materials_count }} materi aktif)
            </option>
          @endforeach
        </select>
        <span class="adm-help">Kategori wajib memiliki minimal 3 materi aktif agar pengecoh acak dapat disusun.</span>
      </div>

      <div class="adm-field">
        <label for="modal_q_count" class="adm-label">Jumlah Soal yang Dibuat *</label>
        <input type="number" name="question_count" id="modal_q_count" class="adm-input" value="5" min="2" max="20" required>
        <span class="adm-help">Sistem memilih target &amp; opsi pengecoh gambar otomatis per soal.</span>
      </div>

      <div class="adm-form-footer">
        <button type="button" class="adm-btn adm-btn--secondary" onclick="document.getElementById('auto-quiz-modal').style.display='none'">Batal</button>
        <button type="submit" class="adm-btn adm-btn--primary">Buat Kuis Sekarang</button>
      </div>
    </form>
  </div>
</div>

<form action="{{ route('admin.quizzes.index') }}" method="GET" class="adm-filter-bar">
  <label for="category_id" style="font-weight: 700; font-size: 0.875rem;">Kategori:</label>
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
            <strong style="color: var(--ink);">{{ $quiz->title }}</strong>
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
          <td>
            <form action="{{ route('admin.quizzes.toggle', $quiz) }}" method="POST" style="display: inline;">
              @csrf
              <button type="submit"
                      class="adm-pill {{ $quiz->is_active ? 'adm-pill--success' : 'adm-pill--neutral' }}"
                      style="border: 0; cursor: pointer;">
                {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </form>
          </td>
          <td class="adm-td-actions">
            <div class="adm-actions">
              <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="adm-btn adm-btn--blue adm-btn--sm">Kelola Soal</a>
              <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="adm-btn adm-btn--secondary adm-btn--sm">Edit</a>
              <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kuis ini beserta semua soalnya?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align: center; padding: 36px; color: var(--ink-soft);">
            Belum ada kuis bergambar. Gunakan Generator Kuis Otomatis untuk membuat kuis instan.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top: 20px;">
  {{ $quizzes->links() }}
</div>
@endsection
