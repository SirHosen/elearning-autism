@extends('layouts.admin')

@section('title', 'Kelola Soal: ' . $quiz->title . ' | Panel Pengelolaan Guru')
@section('page_title', 'Kelola Soal Kuis')

@section('content')
<div class="adm-page-head">
    <div>
      <x-admin.back-link :href="route('admin.quizzes.index')" label="Daftar Kuis" />
      <h1 class="adm-title">Kelola Soal: {{ $quiz->title }}</h1>
      <p class="adm-subtitle">
        Kategori: <strong>{{ $quiz->category?->name ?? 'Umum' }}</strong> &bull; Total Soal: <strong>{{ $quiz->questions->count() }}</strong>
      </p>
    </div>
    <div class="adm-head-actions">
      <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="adm-btn adm-btn--secondary">Ubah Kuis</a>
      @if($quiz->category)
        <a href="{{ route('admin.materials.index', ['category_id' => $quiz->category_id]) }}" class="adm-btn adm-btn--secondary">
          Lihat Bank Materi {{ $quiz->category->name }}
        </a>
      @endif
    </div>
</div>

<div class="adm-grid-aside">
  {{-- Daftar Soal Tersedia --}}
  <div>
    @forelse($quiz->questions as $index => $question)
      <article class="adm-panel">
        <div class="adm-panel__head">
          <div class="adm-q-head">
            <span class="adm-pill adm-pill--info">Soal #{{ $index + 1 }}</span>
            <strong class="adm-q-title">{{ $question->prompt_text }}</strong>
          </div>
          <form action="{{ route('admin.quizzes.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="adm-btn adm-btn--danger adm-btn--xs">Hapus</button>
          </form>
        </div>

        <div class="adm-panel__body">
          <div class="adm-q-options">
            @foreach($question->options as $option)
              @php $isCorrect = $option->material_id === $question->correct_material_id; @endphp
              <div class="adm-q-opt {{ $isCorrect ? 'adm-q-opt--correct' : '' }}">
                <img src="{{ asset('storage/' . $option->material->image_path) }}" alt="{{ $option->material->label }}" class="adm-q-opt__img">
                <span class="adm-q-opt__label">
                  {{ $option->material->label }}
                </span>
                @if($isCorrect)
                  <span class="adm-pill adm-pill--success adm-q-opt__badge">Kunci Benar</span>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </article>
    @empty
      <div class="adm-panel">
        <div class="adm-panel__body adm-table-empty">
          Belum ada soal pada kuis ini. Tambahkan soal melalui formulir di samping kanan.
        </div>
      </div>
    @endforelse
  </div>

  {{-- Form Tambah Soal Manual --}}
  <section class="adm-panel adm-sticky-panel">
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
          <span class="adm-help">Pastikan materi kunci ikut dicentang.</span>
          <div class="adm-checklist-box">
            @forelse($quiz->category?->materials ?? [] as $m)
              <label class="adm-checklist-item">
                <input type="checkbox" name="option_ids[]" value="{{ $m->id }}" {{ (is_array(old('option_ids')) && in_array($m->id, old('option_ids'))) ? 'checked' : '' }} class="adm-checkbox">
                <img src="/storage/{{ $m->image_path }}" alt="{{ $m->label }}" class="adm-checklist-item__img">
                <span>{{ $m->label }}</span>
              </label>
            @empty
              <span class="adm-cell-sub">Belum ada materi aktif pada kategori ini.</span>
            @endforelse
          </div>
          @error('option_ids')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="adm-btn adm-btn--primary adm-btn-block">Simpan Soal Kuis</button>
      </form>
    </div>
  </section>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const keySelect = document.getElementById('correct_material_id');
    const promptInput = document.getElementById('prompt_text');
    const optionChecks = document.querySelectorAll('input[name="option_ids[]"]');
    if (!keySelect) return;

    let lastAutoPrompt = 'Mana yang merupakan ...?';

    keySelect.addEventListener('change', function() {
      const selectedId = keySelect.value;
      const selectedText = keySelect.options[keySelect.selectedIndex]?.text?.trim() || '';

      if (selectedId) {
        optionChecks.forEach((cb) => {
          if (cb.value === selectedId) {
            cb.checked = true;
          }
        });
      }

      if (promptInput && selectedText) {
        const currentVal = promptInput.value.trim();
        if (currentVal === '' || currentVal === 'Mana yang merupakan ...?' || currentVal === lastAutoPrompt) {
          lastAutoPrompt = 'Mana yang merupakan ' + selectedText + '?';
          promptInput.value = lastAutoPrompt;
        }
      }
    });
  });
</script>
@endpush
@endsection
