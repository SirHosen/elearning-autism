@extends('layouts.student')

@section('title', 'Beranda Belajar | EduAutis')
@section('page_title', 'Ruang Belajar')

@section('content')
<div style="text-align: center; margin-bottom: 32px;">
  <p style="font-size: 14px; font-weight: 800; letter-spacing: 2px; color: var(--primary); margin-bottom: 6px;">SELAMAT DATANG</p>
  <h2 style="font-size: var(--fs-title);">Halo, {{ auth()->user()->name }}! 👋</h2>
  <p style="color: var(--ink-soft); font-size: var(--fs-base); margin-top: 6px;">
    Ayo belajar hal baru hari ini. Pilih kegiatan yang kamu sukai.
  </p>
</div>

<div class="tile-grid">
  <!-- Belajar -->
  <a href="#" class="tile tile-blue">
    <span class="tile-icon">🐾</span>
    <span class="tile-label">BELAJAR</span>
  </a>

  <!-- Bermain -->
  <a href="#" class="tile tile-green">
    <span class="tile-icon">🎮</span>
    <span class="tile-label">BERMAIN</span>
  </a>

  <!-- Kuis -->
  <a href="#" class="tile tile-yellow">
    <span class="tile-icon">⭐</span>
    <span class="tile-label">KUIS</span>
  </a>

  <!-- Video -->
  <a href="#" class="tile tile-lavender">
    <span class="tile-icon">🎬</span>
    <span class="tile-label">VIDEO</span>
  </a>

  <!-- Absen -->
  <a href="#" class="tile tile-peach">
    <span class="tile-icon">📅</span>
    <span class="tile-label">ABSEN</span>
  </a>
</div>
@endsection
