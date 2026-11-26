@if (($role ?? 'student') === 'student')
  <svg class="role-vec role-vec--student" viewBox="0 0 360 200" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Ikon vektor murid belajar">
    <defs>
      <linearGradient id="stuBgGrad" x1="0" y1="0" x2="360" y2="200" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#DCEBF7"/>
        <stop offset="55%" stop-color="#CBE3F6"/>
        <stop offset="100%" stop-color="#E5F2FC"/>
      </linearGradient>
    </defs>

    <!-- Latar langit pastel bersih -->
    <rect width="360" height="200" fill="url(#stuBgGrad)"/>
    <circle cx="298" cy="46" r="44" fill="#FFFFFF" opacity="0.42"/>
    <circle cx="64" cy="166" r="56" fill="#2F6F9F" opacity="0.08"/>

    <!-- Awan mengambang lembut -->
    <g class="auth-vec-drift">
      <rect x="28" y="34" width="66" height="22" rx="11" fill="#FFFFFF" opacity="0.88"/>
      <circle cx="48" cy="34" r="13" fill="#FFFFFF" opacity="0.88"/>
      <circle cx="66" cy="30" r="15" fill="#FFFFFF" opacity="0.88"/>
    </g>

    <!-- Bintang & kilau animasi -->
    <g class="auth-vec-twinkle">
      <polygon points="292,38 295.5,46 304,47 297.5,53 299.5,61.5 292,57 284.5,61.5 286.5,53 280,47 288.5,46" fill="#F59E0B"/>
    </g>
    <g class="auth-vec-twinkle-delay">
      <circle cx="254" cy="44" r="4.5" fill="#2F6F9F" opacity="0.45"/>
      <circle cx="102" cy="76" r="3.5" fill="#F59E0B" opacity="0.7"/>
    </g>

    <!-- Tanah / Meja Belajar -->
    <ellipse cx="180" cy="176" rx="108" ry="11" fill="#9BC2E2" opacity="0.55"/>

    <!-- Buku terbuka kiri mengambang -->
    <g class="auth-vec-float">
      <rect x="52" y="96" width="44" height="34" rx="8" fill="#FFFFFF" stroke="#2F6F9F" stroke-width="2.5"/>
      <line x1="74" y1="98" x2="74" y2="128" stroke="#B4D3EB" stroke-width="2"/>
      <line x1="59" y1="107" x2="69" y2="107" stroke="#2F6F9F" stroke-width="2.5" stroke-linecap="round"/>
      <line x1="59" y1="115" x2="68" y2="115" stroke="#93C5FD" stroke-width="2.5" stroke-linecap="round"/>
      <circle cx="85" cy="111" r="5" fill="#FBBF24"/>
    </g>

    <!-- Pensil kanan mengambang -->
    <g class="auth-vec-float-delay">
      <g transform="rotate(-18 286 112)">
        <rect x="278" y="88" width="16" height="44" rx="4" fill="#F59E0B" stroke="#B45309" stroke-width="2"/>
        <rect x="278" y="88" width="16" height="9" rx="3" fill="#FCA5A5" stroke="#B45309" stroke-width="2"/>
        <polygon points="278,132 294,132 286,144" fill="#FDE68A" stroke="#B45309" stroke-width="2" stroke-linejoin="round"/>
        <polygon points="283,139 289,139 286,144" fill="#1F2933"/>
      </g>
    </g>

    <!-- Ikon Karakter Murid (Flat Vector Ramah Anak) -->
    <g class="role-vec__hero">
      <!-- Tas ransel biru belakang -->
      <rect x="136" y="102" width="88" height="62" rx="22" fill="#1E4E75"/>
      <!-- Badan / seragam putih & kerah biru -->
      <path d="M132 174 C132 138 148 124 180 124 C212 124 228 138 228 174 Z" fill="#FFFFFF" stroke="#2F6F9F" stroke-width="3"/>
      <path d="M158 125 L180 144 L202 125" fill="#DCEBF7" stroke="#2F6F9F" stroke-width="2.5" stroke-linejoin="round"/>
      <!-- Lencana saku -->
      <rect x="193" y="146" width="14" height="10" rx="3" fill="#2F6F9F"/>

      <!-- Leher & Kepala -->
      <rect x="171" y="108" width="18" height="18" rx="8" fill="#F8C8A4"/>
      <circle cx="180" cy="76" r="36" fill="#2B3440"/>
      <!-- Poni rambut lembut -->
      <circle cx="180" cy="82" r="30" fill="#FBD3B3"/>
      <path d="M149 76 C152 54 166 46 184 48 C201 50 211 60 211 76 C200 66 187 64 176 66 C165 68 156 72 149 76 Z" fill="#2B3440"/>

      <!-- Wajah ceria -->
      <circle cx="168" cy="82" r="3.4" fill="#1F2933"/>
      <circle cx="192" cy="82" r="3.4" fill="#1F2933"/>
      <circle cx="169" cy="80.8" r="1.1" fill="#FFFFFF"/>
      <circle cx="193" cy="80.8" r="1.1" fill="#FFFFFF"/>
      <ellipse cx="160" cy="90" rx="5" ry="3" fill="#F9A8D4" opacity="0.55"/>
      <ellipse cx="200" cy="90" rx="5" ry="3" fill="#F9A8D4" opacity="0.55"/>
      <path d="M171 94 Q180 102 189 94" stroke="#9A3412" stroke-width="3" stroke-linecap="round" fill="none"/>

      <!-- Buku pegangan siswa di depan -->
      <rect x="148" y="148" width="64" height="28" rx="8" fill="#2F6F9F" stroke="#1E4E75" stroke-width="2.5"/>
      <line x1="180" y1="150" x2="180" y2="174" stroke="#DCEBF7" stroke-width="2.5"/>
      <circle cx="164" cy="162" r="5" fill="#FBBF24"/>
      <rect x="188" y="157" width="16" height="4" rx="2" fill="#FFFFFF"/>
      <rect x="188" y="164" width="12" height="4" rx="2" fill="#DCEBF7"/>
    </g>
  </svg>
@else
  <svg class="role-vec role-vec--teacher" viewBox="0 0 360 200" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Ikon vektor guru mengajar">
    <defs>
      <linearGradient id="tchBgGrad" x1="0" y1="0" x2="360" y2="200" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#DDF0E8"/>
        <stop offset="55%" stop-color="#CCE8DA"/>
        <stop offset="100%" stop-color="#E7F6EF"/>
      </linearGradient>
    </defs>

    <!-- Latar kelas hangat pastel hijau -->
    <rect width="360" height="200" fill="url(#tchBgGrad)"/>
    <circle cx="62" cy="46" r="46" fill="#FFFFFF" opacity="0.45"/>

    <!-- Papan Tulis Kelas di belakang -->
    <rect x="44" y="30" width="210" height="106" rx="14" fill="#1B4332" stroke="#926239" stroke-width="5"/>
    <rect x="52" y="38" width="194" height="90" rx="8" fill="#143326"/>
    <!-- Teks & grafik sederhana pada papan tulis -->
    <text x="68" y="66" font-family="Fredoka, sans-serif" font-size="18" font-weight="700" fill="#B7E4C7">A B C</text>
    <line x1="68" y1="80" x2="124" y2="80" stroke="#4ADE80" stroke-width="3" stroke-linecap="round" opacity="0.7"/>
    <line x1="68" y1="93" x2="108" y2="93" stroke="#B7E4C7" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
    <circle cx="80" cy="112" r="7" fill="#FBBF24" opacity="0.9"/>
    <polyline points="76.5 112 79 114.5 84 109.5" stroke="#143326" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

    <!-- Lampu Ide / Bintang mengambang di kanan atas -->
    <g class="auth-vec-float">
      <circle cx="302" cy="54" r="22" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2.5"/>
      <path d="M302 41 A9 9 0 0 0 296 56.5 V60 H308 V56.5 A9 9 0 0 0 302 41 Z" fill="#FBBF24" stroke="#B45309" stroke-width="2"/>
      <line x1="298" y1="64" x2="306" y2="64" stroke="#B45309" stroke-width="2.5" stroke-linecap="round"/>
    </g>

    <!-- Tanaman pot kecil kiri mengayun halus -->
    <g class="auth-vec-sway">
      <ellipse cx="64" cy="170" rx="18" ry="5" fill="#93C5AC" opacity="0.5"/>
      <path d="M52 146 H76 L72 170 H56 Z" fill="#EA580C" stroke="#9A3412" stroke-width="2"/>
      <path d="M64 146 C56 132 48 134 50 124 C58 124 64 134 64 146 Z" fill="#246B4D"/>
      <path d="M64 146 C72 130 82 132 79 122 C70 122 65 134 64 146 Z" fill="#2D6A4F"/>
    </g>

    <!-- Alas guru -->
    <ellipse cx="204" cy="176" rx="92" ry="10" fill="#93C5AC" opacity="0.55"/>

    <!-- Ikon Karakter Guru (Flat Vector Profesional & Ramah) -->
    <g class="role-vec__hero">
      <!-- Blazer / Baju Guru -->
      <path d="M154 174 C154 138 172 124 204 124 C236 124 254 138 254 174 Z" fill="#246B4D" stroke="#143326" stroke-width="3"/>
      <!-- Kerah V dalam putih -->
      <path d="M188 125 L204 152 L220 125 Z" fill="#FFFFFF"/>
      <!-- Dasi / aksen hangat -->
      <polygon points="204,132 200,148 204,154 208,148" fill="#F59E0B"/>

      <!-- Leher & Kepala -->
      <rect x="195" y="108" width="18" height="18" rx="8" fill="#F8C8A4"/>
      <circle cx="204" cy="76" r="35" fill="#2B3440"/>
      <!-- Sanggul / rambut rapi -->
      <circle cx="233" cy="64" r="12" fill="#2B3440"/>
      <circle cx="204" cy="82" r="29" fill="#FBD3B3"/>
      <path d="M174 75 C177 54 191 47 207 49 C223 51 232 61 232 76 C220 67 209 65 198 66 C188 67 180 71 174 75 Z" fill="#2B3440"/>

      <!-- Kacamata ramah guru -->
      <circle cx="192" cy="82" r="7.5" fill="rgba(255,255,255,0.35)" stroke="#143326" stroke-width="2.4"/>
      <circle cx="216" cy="82" r="7.5" fill="rgba(255,255,255,0.35)" stroke="#143326" stroke-width="2.4"/>
      <line x1="199.5" y1="82" x2="208.5" y2="82" stroke="#143326" stroke-width="2.4"/>
      <circle cx="192" cy="82" r="2.6" fill="#1F2933"/>
      <circle cx="216" cy="82" r="2.6" fill="#1F2933"/>
      <path d="M196 95 Q204 101 212 95" stroke="#9A3412" stroke-width="2.8" stroke-linecap="round" fill="none"/>

      <!-- Buku Catatan / Rapor Guru di lengan -->
      <rect x="218" y="142" width="38" height="30" rx="6" fill="#FEF3C7" stroke="#B45309" stroke-width="2.4"/>
      <line x1="226" y1="152" x2="248" y2="152" stroke="#D97706" stroke-width="2.5" stroke-linecap="round"/>
      <line x1="226" y1="160" x2="244" y2="160" stroke="#D97706" stroke-width="2.5" stroke-linecap="round"/>
    </g>

    <!-- Tongkat tunjuk / indikator kelas -->
    <g class="auth-vec-twinkle">
      <circle cx="312" cy="146" r="14" fill="#FFFFFF" stroke="#246B4D" stroke-width="2.5"/>
      <polyline points="306 146 310 150 318 142" stroke="#246B4D" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
  </svg>
@endif
