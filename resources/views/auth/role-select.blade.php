@extends('layouts.guest')

@section('title', 'Pilih Peran | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div class="auth-shell">

  {{-- Panel Kiri: Konteks Portal Pembelajaran --}}
  <aside class="auth-aside">
    <div>
      <span class="auth-aside__eyebrow">Portal Pembelajaran Inklusif</span>
      <h2 class="auth-aside__title">Belajar Tenang dengan Visual &amp; Audio</h2>
      <p class="auth-aside__lead">
        Lingkungan belajar digital yang dirancang khusus untuk kenyamanan sensori siswa SLB B-C Wijayakusumah serta kemudahan pengelolaan guru.
      </p>
    </div>

    <div class="auth-preview-box">
      <div class="auth-preview-box__label">Tiga Pilar Pembelajaran</div>
      <ul class="auth-preview-list">
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--blue" aria-hidden="true"></span>
            <span>Materi Visual Terfokus</span>
          </span>
          <span class="auth-preview-num">01</span>
        </li>
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--green" aria-hidden="true"></span>
            <span>Narasi Audio Terkendali</span>
          </span>
          <span class="auth-preview-num">02</span>
        </li>
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--amber" aria-hidden="true"></span>
            <span>Latihan Kuis &amp; Permainan</span>
          </span>
          <span class="auth-preview-num">03</span>
        </li>
      </ul>
    </div>

    <div class="auth-aside__meta">
      <span>Sumedang, Jawa Barat</span>
      <span>Tanpa Autoplay &middot; WCAG AA</span>
    </div>
  </aside>

  {{-- Panel Kanan: Pilihan Pintu Masuk --}}
  <section class="auth-panel" aria-labelledby="role-heading">
    <nav class="auth-tabs" aria-label="Navigasi Halaman Masuk">
      <a href="{{ route('login') }}" class="auth-tab is-active" aria-current="page">Pilih Peran</a>
      <a href="{{ route('login.student') }}" class="auth-tab">Masuk Siswa</a>
      <a href="{{ route('login.teacher') }}" class="auth-tab">Masuk Guru</a>
    </nav>

    <div class="auth-head">
      <h1 id="role-heading" class="auth-head__title">Selamat Datang di EduAutis</h1>
      <p class="auth-head__desc">Pilih pintu masuk sesuai peran Anda untuk memulai.</p>
    </div>

    <div class="role-list">
      <a href="{{ route('login.student') }}" class="role-tile role-tile--student">
        <div class="role-tile__top">
          <span class="role-tile__pill role-tile__pill--blue">Area Belajar</span>
          <span class="role-tile__spec">Username &amp; PIN 4–6 Angka</span>
        </div>
        <h2 class="role-tile__title">Saya Siswa</h2>
        <p class="role-tile__desc">
          Masuk untuk melihat kartu belajar, mendengarkan suara, bermain gim edukasi, kuis, dan mencatat kehadiran.
        </p>
        <div class="role-tile__cta">
          <span>Buka Halaman Masuk Siswa</span>
          <span class="role-tile__arrow" aria-hidden="true">&rarr;</span>
        </div>
      </a>

      <a href="{{ route('login.teacher') }}" class="role-tile role-tile--teacher">
        <div class="role-tile__top">
          <span class="role-tile__pill role-tile__pill--green">Panel Pendidik</span>
          <span class="role-tile__spec">Email &amp; Kata Sandi</span>
        </div>
        <h2 class="role-tile__title">Saya Guru</h2>
        <p class="role-tile__desc">
          Masuk untuk mengelola kategori, materi visual-audio, video, permainan, kuis, akun siswa, absensi, dan rapor.
        </p>
        <div class="role-tile__cta">
          <span>Buka Halaman Masuk Guru</span>
          <span class="role-tile__arrow" aria-hidden="true">&rarr;</span>
        </div>
      </a>
    </div>
  </section>

</div>
@endsection
