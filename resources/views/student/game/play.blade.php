@extends('layouts.student')

@section('title', $game->title . ' | Permainan Edukasi')
@section('page_title', $game->title)

@section('content')
<div class="st-page-shell" style="max-width: 900px;">
  {{-- Header Permainan: Tombol Kembali & Indikator Ronde --}}
  <div class="st-page-bar">
    <a href="{{ route('student.games.index') }}" class="st-back-pill">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      <span>Kembali</span>
    </a>

    <div id="game-progress" class="st-topic-pill st-topic-pill--green">
      Memuat ronde...
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
      <h2 id="game-instruction" class="st-arena-instruction">
        {{ $game->instruction_text }}
      </h2>
    </div>

    <span class="st-topic-pill st-topic-pill--blue">
      {{ $game->category->name }}
    </span>
  </div>

  {{-- Arena Permainan Interaktif --}}
  <div id="game-arena" style="min-height: 360px;">
    <div style="text-align: center; padding: 48px 20px; color: var(--ink-soft); font-weight: 600;">
      Menyiapkan arena permainan...
    </div>
  </div>
</div>

<script src="{{ asset('js/games/game-engine.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new GameEngine({
        gameId: {{ $game->id }},
        gameType: '{{ $game->game_type }}',
        roundsUrl: '{{ route('student.games.rounds', $game) }}',
        sessionUrl: '{{ route('student.games.session', $game) }}',
        csrfToken: '{{ csrf_token() }}',
        homeUrl: '{{ route('student.games.index') }}'
    });
});
</script>
@endsection
