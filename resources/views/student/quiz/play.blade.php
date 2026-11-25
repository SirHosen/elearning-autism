@extends('layouts.student')

@section('title', $quiz->title . ' | Kuis Bergambar')
@section('page_title', $quiz->title)

@section('content')
<div class="st-page-shell" style="max-width: 900px;">
  {{-- Header: Tombol Kembali & Progres --}}
  <div class="st-page-bar">
    <a href="{{ route('student.quizzes.index') }}" class="st-back-pill">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      <span>Kembali</span>
    </a>

    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
      <div id="quiz-progress-dots" style="display: flex; gap: 6px; align-items: center;"></div>
      <div id="quiz-progress-text" class="st-topic-pill st-topic-pill--yellow">
        Memuat soal...
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
      <h2 id="quiz-prompt-text" class="st-arena-instruction">
        Memuat pertanyaan...
      </h2>
    </div>

    <span class="st-topic-pill st-topic-pill--blue">
      {{ $quiz->category?->name ?? 'Kuis' }}
    </span>
  </div>

  {{-- Arena Pilihan Jawaban Bergambar --}}
  <div id="quiz-arena" style="min-height: 360px;">
    <div style="text-align: center; padding: 48px 20px; color: var(--ink-soft); font-weight: 600;">
      Menyiapkan kuis...
    </div>
  </div>
</div>

<script src="{{ asset('js/quiz/quiz-engine.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new QuizEngine({
        quizId: {{ $quiz->id }},
        startUrl: '{{ route('student.quizzes.start', $quiz) }}',
        answerUrl: '{{ route('student.quizzes.answer', ':attempt') }}',
        finishUrl: '{{ route('student.quizzes.finish', ':attempt') }}',
        csrfToken: '{{ csrf_token() }}',
        homeUrl: '{{ route('student.quizzes.index') }}'
    });
});
</script>
@endsection
