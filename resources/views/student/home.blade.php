@extends('layouts.student')

@section('title', 'Ruang Belajar | Sahabat Belajar')
@section('page_title', 'Ruang Belajar')

@push('styles')
<style>
  /* ── Greeting Welcome Card ── */
  .home-welcome {
    background: var(--surface);
    border: var(--border) solid var(--line);
    border-radius: 24px;
    padding: 24px 28px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 4px 16px rgba(31, 41, 51, 0.04);
  }

  .home-welcome__meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
  }

  .home-school-pill {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    background: var(--surface-alt);
    color: var(--ink-soft);
    font-size: 0.8125rem;
    font-weight: 700;
  }

  .home-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
  }

  .home-status-pill::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
  }

  .home-status-pill--done {
    background: var(--success-bg);
    color: var(--success);
  }

  .home-status-pill--pending {
    background: var(--hint-bg);
    color: #78350F;
    border: 1px solid rgba(201, 154, 46, 0.4);
  }

  :root[data-theme="contrast"] .home-status-pill--pending {
    color: var(--hint-line);
    border-color: var(--hint-line);
  }

  .home-status-pill--pending:hover {
    background: #FCEFC7;
  }

  .home-greeting {
    font-size: clamp(1.5rem, 3.2vw, 2.125rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.025em;
    margin: 0 0 4px;
    line-height: 1.2;
  }

  .home-subtitle {
    color: var(--ink-soft);
    font-size: calc(1.0625rem * var(--font-scale));
    margin: 0;
    line-height: 1.5;
  }

  /* Tactile Voice Welcome Button (Tap-only, manual playback) */
  .home-voice-btn {
    flex-shrink: 0;
    min-height: 64px;
    padding: 0 24px;
    border-radius: 18px;
    border: var(--border) solid var(--primary);
    border-bottom-width: 4px;
    background: var(--tint-blue);
    color: var(--primary-strong);
    font-size: calc(1.0625rem * var(--font-scale));
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition:
      background-color var(--dur) var(--ease),
      transform var(--dur) var(--ease);
  }

  .home-voice-btn:hover {
    background: #CEE3F5;
  }

  .home-voice-btn:active {
    transform: translateY(2px);
    border-bottom-width: 2px;
  }

  .home-voice-btn.is-playing {
    background: var(--primary);
    color: var(--on-primary);
  }

  .home-voice-bars {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    height: 18px;
  }

  .home-voice-bars span {
    width: 3px;
    border-radius: 2px;
    background: currentColor;
  }

  .home-voice-bars span:nth-child(1) { height: 8px; }
  .home-voice-bars span:nth-child(2) { height: 16px; }
  .home-voice-bars span:nth-child(3) { height: 11px; }

  /* ── Symmetrical 2 + 3 Activity Bento Grid ── */
  .home-bento {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 20px;
  }

  .act-card {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 24px;
    border-radius: 24px;
    border: var(--border) solid var(--line);
    border-bottom-width: 5px;
    text-decoration: none;
    color: var(--ink);
    overflow: hidden;
    transition:
      transform var(--dur) var(--ease),
      box-shadow var(--dur) var(--ease),
      border-color var(--dur) var(--ease);
  }

  .act-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 28px -8px rgba(31, 41, 51, 0.12);
  }

  .act-card:active {
    transform: translateY(2px);
    border-bottom-width: 2px;
  }

  .act-card--featured {
    grid-column: span 3;
    flex-direction: row;
    align-items: center;
    gap: 20px;
    min-height: 210px;
  }

  .act-card--compact {
    grid-column: span 2;
    min-height: 230px;
  }

  /* Pastel Montessori Activity Tints (adapt to [data-theme="contrast"] automatically) */
  .act-card--blue {
    background-color: var(--tint-blue);
    border-color: #B4D2EA;
  }
  .act-card--blue:hover { border-color: var(--primary); }

  .act-card--green {
    background-color: var(--tint-green);
    border-color: #B0DBC3;
  }
  .act-card--green:hover { border-color: var(--success); }

  .act-card--yellow {
    background-color: var(--tint-yellow);
    border-color: #EAD087;
  }
  .act-card--yellow:hover { border-color: #B45309; }

  .act-card--lavender {
    background-color: var(--tint-lavender);
    border-color: #CEC2EE;
  }
  .act-card--lavender:hover { border-color: #6D28D9; }

  .act-card--peach {
    background-color: var(--tint-peach);
    border-color: #F0C2A4;
  }
  .act-card--peach:hover { border-color: #C2410C; }

  .act-card--attended {
    border-color: var(--success);
  }

  :root[data-theme="contrast"] .act-card {
    background-color: var(--surface);
    border-color: var(--line);
    border-width: 3px;
  }

  :root[data-theme="contrast"] .act-card:hover {
    border-color: var(--primary);
  }

  .act-card__info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    flex: 1;
    min-width: 0;
  }

  .act-card__title {
    font-size: calc(1.5rem * var(--font-scale));
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.02em;
    line-height: 1.2;
  }

  .act-card__desc {
    font-size: calc(0.9375rem * var(--font-scale));
    color: var(--ink-soft);
    line-height: 1.45;
  }

  .act-card__pill {
    margin-top: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 36px;
    padding: 0 14px;
    border-radius: 999px;
    background: var(--surface);
    color: var(--ink);
    font-size: 0.875rem;
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(31, 41, 51, 0.06);
  }

  :root[data-theme="contrast"] .act-card__pill {
    background: var(--primary);
    color: var(--on-primary);
  }

  .act-card--blue:hover .act-card__pill { background: var(--primary); color: #FFFFFF; }
  .act-card--green:hover .act-card__pill { background: var(--success); color: #FFFFFF; }
  .act-card--yellow:hover .act-card__pill { background: #B45309; color: #FFFFFF; }
  .act-card--lavender:hover .act-card__pill { background: #6D28D9; color: #FFFFFF; }
  .act-card--peach:hover .act-card__pill { background: #C2410C; color: #FFFFFF; }

  :root[data-theme="contrast"] .act-card:hover .act-card__pill {
    background: var(--primary-strong);
    color: var(--on-primary);
  }

  /* Montessori Flat Picture-Book Artwork Frame */
  .act-card__art {
    flex-shrink: 0;
    display: grid;
    place-items: center;
  }

  .act-card--featured .act-card__art {
    width: 152px;
    height: 128px;
  }

  .act-card--compact .act-card__art {
    width: 100%;
    height: 100px;
    justify-items: start;
    margin-bottom: 14px;
  }

  .act-card__art svg {
    width: 100%;
    height: 100%;
    max-width: 152px;
  }

  .act-badge-done {
    position: absolute;
    top: 16px;
    right: 16px;
    background-color: var(--success);
    color: var(--on-success);
    font-size: 0.8125rem;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(36, 107, 77, 0.25);
  }

  .act-badge-done::before {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
  }

  /* Respect user motion preference off */
  :root[data-motion="off"] .act-card:hover {
    transform: none;
  }

  @media (prefers-reduced-motion: reduce) {
    .act-card:hover {
      transform: none;
    }
  }

  /* ── Responsive Tablet (580px - 899px) ── */
  @media (max-width: 899px) {
    .home-bento {
      grid-template-columns: repeat(2, 1fr);
    }

    .act-card--featured,
    .act-card--compact {
      grid-column: span 1;
      flex-direction: column;
      align-items: flex-start;
      min-height: 210px;
    }

    .act-card--featured .act-card__art {
      width: 100%;
      height: 100px;
      justify-items: start;
      margin-bottom: 10px;
    }

    .act-card--featured .act-card__info {
      order: 2;
    }

    .act-card--featured .act-card__art {
      order: 1;
    }

    /* Make 5th card ABSEN span full 2-column width on tablet cleanly */
    .act-card--absen {
      grid-column: span 2;
      flex-direction: row;
      align-items: center;
      min-height: 160px;
    }

    .act-card--absen .act-card__art {
      width: 140px;
      margin-bottom: 0;
      order: 2;
    }

    .act-card--absen .act-card__info {
      order: 1;
    }
  }

  /* ── Responsive Mobile (< 580px) ── */
  @media (max-width: 579px) {
    .home-welcome {
      flex-direction: column;
      align-items: flex-start;
      padding: 20px;
    }

    .home-voice-btn {
      width: 100%;
      justify-content: center;
    }

    .home-bento {
      grid-template-columns: 1fr;
      gap: 14px;
    }

    .act-card--featured,
    .act-card--compact,
    .act-card--absen {
      grid-column: span 1;
      flex-direction: row;
      align-items: center;
      min-height: 132px;
      padding: 18px;
      gap: 14px;
    }

    .act-card__info {
      order: 1 !important;
    }

    .act-card__art {
      order: 2 !important;
      width: 96px !important;
      height: 82px !important;
      margin-bottom: 0 !important;
    }

    .act-badge-done {
      position: static;
      margin-bottom: 6px;
    }
  }
</style>
@endpush

@section('content')
{{-- 1. KARTU SAPAAN & PENGENDALI SUARA --}}
<section class="home-welcome" aria-label="Sapaan siswa">
  <div class="home-welcome__text">
    <div class="home-welcome__meta">
      <span class="home-school-pill">SLB B-C Wijayakusumah</span>
      @if ($hasAttendedToday)
        <span class="home-status-pill home-status-pill--done">Sudah Hadir Hari Ini</span>
      @else
        <a href="{{ route('student.attendance.index') }}" class="home-status-pill home-status-pill--pending">
          Belum Absen &middot; Ketuk di sini
        </a>
      @endif
    </div>
    <h2 class="home-greeting">Halo, {{ $user->name }}!</h2>
    <p class="home-subtitle">Pilih kegiatan yang mau kamu lakukan hari ini.</p>
  </div>

  <button
    type="button"
    id="btnHomeVoice"
    class="home-voice-btn"
    aria-pressed="false"
    data-greeting="Halo {{ $user->name }}! Pilih kegiatan yang mau kamu lakukan hari ini: Belajar, Bermain, Kuis, Video, atau Absen."
    data-volume="{{ ($setting?->volume ?? 70) / 100 }}"
    title="Dengarkan sapaan">
    <span class="home-voice-bars" aria-hidden="true">
      <span></span><span></span><span></span>
    </span>
    <span id="homeVoiceText">Dengar</span>
  </button>
</section>

{{-- 2. MENU KEGIATAN UTAMA (2 HERO ATAS + 3 KARTU BAWAH) --}}
<div class="home-bento">

  {{-- 1. BELAJAR (Picture Book Flat Montessori Illustration - Non-Anime) --}}
  <a href="{{ route('student.learn.index') }}" class="act-card act-card--featured act-card--blue">
    <div class="act-card__info">
      <span class="act-card__title">BELAJAR</span>
      <span class="act-card__desc">Kenali hewan, warna, dan benda sekitar</span>
      <span class="act-card__pill">Buka &rarr;</span>
    </div>
    <div class="act-card__art" aria-hidden="true">
      <svg viewBox="0 0 160 128" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Latar kertas hangat -->
        <rect x="12" y="20" width="136" height="92" rx="16" fill="#2F6F9F"/>
        <!-- Halaman Kiri (Gambar alam sederhana) -->
        <path d="M20 26 H76 V102 H26 C22 102 20 100 20 96 V26 Z" fill="#FFFFFF"/>
        <rect x="28" y="34" width="40" height="36" rx="8" fill="#DCEBF7"/>
        <circle cx="56" cy="45" r="6" fill="#F59E0B"/>
        <path d="M28 64 C36 52 46 52 54 64 L68 54 L68 70 H28 Z" fill="#246B4D"/>
        <rect x="28" y="78" width="38" height="6" rx="3" fill="#CBD5E1"/>
        <rect x="28" y="88" width="26" height="6" rx="3" fill="#E2E8F0"/>
        <!-- Halaman Kanan (Kartu huruf / kata) -->
        <path d="M84 26 H140 V96 C140 100 138 102 134 102 H84 V26 Z" fill="#FBF8F3"/>
        <rect x="92" y="36" width="38" height="22" rx="6" fill="#FCEFC7"/>
        <circle cx="103" cy="47" r="5" fill="#EA580C"/>
        <rect x="112" y="44" width="12" height="6" rx="3" fill="#D97706"/>
        <rect x="92" y="66" width="38" height="6" rx="3" fill="#CBD5E1"/>
        <rect x="92" y="78" width="32" height="6" rx="3" fill="#CBD5E1"/>
        <rect x="92" y="90" width="22" height="6" rx="3" fill="#CBD5E1"/>
        <!-- Pembatas Buku -->
        <path d="M122 16 H134 V40 L128 34 L122 40 V16 Z" fill="#E05D44"/>
      </svg>
    </div>
  </a>

  {{-- 2. BERMAIN (Montessori Wooden Stacking Toy Blocks - Non-Anime) --}}
  <a href="{{ route('student.games.index') }}" class="act-card act-card--featured act-card--green">
    <div class="act-card__info">
      <span class="act-card__title">BERMAIN</span>
      <span class="act-card__desc">Permainan tebak gambar seru</span>
      <span class="act-card__pill">Mainkan &rarr;</span>
    </div>
    <div class="act-card__art" aria-hidden="true">
      <svg viewBox="0 0 160 128" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Alas meja kayu -->
        <rect x="14" y="102" width="132" height="12" rx="6" fill="#246B4D" opacity="0.22"/>
        <!-- Balok Bawah Hijau -->
        <rect x="24" y="68" width="66" height="34" rx="10" fill="#246B4D"/>
        <circle cx="42" cy="85" r="7" fill="#DDF0E8"/>
        <rect x="56" y="79" width="24" height="12" rx="6" fill="#DDF0E8"/>
        <!-- Balok Tengah Biru -->
        <rect x="32" y="38" width="52" height="28" rx="9" fill="#2F6F9F"/>
        <rect x="44" y="47" width="28" height="10" rx="5" fill="#DCEBF7"/>
        <!-- Balok Atap Segitiga Kuning -->
        <path d="M58 14 L82 36 H34 L58 14 Z" fill="#F59E0B" stroke="#D97706" stroke-width="2" stroke-linejoin="round"/>
        <!-- Bola & Cincin Susun Kanan -->
        <rect x="102" y="84" width="38" height="18" rx="9" fill="#EA580C"/>
        <rect x="107" y="66" width="28" height="16" rx="8" fill="#F59E0B"/>
        <circle cx="121" cy="50" r="13" fill="#2F6F9F"/>
        <circle cx="117" cy="46" r="4" fill="#DCEBF7"/>
      </svg>
    </div>
  </a>

  {{-- 3. KUIS (Picture Card Quiz + Golden Stars - Non-Anime) --}}
  <a href="{{ route('student.quizzes.index') }}" class="act-card act-card--compact act-card--yellow">
    <div class="act-card__art" aria-hidden="true">
      <svg viewBox="0 0 148 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Kartu Kuis -->
        <rect x="10" y="32" width="128" height="60" rx="14" fill="#FFFFFF" stroke="#D97706" stroke-width="2.5"/>
        <!-- Pilihan Kiri -->
        <rect x="20" y="42" width="48" height="40" rx="9" fill="#F3EEE5"/>
        <circle cx="44" cy="62" r="10" fill="#94A3B8"/>
        <!-- Pilihan Kanan Benar (Centang Hijau) -->
        <rect x="78" y="42" width="50" height="40" rx="9" fill="#E3F2EA" stroke="#246B4D" stroke-width="2"/>
        <path d="M95 62 L101 68 L113 55" stroke="#246B4D" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
        <!-- Tiga Bintang Hadiah -->
        <polygon points="44,10 47.5,17 55,18 49.5,23.5 51,31 44,27.5 37,31 38.5,23.5 33,18 40.5,17" fill="#F59E0B"/>
        <polygon points="74,4 78.5,13 88,14.5 81,21 82.5,30.5 74,26 65.5,30.5 67,21 60,14.5 69.5,13" fill="#F59E0B"/>
        <polygon points="104,10 107.5,17 115,18 109.5,23.5 111,31 104,27.5 97,31 98.5,23.5 93,18 100.5,17" fill="#F59E0B"/>
      </svg>
    </div>
    <div class="act-card__info">
      <span class="act-card__title">KUIS</span>
      <span class="act-card__desc">Latihan kuis bergambar</span>
      <span class="act-card__pill">Mulai &rarr;</span>
    </div>
  </a>

  {{-- 4. VIDEO (Cozy Pastel Tablet Cinema - Non-Anime) --}}
  <a href="{{ route('student.videos.index') }}" class="act-card act-card--compact act-card--lavender">
    <div class="act-card__art" aria-hidden="true">
      <svg viewBox="0 0 148 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Bingkai Tablet / Layar -->
        <rect x="12" y="10" width="124" height="80" rx="16" fill="#6D28D9"/>
        <!-- Layar Cerah -->
        <rect x="20" y="18" width="108" height="64" rx="10" fill="#DCEBF7"/>
        <!-- Pemandangan Bukti Tenang di Layar -->
        <circle cx="106" cy="34" r="9" fill="#F59E0B"/>
        <path d="M20 68 C42 50 64 50 86 68 L112 52 L128 64 V82 H20 Z" fill="#246B4D" opacity="0.85"/>
        <!-- Tombol Putar Tengah -->
        <circle cx="74" cy="50" r="18" fill="#FFFFFF"/>
        <polygon points="69,41 85,50 69,59" fill="#6D28D9"/>
      </svg>
    </div>
    <div class="act-card__info">
      <span class="act-card__title">VIDEO</span>
      <span class="act-card__desc">Tonton video pembelajaran</span>
      <span class="act-card__pill">Tonton &rarr;</span>
    </div>
  </a>

  {{-- 5. ABSEN (Warm Morning Calendar + Check Badge - Non-Anime) --}}
  <a href="{{ route('student.attendance.index') }}" class="act-card act-card--compact act-card--peach act-card--absen {{ $hasAttendedToday ? 'act-card--attended' : '' }}">
    @if ($hasAttendedToday)
      <span class="act-badge-done">Sudah Hadir</span>
    @endif
    <div class="act-card__art" aria-hidden="true">
      <svg viewBox="0 0 148 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Badan Kalender -->
        <rect x="18" y="16" width="96" height="76" rx="14" fill="#FFFFFF" stroke="#EA580C" stroke-width="2.5"/>
        <!-- Kepala Kalender Peach/Oranye -->
        <path d="M18 30 C18 22 24 16 32 16 H100 C108 16 114 22 114 30 V38 H18 V30 Z" fill="#EA580C"/>
        <!-- Ring Pengikat -->
        <rect x="42" y="8" width="10" height="18" rx="5" fill="#1F2933"/>
        <rect x="80" y="8" width="10" height="18" rx="5" fill="#1F2933"/>
        <!-- Kotak Tanggal -->
        <rect x="30" y="48" width="18" height="14" rx="4" fill="#FBE3D6"/>
        <rect x="57" y="48" width="18" height="14" rx="4" fill="#FBE3D6"/>
        <rect x="84" y="48" width="18" height="14" rx="4" fill="#FBE3D6"/>
        <rect x="30" y="68" width="18" height="14" rx="4" fill="#FBE3D6"/>
        <rect x="57" y="68" width="18" height="14" rx="4" fill="#FBE3D6"/>
        <!-- Lencana Centang Hadir Hijau -->
        <circle cx="110" cy="70" r="22" fill="#246B4D" stroke="#FFFFFF" stroke-width="3"/>
        <path d="M101 70 L107 76 L119 63" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <div class="act-card__info">
      <span class="act-card__title">ABSEN</span>
      <span class="act-card__desc">
        {{ $hasAttendedToday ? 'Kehadiranmu hari ini tercatat' : 'Tekan untuk mencatat kehadiranmu' }}
      </span>
      <span class="act-card__pill">
        {{ $hasAttendedToday ? 'Lihat Status' : 'Saya Hadir' }} &rarr;
      </span>
    </div>
  </a>

</div>
@endsection

@push('scripts')
<script>
  const btnHomeVoice = document.getElementById('btnHomeVoice');
  const homeVoiceText = document.getElementById('homeVoiceText');

  if (btnHomeVoice) {
    const studentVolume = parseFloat(btnHomeVoice.dataset.volume || '0.7');
    const greeting = btnHomeVoice.dataset.greeting || 'Halo! Ayo mulai belajar hari ini.';

    btnHomeVoice.addEventListener('click', () => {
      if (!('speechSynthesis' in window)) return;

      if (btnHomeVoice.classList.contains('is-playing')) {
        window.speechSynthesis.cancel();
        btnHomeVoice.classList.remove('is-playing');
        btnHomeVoice.setAttribute('aria-pressed', 'false');
        homeVoiceText.textContent = 'Dengar';
        return;
      }

      window.speechSynthesis.cancel();
      const utter = new SpeechSynthesisUtterance(greeting);
      utter.lang = 'id-ID';
      utter.rate = 0.85;
      utter.volume = Math.max(0, Math.min(1, studentVolume));

      utter.onstart = () => {
        btnHomeVoice.classList.add('is-playing');
        btnHomeVoice.setAttribute('aria-pressed', 'true');
        homeVoiceText.textContent = 'Stop';
      };

      utter.onend = utter.onerror = () => {
        btnHomeVoice.classList.remove('is-playing');
        btnHomeVoice.setAttribute('aria-pressed', 'false');
        homeVoiceText.textContent = 'Dengar';
      };

      window.speechSynthesis.speak(utter);
    });
  }
</script>
@endpush
