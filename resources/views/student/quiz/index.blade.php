@extends('layouts.student')

@section('title', 'Kuis Bergambar | Sahabat Belajar')
@section('page_title', 'Kuis Bergambar')

@section('content')
<div class="st-page-shell">
  <div class="st-page-bar">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      <span>Kembali ke Beranda</span>
    </a>

    <span class="st-topic-pill st-topic-pill--yellow">
      Kuis Mandiri
    </span>
  </div>

  <div class="st-intro-banner">
    <div class="st-intro-banner__text">
      <h2>Kuis Bergambar Santai</h2>
      <p>Pilih kuis di bawah ini dan pilih gambar yang tepat tanpa batas waktu.</p>
    </div>
    <div class="st-intro-banner__art" style="background: var(--tint-yellow);" aria-hidden="true">
      <svg viewBox="0 0 80 80" width="60" height="60" fill="none">
        <rect x="18" y="14" width="44" height="54" rx="10" fill="#B45309"/>
        <rect x="24" y="22" width="32" height="40" rx="6" fill="#FFFFFF"/>
        <rect x="30" y="10" width="20" height="10" rx="5" fill="#FBBF24"/>
        <polygon points="40,30 43,36 50,37 45,42 46,49 40,45 34,49 35,42 30,37 37,36" fill="#FBBF24"/>
        <line x1="30" y1="54" x2="50" y2="54" stroke="#CBD5E1" stroke-width="3" stroke-linecap="round"/>
      </svg>
    </div>
  </div>

  @if($quizzes->isEmpty())
    <div class="st-empty-card">
      <h2 style="font-size: 1.375rem; font-weight: 700; margin: 0 0 8px;">Belum Ada Kuis</h2>
      <p style="color: var(--ink-soft); margin: 0 0 24px;">Bapak/Ibu guru akan menambahkan kuis bergambar di sini segera.</p>
      <a href="{{ route('student.home') }}" class="btn btn-student btn-primary" style="border-radius: 999px;">Kembali ke Menu Utama</a>
    </div>
  @else
    <div class="st-hub-grid">
      @foreach($quizzes as $quiz)
        <a href="{{ route('student.quizzes.play', $quiz) }}" class="st-hub-card st-hub-card--yellow">
          <div class="st-hub-card__stage st-hub-card__stage--yellow">
            <span class="st-hub-card__badge">{{ $quiz->category?->name ?? 'Umum' }}</span>

            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="108" rx="40" ry="7" fill="#E6D8AA"/>
              <rect x="28" y="24" width="72" height="78" rx="14" fill="#B45309"/>
              <rect x="35" y="34" width="58" height="60" rx="8" fill="#FFFFFF"/>
              <rect x="46" y="18" width="36" height="14" rx="7" fill="#FBBF24"/>
              <circle cx="52" cy="54" r="8" fill="#246B4D"/>
              <polyline points="48 54 51 57 57 51" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
              <line x1="66" y1="54" x2="84" y2="54" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round"/>
              <circle cx="52" cy="76" r="8" fill="#FCEFC7" stroke="#D97706" stroke-width="2.5"/>
              <line x1="66" y1="76" x2="84" y2="76" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round"/>
            </svg>
          </div>

          <h3 class="st-hub-card__title">{{ $quiz->title }}</h3>
          <p class="st-hub-card__desc">
            Jawab pertanyaan bergambar dengan tenang, satu soal setiap layar.
          </p>

          <div class="st-hub-card__footer">
            <span class="st-hub-card__meta">{{ $quiz->questions_count }} Soal Bergambar</span>
            <span class="st-hub-card__cta">Mulai Kuis &rarr;</span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
