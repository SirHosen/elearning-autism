@extends('layouts.student')

@section('title', 'Permainan Edukasi | Sahabat Belajar')
@section('page_title', 'Permainan Edukasi')

@section('content')
<div class="st-page-shell">
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali ke Beranda</span>
    </a>

    <span class="st-topic-pill st-topic-pill--green">
      {{ $games->count() }} Permainan Aktif
    </span>
  </div>

  <div class="st-intro-banner st-anim st-anim--2">
    <div class="st-intro-banner__text">
      <h2>Ayo Bermain Sambil Belajar</h2>
      <p>Pilih satu permainan di bawah ini dan ikuti petunjuk suara dengan santai.</p>
    </div>
    <div class="st-intro-banner__art" style="background: var(--tint-green);" aria-hidden="true">
      <svg viewBox="0 0 80 80" width="60" height="60" fill="none">
        <rect x="12" y="24" width="56" height="36" rx="16" fill="#246B4D"/>
        <circle cx="28" cy="42" r="8" fill="#DDF0E8"/>
        <line x1="28" y1="38" x2="28" y2="46" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round"/>
        <line x1="24" y1="42" x2="32" y2="42" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round"/>
        <circle cx="49" cy="38" r="3.5" fill="#FBBF24"/>
        <circle cx="56" cy="45" r="3.5" fill="#93C5FD"/>
      </svg>
    </div>
  </div>

  @if($games->isEmpty())
    <div class="st-empty-card st-anim st-anim--3">
      <h2 style="font-size: 1.375rem; font-weight: 700; margin: 0 0 8px;">Belum Ada Permainan</h2>
      <p style="color: var(--ink-soft); margin: 0 0 24px;">Bapak/Ibu guru akan menambahkan permainan edukasi di sini segera.</p>
      <a href="{{ route('student.home') }}" class="btn btn-student btn-primary st-pill-cta">
        @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-btn-arrow st-btn-arrow--left'])
        <span>Kembali ke Menu Utama</span>
      </a>
    </div>
  @else
    <div class="st-hub-grid st-anim st-anim--3">
      @foreach($games as $game)
        @php
          $colorMod = match ($game->category?->color_key ?? 'green') {
              'blue' => 'blue',
              'yellow' => 'yellow',
              'lavender' => 'lavender',
              'peach' => 'peach',
              default => 'green',
          };
          $typeLabel = match ($game->game_type) {
              'cocokkan' => 'Cocokkan',
              'seret_tempatkan' => 'Tarik & Susun',
              default => 'Pilih Benar',
          };
        @endphp
        <a href="{{ route('student.games.play', $game) }}" class="st-hub-card st-hub-card--{{ $colorMod }}">
          <div class="st-hub-card__stage st-hub-card__stage--illustrated st-hub-card__stage--{{ $colorMod }}">
            @include('student.partials.game-art', [
                'categorySlug' => $game->category?->slug,
                'gameType' => $game->game_type,
            ])
            <div class="st-hub-card__stage-bar">
              <span class="st-hub-card__badge">{{ $game->category?->name ?? 'Umum' }}</span>
              <span class="st-hub-card__mode">{{ $typeLabel }}</span>
            </div>
          </div>

          <h3 class="st-hub-card__title">{{ $game->title }}</h3>
          <p class="st-hub-card__desc">{{ $game->instruction_text }}</p>

          <div class="st-hub-card__footer">
            <span class="st-hub-card__meta">{{ $game->round_count }} Ronde &bull; {{ $game->option_count }} Pilihan</span>
            <span class="st-hub-card__cta">
              <span>Mulai Main</span>
              @include('student.partials.icon-arrow', ['size' => 15])
            </span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
