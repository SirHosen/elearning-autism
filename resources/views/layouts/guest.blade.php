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
  @stack('styles')
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh; background-color: var(--bg);">
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  <main id="main-content" style="flex: 1; display: grid; place-items: center; padding: 24px;">
    @yield('content')
  </main>

  <footer style="text-align: center; padding: 16px; color: var(--ink-soft); font-size: 14px;">
    SLB B-C Wijayakusumah · Media Belajar Ramah Sensori
  </footer>

  @stack('scripts')
</body>
</html>
