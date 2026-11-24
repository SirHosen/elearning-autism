@extends('layouts.student')

@section('title', 'Ruang Belajar | EduAutis SLB B-C Wijayakusumah')
@section('page_title', 'Ruang Belajar')

@section('content')
<div style="text-align: center; margin-bottom: 32px;">
  <p style="font-size: 13px; font-weight: 800; letter-spacing: 2px; color: var(--primary); margin-bottom: 6px;">RUANG BELAJAR EDUAUTIS</p>
  <h2 style="font-size: var(--fs-title); margin-bottom: 8px;">Halo, {{ $user->name }}! 👋</h2>
  <p style="color: var(--ink-soft); font-size: var(--fs-base); max-width: 520px; margin: 0 auto;">
    Ayo belajar hal baru hari ini. Pilih kegiatan yang kamu sukai di bawah ini.
  </p>
</div>

<div class="tile-grid">
  <!-- 1. BELAJAR -->
  <a href="{{ route('student.learn.index') }}" class="tile tile-blue" style="position: relative;">
    <span class="tile-icon">🐾</span>
    <span class="tile-label">BELAJAR</span>
    <small style="color: var(--ink-soft); font-size: 14px; margin-top: 4px;">Kenali hewan, warna, dan benda</small>
  </a>

  <!-- 2. BERMAIN -->
  <a href="{{ route('student.games.index') }}" class="tile tile-green">
    <span class="tile-icon">🎮</span>
    <span class="tile-label">BERMAIN</span>
    <small style="color: var(--ink-soft); font-size: 14px; margin-top: 4px;">Permainan tebak gambar seru</small>
  </a>

  <!-- 3. KUIS -->
  <a href="{{ route('student.quizzes.index') }}" class="tile tile-yellow">
    <span class="tile-icon">⭐</span>
    <span class="tile-label">KUIS</span>
    <small style="color: var(--ink-soft); font-size: 14px; margin-top: 4px;">Latihan kuis bergambar</small>
  </a>

  <!-- 4. VIDEO -->
  <a href="{{ route('student.videos.index') }}" class="tile tile-lavender">
    <span class="tile-icon">🎬</span>
    <span class="tile-label">VIDEO</span>
    <small style="color: var(--ink-soft); font-size: 14px; margin-top: 4px;">Tonton video pembelajaran</small>
  </a>

  <!-- 5. ABSEN (Dengan Lencana Centang Jika Sudah Hadir) -->
  <a href="{{ route('student.attendance.index') }}" class="tile tile-peach" style="position: relative;">
    @if ($hasAttendedToday)
      <div style="position: absolute; top: 12px; right: 12px; background: var(--success); color: #fff; font-size: 12px; font-weight: 800; padding: 4px 10px; border-radius: 20px; display: flex; align-items: center; gap: 4px;">
        <span>✓</span> <span>Sudah Hadir</span>
      </div>
    @endif
    <span class="tile-icon">📅</span>
    <span class="tile-label">ABSEN</span>
    <small style="color: var(--ink-soft); font-size: 14px; margin-top: 4px;">
      {{ $hasAttendedToday ? 'Kehadiranmu hari ini tercatat' : 'Tekan untuk mencatat kehadiranmu' }}
    </small>
  </a>
</div>
@endsection
