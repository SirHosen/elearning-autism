@extends('layouts.student')

@section('title', 'Presensi Kehadiran Siswa | Sahabat Belajar')
@section('page_title', 'Presensi Kehadiran')

@section('content')
<div style="max-width: 580px; margin: 0 auto; padding-top: 16px;">
  <!-- Tombol Kembali -->
  <div style="margin-bottom: 24px;">
    <a href="{{ route('student.home') }}" class="btn btn-secondary" style="font-size: 0.9375rem; font-weight: 700; padding: 10px 20px; border-radius: 999px;">
      &larr; Kembali ke Beranda
    </a>
  </div>

  @if($hasAttendedToday)
    <!-- Kondisi Sudah Hadir -->
    <div class="card" style="text-align: center; padding: 48px 32px; border-radius: 20px; border-top: 5px solid var(--success);">
      <div style="width: 72px; height: 72px; border-radius: 50%; background: var(--success-bg); color: var(--success); display: grid; place-items: center; margin: 0 auto 20px; font-size: 1.75rem; font-weight: 800;">
        H
      </div>
      <h1 style="font-size: 1.875rem; font-weight: 800; color: var(--text); margin-bottom: 8px; letter-spacing: -0.4px;">
        Kehadiranmu Tercatat!
      </h1>
      <p style="color: var(--ink-soft); font-size: 1.0625rem; margin-bottom: 32px; line-height: 1.5;">
        Terima kasih, <strong>{{ $user->name }}</strong>. Kamu sudah hadir dan siap belajar hari ini.
      </p>
      <div style="display: flex; justify-content: center;">
        <a href="{{ route('student.home') }}" class="btn btn-student btn-primary" style="font-size: 1.0625rem; border-radius: 999px;">
          Kembali ke Menu Utama
        </a>
      </div>
    </div>
  @else
    <!-- Kondisi Belum Hadir -->
    <div class="card" style="text-align: center; padding: 48px 32px; border-radius: 20px; border-top: 5px solid var(--primary);">
      <div style="width: 72px; height: 72px; border-radius: 50%; background: var(--tint-blue); color: var(--primary); display: grid; place-items: center; margin: 0 auto 20px; font-size: 1.75rem; font-weight: 800;">
        A
      </div>
      <h1 style="font-size: 1.875rem; font-weight: 800; color: var(--text); margin-bottom: 8px; letter-spacing: -0.4px;">
        Halo, {{ $user->name }}!
      </h1>
      <p style="color: var(--ink-soft); font-size: 1.125rem; margin-bottom: 36px; line-height: 1.5;">
        Apakah kamu hadir untuk belajar hari ini? Tekan tombol di bawah untuk mencatat kehadiranmu.
      </p>

      <form action="{{ route('student.attendance.submit') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-student btn-soft" style="font-size: 1.25rem; font-weight: 800; border-radius: 999px; width: 100%; max-width: 320px; margin: 0 auto;">
          Saya Hadir!
        </button>
      </form>
    </div>
  @endif
</div>

@if(session('success_attended'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    if ('speechSynthesis' in window) {
        const msg = new SpeechSynthesisUtterance('Terima kasih, {{ $user->name }}! Kehadiranmu hari ini sudah tercatat.');
        msg.lang = 'id-ID';
        msg.rate = 0.85;
        window.speechSynthesis.speak(msg);
    }
});
</script>
@endif
@endsection
