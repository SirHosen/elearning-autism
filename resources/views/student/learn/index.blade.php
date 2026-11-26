@extends('layouts.student')

@section('title', 'Pilih Materi Belajar | Sahabat Belajar')
@section('page_title', 'Belajar')

@section('content')
<div class="st-page-shell">
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali ke Beranda</span>
    </a>

    <span class="st-topic-pill st-topic-pill--blue">
      Kartu Gambar &amp; Suara
    </span>
  </div>

  <div class="st-intro-banner st-anim st-anim--2">
    <div class="st-intro-banner__text">
      <h2>Pilih Kategori Belajar</h2>
      <p>Tekan satu kartu di bawah ini untuk mengenal gambar dan mendengarkan suaranya.</p>
    </div>
    <div class="st-intro-banner__art" style="background: var(--tint-blue);" aria-hidden="true">
      <svg viewBox="0 0 80 80" width="60" height="60" fill="none">
        <rect x="10" y="18" width="60" height="44" rx="10" fill="#2F6F9F"/>
        <path d="M14 22 C26 18 35 20 40 26 L40 58 C34 54 24 54 14 58 Z" fill="#FFFFFF"/>
        <path d="M66 22 C54 18 45 20 40 26 L40 58 C46 54 56 54 66 58 Z" fill="#F1F5F9"/>
        <circle cx="53" cy="38" r="7" fill="#F59E0B"/>
        <line x1="20" y1="34" x2="34" y2="34" stroke="#94A3B8" stroke-width="3" stroke-linecap="round"/>
        <line x1="20" y1="43" x2="32" y2="43" stroke="#94A3B8" stroke-width="3" stroke-linecap="round"/>
      </svg>
    </div>
  </div>

  <div class="st-hub-grid">
    @forelse ($categories as $cat)
      @php
        $colorKey = $cat->color_key ?? 'blue';
        $stageMod = match ($colorKey) {
            'green' => 'green',
            'yellow' => 'yellow',
            'lavender' => 'lavender',
            'peach' => 'peach',
            default => 'blue',
        };
      @endphp
      <a href="{{ route('student.learn.category', $cat->slug) }}" class="st-hub-card st-hub-card--{{ $stageMod }}">
        <div class="st-hub-card__stage st-hub-card__stage--{{ $stageMod }}">
          <span class="st-hub-card__badge">Topik {{ $loop->iteration }}</span>

          @if ($cat->slug === 'hewan')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="106" rx="40" ry="8" fill="#BBD8EC"/>
              <polygon points="34,54 38,24 58,42" fill="#E08E45"/>
              <polygon points="94,54 90,24 70,42" fill="#E08E45"/>
              <polygon points="38,50 42,32 54,44" fill="#F9A8B8"/>
              <polygon points="90,50 86,32 74,44" fill="#F9A8B8"/>
              <ellipse cx="64" cy="70" rx="38" ry="32" fill="#E08E45"/>
              <ellipse cx="64" cy="80" rx="22" ry="16" fill="#FFF3E4"/>
              <circle cx="50" cy="64" r="5" fill="#1F2933"/>
              <circle cx="78" cy="64" r="5" fill="#1F2933"/>
              <circle cx="51.5" cy="62.5" r="1.6" fill="#FFFFFF"/>
              <circle cx="79.5" cy="62.5" r="1.6" fill="#FFFFFF"/>
              <polygon points="60,74 68,74 64,79" fill="#E56B8B"/>
              <path d="M56 83 Q60 88 64 81 Q68 88 72 83" stroke="#5C3210" stroke-width="2.6" stroke-linecap="round"/>
              <path d="M40 74 L22 70 M40 80 L22 82 M88 74 L106 70 M88 80 L106 82" stroke="#5C3210" stroke-width="2.2" stroke-linecap="round"/>
            </svg>
          @elseif ($cat->slug === 'warna')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="108" rx="42" ry="7" fill="#D5E3CE"/>
              <path d="M64 24 C92 24 108 42 108 66 C108 86 92 100 68 100 C56 100 50 92 52 84 C54 78 46 74 38 78 C24 84 18 72 18 58 C18 38 38 24 64 24 Z" fill="#F4D7AE" stroke="#C59862" stroke-width="4"/>
              <circle cx="44" cy="48" r="9" fill="#E53935"/>
              <circle cx="68" cy="40" r="9" fill="#FBBF24"/>
              <circle cx="88" cy="54" r="9" fill="#2F6F9F"/>
              <circle cx="82" cy="78" r="9" fill="#246B4D"/>
              <circle cx="34" cy="66" r="6" fill="#FFFFFF" stroke="#C59862" stroke-width="2"/>
            </svg>
          @elseif ($cat->slug === 'benda')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="106" rx="42" ry="7" fill="#E5D8B1"/>
              <rect x="20" y="54" width="50" height="44" rx="10" fill="#2F6F9F"/>
              <rect x="25" y="58" width="20" height="36" rx="4" fill="#FFFFFF"/>
              <rect x="46" y="58" width="19" height="36" rx="4" fill="#F1F5F9"/>
              <circle cx="84" cy="72" r="28" fill="#FFFDF9" stroke="#CBD5E1" stroke-width="3"/>
              <path d="M84 44 A28 28 0 0 1 111 66 Q97 72 84 68 Q71 72 57 66 A28 28 0 0 1 84 44 Z" fill="#E53935"/>
              <circle cx="84" cy="72" r="9" fill="#FBBF24"/>
            </svg>
          @elseif ($cat->slug === 'huruf')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="108" rx="42" ry="7" fill="#D5CFE8"/>
              <rect x="20" y="48" width="44" height="44" rx="10" transform="rotate(-6 42 70)" fill="#FBBF24" stroke="#B45309" stroke-width="3"/>
              <text x="41" y="79" text-anchor="middle" font-family="Lexend, sans-serif" font-weight="700" font-size="24" fill="#78350F">A</text>
              <rect x="64" y="42" width="44" height="44" rx="10" transform="rotate(6 86 64)" fill="#2F6F9F" stroke="#1E4B6D" stroke-width="3"/>
              <text x="86" y="73" text-anchor="middle" font-family="Lexend, sans-serif" font-weight="700" font-size="24" fill="#FFFFFF">B</text>
            </svg>
          @elseif ($cat->slug === 'angka')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="108" rx="42" ry="7" fill="#EBD0C2"/>
              <rect x="24" y="34" width="80" height="62" rx="10" stroke="#78350F" stroke-width="5" fill="#FFF8EE"/>
              <line x1="26" y1="52" x2="102" y2="52" stroke="#9A7B65" stroke-width="3"/>
              <line x1="26" y1="74" x2="102" y2="74" stroke="#9A7B65" stroke-width="3"/>
              <circle cx="44" cy="52" r="7" fill="#E53935"/>
              <circle cx="62" cy="52" r="7" fill="#FBBF24"/>
              <circle cx="52" cy="74" r="7" fill="#246B4D"/>
              <circle cx="70" cy="74" r="7" fill="#2F6F9F"/>
              <circle cx="88" cy="74" r="7" fill="#2F6F9F"/>
            </svg>
          @elseif ($cat->slug === 'musik-nada')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="108" rx="42" ry="7" fill="#D5CFE8"/>
              <rect x="22" y="84" width="84" height="12" rx="6" fill="#92400E"/>
              <rect x="26" y="42" width="16" height="44" rx="5" fill="#E53935"/>
              <rect x="46" y="48" width="16" height="38" rx="5" fill="#FBBF24"/>
              <rect x="66" y="54" width="16" height="32" rx="5" fill="#246B4D"/>
              <rect x="86" y="60" width="16" height="26" rx="5" fill="#2F6F9F"/>
              <circle cx="82" cy="34" r="7" fill="#6D28D9"/>
              <line x1="88" y1="34" x2="88" y2="16" stroke="#6D28D9" stroke-width="3.5" stroke-linecap="round"/>
              <path d="M88 16 Q98 16 98 24" stroke="#6D28D9" stroke-width="3.5" stroke-linecap="round" fill="none"/>
            </svg>
          @elseif ($cat->slug === 'matematika')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="108" rx="42" ry="7" fill="#CDE3D6"/>
              <rect x="20" y="30" width="38" height="38" rx="10" fill="#246B4D"/>
              <line x1="39" y1="40" x2="39" y2="58" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round"/>
              <line x1="30" y1="49" x2="48" y2="49" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round"/>
              <rect x="70" y="30" width="38" height="38" rx="10" fill="#2F6F9F"/>
              <line x1="80" y1="49" x2="98" y2="49" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round"/>
              <rect x="20" y="76" width="88" height="24" rx="10" fill="#FFFFFF" stroke="#246B4D" stroke-width="3"/>
              <circle cx="40" cy="88" r="5" fill="#E53935"/>
              <circle cx="56" cy="88" r="5" fill="#FBBF24"/>
              <circle cx="72" cy="88" r="5" fill="#246B4D"/>
              <circle cx="88" cy="88" r="5" fill="#2F6F9F"/>
            </svg>
          @elseif ($cat->slug === 'bahasa-inggris')
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <ellipse cx="64" cy="108" rx="42" ry="7" fill="#EED8C8"/>
              <circle cx="48" cy="64" r="28" fill="#2F6F9F"/>
              <ellipse cx="48" cy="64" rx="12" ry="28" fill="none" stroke="#DCEBF7" stroke-width="2.5"/>
              <line x1="22" y1="64" x2="74" y2="64" stroke="#DCEBF7" stroke-width="2.5"/>
              <rect x="58" y="30" width="52" height="34" rx="12" fill="#FFFFFF" stroke="#EA580C" stroke-width="3"/>
              <text x="84" y="52" text-anchor="middle" font-family="Fredoka, sans-serif" font-weight="700" font-size="16" fill="#C2410C">ABC</text>
            </svg>
          @else
            <svg class="st-hub-card__art" viewBox="0 0 128 128" fill="none" aria-hidden="true">
              <rect x="22" y="30" width="84" height="68" rx="14" fill="#2F6F9F"/>
              <rect x="30" y="38" width="68" height="52" rx="8" fill="#FFFFFF"/>
              <polygon points="64,46 69,58 82,59 72,68 75,80 64,73 53,80 56,68 46,59 59,58" fill="#FBBF24"/>
            </svg>
          @endif
        </div>

        <h3 class="st-hub-card__title">{{ $cat->name }}</h3>
        <p class="st-hub-card__desc">
          Lihat kartu gambar dan dengarkan pelafalan kata untuk topik {{ strtolower($cat->name) }}.
        </p>

        <div class="st-hub-card__footer">
          <span class="st-hub-card__meta">{{ $cat->materials_count }} materi tersedia</span>
          <span class="st-hub-card__cta">
            <span>Buka</span>
            @include('student.partials.icon-arrow', ['size' => 15])
          </span>
        </div>
      </a>
    @empty
      <div class="st-empty-card" style="grid-column: 1 / -1;">
        <h3 class="st-hub-card__title">Belum Ada Kategori</h3>
        <p class="st-hub-card__desc">Bapak/Ibu guru sedang menyiapkan materi belajar untukmu.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection
