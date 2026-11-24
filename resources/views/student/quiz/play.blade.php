@extends('layouts.student')

@section('title', $quiz->title)

@section('content')
<div class="container" style="max-width: 900px; padding-top: var(--space-6); padding-bottom: var(--space-10);">
    {{-- Header: Tombol Kembali & Progres --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
        <a href="{{ route('student.quizzes.index') }}" class="btn btn-secondary" style="font-size: 1.125rem; font-weight: 600; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
            <span>&larr;</span> Kembali
        </a>
        <div style="display: flex; align-items: center; gap: var(--space-4);">
            <div id="quiz-progress-dots" style="display: flex; gap: 6px; align-items: center;"></div>
            <div id="quiz-progress-text" class="badge" style="background: var(--surface); border: 2px solid var(--border); font-size: 1rem; font-weight: 700; padding: var(--space-2) var(--space-4); border-radius: var(--radius-full); color: var(--text);">
                Memuat soal...
            </div>
        </div>
    </div>

    {{-- Kartu Pertanyaan & Tombol Dengar --}}
    <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4); display: flex; align-items: center; justify-content: space-between; gap: var(--space-4); border-radius: var(--radius-lg); background: var(--surface);">
        <div style="display: flex; align-items: center; gap: var(--space-3);">
            <button id="btn-speak-prompt" type="button" class="btn btn-primary" style="min-width: 48px; width: 48px; height: 48px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" title="Dengarkan Pertanyaan">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
            </button>
            <h2 id="quiz-prompt-text" style="margin: 0; font-size: 1.375rem; font-weight: 700; color: var(--text);">
                Memuat pertanyaan...
            </h2>
        </div>
        <span class="badge" style="background: var(--surface-alt); font-size: 0.8125rem;">
            {{ $quiz->category?->name ?? 'Kuis' }}
        </span>
    </div>

    {{-- Arena Pilihan Jawaban Bergambar --}}
    <div id="quiz-arena" style="min-height: 380px;">
        <div style="text-align: center; padding: var(--space-10); color: var(--text-muted);">
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
