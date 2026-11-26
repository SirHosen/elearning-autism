@extends('layouts.student')

@section('title', $quiz->title . ' | Kuis Bergambar')
@section('page_title', $quiz->title)

@section('content')
<div class="st-page-shell st-page-shell--arena">
  {{-- Header: Tombol Kembali, Suara, Skor & Progres --}}
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.quizzes.index') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali</span>
    </a>

    <div class="st-hud-group">
      <button type="button" id="btn-toggle-quiz-sfx" class="st-sfx-toggle" aria-pressed="true" title="Aktifkan atau matikan suara kuis">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
        </svg>
        <span id="quiz-sfx-label">Suara Aktif</span>
      </button>

      <div id="quiz-score-pill" class="st-topic-pill st-topic-pill--green" aria-live="polite">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="#F59E0B" stroke="#B45309" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
        <span>Skor: <strong id="quiz-score-val">0</strong></span>
      </div>

      <div class="st-progress-pill-wrap">
        <div id="quiz-progress-dots" class="st-progress-dots" aria-hidden="true"></div>
        <div id="quiz-progress-text" class="st-topic-pill st-topic-pill--yellow">
          Memuat soal...
        </div>
      </div>
    </div>
  </div>

  {{-- Kartu Pertanyaan & Tombol Dengar --}}
  <div class="st-arena-header-card">
    <div class="st-arena-instruction-wrap">
      <button id="btn-speak-prompt" type="button" class="st-speak-circle" title="Dengarkan Pertanyaan" aria-label="Dengarkan Pertanyaan">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
        </svg>
      </button>
      <div>
        <h2 id="quiz-prompt-text" class="st-arena-instruction">
          Memuat pertanyaan...
        </h2>
        <p class="st-arena-instruction-hint">Ketuk gambar yang sesuai lalu tekan Lanjut</p>
      </div>
    </div>

    <span class="st-topic-pill st-topic-pill--blue">
      {{ $quiz->category?->name ?? 'Kuis' }}
    </span>
  </div>

  {{-- Papan Kuis Terpadu --}}
  <section class="st-arena-board" aria-label="Area kuis">
    <div id="quiz-feedback" class="st-arena-feedback st-arena-feedback--neutral" role="status" aria-live="polite">
      <span class="st-arena-feedback__icon" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="16" x2="12" y2="12"/>
          <line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>
      </span>
      <span id="quiz-feedback-text" class="st-arena-feedback__text">Pilih satu gambar di bawah dengan tenang.</span>
    </div>

    <div id="quiz-arena" class="st-arena-stage">
      <div class="st-arena-loading">
        Menyiapkan kuis...
      </div>
    </div>
  </section>
</div>

<script src="/js/quiz/quiz-engine.js?v={{ @filemtime(public_path('js/quiz/quiz-engine.js')) ?: '2' }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new QuizEngine({
        quizId: {{ $quiz->id }},
        categorySlug: '{{ $quiz->category?->slug ?? '' }}',
        startUrl: '{{ route('student.quizzes.start', $quiz) }}',
        answerUrl: '{{ route('student.quizzes.answer', ':attempt') }}',
        finishUrl: '{{ route('student.quizzes.finish', ':attempt') }}',
        csrfToken: '{{ csrf_token() }}',
        homeUrl: '{{ route('student.quizzes.index') }}'
    });
});
</script>
@endsection
