@extends('layouts.guest')

@section('title', 'Pilih Peran | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div style="width: 100%; max-width: 680px; text-align: center;">
  <div style="display: inline-block; width: 64px; height: 64px; border-radius: 20px; background: var(--primary); color: #fff; line-height: 64px; font-weight: 900; font-size: 24px; margin-bottom: 16px;">EA</div>
  <p style="font-size: 13px; font-weight: 800; letter-spacing: 2px; color: var(--primary); margin-bottom: 8px;">MEDIA PEMBELAJARAN INTERAKTIF</p>
  <h1 style="font-size: clamp(28px, 4vw, 42px); margin-bottom: 12px;">Selamat Datang di EduAutis</h1>
  <p style="color: var(--ink-soft); font-size: 18px; max-width: 480px; margin: 0 auto 36px;">
    Silakan pilih peran kamu untuk mulai menggunakan ruang belajar.
  </p>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;">
    <!-- Kartu Siswa -->
    <a href="{{ route('login.student') }}" class="tile tile-blue" style="min-height: 220px; padding: 28px; text-decoration: none;">
      <span class="tile-icon">🎒</span>
      <span class="tile-label" style="font-size: 24px; margin-bottom: 8px;">Saya Siswa</span>
      <p style="color: var(--ink-soft); font-size: 15px; margin: 0;">Masuk dengan nama pengguna dan PIN angka.</p>
    </a>

    <!-- Kartu Guru -->
    <a href="{{ route('login.teacher') }}" class="tile tile-green" style="min-height: 220px; padding: 28px; text-decoration: none;">
      <span class="tile-icon">🧑‍🏫</span>
      <span class="tile-label" style="font-size: 24px; margin-bottom: 8px;">Saya Guru</span>
      <p style="color: var(--ink-soft); font-size: 15px; margin: 0;">Masuk untuk mengelola materi dan melihat absensi.</p>
    </a>
  </div>
</div>
@endsection
