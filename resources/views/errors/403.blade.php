<!DOCTYPE html>
<html lang="id" data-theme="calm">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akses Tidak Diizinkan - EduAutis</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css">
</head>
<body style="display: grid; place-items: center; min-height: 100vh; padding: 24px;">
  <div style="background: var(--surface); border: var(--border) solid var(--line); border-radius: var(--radius); padding: 40px; max-width: 500px; text-align: center;">
    <div style="font-size: 72px; margin-bottom: 16px;">🛑</div>
    <h1 style="font-size: var(--fs-title); margin-bottom: 12px;">Halaman Terkunci</h1>
    <p style="color: var(--ink-soft); font-size: var(--fs-base); margin-bottom: 24px;">
      {{ $message ?? 'Ups! Kamu tidak dapat membuka halaman ini. Ayo kembali belajar bersama teman-teman.' }}
    </p>
    <a href="/beranda" class="btn btn-student btn-primary">Kembali ke Beranda ⌂</a>
  </div>
</body>
</html>
