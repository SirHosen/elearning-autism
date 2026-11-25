@extends('layouts.admin')

@section('title', 'Kelola Soal: ' . $quiz->title . ' | Panel Pengelolaan Guru')
@section('page_title', 'Kelola Soal Kuis')

@section('content')
<div class="adm-page-head">
  <div>
    <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm" style="margin-bottom: 10px;">&larr; Daftar Kuis</a>
    <h1 class="adm-title">Kelola Soal: {{ $quiz->title }}</h1>
    <p class="adm-subtitle">
      Kategori: <strong>{{ $quiz->category?->name ?? 'Umum' }}</strong> &bull; Total Soal: <strong>{{ $quiz->questions->count() }}</strong>
    </p>
  </div>
  @if($quiz->category)
    <div class="adm-head-actions">
      <a href="{{ route('admin.materials.index', ['category_id' => $quiz->category_id]) }}" class="adm-btn adm-btn--secondary">
        Lihat Bank Materi {{ $quiz->category->name }}
      </a>
    </div>
  @endif
</div>

<div class="adm-grid-aside">
  {{-- Daftar Soal Tersedia --}}
  <div>
    @forelse($quiz->questions as $index => $question)
      <article class="adm-panel">
        <div class="adm-panel__head">
          <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <span class="adm-pill adm-pill--info">Soal #{{ $index + 1 }}</span>
            <strong style="font-size: 1rem; color: var(--ink);">{{ $question->prompt_text }}</strong>
          </div>
          <form action="{{ route('admin.quizzes.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="adm-btn adm-btn--danger adm-btn--xs">Hapus</button>
          </form>
        </div>

        <div class="adm-panel__body">
          <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: stretch;">
            @foreach($question->options as $option)
              @php $isCorrect = $option->material_id === $question->correct_material_id; @endphp
              <div style="min-width: 98px; display: flex; flex-direction: column; align-items: center; padding: 10px; border: 2px solid {{ $isCorrect ? 'var(--success)' : 'var(--line)' }}; border-radius: 12px; background: {{ $isCorrect ? 'var(--success-bg)' : 'var(--surface-alt)' }};">
                <img src="{{ asset('storage/' . $option->material->image_path) }}" alt="{{ $option->material->label }}" style="width: 64px; height: 64px; object-fit: contain;">
                <span style="font-size: 0.8125rem; font-weight: 700; margin-top: 6px; color: var(--ink);">
                  {{ $option->material->label }}
                </span>
                @if($isCorrect)
                  <span class="adm-pill adm-pill--success" style="margin-top: 4px; font-size: 0.6875rem;">Kunci Benar</span>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </article>
    @empty
      <div class="adm-panel">
        <div class="adm-panel__body" style="text-align: center; padding: 40px 24px; color: var(--ink-soft);">
          Belum ada soal pada kuis ini. Tambahkan soal melalui formulir di samping kanan.
        </div>
      </div>
    @endforelse
  </div>

  {{-- Form Tambah Soal Manual --}}
  <section class="adm-panel">
    <div class="adm-panel__head">
      <div>
        <h2 class="adm-panel__title">+ Tambah Soal Manual</h2>
        <p class="adm-panel__sub">Pilih kunci jawaban dan 2&ndash;4 gambar opsi</p>
      </div>
    </div>
    <div class="adm-panel__body">
      <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST">
        @csrf

        <div class="adm-field">
          <label for="prompt_text" class="adm-label">Pertanyaan Suara / Layar *</label>
          <input type="text" name="prompt_text" id="prompt_text" class="adm-input" value="{{ old('prompt_text', 'Mana yang merupakan ...?') }}" required>
          @error('prompt_text')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label for="correct_material_id" class="adm-label">Jawaban Benar (Kunci) *</label>
          <select name="correct_material_id" id="correct_material_id" class="adm-select" required>
            <option value="">-- Pilih Materi Kunci --</option>
            @foreach($quiz->category?->materials ?? [] as $m)
              <option value="{{ $m->id }}" {{ old('correct_material_id') == $m->id ? 'selected' : '' }}>
                {{ $m->label }}
              </option>
            @endforeach
          </select>
          @error('correct_material_id')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-field">
          <label class="adm-label">Pilihan Gambar (Pilih 2&ndash;4 materi) *</label>
          <span class="adm-help" style="margin-bottom: 8px; margin-top: 0;">Pastikan materi kunci ikut dicentang.</span>
          <div style="display: flex; flex-direction: column; gap: 6px; max-height: 260px; overflow-y: auto; padding: 8px; border: 1.5px solid var(--line); border-radius: 10px; background: var(--surface-alt);">
            @forelse($quiz->category?->materials ?? [] as $m)
              <label style="display: flex; align-items: center; gap: 10px; padding: 6px 8px; border-radius: 8px; background: var(--surface); border: 1px solid var(--line); cursor: pointer; font-size: 0.875rem; font-weight: 600;">
                <input type="checkbox" name="option_ids[]" value="{{ $m->id }}" {{ (is_array(old('option_ids')) && in_array($m->id, old('option_ids'))) ? 'checked' : '' }} style="width: 17px; height: 17px;">
                <img src="/storage/{{ $m->image_path }}" alt="{{ $m->label }}" style="width: 28px; height: 28px; object-fit: contain; border-radius: 6px; background: var(--surface-alt);">
                <span>{{ $m->label }}</span>
              </label>
            @empty
              <span style="font-size: 0.8125rem; color: var(--ink-soft);">Belum ada materi aktif pada kategori ini.</span>
            @endforelse
          </div>
          @error('option_ids')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="adm-btn adm-btn--primary" style="width: 100%;">Simpan Soal Kuis</button>
      </form>
    </div>
  </section>
</div>
@endsection
