<!DOCTYPE html>
<html lang="id" data-theme="calm" data-font-scale="normal" data-motion="minimal">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sahabat Belajar | SLB B-C Wijayakusumah')</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css">
  <link rel="stylesheet" href="/css/auth.css?v={{ @filemtime(public_path('css/auth.css')) ?: '2' }}">
  @stack('styles')
</head>
<body class="auth-body">
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  <main id="main-content" class="auth-stage">
    <a href="{{ route('login') }}" class="auth-site-brand">
      <span class="auth-site-brand__name">Sahabat Belajar</span>
      <span class="auth-site-brand__sep" aria-hidden="true"></span>
      <span class="auth-site-brand__school">SLB B-C Wijayakusumah</span>
    </a>

    @yield('content')
  </main>

  <footer class="auth-footer">
    <span>SLB B-C Wijayakusumah Sumedang</span>
  </footer>

  @stack('scripts')
</body>
</html>
