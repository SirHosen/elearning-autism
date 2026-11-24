@extends('layouts.student')

@section('title', 'Permainan Edukasi')

@section('content')
<div class="container" style="padding-top: var(--space-6); padding-bottom: var(--space-10);">
    {{-- Header Siswa --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
        <a href="{{ route('student.home') }}" class="btn btn-secondary" style="font-size: 1.125rem; font-weight: 600; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
            <span>&larr;</span> Kembali
        </a>
        <h1 style="margin: 0; font-size: 1.75rem; color: var(--text);">Permainan Edukasi</h1>
    </div>

    @if($games->isEmpty())
        <div class="card" style="text-align: center; padding: var(--space-10); max-width: 500px; margin: 0 auto;">
            <div style="font-size: 3rem; margin-bottom: var(--space-2);">🎮</div>
            <h2 style="font-size: 1.25rem; margin-bottom: var(--space-2);">Belum Ada Permainan</h2>
            <p style="color: var(--text-muted); margin-bottom: var(--space-6);">Bapak/Ibu guru akan menambahkan permainan edukasi di sini segera.</p>
            <a href="{{ route('student.home') }}" class="btn btn-primary" style="border-radius: var(--radius-full);">Kembali ke Menu Utama</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: var(--space-6);">
            @foreach($games as $game)
                <a href="{{ route('student.games.play', $game) }}" class="card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; padding: var(--space-6); transition: transform var(--transition-base), box-shadow var(--transition-base); border-radius: var(--radius-lg); border-top: 6px solid var(--primary, #5A8DEE);" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-4);">
                        <span class="badge" style="background: var(--surface-alt); font-size: 0.8125rem;">
                            {{ $game->category->name }}
                        </span>
                        @if($game->game_type === 'pilih_benar')
                            <span style="font-size: 1.75rem;">🎯</span>
                        @elseif($game->game_type === 'cocokkan')
                            <span style="font-size: 1.75rem;">🧩</span>
                        @else
                            <span style="font-size: 1.75rem;">✋</span>
                        @endif
                    </div>
                    <h3 style="margin: 0 0 var(--space-2) 0; font-size: 1.25rem; font-weight: 700; color: var(--text);">
                        {{ $game->title }}
                    </h3>
                    <p style="margin: 0 0 var(--space-4) 0; color: var(--text-muted); font-size: 0.9375rem; flex-grow: 1;">
                        {{ $game->instruction_text }}
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: var(--space-3); margin-top: auto;">
                        <span style="font-size: 0.8125rem; color: var(--text-muted); font-weight: 500;">
                            {{ $game->round_count }} Ronde
                        </span>
                        <span style="color: var(--primary); font-weight: 700; font-size: 1rem; display: flex; align-items: center; gap: var(--space-1);">
                            Main &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
