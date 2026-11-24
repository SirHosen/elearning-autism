@extends('layouts.guest')

@section('title', 'Pilih Peran | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div class="rs-container">

  {{-- Headline --}}
  <div class="rs-hero">
    <span class="rs-badge">Portal Pembelajaran Inklusif</span>
    <h1 class="rs-title">Selamat Datang di EduAutis</h1>
    <p class="rs-subtitle">Pilih pintu masuk sesuai peran Anda untuk memulai.</p>
  </div>

  {{-- Cards --}}
  <div class="rs-grid">

    {{-- ── Siswa ── --}}
    <a href="{{ route('login.student') }}" class="rs-card rs-card--blue">
      <div class="rs-card__top">
        <div class="rs-card__icon rs-card__icon--blue">
          <span class="rs-card__letter">S</span>
        </div>
        <span class="rs-card__tag rs-card__tag--blue">Ruang Belajar</span>
      </div>

      <h2 class="rs-card__title">Saya Siswa</h2>
      <p class="rs-card__desc">Masuk dengan nama pengguna dan PIN untuk belajar, bermain game, dan presensi.</p>

      <ul class="rs-card__list">
        <li>Materi visual gambar &amp; suara narasi</li>
        <li>Permainan edukasi motorik &amp; kuis</li>
        <li>Presensi harian otomatis</li>
      </ul>

      <div class="rs-card__action">
        <span class="rs-card__cta">Masuk sebagai Siswa</span>
        <span class="rs-card__arrow">&rarr;</span>
      </div>
    </a>

    {{-- ── Guru ── --}}
    <a href="{{ route('login.teacher') }}" class="rs-card rs-card--green">
      <div class="rs-card__top">
        <div class="rs-card__icon rs-card__icon--green">
          <span class="rs-card__letter">G</span>
        </div>
        <span class="rs-card__tag rs-card__tag--green">Panel Pendidik</span>
      </div>

      <h2 class="rs-card__title">Saya Guru</h2>
      <p class="rs-card__desc">Masuk dengan email dan kata sandi untuk mengelola materi, kuis, absensi, dan rapor.</p>

      <ul class="rs-card__list">
        <li>Kelola materi foto &amp; video</li>
        <li>Generator kuis ramah autis</li>
        <li>Rekap kehadiran &amp; rapor</li>
      </ul>

      <div class="rs-card__action">
        <span class="rs-card__cta">Masuk sebagai Guru</span>
        <span class="rs-card__arrow">&rarr;</span>
      </div>
    </a>

  </div>
</div>

<style>
/* ══════════════ ROLE SELECT ══════════════ */
.rs-container {
  width: 100%;
  max-width: 820px;
  margin: 0 auto;
}

/* ── Hero ── */
.rs-hero {
  text-align: center;
  margin-bottom: 44px;
}
.rs-badge {
  display: inline-block;
  padding: 5px 16px;
  border-radius: 999px;
  background: var(--tint-blue);
  color: var(--primary);
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.6px;
  text-transform: uppercase;
  margin-bottom: 16px;
}
.rs-title {
  font-size: clamp(1.625rem, 3.5vw, 2.25rem);
  font-weight: 800;
  color: var(--text);
  margin: 0 0 10px;
  letter-spacing: -0.5px;
  line-height: 1.2;
}
.rs-subtitle {
  color: var(--text-muted);
  font-size: 1.0625rem;
  margin: 0;
  line-height: 1.5;
}

/* ── Grid ── */
.rs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
}

/* ── Card ── */
.rs-card {
  position: relative;
  display: flex;
  flex-direction: column;
  padding: 32px 28px 24px;
  border-radius: 16px;
  background: var(--surface);
  border: 1.5px solid var(--line);
  text-decoration: none;
  color: inherit;
  transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
  overflow: hidden;
}
.rs-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 4px;
}
.rs-card--blue::before { background: var(--primary); }
.rs-card--green::before { background: var(--success); }

.rs-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 40px rgba(31,41,51,0.10);
}
.rs-card--blue:hover { border-color: var(--primary); }
.rs-card--green:hover { border-color: var(--success); }

.rs-card:focus-visible {
  outline: 3px solid var(--focus);
  outline-offset: 3px;
}

/* ── Card Top ── */
.rs-card__top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

/* ── Icon (letter monogram) ── */
.rs-card__icon {
  width: 52px; height: 52px;
  border-radius: 14px;
  display: grid;
  place-items: center;
}
.rs-card__icon--blue {
  background: var(--tint-blue);
  border: 1.5px solid rgba(47,111,159,0.18);
}
.rs-card__icon--green {
  background: var(--success-bg);
  border: 1.5px solid rgba(36,107,77,0.18);
}
.rs-card__letter {
  font-size: 1.375rem;
  font-weight: 800;
  line-height: 1;
}
.rs-card__icon--blue .rs-card__letter { color: var(--primary); }
.rs-card__icon--green .rs-card__letter { color: var(--success); }

/* ── Tag ── */
.rs-card__tag {
  font-size: 0.6875rem;
  font-weight: 700;
  letter-spacing: 0.4px;
  padding: 4px 12px;
  border-radius: 999px;
  text-transform: uppercase;
}
.rs-card__tag--blue {
  background: var(--tint-blue);
  color: var(--primary);
}
.rs-card__tag--green {
  background: var(--success-bg);
  color: var(--success);
}

/* ── Card Body ── */
.rs-card__title {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text);
  margin: 0 0 8px;
  letter-spacing: -0.3px;
}
.rs-card__desc {
  color: var(--text-muted);
  font-size: 0.9375rem;
  line-height: 1.55;
  margin: 0 0 20px;
}

/* ── Feature list ── */
.rs-card__list {
  list-style: none;
  padding: 0;
  margin: 0 0 auto;
  display: flex;
  flex-direction: column;
  gap: 7px;
}
.rs-card__list li {
  position: relative;
  padding-left: 20px;
  font-size: 0.84375rem;
  color: var(--text-muted);
  line-height: 1.5;
}
.rs-card__list li::before {
  content: '';
  position: absolute;
  left: 0;
  top: 7px;
  width: 8px; height: 8px;
  border-radius: 50%;
}
.rs-card--blue .rs-card__list li::before { background: var(--primary); opacity: 0.5; }
.rs-card--green .rs-card__list li::before { background: var(--success); opacity: 0.5; }

/* ── Action bar ── */
.rs-card__action {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 24px;
  padding-top: 18px;
  border-top: 1px solid var(--line);
}
.rs-card__cta {
  font-weight: 700;
  font-size: 0.9375rem;
}
.rs-card--blue .rs-card__cta { color: var(--primary); }
.rs-card--green .rs-card__cta { color: var(--success); }

.rs-card__arrow {
  width: 34px; height: 34px;
  border-radius: 50%;
  display: grid;
  place-items: center;
  font-size: 1.125rem;
  font-weight: 400;
  color: var(--on-primary);
  transition: transform 0.18s ease;
}
.rs-card--blue .rs-card__arrow { background: var(--primary); }
.rs-card--green .rs-card__arrow { background: var(--success); }
.rs-card:hover .rs-card__arrow { transform: translateX(3px); }
</style>
@endsection
