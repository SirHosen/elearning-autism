@php
  $user = auth()->user();
  $setting = $user?->setting;
  $theme = $setting?->theme ?? 'calm';
  $fontScale = $setting?->font_scale ?? 'normal';
  $motion = $setting?->animation_level ?? 'minimal';
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $theme }}" data-font-scale="{{ $fontScale }}" data-motion="{{ $motion }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Sahabat Belajar | SLB B-C Wijayakusumah')</title>
  <link rel="stylesheet" href="/css/tokens.css?v={{ @filemtime(public_path('css/tokens.css')) ?: '1' }}">
  <link rel="stylesheet" href="/css/themes.css?v={{ @filemtime(public_path('css/themes.css')) ?: '1' }}">
  <link rel="stylesheet" href="/css/base.css?v={{ @filemtime(public_path('css/base.css')) ?: '1' }}">
  <link rel="stylesheet" href="/css/components.css?v={{ @filemtime(public_path('css/components.css')) ?: '2' }}">
  <link rel="stylesheet" href="/css/layouts.css?v={{ @filemtime(public_path('css/layouts.css')) ?: '2' }}">
  <link rel="stylesheet" href="/css/student.css?v={{ @filemtime(public_path('css/student.css')) ?: '2' }}">
  @stack('styles')
</head>
<body class="student-body">
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  {{-- Latar belakang ambient lembut ramah sensorik (disembunyikan otomatis pada tema kontras tinggi) --}}
  <div class="st-ambient-bg" aria-hidden="true">
    <svg class="st-ambient-svg" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" fill="none">
      <!-- Lengkung bukit pastel sangat lembut di bagian bawah -->
      <path d="M0 780 C320 710 620 830 980 760 C1200 715 1340 745 1440 770 V900 H0 Z" fill="#DCEBF7" opacity="0.45"/>
      <path d="M0 825 C400 865 760 760 1140 815 C1290 838 1380 820 1440 805 V900 H0 Z" fill="#DDF0E8" opacity="0.5"/>

      <!-- Awan lembut tepi kiri & kanan -->
      <g class="st-ambient-float">
        <rect x="64" y="132" width="120" height="36" rx="18" fill="#FFFFFF" opacity="0.72"/>
        <circle cx="98" cy="132" r="22" fill="#FFFFFF" opacity="0.72"/>
        <circle cx="132" cy="126" r="26" fill="#FFFFFF" opacity="0.72"/>
      </g>

      <g class="st-ambient-float st-ambient-float--slow">
        <rect x="1210" y="168" width="136" height="38" rx="19" fill="#FFFFFF" opacity="0.68"/>
        <circle cx="1252" cy="168" r="23" fill="#FFFFFF" opacity="0.68"/>
        <circle cx="1290" cy="160" r="28" fill="#FFFFFF" opacity="0.68"/>
      </g>

      <!-- Lingkaran & bintang pastel halus di tepi luar -->
      <circle cx="110" cy="480" r="46" fill="#FCEFC7" opacity="0.42"/>
      <circle cx="1340" cy="520" r="58" fill="#E8E4F8" opacity="0.38"/>
      <polygon class="st-ambient-twinkle" points="210,235 214,244 224,245 217,252 219,262 210,257 201,262 203,252 196,245 206,244" fill="#F59E0B" opacity="0.32"/>
      <polygon class="st-ambient-twinkle st-ambient-twinkle--delay" points="1310,295 1314,303 1323,304 1316,310 1318,319 1310,315 1302,319 1304,310 1297,304 1306,303" fill="#2F6F9F" opacity="0.22"/>
    </svg>
  </div>

  <header class="student-header">
    <div class="student-header-inner">
      <div class="student-header-left">
        <a href="{{ route('student.home') }}"
           class="student-nav-btn {{ request()->routeIs('student.home') ? 'is-active' : '' }}"
           title="Kembali ke Beranda">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
          <span>Beranda</span>
        </a>
        @yield('header_left')
      </div>

      <h1 class="student-header-title">@yield('page_title', 'Sahabat Belajar')</h1>

      <div class="student-header-actions">
        <a href="{{ route('student.settings') }}"
           class="student-nav-btn {{ request()->routeIs('student.settings') ? 'is-active' : '' }}"
           title="Pengaturan Tampilan & Suara">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
          <span>Atur</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="student-nav-btn student-nav-btn--logout" title="Keluar">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Keluar</span>
          </button>
        </form>
      </div>
    </div>
  </header>

  <main id="main-content" class="student-container">
    @yield('content')
  </main>

  @php
    $studentAudioSettings = [
      'volume' => $setting?->volume ?? 70,
      'sfx_enabled' => (bool) ($setting?->sfx_enabled ?? false),
    ];
  @endphp
  <script type="application/json" id="user-settings-data">@json($studentAudioSettings)</script>
  <script src="/js/core/audio-manager.js?v={{ @filemtime(public_path('js/core/audio-manager.js')) ?: '2' }}" type="module"></script>
  @stack('scripts')
</body>
</html>
