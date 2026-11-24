@extends('layouts.student')

@section('title', 'Permainan Edukasi | EduAutis')
@section('page_title', 'Permainan Edukasi')

@section('content')
<div style="max-width: 960px; margin: 0 auto;">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <a href="{{ route('student.home') }}" class="btn btn-secondary" style="font-size: 0.9375rem; font-weight: 700; padding: 10px 20px; border-radius: 999px;">
      &larr; Kembali ke Beranda
    </a>
    <span class="badge badge-success" style="font-size: 0.8125rem; padding: 6px 14px;">
      Permainan Aktif
    </span>
  </div>

  @if($games->isEmpty())
    <div class="card" style="text-align: center; padding: 48px 24px; max-width: 500px; margin: 0 auto;">
      <h2 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 8px;">Belum Ada Permainan</h2>
      <p style="color: var(--ink-soft); margin-bottom: 24px;">Bapak/Ibu guru akan menambahkan permainan edukasi di sini segera.</p>
      <a href="{{ route('student.home') }}" class="btn btn-primary" style="border-radius: 999px;">Kembali ke Menu Utama</a>
    </div>
  @else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
      @foreach($games as $game)
        <a href="{{ route('student.games.play', $game) }}" class="card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; padding: 24px; border-radius: 16px; border-top: 4px solid var(--success); transition: transform 0.2s ease, box-shadow 0.2s ease;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <span class="badge badge-neutral" style="font-size: 0.75rem;">
              {{ $game->category->name }}
            </span>
            <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--tint-green); color: var(--success); font-weight: 800; font-size: 1rem; display: grid; place-items: center;">
              {{ strtoupper(substr($game->game_type, 0, 1)) }}
            </div>
          </div>
          <h3 style="margin: 0 0 8px 0; font-size: 1.25rem; font-weight: 800; color: var(--text);">
            {{ $game->title }}
          </h3>
          <p style="margin: 0 0 20px 0; color: var(--ink-soft); font-size: 0.9375rem; line-height: 1.45; flex-grow: 1;">
            {{ $game->instruction_text }}
          </p>
          <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--line); padding-top: 14px; margin-top: auto;">
            <span style="font-size: 0.8125rem; color: var(--ink-soft); font-weight: 600;">
              {{ $game->round_count }} Ronde
            </span>
            <span style="color: var(--success); font-weight: 800; font-size: 0.9375rem;">
              Mulai Main &rarr;
            </span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
