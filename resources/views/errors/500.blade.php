@php
  $errUser = null;
  try {
    $errUser = auth()->user();
  } catch (\Throwable) {
    $errUser = null;
  }
  $isAdmin = $errUser?->isAdmin() ?? false;
  $errTheme = $errUser?->setting?->theme ?? 'calm';
  $homeUrl = $isAdmin ? '/admin' : ($errUser ? '/beranda' : '/masuk');
  $homeLabel = $isAdmin ? 'Kembali ke Panel Guru' : 'Kembali ke Menu Utama';
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $errTheme }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ada Perbaikan Kecil - Sahabat Belajar</title>
  <link rel="stylesheet" href="/css/tokens.css?v={{ @filemtime(public_path('css/tokens.css')) ?: '1' }}">
  <link rel="stylesheet" href="/css/themes.css?v={{ @filemtime(public_path('css/themes.css')) ?: '1' }}">
  <link rel="stylesheet" href="/css/base.css?v={{ @filemtime(public_path('css/base.css')) ?: '1' }}">
  <link rel="stylesheet" href="/css/components.css?v={{ @filemtime(public_path('css/components.css')) ?: '2' }}">
</head>
<body class="error-page">
  <main class="error-card">
    <div class="error-badge error-badge--warn" aria-hidden="true">500</div>
    <h1 class="error-title">Sedang Ada Perbaikan Kecil</h1>
    <p class="error-desc">
      Sistem sedang merapikan materi belajar sebentar. Jangan khawatir, kamu bisa kembali ke menu utama sekarang.
    </p>
    <div class="error-actions">
      <a href="{{ $homeUrl }}" class="btn btn-student btn-primary error-cta">{{ $homeLabel }}</a>
    </div>
  </main>
</body>
</html>
