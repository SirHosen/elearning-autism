@extends('layouts.student')

@section('title', $game->title)

@section('content')
<div class="container" style="max-width: 900px; padding-top: var(--space-6); padding-bottom: var(--space-10);">
    {{-- Header Permainan: Tombol Kembali & Indikator Ronde --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
        <a href="{{ route('student.games.index') }}" class="btn btn-secondary" style="font-size: 1.125rem; font-weight: 600; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
            <span>&larr;</span> Kembali
        </a>
        <div id="game-progress" class="badge" style="background: var(--surface); border: 2px solid var(--border); font-size: 1.125rem; font-weight: 700; padding: var(--space-2) var(--space-5); border-radius: var(--radius-full); color: var(--text);">
            Memuat ronde...
        </div>
    </div>

    {{-- Kartu Instruksi --}}
    <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4); display: flex; align-items: center; justify-content: space-between; gap: var(--space-4); border-radius: var(--radius-lg); background: var(--surface);">
        <div style="display: flex; align-items: center; gap: var(--space-3);">
            <button id="btn-speak-instruction" type="button" class="btn btn-primary" style="min-width: 48px; width: 48px; height: 48px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" title="Dengarkan Suara Instruksi">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
            </button>
            <h2 id="game-instruction" style="margin: 0; font-size: 1.375rem; font-weight: 700; color: var(--text);">
                {{ $game->instruction_text }}
            </h2>
        </div>
        <span class="badge" style="background: var(--surface-alt); font-size: 0.8125rem;">
            {{ $game->category->name }}
        </span>
    </div>

    {{-- Arena Permainan Interaktif --}}
    <div id="game-arena" style="min-height: 380px;">
        <div style="text-align: center; padding: var(--space-10); color: var(--text-muted);">
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
