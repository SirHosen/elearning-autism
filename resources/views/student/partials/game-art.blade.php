@php
  $slug = $categorySlug ?? 'umum';
  $type = $gameType ?? 'pilih_benar';
  $scene = match (true) {
      $slug === 'musik-nada' => 'music',
      $slug === 'matematika' => 'math',
      $slug === 'bahasa-inggris' => 'english',
      $type === 'cocokkan' => 'match',
      $type === 'seret_tempatkan' => 'drag',
      default => 'pick',
  };
@endphp

<svg class="st-hub-vec" viewBox="0 0 320 176" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  @if ($scene === 'music')
    <rect width="320" height="176" fill="#EDE9FE"/>
    <circle cx="268" cy="40" r="36" fill="#FFFFFF" opacity="0.55"/>
    <circle cx="52" cy="146" r="44" fill="#7C3AED" opacity="0.09"/>
    <g class="st-vec-twinkle">
      <polygon points="264,34 267,41 275,42 269,47 271,55 264,51 257,55 259,47 253,42 261,41" fill="#F59E0B"/>
    </g>
    <g class="st-vec-float">
      <circle cx="72" cy="64" r="9" fill="#6D28D9"/>
      <line x1="79" y1="64" x2="79" y2="38" stroke="#6D28D9" stroke-width="3.5" stroke-linecap="round"/>
      <path d="M79 38 Q92 38 92 49" stroke="#6D28D9" stroke-width="3.5" stroke-linecap="round" fill="none"/>
    </g>
    <g class="st-vec-float-delay">
      <circle cx="242" cy="76" r="7.5" fill="#E53935"/>
      <line x1="248" y1="76" x2="248" y2="54" stroke="#E53935" stroke-width="3" stroke-linecap="round"/>
    </g>
    <ellipse cx="160" cy="148" rx="98" ry="11" fill="#C4B5FD" opacity="0.55"/>
    <g class="st-vec__hero">
      <rect x="86" y="122" width="148" height="22" rx="10" fill="#78350F"/>
      <rect x="94" y="64" width="24" height="62" rx="8" fill="#E53935" stroke="#FFFFFF" stroke-width="2.5"/>
      <rect x="122" y="70" width="24" height="56" rx="8" fill="#FB8C00" stroke="#FFFFFF" stroke-width="2.5"/>
      <rect x="150" y="76" width="24" height="50" rx="8" fill="#FBBF24" stroke="#FFFFFF" stroke-width="2.5"/>
      <rect x="178" y="82" width="24" height="44" rx="8" fill="#246B4D" stroke="#FFFFFF" stroke-width="2.5"/>
      <rect x="206" y="88" width="24" height="38" rx="8" fill="#2F6F9F" stroke="#FFFFFF" stroke-width="2.5"/>
      <circle cx="106" cy="78" r="3.5" fill="#FFFFFF"/>
      <circle cx="134" cy="84" r="3.5" fill="#FFFFFF"/>
      <circle cx="162" cy="90" r="3.5" fill="#FFFFFF"/>
      <circle cx="190" cy="96" r="3.5" fill="#FFFFFF"/>
      <circle cx="218" cy="102" r="3.5" fill="#FFFFFF"/>
    </g>

  @elseif ($scene === 'math')
    <rect width="320" height="176" fill="#E3F2EA"/>
    <circle cx="56" cy="48" r="38" fill="#FFFFFF" opacity="0.55"/>
    <circle cx="272" cy="144" r="46" fill="#246B4D" opacity="0.1"/>
    <g class="st-vec-float">
      <rect x="52" y="78" width="38" height="38" rx="11" fill="#2F6F9F" stroke="#FFFFFF" stroke-width="2.5"/>
      <line x1="71" y1="88" x2="71" y2="106" stroke="#FFFFFF" stroke-width="3.5" stroke-linecap="round"/>
      <line x1="62" y1="97" x2="80" y2="97" stroke="#FFFFFF" stroke-width="3.5" stroke-linecap="round"/>
    </g>
    <g class="st-vec-float-delay">
      <circle cx="252" cy="82" r="20" fill="#FBBF24" stroke="#FFFFFF" stroke-width="2.5"/>
      <polygon points="252,72 262,90 242,90" fill="#FFFFFF"/>
    </g>
    <ellipse cx="160" cy="148" rx="94" ry="11" fill="#A7D7C0" opacity="0.6"/>
    <g class="st-vec__hero">
      <rect x="108" y="48" width="104" height="92" rx="18" fill="#FFFFFF" stroke="#246B4D" stroke-width="3.5"/>
      <rect x="120" y="60" width="80" height="24" rx="8" fill="#E3F2EA"/>
      <circle cx="138" cy="72" r="6" fill="#E53935"/>
      <circle cx="160" cy="72" r="6" fill="#F59E0B"/>
      <circle cx="182" cy="72" r="6" fill="#2F6F9F"/>
      <rect x="120" y="94" width="36" height="34" rx="10" fill="#246B4D"/>
      <circle cx="138" cy="111" r="9" fill="#FFFFFF" opacity="0.9"/>
      <polygon points="182,94 198,126 166,126" fill="#EA580C"/>
    </g>

  @elseif ($scene === 'english')
    <rect width="320" height="176" fill="#FBE3D6"/>
    <circle cx="268" cy="48" r="40" fill="#FFFFFF" opacity="0.52"/>
    <circle cx="48" cy="144" r="42" fill="#EA580C" opacity="0.09"/>
    <g class="st-vec-twinkle">
      <polygon points="262,38 265,45 273,46 267,51 269,59 262,55 255,59 257,51 251,46 259,45" fill="#F59E0B"/>
    </g>
    <ellipse cx="160" cy="148" rx="98" ry="11" fill="#F2BEA2" opacity="0.58"/>
    <g class="st-vec__hero">
      <!-- Kartu kiri: gambar apel -->
      <rect x="74" y="62" width="70" height="76" rx="16" fill="#FFFFFF" stroke="#EA580C" stroke-width="3"/>
      <circle cx="109" cy="102" r="18" fill="#E53935"/>
      <path d="M109 84 Q112 74 120 72" stroke="#246B4D" stroke-width="3.5" stroke-linecap="round"/>
      <!-- Garis penghubung -->
      <path d="M148 100 Q160 88 172 100" stroke="#C2410C" stroke-width="3" stroke-dasharray="5 4" stroke-linecap="round"/>
      <!-- Kartu kanan: kata APPLE -->
      <rect x="176" y="72" width="76" height="56" rx="16" fill="#2F6F9F" stroke="#1E4E75" stroke-width="3"/>
      <text x="214" y="106" text-anchor="middle" font-family="Fredoka, sans-serif" font-weight="700" font-size="18" fill="#FFFFFF">Apple</text>
    </g>
    <g class="st-vec-float">
      <rect x="132" y="34" width="56" height="28" rx="14" fill="#FFFFFF" stroke="#C2410C" stroke-width="2.5"/>
      <text x="160" y="53" text-anchor="middle" font-family="Fredoka, sans-serif" font-weight="700" font-size="14" fill="#C2410C">ABC</text>
    </g>

  @elseif ($scene === 'match')
    <rect width="320" height="176" fill="#DDF0E8"/>
    <circle cx="64" cy="44" r="36" fill="#FFFFFF" opacity="0.52"/>
    <g class="st-vec-twinkle">
      <polygon points="160,30 163,37 171,38 165,43 167,51 160,47 153,51 155,43 149,38 157,37" fill="#F59E0B"/>
    </g>
    <ellipse cx="160" cy="150" rx="104" ry="11" fill="#A7D7C0" opacity="0.6"/>
    <g class="st-vec__hero">
      <!-- Kolom Kiri: Kartu Warna -->
      <rect x="70" y="58" width="68" height="36" rx="12" fill="#FFFFFF" stroke="#246B4D" stroke-width="2.8"/>
      <circle cx="104" cy="76" r="11" fill="#F59E0B"/>
      <rect x="70" y="102" width="68" height="36" rx="12" fill="#FFFFFF" stroke="#2F6F9F" stroke-width="2.8"/>
      <circle cx="104" cy="120" r="11" fill="#2F6F9F"/>
      <!-- Garis Pasangan -->
      <path d="M140 76 C156 76 164 120 180 120" stroke="#246B4D" stroke-width="3.5" stroke-linecap="round"/>
      <path d="M140 120 C156 120 164 76 180 76" stroke="#2F6F9F" stroke-width="3" stroke-dasharray="5 4" stroke-linecap="round"/>
      <!-- Kolom Kanan: Kartu Label -->
      <rect x="182" y="58" width="68" height="36" rx="12" fill="#E8F2FA" stroke="#2F6F9F" stroke-width="2.5"/>
      <rect x="196" y="72" width="40" height="8" rx="4" fill="#2F6F9F"/>
      <rect x="182" y="102" width="68" height="36" rx="12" fill="#246B4D"/>
      <rect x="196" y="116" width="40" height="8" rx="4" fill="#FFFFFF"/>
    </g>

  @elseif ($scene === 'drag')
    <rect width="320" height="176" fill="#FCEFC7"/>
    <circle cx="268" cy="44" r="38" fill="#FFFFFF" opacity="0.55"/>
    <g class="st-vec-twinkle-delay">
      <circle cx="64" cy="52" r="5" fill="#D97706" opacity="0.5"/>
      <circle cx="256" cy="112" r="4.5" fill="#246B4D" opacity="0.45"/>
    </g>
    <ellipse cx="160" cy="150" rx="102" ry="11" fill="#E5CF92" opacity="0.6"/>
    <g class="st-vec__hero">
      <!-- Objek melayang ditarik -->
      <g class="st-vec-float">
        <rect x="80" y="56" width="60" height="60" rx="16" fill="#2F6F9F" stroke="#FFFFFF" stroke-width="3"/>
        <circle cx="110" cy="86" r="15" fill="#FBBF24"/>
      </g>
      <!-- Panah lengkung pindah -->
      <path d="M146 80 Q162 64 178 82" stroke="#B45309" stroke-width="3.5" stroke-linecap="round" fill="none"/>
      <polyline points="172 84 180 84 179 75" stroke="#B45309" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
      <!-- Kotak target menyala -->
      <rect x="184" y="76" width="64" height="64" rx="16" fill="#FFFFFF" stroke="#246B4D" stroke-width="3.5" stroke-dasharray="7 5"/>
      <circle cx="216" cy="108" r="14" fill="#E3F2EA"/>
      <polyline points="210 108 214 112 223 103" stroke="#246B4D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    </g>

  @else
    {{-- Default / Pilih Benar (e.g. Tebak Nama Hewan) --}}
    <rect width="320" height="176" fill="#DCEBF7"/>
    <circle cx="268" cy="44" r="36" fill="#FFFFFF" opacity="0.52"/>
    <g class="st-vec-twinkle">
      <polygon points="264,34 267,41 275,42 269,47 271,55 264,51 257,55 259,47 253,42 261,41" fill="#F59E0B"/>
    </g>
    <ellipse cx="160" cy="150" rx="104" ry="11" fill="#A9CCE8" opacity="0.6"/>
    <g class="st-vec__hero">
      <!-- Kartu belakang kiri -->
      <rect x="64" y="64" width="64" height="74" rx="14" transform="rotate(-6 96 101)" fill="#FFFFFF" stroke="#9CC5E6" stroke-width="2.8"/>
      <circle cx="96" cy="96" r="14" fill="#93C5FD"/>
      <!-- Kartu belakang kanan -->
      <rect x="192" y="64" width="64" height="74" rx="14" transform="rotate(6 224 101)" fill="#FFFFFF" stroke="#9CC5E6" stroke-width="2.8"/>
      <rect x="212" y="84" width="24" height="24" rx="6" fill="#FDE68A"/>
      <!-- Kartu Utama Terpilih di Tengah -->
      <rect x="118" y="48" width="84" height="94" rx="18" fill="#FFFFFF" stroke="#2F6F9F" stroke-width="3.5"/>
      <!-- Wajah Kucing Ramah di dalam kartu -->
      <polygon points="140,86 144,68 156,78" fill="#E08E45"/>
      <polygon points="180,86 176,68 164,78" fill="#E08E45"/>
      <ellipse cx="160" cy="95" rx="22" ry="18" fill="#E08E45"/>
      <circle cx="152" cy="92" r="2.8" fill="#1F2933"/>
      <circle cx="168" cy="92" r="2.8" fill="#1F2933"/>
      <path d="M155 100 Q160 105 165 100" stroke="#5C3210" stroke-width="2.4" stroke-linecap="round"/>
      <!-- Lencana Centang Hijau -->
      <circle cx="196" cy="128" r="15" fill="#246B4D" stroke="#FFFFFF" stroke-width="2.5"/>
      <polyline points="190 128 194 132 203 123" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
  @endif
</svg>
