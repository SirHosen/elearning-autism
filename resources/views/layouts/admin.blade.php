<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Pengelolaan Guru | SLB B-C Wijayakusumah')</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css?v={{ @filemtime(public_path('css/layouts.css')) ?: '2' }}">
  <link rel="stylesheet" href="/css/admin.css?v={{ @filemtime(public_path('css/admin.css')) ?: '1' }}">
  @stack('styles')
</head>
<body>
  <a href="#admin-main" class="skip-link">Lewati ke konten panel</a>

  @php
    $navCounts = $adminSidebarCounts ?? [
      'students' => 0,
      'unattendedToday' => 0,
      'materials' => 0,
      'quizzes' => 0,
    ];
    $teacher = auth()->user();
    $initials = collect(explode(' ', trim($teacher?->name ?? 'Guru')))
      ->filter()
      ->map(fn ($w) => strtoupper(substr($w, 0, 1)))
      ->take(2)
      ->implode('');
  @endphp

  <div class="adm-shell">
    <div id="admSidebarBackdrop" class="adm-sidebar-backdrop" aria-hidden="true"></div>

    <aside id="admSidebar" class="adm-sidebar" aria-label="Menu navigasi panel guru">
      <a href="{{ route('admin.dashboard') }}" class="adm-brand">
        <div class="adm-brand__logo" aria-hidden="true">SB</div>
        <div>
          <span class="adm-brand__title">Sahabat Belajar</span>
          <span class="adm-brand__sub">Panel Pengelolaan Guru</span>
        </div>
      </a>

      <nav class="adm-nav">
        <div class="adm-nav__group">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="adm-nav__link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="3" y="3" width="7" height="9" rx="1.5"/>
              <rect x="14" y="3" width="7" height="5" rx="1.5"/>
              <rect x="14" y="12" width="7" height="9" rx="1.5"/>
              <rect x="3" y="16" width="7" height="5" rx="1.5"/>
            </svg>
            <span>Ringkasan</span>
          </span>
        </a>

        <div class="adm-nav__group">Kurikulum &amp; Konten</div>
        <a href="{{ route('admin.categories.index') }}" class="adm-nav__link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
            </svg>
            <span>Kategori</span>
          </span>
        </a>
        <a href="{{ route('admin.materials.index') }}" class="adm-nav__link {{ request()->routeIs('admin.materials.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
            </svg>
            <span>Materi Belajar</span>
          </span>
          @if(($navCounts['materials'] ?? 0) > 0)
            <span class="adm-nav__badge">{{ $navCounts['materials'] }}</span>
          @endif
        </a>
        <a href="{{ route('admin.videos.index') }}" class="adm-nav__link {{ request()->routeIs('admin.videos.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="2" y="4" width="20" height="16" rx="2.5"/>
              <polygon points="10 9 16 12 10 15 10 9"/>
            </svg>
            <span>Video</span>
          </span>
        </a>
        <a href="{{ route('admin.games.index') }}" class="adm-nav__link {{ request()->routeIs('admin.games.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="6" y1="12" x2="10" y2="12"/>
              <line x1="8" y1="10" x2="8" y2="14"/>
              <line x1="15" y1="13" x2="15.01" y2="13"/>
              <line x1="18" y1="11" x2="18.01" y2="11"/>
              <rect x="2" y="6" width="20" height="12" rx="4"/>
            </svg>
            <span>Permainan</span>
          </span>
        </a>
        <a href="{{ route('admin.quizzes.index') }}" class="adm-nav__link {{ request()->routeIs('admin.quizzes.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M9 11l3 3L22 4"/>
              <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            <span>Kuis</span>
          </span>
          @if(($navCounts['quizzes'] ?? 0) > 0)
            <span class="adm-nav__badge">{{ $navCounts['quizzes'] }}</span>
          @endif
        </a>

        <div class="adm-nav__group">Siswa &amp; Evaluasi</div>
        <a href="{{ route('admin.students.index') }}" class="adm-nav__link {{ request()->routeIs('admin.students.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>Data Siswa</span>
          </span>
          @if(($navCounts['students'] ?? 0) > 0)
            <span class="adm-nav__badge">{{ $navCounts['students'] }}</span>
          @endif
        </a>
        <a href="{{ route('admin.attendances.index') }}" class="adm-nav__link {{ request()->routeIs('admin.attendances.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
              <path d="M9 16l2 2 4-4"/>
            </svg>
            <span>Rekap Absensi</span>
          </span>
          @if(($navCounts['unattendedToday'] ?? 0) > 0)
            <span class="adm-nav__badge adm-nav__badge--warn" title="Belum presensi hari ini">{{ $navCounts['unattendedToday'] }}</span>
          @endif
        </a>
        <a href="{{ route('admin.reports.index') }}" class="adm-nav__link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="18" y1="20" x2="18" y2="10"/>
              <line x1="12" y1="20" x2="12" y2="4"/>
              <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            <span>Hasil Belajar</span>
          </span>
        </a>

        <div class="adm-nav__group">Akun</div>
        <a href="{{ route('admin.profile') }}" class="adm-nav__link {{ request()->routeIs('admin.profile') ? 'is-active' : '' }}">
          <span class="adm-nav__link-main">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <span>Profil Guru</span>
          </span>
        </a>
      </nav>

      <div class="adm-sidebar__footer">
        <div style="font-size: 0.75rem; color: rgba(226, 232, 240, 0.75); padding: 0 4px;">
          SLB B-C Wijayakusumah &bull; Sumedang
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="adm-logout-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Keluar Panel</span>
          </button>
        </form>
      </div>
    </aside>

    <div class="adm-workspace">
      <header class="adm-topbar">
        <div class="adm-topbar__left">
          <button type="button" id="admDrawerBtn" class="adm-drawer-btn" aria-controls="admSidebar" aria-expanded="false">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="3" y1="12" x2="21" y2="12"/>
              <line x1="3" y1="6" x2="21" y2="6"/>
              <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            <span>Menu</span>
          </button>

          <nav class="adm-breadcrumb" aria-label="Breadcrumb">
            <span>Panel Pengelolaan Guru</span>
            <span class="adm-breadcrumb__sep">/</span>
            <span class="adm-breadcrumb__current">@yield('page_title', 'Ringkasan')</span>
          </nav>
        </div>

        <div class="adm-topbar__right">
          <a href="{{ route('admin.materials.create') }}" class="adm-quick-link">+ Materi</a>
          <a href="{{ route('admin.students.create') }}" class="adm-quick-link">+ Siswa</a>
          <a href="{{ route('admin.attendances.index') }}" class="adm-quick-link">Absensi</a>
          <a href="{{ route('admin.profile') }}" class="adm-user-chip" title="Kelola Profil Guru">
            <span class="adm-user-chip__avatar" aria-hidden="true">{{ $initials ?: 'GR' }}</span>
            <span>{{ $teacher?->name ?? 'Guru' }}</span>
          </a>
        </div>
      </header>

      <main id="admin-main" class="adm-main">
        @if (session('success'))
          <div class="feedback-bar feedback-success" role="status" style="margin-bottom: 20px;">
            <span style="font-weight: 800;">Berhasil</span> <span>{{ session('success') }}</span>
          </div>
        @endif
        @if (session('error'))
          <div class="feedback-bar feedback-hint" role="alert" style="margin-bottom: 20px;">
            <span style="font-weight: 800;">Perhatian</span> <span>{{ session('error') }}</span>
          </div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>

  <script>
    (function() {
      const btn = document.getElementById('admDrawerBtn');
      const sidebar = document.getElementById('admSidebar');
      const backdrop = document.getElementById('admSidebarBackdrop');
      if (!btn || !sidebar || !backdrop) return;

      function setOpen(open) {
        sidebar.classList.toggle('is-open', open);
        backdrop.classList.toggle('is-open', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      }

      btn.addEventListener('click', () => setOpen(!sidebar.classList.contains('is-open')));
      backdrop.addEventListener('click', () => setOpen(false));
      window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') setOpen(false);
      });
    })();
  </script>
  @stack('scripts')
</body>
</html>
