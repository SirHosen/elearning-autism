<!DOCTYPE html>
<html lang="id" data-theme="calm" data-font-scale="normal" data-motion="minimal">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'EduAutis | SLB B-C Wijayakusumah')</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css">
  <link rel="stylesheet" href="/css/auth.css?v={{ @filemtime(public_path('css/auth.css')) ?: '1' }}">
  @stack('styles')
</head>
<body class="auth-body">
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  <header class="auth-topbar">
    <div class="auth-topbar__inner">
      <a href="{{ route('login') }}" class="auth-brand">
        <span class="auth-brand__mark" aria-hidden="true">
          <span class="auth-brand__layer-back"></span>
          <span class="auth-brand__layer-front"></span>
        </span>
        <span class="auth-brand__text">
          <span class="auth-brand__name">EduAutis</span>
          <span class="auth-brand__org">SLB B-C Wijayakusumah</span>
        </span>
      </a>

      <div class="auth-sensory-pill" aria-label="Desain ramah sensori aktif">
        <span class="auth-sensory-pill__dot" aria-hidden="true"></span>
        <span>Desain Ramah Sensori</span>
      </div>
    </div>
  </header>

  <main id="main-content" class="auth-stage">
    @yield('content')
  </main>

  <footer class="auth-bottom">
    <p>&copy; 2026 SLB B-C Wijayakusumah Sumedang &middot; Platform Pembelajaran Inklusif Visual-Audio &middot; WCAG 2.1 AA</p>
  </footer>

  @stack('scripts')
</body>
</html>
