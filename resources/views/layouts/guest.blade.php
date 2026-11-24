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

  <!-- Header Navigasi Tamu / Autentikasi -->
  <header style="background: var(--surface); border-bottom: 1px solid var(--border); padding: 14px 24px;">
    <div style="max-width: 1080px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
      <!-- Brand Logo & Identity -->
      <a href="{{ route('login') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit;">
        <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25); color: #FFFFFF;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            <circle cx="12" cy="10" r="2.5"></circle>
          </svg>
        </div>
        <div>
          <span style="font-size: 1.1875rem; font-weight: 800; letter-spacing: -0.3px; color: var(--text); line-height: 1.2; display: block;">EduAutis</span>
          <span style="font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.8px; color: var(--text-muted); text-transform: uppercase; display: block;">SLB B-C Wijayakusumah</span>
        </div>
      </a>

      <!-- Sensory-Safe Badge -->
      <div style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 999px; background: var(--surface-alt); border: 1px solid var(--border); font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">
        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--success, #246B4D); display: inline-block;"></span>
        <span class="hide-mobile">Desain Ramah Sensori</span>
      </div>
    </div>
  </header>

  <!-- Konten Utama -->
  <main id="main-content" style="flex: 1; display: grid; place-items: center; padding: 40px 20px;">
    @yield('content')
  </main>

  <!-- Footer Profesional -->
  <footer style="background: var(--surface); border-top: 1px solid var(--border); padding: 20px 24px; text-align: center; color: var(--text-muted); font-size: 0.8125rem;">
    <div style="max-width: 1080px; margin: 0 auto; display: flex; flex-direction: column; gap: 6px; align-items: center;">
      <p style="margin: 0; font-weight: 500;">
        &copy; 2026 SLB B-C Wijayakusumah Sumedang &bull; Sistem Pembelajaran Inklusif Berbasis Visual-Audio
      </p>
      <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted); opacity: 0.85;">
        Dirancang khusus untuk kenyamanan sensorik anak autis &bull; Standar Aksesibilitas WCAG 2.1 AA/AAA
      </p>
    </div>
  </footer>

  <style>
    @media (max-width: 640px) {
      .hide-mobile { display: none; }
    }
  </style>

  @stack('scripts')
</body>
</html>
