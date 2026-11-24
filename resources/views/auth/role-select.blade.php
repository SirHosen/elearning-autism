@extends('layouts.guest')

@section('title', 'Pilih Peran | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div style="width: 100%; max-width: 780px; margin: 0 auto; text-align: center;">

  <!-- Lencana Header -->
  <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; border-radius: 999px; background: rgba(37, 99, 235, 0.08); border: 1px solid rgba(37, 99, 235, 0.2); color: var(--primary, #1D4ED8); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 20px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="10"></circle>
      <polyline points="12 6 12 12 16 14"></polyline>
    </svg>
    Portal Pembelajaran Inklusif
  </div>

  <!-- Judul Utama & Penjelasan -->
  <h1 style="font-size: clamp(26px, 3.5vw, 38px); font-weight: 800; color: var(--text); margin: 0 0 12px 0; letter-spacing: -0.5px; line-height: 1.25;">
    Selamat Datang di EduAutis
  </h1>
  <p style="color: var(--text-muted); font-size: 1.0625rem; max-width: 540px; margin: 0 auto 40px auto; line-height: 1.6;">
    Silakan pilih pintu masuk sesuai peran Anda untuk mengakses ruang belajar visual-audio atau panel administrasi pengajar.
  </p>

  <!-- Pilihan Kartu Peran -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 24px; text-align: left;">

    <!-- KARTU 1: SISWA -->
    <a href="{{ route('login.student') }}" class="card role-card" style="text-decoration: none; color: inherit; padding: 32px 28px; border-radius: 18px; border: 2px solid var(--border); background: var(--surface); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: var(--shadow-sm); position: relative; overflow: hidden;">
      <!-- Accent Top Border -->
      <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #2563EB, #3B82F6);"></div>

      <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
          <!-- SVG Icon Siswa Modern -->
          <div style="width: 58px; height: 58px; border-radius: 14px; background: rgba(37, 99, 235, 0.08); border: 1px solid rgba(37, 99, 235, 0.16); display: flex; align-items: center; justify-content: center; color: #1D4ED8;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
              <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
            </svg>
          </div>
          <span style="font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 999px; background: rgba(37, 99, 235, 0.08); color: #1D4ED8; text-transform: uppercase;">
            Area Belajar
          </span>
        </div>

        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text); margin: 0 0 8px 0; letter-spacing: -0.3px;">
          Saya Siswa
        </h2>
        <p style="color: var(--text-muted); font-size: 0.9375rem; line-height: 1.55; margin: 0 0 20px 0;">
          Masuk dengan nama pengguna dan 4 digit PIN angka untuk mulai belajar, bermain game, dan presensi.
        </p>

        <!-- Fitur Siswa (SVG Checkmarks) -->
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 0.84375rem; color: var(--text-muted);">
          <li style="display: flex; align-items: center; gap: 10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Materi visual gambar & suara narasi ramah</span>
          </li>
          <li style="display: flex; align-items: center; gap: 10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Permainan edukasi motorik & kuis bergambar</span>
          </li>
          <li style="display: flex; align-items: center; gap: 10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Tombol sentuh besar & numpad angka intuitif</span>
          </li>
        </ul>
      </div>

      <!-- Action Bar Kartu -->
      <div style="margin-top: 28px; padding-top: 18px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
        <span style="font-weight: 700; font-size: 0.9375rem; color: #1D4ED8;">Masuk Ruang Siswa</span>
        <div class="card-btn-icon" style="width: 36px; height: 36px; border-radius: 50%; background: #1D4ED8; color: #FFFFFF; display: flex; align-items: center; justify-content: center; transition: transform 0.2s ease;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </div>
      </div>
    </a>

    <!-- KARTU 2: GURU -->
    <a href="{{ route('login.teacher') }}" class="card role-card" style="text-decoration: none; color: inherit; padding: 32px 28px; border-radius: 18px; border: 2px solid var(--border); background: var(--surface); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: var(--shadow-sm); position: relative; overflow: hidden;">
      <!-- Accent Top Border -->
      <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #059669, #10B981);"></div>

      <div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
          <!-- SVG Icon Guru Modern -->
          <div style="width: 58px; height: 58px; border-radius: 14px; background: rgba(5, 150, 105, 0.08); border: 1px solid rgba(5, 150, 105, 0.16); display: flex; align-items: center; justify-content: center; color: #047857;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
          </div>
          <span style="font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 999px; background: rgba(5, 150, 105, 0.08); color: #047857; text-transform: uppercase;">
            Panel Pendidik
          </span>
        </div>

        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text); margin: 0 0 8px 0; letter-spacing: -0.3px;">
          Saya Guru
        </h2>
        <p style="color: var(--text-muted); font-size: 0.9375rem; line-height: 1.55; margin: 0 0 20px 0;">
          Masuk dengan email dan kata sandi untuk mengelola materi, kartu siswa, kuis, absensi, dan rapor.
        </p>

        <!-- Fitur Guru (SVG Checkmarks) -->
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 0.84375rem; color: var(--text-muted);">
          <li style="display: flex; align-items: center; gap: 10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Kelola materi foto objek nyata & video</span>
          </li>
          <li style="display: flex; align-items: center; gap: 10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Generator kuis instan 1-klik ramah autis</span>
          </li>
          <li style="display: flex; align-items: center; gap: 10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#246B4D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Rekap kehadiran & cetak rapor ringkas siap print</span>
          </li>
        </ul>
      </div>

      <!-- Action Bar Kartu -->
      <div style="margin-top: 28px; padding-top: 18px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
        <span style="font-weight: 700; font-size: 0.9375rem; color: #047857;">Masuk Panel Guru</span>
        <div class="card-btn-icon" style="width: 36px; height: 36px; border-radius: 50%; background: #047857; color: #FFFFFF; display: flex; align-items: center; justify-content: center; transition: transform 0.2s ease;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </div>
      </div>
    </a>

  </div>
</div>

<style>
.role-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
  border-color: rgba(37, 99, 235, 0.4);
}
.role-card:hover .card-btn-icon {
  transform: translateX(3px);
}
.role-card:focus-visible {
  outline: 3px solid var(--primary);
  outline-offset: 3px;
}
</style>
@endsection
