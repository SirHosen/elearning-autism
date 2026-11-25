@extends('layouts.student')

@section('title', 'Presensi Kehadiran Siswa | Sahabat Belajar')
@section('page_title', 'Presensi Kehadiran')

@section('content')
<div class="st-page-shell st-page-shell--narrow" style="max-width: 600px;">
  <div class="st-page-bar">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      <span>Kembali ke Beranda</span>
    </a>

    <span class="st-topic-pill {{ $hasAttendedToday ? 'st-topic-pill--green' : 'st-topic-pill--peach' }}">
      {{ $hasAttendedToday ? 'Sudah Hadir' : 'Presensi Hari Ini' }}
    </span>
  </div>

  @if($hasAttendedToday)
    <!-- Kondisi Sudah Hadir -->
    <div class="st-attendance-card">
      <div class="st-attendance-art" aria-hidden="true">
        <svg viewBox="0 0 144 144" width="144" height="144" fill="none">
          <circle cx="72" cy="72" r="64" fill="#DDF0E8"/>
          <rect x="30" y="34" width="84" height="78" rx="18" fill="#FFFFFF" stroke="#246B4D" stroke-width="4"/>
          <rect x="30" y="34" width="84" height="22" rx="12" fill="#246B4D"/>
          <rect x="48" y="24" width="10" height="18" rx="5" fill="#17523A"/>
          <rect x="86" y="24" width="10" height="18" rx="5" fill="#17523A"/>
          <circle cx="72" cy="84" r="22" fill="#E3F2EA"/>
          <polyline points="60 84 69 93 85 75" stroke="#246B4D" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <h2 style="font-size: clamp(1.625rem, 4vw, 2rem); font-weight: 700; color: var(--ink); margin: 0 0 10px;">
        Kehadiranmu Tercatat!
      </h2>
      <p style="color: var(--ink-soft); font-size: calc(1.0625rem * var(--font-scale)); margin: 0 0 28px; line-height: 1.5;">
        Terima kasih, <strong>{{ $user->name }}</strong>. Kamu sudah hadir dan siap belajar hari ini.
      </p>

      <div style="display: flex; justify-content: center;">
        <a href="{{ route('student.home') }}" class="btn btn-student btn-primary" style="border-radius: 999px; min-width: 240px;">
          Kembali ke Menu Utama
        </a>
      </div>
    </div>
  @else
    <!-- Kondisi Belum Hadir -->
    <div class="st-attendance-card">
      <div class="st-attendance-art" aria-hidden="true">
        <svg viewBox="0 0 144 144" width="144" height="144" fill="none">
          <circle cx="72" cy="72" r="64" fill="#FCEFC7"/>
          <circle cx="72" cy="74" r="34" fill="#FBBF24"/>
          <g stroke="#D97706" stroke-width="6" stroke-linecap="round">
            <line x1="72" y1="20" x2="72" y2="30"/>
            <line x1="72" y1="118" x2="72" y2="128"/>
            <line x1="18" y1="74" x2="28" y2="74"/>
            <line x1="116" y1="74" x2="126" y2="74"/>
            <line x1="34" y1="36" x2="41" y2="43"/>
            <line x1="103" y1="105" x2="110" y2="112"/>
            <line x1="110" y1="36" x2="103" y2="43"/>
            <line x1="41" y1="105" x2="34" y2="112"/>
          </g>
          <circle cx="60" cy="70" r="4" fill="#78350F"/>
          <circle cx="84" cy="70" r="4" fill="#78350F"/>
          <path d="M60 82 Q72 91 84 82" stroke="#78350F" stroke-width="4" stroke-linecap="round"/>
        </svg>
      </div>

      <h2 style="font-size: clamp(1.625rem, 4vw, 2rem); font-weight: 700; color: var(--ink); margin: 0 0 10px;">
        Halo, {{ $user->name }}!
      </h2>
      <p style="color: var(--ink-soft); font-size: calc(1.0625rem * var(--font-scale)); margin: 0 0 32px; line-height: 1.5;">
        Apakah kamu hadir untuk belajar hari ini? Tekan tombol hijau di bawah ini untuk mencatat kehadiranmu.
      </p>

      <form action="{{ route('student.attendance.submit') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-student btn-soft" style="font-size: calc(1.25rem * var(--font-scale)); font-weight: 700; border-radius: 999px; width: 100%; max-width: 320px; margin: 0 auto; border-bottom-width: 5px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span>Saya Hadir!</span>
        </button>
      </form>
    </div>
  @endif
</div>

@if(session('success_attended'))
@php
  $attendanceGreeting = 'Terima kasih, ' . $user->name . '! Kehadiranmu hari ini sudah tercatat.';
@endphp
<script>
document.addEventListener('DOMContentLoaded', function() {
    const msgText = @json($attendanceGreeting);
    if (window.audioManager) {
        window.audioManager.play({ text: msgText });
        return;
    }
    if ('speechSynthesis' in window) {
        const msg = new SpeechSynthesisUtterance(msgText);
        msg.lang = 'id-ID';
        msg.rate = 0.85;
        window.speechSynthesis.speak(msg);
    }
});
</script>
@endif
@endsection
