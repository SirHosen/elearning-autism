<!DOCTYPE html>
<html lang="id" data-theme="calm" data-font-scale="normal" data-motion="minimal">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panduan Gaya Komponen (Styleguide) - EduAutis</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <link rel="stylesheet" href="/css/themes.css">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/components.css">
  <link rel="stylesheet" href="/css/layouts.css">
  <style>
    .sg-section { margin-bottom: 48px; padding-bottom: 24px; border-bottom: 1px solid var(--line); }
    .sg-row { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; margin-top: 16px; }
  </style>
</head>
<body>
  <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

  <header class="student-header">
    <a href="/beranda" class="btn btn-secondary" style="min-height: 48px; padding: 0 16px;">Beranda</a>
    <h1 class="student-header-title">Panduan Gaya Komponen (/dev/styleguide)</h1>
    <div class="student-header-actions">
      <button class="btn btn-secondary" onclick="toggleTheme()" style="min-height: 48px; padding: 0 16px;">Ubah Tema</button>
    </div>
  </header>

  <main id="main-content" class="student-container">
    <section class="sg-section">
      <h2>1. Tombol Siswa (Area Sentuh Min 64px)</h2>
      <div class="sg-row">
        <button class="btn btn-student btn-primary">Mulai Belajar <span>&rarr;</span></button>
        <button class="btn btn-student btn-secondary">Sebelumnya</button>
        <button class="btn btn-student btn-soft">Benar! Lanjut</button>
        <button class="btn btn-student btn-secondary" disabled>Tombol Dinonaktifkan</button>
      </div>
    </section>

    <section class="sg-section">
      <h2>2. Speaker Button (Audio Manager)</h2>
      <div class="sg-row">
        <button class="btn-speaker" id="demoSpeaker" onclick="toggleSpeakerDemo()">
          <span id="speakerIcon" style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: var(--primary);"></span>
          <span id="speakerText">Dengar</span>
        </button>
        <button class="btn-speaker is-playing">
          <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: var(--primary);"></span>
          <span>Sedang berbunyi...</span>
        </button>
        <button class="btn-speaker" style="opacity: 0.6; cursor: not-allowed;">
          <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: var(--line);"></span>
          <span>Suara tidak tersedia</span>
        </button>
      </div>
    </section>

    <section class="sg-section">
      <h2>3. Navigasi Tile Siswa (Min 180&times;180px)</h2>
      <div class="tile-grid">
        <div class="tile tile-blue">
          <div class="tile-monogram">B</div>
          <span class="tile-label">BELAJAR</span>
          <span class="tile-desc">Kenali hewan, warna, dan benda sekitar</span>
        </div>
        <div class="tile tile-green">
          <div class="tile-monogram">M</div>
          <span class="tile-label">BERMAIN</span>
          <span class="tile-desc">Permainan tebak gambar dan motorik seru</span>
        </div>
        <div class="tile tile-yellow">
          <div class="tile-monogram">K</div>
          <span class="tile-label">KUIS</span>
          <span class="tile-desc">Latihan kuis bergambar dan kumpulkan bintang</span>
        </div>
        <div class="tile tile-lavender">
          <div class="tile-monogram">V</div>
          <span class="tile-label">VIDEO</span>
          <span class="tile-desc">Tonton video pembelajaran menarik</span>
        </div>
        <div class="tile tile-peach">
          <div class="tile-monogram">A</div>
          <span class="tile-label">ABSEN</span>
          <span class="tile-desc">Tekan untuk mencatat kehadiranmu</span>
        </div>
      </div>
    </section>

    <section class="sg-section">
      <h2>4. Bilah Umpan Balik (FeedbackBar)</h2>
      <div class="feedback-bar feedback-success" role="status" aria-live="polite">
        <span>Benar! Hebat sekali!</span>
      </div>
      <div class="feedback-bar feedback-hint" role="status" aria-live="polite">
        <span>Coba lagi ya. Kamu pasti bisa!</span>
      </div>
    </section>

    <section class="sg-section">
      <h2>5. Numpad PIN Siswa</h2>
      <div class="numpad">
        <button class="numpad-btn">1</button>
        <button class="numpad-btn">2</button>
        <button class="numpad-btn">3</button>
        <button class="numpad-btn">4</button>
        <button class="numpad-btn">5</button>
        <button class="numpad-btn">6</button>
        <button class="numpad-btn">7</button>
        <button class="numpad-btn">8</button>
        <button class="numpad-btn">9</button>
        <button class="numpad-btn" style="color: var(--danger); font-size: 16px;">Hapus</button>
        <button class="numpad-btn">0</button>
        <button class="numpad-btn" style="color: #fff; background: var(--primary);">Masuk</button>
      </div>
    </section>
  </main>

  <script>
    function toggleTheme() {
      const html = document.documentElement;
      const current = html.getAttribute('data-theme');
      html.setAttribute('data-theme', current === 'calm' ? 'contrast' : 'calm');
    }

    function toggleSpeakerDemo() {
      const btn = document.getElementById('demoSpeaker');
      btn.classList.toggle('is-playing');
    }
  </script>
</body>
</html>
