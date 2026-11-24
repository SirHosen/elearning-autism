<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Guru | SLB B-C Wijayakusumah')</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css">
  <style>
    .admin-nav { display: flex; flex-direction: column; gap: 4px; margin-top: 16px; }
    .admin-nav-item {
      display: flex; align-items: center; gap: 12px; padding: 10px 14px;
      border-radius: var(--radius-sm); text-decoration: none; color: var(--ink-soft);
      font-weight: 600; font-size: 14px; transition: background 150ms ease;
    }
    .admin-nav-item:hover, .admin-nav-item.active {
      background-color: var(--surface-alt); color: var(--primary); font-weight: 700;
    }
    .admin-nav-item .nav-dot {
      width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .admin-nav-item.active .nav-dot { opacity: 1; }
    .admin-nav-item .nav-dot { opacity: 0.4; }
    .toast-box {
      position: fixed; right: 24px; bottom: 24px; background: var(--ink); color: #fff;
      padding: 14px 20px; border-radius: var(--radius-sm); z-index: 1000; box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    .admin-nav-section {
      font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.8px;
      text-transform: uppercase; color: var(--ink-soft); opacity: 0.6;
      padding: 16px 14px 6px; margin-top: 4px;
    }
  </style>
  @stack('styles')
</head>
<body>
  <a href="#admin-main" class="skip-link">Lewati ke konten panel</a>

  <div class="admin-layout">
    <aside class="admin-sidebar">
      <div style="display: flex; align-items: center; gap: 12px; padding-bottom: 16px; border-bottom: 1px solid var(--line);">
        <div style="width: 42px; height: 42px; border-radius: 12px; background: var(--primary); color: #fff; display: grid; place-items: center; font-weight: 900; font-size: 18px;">EA</div>
        <div>
          <b style="font-size: 15px; display: block;">SLB B-C Wijayakusumah</b>
          <small style="color: var(--ink-soft); font-size: 12px;">Panel Pengelolaan Guru</small>
        </div>
      </div>

      <nav class="admin-nav">
        <div class="admin-nav-section">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--primary);"></span>
          <span>Ringkasan</span>
        </a>

        <div class="admin-nav-section">Konten</div>
        <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--ink-soft);"></span>
          <span>Kategori</span>
        </a>
        <a href="{{ route('admin.materials.index') }}" class="admin-nav-item {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--tint-blue); border: 1px solid var(--primary);"></span>
          <span>Materi Belajar</span>
        </a>
        <a href="{{ route('admin.videos.index') }}" class="admin-nav-item {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--tint-lavender); border: 1px solid #7C3AED;"></span>
          <span>Video</span>
        </a>
        <a href="{{ route('admin.games.index') }}" class="admin-nav-item {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--tint-green); border: 1px solid var(--success);"></span>
          <span>Permainan</span>
        </a>
        <a href="{{ route('admin.quizzes.index') }}" class="admin-nav-item {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--tint-yellow); border: 1px solid var(--hint-line);"></span>
          <span>Kuis</span>
        </a>

        <div class="admin-nav-section">Siswa</div>
        <a href="{{ route('admin.students.index') }}" class="admin-nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--tint-peach); border: 1px solid #E97451;"></span>
          <span>Data Siswa</span>
        </a>
        <a href="{{ route('admin.attendances.index') }}" class="admin-nav-item {{ request()->routeIs('admin.attendances.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--success-bg); border: 1px solid var(--success);"></span>
          <span>Rekap Absensi</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="admin-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--tint-blue); border: 1px solid var(--primary);"></span>
          <span>Hasil Belajar</span>
        </a>

        <div class="admin-nav-section">Akun</div>
        <a href="{{ route('admin.profile') }}" class="admin-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
          <span class="nav-dot" style="background: var(--ink-soft);"></span>
          <span>Profil Guru</span>
        </a>
      </nav>

      <div style="margin-top: auto; padding-top: 16px; border-top: 1px solid var(--line);">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-secondary" style="width: 100%; min-height: 44px; font-size: 14px;">
            Keluar Panel
          </button>
        </form>
      </div>
    </aside>

    <main id="admin-main" class="admin-main">
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

  @stack('scripts')
</body>
</html>
