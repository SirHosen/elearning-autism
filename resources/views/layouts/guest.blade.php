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
<body style="display: flex; flex-direction: column; min-height: 100vh; background-color: var(--bg); color: var(--text);">
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  {{-- ═══ HEADER ═══ --}}
  <header class="guest-header">
    <div class="guest-header__inner">
      <a href="{{ route('login') }}" class="guest-brand">
        <div class="guest-brand__mark">E</div>
        <div class="guest-brand__text">
          <span class="guest-brand__name">EduAutis</span>
          <span class="guest-brand__org">SLB B-C Wijayakusumah</span>
        </div>
      </a>
      <div class="guest-status">
        <span class="guest-status__dot"></span>
        <span class="guest-status__label hide-mobile">Desain Ramah Sensori</span>
      </div>
    </div>
  </header>

  {{-- ═══ MAIN ═══ --}}
  <main id="main-content" class="guest-main">
    @yield('content')
  </main>

  {{-- ═══ FOOTER ═══ --}}
  <footer class="guest-footer">
    <p>&copy; 2026 SLB B-C Wijayakusumah Sumedang</p>
    <p class="guest-footer__sub">Platform Pembelajaran Inklusif &bull; WCAG 2.1 AA</p>
  </footer>

  <style>
    /* ── Header ── */
    .guest-header {
      background: var(--surface);
      border-bottom: 1px solid var(--line);
      padding: 0 24px;
      height: 64px;
      display: flex;
      align-items: center;
    }
    .guest-header__inner {
      max-width: 1080px;
      width: 100%;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* ── Brand ── */
    .guest-brand {
      display: flex;
      align-items: center;
      gap: 14px;
      text-decoration: none;
      color: inherit;
    }
    .guest-brand__mark {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: var(--primary);
      color: var(--on-primary);
      font-size: 1.25rem;
      font-weight: 800;
      display: grid;
      place-items: center;
      letter-spacing: -0.5px;
    }
    .guest-brand__name {
      display: block;
      font-size: 1.125rem;
      font-weight: 800;
      letter-spacing: -0.4px;
      color: var(--text);
      line-height: 1.2;
    }
    .guest-brand__org {
      display: block;
      font-size: 0.6875rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      color: var(--text-muted);
      text-transform: uppercase;
    }

    /* ── Sensory Status ── */
    .guest-status {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 14px;
      border-radius: 999px;
      background: var(--success-bg);
      border: 1px solid rgba(36, 107, 77, 0.15);
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--success);
    }
    .guest-status__dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: var(--success);
    }

    /* ── Main ── */
    .guest-main {
      flex: 1;
      display: grid;
      place-items: center;
      padding: 48px 20px;
    }

    /* ── Footer ── */
    .guest-footer {
      background: var(--surface);
      border-top: 1px solid var(--line);
      padding: 18px 24px;
      text-align: center;
      font-size: 0.8125rem;
      color: var(--text-muted);
    }
    .guest-footer p { margin: 0; }
    .guest-footer__sub {
      font-size: 0.6875rem;
      opacity: 0.7;
      margin-top: 2px !important;
    }

    @media (max-width: 640px) {
      .hide-mobile { display: none; }
      .guest-main { padding: 32px 16px; }
    }
  </style>

  @stack('scripts')
</body>
</html>
