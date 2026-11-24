@extends('layouts.admin')

@section('title', 'Dashboard | Panel Guru')

@section('content')
<div style="margin-bottom: 28px;">
  <p style="font-size: 13px; font-weight: 800; letter-spacing: 2px; color: var(--primary); margin-bottom: 4px;">PANEL PENGELOLAAN</p>
  <h2 style="font-size: 28px;">Selamat bekerja, Guru! 👋</h2>
  <p style="color: var(--ink-soft); font-size: 15px; margin-top: 4px;">Pantau kehadiran dan kelola materi untuk siswa SLB B-C Wijayakusumah.</p>
</div>

<!-- Statistik Ringkasan -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <small style="color: var(--ink-soft); font-weight: 700;">Siswa Aktif</small>
    <strong style="display: block; font-size: 32px; margin-top: 6px; color: var(--ink);">{{ $totalStudents }}</strong>
  </div>
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <small style="color: var(--ink-soft); font-weight: 700;">Hadir Hari Ini</small>
    <strong style="display: block; font-size: 32px; margin-top: 6px; color: var(--success);">{{ $todayAttendedCount }}</strong>
  </div>
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <small style="color: var(--ink-soft); font-weight: 700;">Belum Presensi</small>
    <strong style="display: block; font-size: 32px; margin-top: 6px; color: #D97706;">{{ $unattendedCount }}</strong>
  </div>
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <small style="color: var(--ink-soft); font-weight: 700;">Materi Pembelajaran</small>
    <strong style="display: block; font-size: 32px; margin-top: 6px; color: var(--primary);">{{ $activeMaterialsCount }}</strong>
  </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
  <!-- Daftar Belum Absen Hari Ini -->
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <h3 style="font-size: 18px; margin-bottom: 14px;">Belum Presensi Hari Ini</h3>
    @if ($unattendedStudents->isEmpty())
      <p style="color: var(--success); font-weight: 700; font-size: 14px;">🎉 Luar biasa! Seluruh siswa sudah presensi hari ini.</p>
    @else
      <div style="display: flex; flex-direction: column; gap: 8px;">
        @foreach ($unattendedStudents->take(6) as $s)
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: var(--surface-alt); border-radius: 8px;">
            <div>
              <strong style="font-size: 14px; display: block;">{{ $s->name }}</strong>
              <small style="color: var(--ink-soft); font-size: 12px;">{{ $s->class_name ?? 'Umum' }}</small>
            </div>
            <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary" style="min-height: 32px; padding: 0 10px; font-size: 12px;">
              Catat Kehadiran
            </a>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <!-- Aktivitas Kuis Terbaru -->
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <h3 style="font-size: 18px; margin-bottom: 14px;">Aktivitas Kuis Terbaru</h3>
    @if ($recentQuizAttempts->isEmpty())
      <p style="color: var(--ink-soft); font-size: 14px;">Belum ada riwayat pengerjaan kuis.</p>
    @else
      <div style="display: flex; flex-direction: column; gap: 8px;">
        @foreach ($recentQuizAttempts as $attempt)
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: var(--surface-alt); border-radius: 8px;">
            <div>
              <strong style="font-size: 14px; display: block;">{{ $attempt->user->name }}</strong>
              <small style="color: var(--ink-soft); font-size: 12px;">{{ $attempt->quiz->title }}</small>
            </div>
            <span style="font-size: 13px; font-weight: 800; color: var(--success);">
              {{ $attempt->correct_first_try }}/{{ $attempt->total_questions }} Benar
            </span>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection
