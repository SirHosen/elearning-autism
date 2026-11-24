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
  <title>@yield('title', 'EduAutis | SLB B-C Wijayakusumah')</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css">
  @stack('styles')
</head>
<body>
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  <!-- Header Siswa Tetap & Konsisten (Tinggi 72px) -->
  <header class="student-header">
    <div style="display: flex; align-items: center; gap: 12px;">
      <a href="{{ route('student.home') }}" class="btn btn-secondary" style="min-height: 48px; padding: 0 16px; font-size: 18px;" title="Kembali ke Beranda">
        <span>⌂</span> <span>Beranda</span>
      </a>
      @yield('header_left')
    </div>

    <h1 class="student-header-title">@yield('page_title', 'EduAutis')</h1>

    <div class="student-header-actions">
      <a href="{{ route('student.settings') }}" class="btn btn-secondary" style="min-height: 48px; padding: 0 16px;" title="Pengaturan Tampilan & Suara">
        <span>⚙</span> <span>Atur</span>
      </a>
      <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-secondary" style="min-height: 48px; padding: 0 16px;" title="Keluar">
          <span>↪</span> <span>Keluar</span>
        </button>
      </form>
    </div>
  </header>

  <main id="main-content" class="student-container">
    @yield('content')
  </main>

  <script src="/js/core/audio-manager.js" type="module"></script>
  @stack('scripts')
</body>
</html>
