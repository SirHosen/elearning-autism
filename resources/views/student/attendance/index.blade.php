@extends('layouts.student')

@section('title', 'Presensi Kehadiran Siswa')

@section('content')
<div class="container" style="max-width: 600px; padding-top: var(--space-8); padding-bottom: var(--space-10);">
    {{-- Tombol Kembali --}}
    <div style="margin-bottom: var(--space-6);">
        <a href="{{ route('student.home') }}" class="btn btn-secondary" style="font-size: 1.125rem; font-weight: 600; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
            <span>&larr;</span> Kembali
        </a>
    </div>

    @if($hasAttendedToday)
        {{-- Kondisi Sudah Hadir --}}
        <div class="card" style="text-align: center; padding: var(--space-10); border-radius: var(--radius-xl); background: var(--surface); border-top: 8px solid var(--success, #246B4D);">
            <div style="width: 90px; height: 90px; border-radius: 50%; background: var(--success-bg, #E3F2EA); color: var(--success, #246B4D); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-4); font-size: 3rem; animation: pulseGentle 2s infinite;">
                &check;
            </div>
            <h1 style="font-size: 1.875rem; color: var(--text); margin-bottom: var(--space-2);">Kehadiranmu Tercatat!</h1>
            <p style="color: var(--text-muted); font-size: 1.125rem; margin-bottom: var(--space-6);">
                Terima kasih, <strong>{{ $user->name }}</strong>. Kamu sudah hadir dan siap belajar hari ini.
            </p>
            <div style="display: flex; justify-content: center;">
                <a href="{{ route('student.home') }}" class="btn btn-primary" style="font-size: 1.125rem; padding: var(--space-4) var(--space-8); border-radius: var(--radius-full);">
                    Kembali ke Menu Utama
                </a>
            </div>
        </div>
    @else
        {{-- Kondisi Belum Hadir --}}
        <div class="card" style="text-align: center; padding: var(--space-10); border-radius: var(--radius-xl); background: var(--surface);">
            <div style="font-size: 4.5rem; margin-bottom: var(--space-3);">
                🙋‍♂️
            </div>
            <h1 style="font-size: 2rem; color: var(--text); margin-bottom: var(--space-2);">
                Halo, {{ $user->name }}!
            </h1>
            <p style="color: var(--text-muted); font-size: 1.25rem; margin-bottom: var(--space-8);">
                Apakah kamu hadir untuk belajar hari ini?
            </p>

            <form action="{{ route('student.attendance.submit') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" style="font-size: 1.375rem; font-weight: 700; padding: var(--space-5) var(--space-10); border-radius: var(--radius-full); background: var(--success, #246B4D); border-color: var(--success, #246B4D); color: #FFFFFF; display: inline-flex; align-items: center; gap: var(--space-3); box-shadow: var(--shadow-md); transition: transform 0.2s ease;">
                    <span style="font-size: 1.75rem;">&check;</span>
                    <span>Saya Hadir!</span>
                </button>
            </form>
        </div>
    @endif
</div>

<style>
@keyframes pulseGentle {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.06); }
}
</style>

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
