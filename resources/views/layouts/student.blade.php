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
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css?v={{ @filemtime(public_path('css/layouts.css')) ?: '2' }}">
  @stack('styles')
</head>
<body class="student-body">
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  <header class="student-header">
    <div class="student-header-inner">
      <div class="student-header-left">
        <a href="{{ route('student.home') }}"
           class="student-nav-btn {{ request()->routeIs('student.home') ? 'is-active' : '' }}"
           title="Kembali ke Beranda">
          Beranda
        </a>
        @yield('header_left')
      </div>

      <h1 class="student-header-title">@yield('page_title', 'Sahabat Belajar')</h1>

      <div class="student-header-actions">
        <a href="{{ route('student.settings') }}"
           class="student-nav-btn {{ request()->routeIs('student.settings') ? 'is-active' : '' }}"
           title="Pengaturan Tampilan & Suara">
          Atur
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="student-nav-btn student-nav-btn--logout" title="Keluar">
            Keluar
          </button>
        </form>
      </div>
    </div>
  </header>

  <main id="main-content" class="student-container">
    @yield('content')
  </main>

  <script src="/js/core/audio-manager.js" type="module"></script>
  @stack('scripts')
</body>
</html>
