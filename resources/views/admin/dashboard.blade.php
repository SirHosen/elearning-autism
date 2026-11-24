@extends('layouts.admin')

@section('title', 'Dashboard | Panel Guru')

@push('styles')
<style>
  .admin-page-head {
    margin-bottom: 24px;
  }
  .admin-eyebrow {
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 1.5px;
    color: var(--primary);
    text-transform: uppercase;
    margin-bottom: 4px;
  }
  .admin-panels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
  }
  .admin-panel {
    background-color: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    padding: 20px;
    box-shadow: var(--shadow-sm);
  }
  .admin-panel-title {
    font-size: 1.0625rem;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--line);
  }
  .admin-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .admin-list-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    background-color: var(--surface-alt);
    border-radius: 10px;
  }
  .admin-list-name {
    font-size: 0.9375rem;
    font-weight: 700;
    display: block;
    color: var(--ink);
  }
  .admin-list-meta {
    font-size: 0.8125rem;
    color: var(--ink-soft);
  }
</style>
@endpush

@section('content')
<div class="admin-page-head">
  <p class="admin-eyebrow">Panel Pengelolaan</p>
  <h2 style="font-size: 1.75rem;">Selamat bekerja, Guru</h2>
  <p style="font-size: 0.9375rem; margin-top: 4px;">
    Pantau kehadiran dan kelola materi untuk siswa SLB B-C Wijayakusumah.
  </p>
</div>

<div class="stat-grid">
  <div class="stat-card">
    <span class="stat-card-label">Siswa Aktif</span>
    <strong class="stat-card-value">{{ $totalStudents }}</strong>
  </div>
  <div class="stat-card">
    <span class="stat-card-label">Hadir Hari Ini</span>
    <strong class="stat-card-value" style="color: var(--success);">{{ $todayAttendedCount }}</strong>
  </div>
  <div class="stat-card">
    <span class="stat-card-label">Belum Presensi</span>
    <strong class="stat-card-value" style="color: var(--hint-line);">{{ $unattendedCount }}</strong>
  </div>
  <div class="stat-card">
    <span class="stat-card-label">Materi Pembelajaran</span>
    <strong class="stat-card-value" style="color: var(--primary);">{{ $activeMaterialsCount }}</strong>
  </div>
</div>

<div class="admin-panels-grid">
  <section class="admin-panel">
    <h3 class="admin-panel-title">Belum Presensi Hari Ini</h3>
    @if ($unattendedStudents->isEmpty())
      <div class="feedback-bar feedback-success" style="margin: 0; font-size: 0.9375rem;">
        <span>Seluruh siswa sudah tercatat hadir hari ini.</span>
      </div>
    @else
      <div class="admin-list">
        @foreach ($unattendedStudents->take(6) as $s)
          <div class="admin-list-row">
            <div>
              <span class="admin-list-name">{{ $s->name }}</span>
              <span class="admin-list-meta">{{ $s->class_name ?? 'Umum' }}</span>
            </div>
            <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary" style="min-height: 36px; padding: 0 12px; font-size: 0.8125rem;">
              Catat Hadir
            </a>
          </div>
        @endforeach
      </div>
    @endif
  </section>

  <section class="admin-panel">
    <h3 class="admin-panel-title">Aktivitas Kuis Terbaru</h3>
    @if ($recentQuizAttempts->isEmpty())
      <p style="font-size: 0.9375rem;">Belum ada riwayat pengerjaan kuis.</p>
    @else
      <div class="admin-list">
        @foreach ($recentQuizAttempts as $attempt)
          <div class="admin-list-row">
            <div>
              <span class="admin-list-name">{{ $attempt->user->name }}</span>
              <span class="admin-list-meta">{{ $attempt->quiz->title }}</span>
            </div>
            <span style="font-size: 0.875rem; font-weight: 800; color: var(--success);">
              {{ $attempt->correct_first_try }}/{{ $attempt->total_questions }} Benar
            </span>
          </div>
        @endforeach
      </div>
    @endif
  </section>
</div>
@endsection