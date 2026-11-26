@extends('layouts.student')

@section('title', $game->title . ' | Permainan Edukasi')
@section('page_title', $game->title)

@section('content')
<div class="st-page-shell st-page-shell--arena">
  {{-- Header Permainan: Tombol Kembali, Suara Efek, Skor & Indikator Ronde --}}
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.games.index') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali</span>
    </a>

    <div class="st-hud-group">
      <button type="button" id="btn-toggle-sfx" class="st-sfx-toggle" aria-pressed="true" title="Aktifkan atau matikan suara permainan">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
        </svg>
        <span id="sfx-toggle-label">Suara Aktif</span>
      </button>

      <div id="game-score-pill" class="st-topic-pill st-topic-pill--yellow" aria-live="polite">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="#F59E0B" stroke="#B45309" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
        <span>Skor: <strong id="game-score-val">0</strong></span>
      </div>

      <div class="st-progress-pill-wrap">
        <div id="game-progress-dots" class="st-progress-dots" aria-hidden="true"></div>
        <div id="game-progress" class="st-topic-pill st-topic-pill--green">
          Memuat ronde...
        </div>
      </div>
    </div>
  </div>

  {{-- Kartu Instruksi + Tombol Suara --}}
  <div class="st-arena-header-card">
    <div class="st-arena-instruction-wrap">
      <button id="btn-speak-instruction" type="button" class="st-speak-circle" title="Dengarkan Suara Instruksi" aria-label="Dengarkan Suara Instruksi">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
        </svg>
      </button>
      <div>
        <h2 id="game-instruction" class="st-arena-instruction">
          {{ $game->instruction_text }}
        </h2>
        <p class="st-arena-instruction-hint">Ketuk tombol biru untuk mendengar petunjuk</p>
      </div>
    </div>

    <span class="st-topic-pill st-topic-pill--blue">
      {{ $game->category->name }}
    </span>
  </div>

  {{-- Papan Arena Permainan Terpadu --}}
  <section class="st-arena-board" aria-label="Area permainan">
    <div id="game-feedback" class="st-arena-feedback st-arena-feedback--neutral" role="status" aria-live="polite">
      <span class="st-arena-feedback__icon" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="16" x2="12" y2="12"/>
          <line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>
      </span>
      <span id="game-feedback-text" class="st-arena-feedback__text">Siap bermain bersama? Ayo mulai!</span>
    </div>

    <div id="game-arena" class="st-arena-stage">
      <div class="st-arena-loading">
        Menyiapkan arena permainan...
      </div>
    </div>
  </section>
</div>

<script src="/js/games/game-engine.js?v={{ @filemtime(public_path('js/games/game-engine.js')) ?: '2' }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new GameEngine({
        gameId: {{ $game->id }},
        gameType: '{{ $game->game_type }}',
        categorySlug: '{{ $game->category?->slug ?? '' }}',
        roundsUrl: '{{ route('student.games.rounds', $game) }}',
        sessionUrl: '{{ route('student.games.session', $game) }}',
        csrfToken: '{{ csrf_token() }}',
        homeUrl: '{{ route('student.games.index') }}'
    });
});
</script>
@endsection
