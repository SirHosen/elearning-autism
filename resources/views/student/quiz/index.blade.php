@extends('layouts.student')

@section('title', 'Kuis Bergambar | Sahabat Belajar')
@section('page_title', 'Kuis Bergambar')

@section('content')
<div class="st-page-shell">
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali ke Beranda</span>
    </a>

    <span class="st-topic-pill st-topic-pill--yellow">
      Kuis Mandiri
    </span>
  </div>

  <div class="st-intro-banner st-anim st-anim--2">
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
      <a href="{{ route('student.home') }}" class="btn btn-student btn-primary st-pill-cta">
        @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-btn-arrow st-btn-arrow--left'])
        <span>Kembali ke Menu Utama</span>
      </a>
    </div>
  @else
    <div class="st-hub-grid st-anim st-anim--3">
      @foreach($quizzes as $quiz)
        @php
          $colorMod = match ($quiz->category?->color_key ?? 'yellow') {
              'blue' => 'blue',
              'green' => 'green',
              'lavender' => 'lavender',
              'peach' => 'peach',
              default => 'yellow',
          };
        @endphp
        <a href="{{ route('student.quizzes.play', $quiz) }}" class="st-hub-card st-hub-card--{{ $colorMod }}">
          <div class="st-hub-card__stage st-hub-card__stage--illustrated st-hub-card__stage--{{ $colorMod }}">
            @include('student.partials.game-art', [
                'categorySlug' => $quiz->category?->slug,
                'gameType' => 'pilih_benar',
            ])
            <div class="st-hub-card__stage-bar">
              <span class="st-hub-card__badge">{{ $quiz->category?->name ?? 'Umum' }}</span>
              <span class="st-hub-card__mode">Kuis</span>
            </div>
          </div>

          <h3 class="st-hub-card__title">{{ $quiz->title }}</h3>
          <p class="st-hub-card__desc">
            Jawab pertanyaan bergambar dengan tenang, satu soal setiap layar.
          </p>

          <div class="st-hub-card__footer">
            <span class="st-hub-card__meta">{{ $quiz->questions_count }} Soal Bergambar</span>
            <span class="st-hub-card__cta">
              <span>Mulai Kuis</span>
              @include('student.partials.icon-arrow', ['size' => 15])
            </span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
