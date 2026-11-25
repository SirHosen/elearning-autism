@extends('layouts.student')

@section('title', 'Permainan Edukasi | Sahabat Belajar')
@section('page_title', 'Permainan Edukasi')

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

    <span class="st-topic-pill st-topic-pill--green">
      Permainan Aktif
    </span>
  </div>

  <div class="st-intro-banner">
    <div class="st-intro-banner__text">
      <h2>Ayo Bermain Sambil Belajar</h2>
      <p>Pilih satu permainan di bawah ini dan ikuti petunjuk suara dengan santai.</p>
    </div>
    <div class="st-intro-banner__art" style="background: var(--tint-green);" aria-hidden="true">
      <svg viewBox="0 0 80 80" width="60" height="60" fill="none">
        <path d="M22 30 L58 30 L58 60 L22 60 Z" rx="8" fill="#246B4D"/>
        <circle cx="40" cy="24" r="9" fill="#FBBF24"/>
        <rect x="26" y="34" width="28" height="22" rx="6" fill="#FFFFFF"/>
        <polygon points="40,38 43,43 49,44 45,48 46,53 40,50 34,53 35,48 31,44 37,43" fill="#246B4D"/>
      </svg>
    </div>
  </div>

  @if($games->isEmpty())
    <div class="st-empty-card">
      <h2 style="font-size: 1.375rem; font-weight: 700; margin: 0 0 8px;">Belum Ada Permainan</h2>
      <p style="color: var(--ink-soft); margin: 0 0 24px;">Bapak/Ibu guru akan menambahkan permainan edukasi di sini segera.</p>
      <a href="{{ route('student.home') }}" class="btn btn-student btn-primary" style="border-radius: 999px;">Kembali ke Menu Utama</a>
    </div>
  @else
    <div class="st-hub-grid">
      @foreach($games as $game)
        <a href="{{ route('student.games.play', $game) }}" class="st-hub-card st-hub-card--green">
          <div class="st-hub-card__stage st-hub-card__stage--green">
            <span class="st-hub-card__badge">{{ $game->category->name }}</span>

            @if($game->game_type === 'cocokkan')
              <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
                <rect x="18" y="34" width="42" height="58" rx="12" fill="#FFFFFF" stroke="#246B4D" stroke-width="3.5"/>
                <circle cx="39" cy="56" r="11" fill="#FBBF24"/>
                <rect x="68" y="34" width="42" height="58" rx="12" fill="#246B4D"/>
                <line x1="77" y1="62" x2="101" y2="62" stroke="#FFFFFF" stroke-width="5" stroke-linecap="round"/>
                <circle cx="64" cy="63" r="8" fill="#E53935"/>
              </svg>
            @elseif($game->game_type === 'seret_tempatkan')
              <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
                <rect x="22" y="28" width="38" height="38" rx="10" fill="#2F6F9F"/>
                <polygon points="41,37 48,54 34,54" fill="#FFFFFF"/>
                <path d="M64 48 L78 58 L70 58 L70 72" stroke="#246B4D" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                <rect x="68" y="64" width="40" height="40" rx="10" fill="#FFFFFF" stroke="#246B4D" stroke-width="3.5" stroke-dasharray="6 4"/>
              </svg>
            @else
              <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
                <rect x="20" y="34" width="36" height="48" rx="10" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="3"/>
                <rect x="46" y="26" width="40" height="56" rx="12" fill="#FFFFFF" stroke="#246B4D" stroke-width="4"/>
                <polygon points="66,38 70,48 80,49 72,56 75,66 66,60 57,66 60,56 52,49 62,48" fill="#FBBF24"/>
                <circle cx="88" cy="78" r="14" fill="#2F6F9F"/>
                <polyline points="82 78 86 82 95 73" stroke="#FFFFFF" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            @endif
          </div>

          <h3 class="st-hub-card__title">{{ $game->title }}</h3>
          <p class="st-hub-card__desc">{{ $game->instruction_text }}</p>

          <div class="st-hub-card__footer">
            <span class="st-hub-card__meta">{{ $game->round_count }} Ronde</span>
            <span class="st-hub-card__cta">Mulai Main &rarr;</span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
