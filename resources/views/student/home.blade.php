@extends('layouts.student')

@section('title', 'Ruang Belajar | EduAutis SLB B-C Wijayakusumah')
@section('page_title', 'Ruang Belajar')

@push('styles')
<style>
  .student-hero {
    text-align: center;
    margin-bottom: 36px;
  }
  .student-eyebrow {
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 1.2px;
    color: var(--primary);
    text-transform: uppercase;
    margin-bottom: 6px;
  }
  .student-greeting {
    font-size: clamp(1.75rem, 3.5vw, 2.25rem);
    font-weight: 800;
    color: var(--text);
    margin-bottom: 8px;
    letter-spacing: -0.5px;
  }
  .student-subtitle {
    color: var(--ink-soft);
    font-size: 1.0625rem;
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.5;
  }
</style>
@endpush

@section('content')
<div class="student-hero">
  <p class="student-eyebrow">Ruang Belajar EduAutis</p>
  <h2 class="student-greeting">Halo, {{ $user->name }}!</h2>
  <p class="student-subtitle">
    Ayo belajar hal baru hari ini. Pilih kegiatan yang kamu sukai di bawah ini.
  </p>
</div>

<div class="tile-grid">
  <!-- 1. BELAJAR -->
  <a href="{{ route('student.learn.index') }}" class="tile tile-blue">
    <div class="tile-monogram">B</div>
    <span class="tile-label">BELAJAR</span>
    <span class="tile-desc">Kenali hewan, warna, dan benda sekitar</span>
  </a>

  <!-- 2. BERMAIN -->
  <a href="{{ route('student.games.index') }}" class="tile tile-green">
    <div class="tile-monogram">M</div>
    <span class="tile-label">BERMAIN</span>
    <span class="tile-desc">Permainan tebak gambar dan motorik seru</span>
  </a>

  <!-- 3. KUIS -->
  <a href="{{ route('student.quizzes.index') }}" class="tile tile-yellow">
    <div class="tile-monogram">K</div>
    <span class="tile-label">KUIS</span>
    <span class="tile-desc">Latihan kuis bergambar dan kumpulkan bintang</span>
  </a>

  <!-- 4. VIDEO -->
  <a href="{{ route('student.videos.index') }}" class="tile tile-lavender">
    <div class="tile-monogram">V</div>
    <span class="tile-label">VIDEO</span>
    <span class="tile-desc">Tonton video pembelajaran menarik dan bernyanyi</span>
  </a>

  <!-- 5. ABSEN -->
  <a href="{{ route('student.attendance.index') }}" class="tile tile-peach" style="position: relative;">
    @if ($hasAttendedToday)
      <span class="tile-badge-done">
        <span>Sudah Hadir</span>
      </span>
    @endif
    <div class="tile-monogram">A</div>
    <span class="tile-label">ABSEN</span>
    <span class="tile-desc">
      {{ $hasAttendedToday ? 'Kehadiranmu hari ini tercatat' : 'Tekan untuk mencatat kehadiranmu' }}
    </span>
  </a>
</div>
@endsection
