@extends('layouts.admin')

@section('title', 'Data Siswa | Panel Pengelolaan Guru')
@section('page_title', 'Data Siswa')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Direktori Peserta Didik</p>
    <h1 class="adm-title">Data Akun Siswa</h1>
    <p class="adm-subtitle">Kelola akun masuk siswa, pantau partisipasi belajar, cetak kartu PIN, dan buka rapor hasil belajar.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.attendances.index') }}" class="adm-btn adm-btn--secondary">Rekap Absensi</a>
    <a href="{{ route('admin.students.create') }}" class="adm-btn adm-btn--primary">+ Tambah Siswa Baru</a>
  </div>
</div>

{{-- Ringkasan Statistik Siswa --}}
<div class="adm-kpi-grid" style="margin-bottom: 20px;">
  <div class="adm-kpi-card adm-kpi-card--blue">
    <span class="adm-kpi-card__label">Total Terdaftar</span>
    <strong class="adm-kpi-card__val">{{ $summaryStats['total'] }}</strong>
    <span class="adm-kpi-card__sub">Seluruh akun peserta didik</span>
  </div>
  <div class="adm-kpi-card adm-kpi-card--green">
    <span class="adm-kpi-card__label">Akun Aktif</span>
    <strong class="adm-kpi-card__val">{{ $summaryStats['active'] }}</strong>
    <span class="adm-kpi-card__sub">Dapat masuk ke ruang belajar siswa</span>
  </div>
  <div class="adm-kpi-card adm-kpi-card--amber">
    <span class="adm-kpi-card__label">Akun Nonaktif</span>
    <strong class="adm-kpi-card__val">{{ $summaryStats['inactive'] }}</strong>
    <span class="adm-kpi-card__sub">Ditangguhkan sementara</span>
  </div>
</div>

{{-- Banner Flash PIN Baru (Tampil Sekali) --}}
@if (session('generated_pin'))
  <div style="background: var(--tint-yellow); border: 2px solid var(--hint-line); border-radius: var(--adm-radius); padding: 20px; margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <p style="font-size: 0.75rem; font-weight: 700; color: #78350F; letter-spacing: 0.8px; margin: 0 0 4px;">
          KARTU PIN DIGITAL SISWA (HANYA TAMPIL SEKALI)
        </p>
        <h2 style="font-size: 1.25rem; margin: 0 0 6px; color: var(--ink);">
          {{ session('student_name') }} (Username: <code>{{ session('student_username') }}</code>)
        </h2>
        <p style="font-size: 1rem; margin: 0; color: var(--ink);">
          PIN Masuk: <strong style="font-size: 1.5rem; letter-spacing: 4px; color: var(--primary-strong);">{{ session('generated_pin') }}</strong>
        </p>
      </div>
      <div>
        <a href="{{ route('admin.students.card', session('student_id')) }}" target="_blank" class="adm-btn adm-btn--primary">
          Cetak Kartu PIN
        </a>
      </div>
    </div>
  </div>
@endif

{{-- Filter & Pencarian Siswa --}}
<form action="{{ route('admin.students.index') }}" method="GET" class="adm-filter-bar">
  <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama siswa, username, atau kelas..." class="adm-input" style="flex: 1; min-width: 220px;">
  <select name="class_name" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Kelas</option>
    @foreach($classes as $cls)
      <option value="{{ $cls }}" {{ request('class_name') === $cls ? 'selected' : '' }}>{{ $cls }}</option>
    @endforeach
  </select>
  <select name="status" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Status</option>
    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
  </select>
  <button type="submit" class="adm-btn adm-btn--secondary adm-btn--sm">Cari</button>
  @if (request()->hasAny(['q', 'class_name', 'status']))
    <a href="{{ route('admin.students.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">Reset</a>
  @endif
</form>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead>
      <tr>
        <th>Nama Siswa &amp; Username</th>
        <th>Kelas</th>
        <th>Aktivitas Belajar</th>
        <th>Terakhir Masuk</th>
        <th>Status</th>
        <th style="text-align: right;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($students as $s)
        @php
          $initials = collect(explode(' ', trim($s->name)))
            ->filter()
            ->map(fn ($w) => strtoupper(substr($w, 0, 1)))
            ->take(2)
            ->implode('');
        @endphp
        <tr>
          <td>
            <div style="display: flex; align-items: center; gap: 10px;">
              <span class="adm-avatar" aria-hidden="true">{{ $initials ?: 'S' }}</span>
              <div>
                <strong style="color: var(--ink);">{{ $s->name }}</strong>
                <div style="font-size: 0.75rem; color: var(--ink-soft);"><code>{{ $s->username }}</code></div>
              </div>
            </div>
          </td>
          <td>{{ $s->class_name ?? '-' }}</td>
          <td>
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
              <span class="adm-pill adm-pill--success">{{ $s->attended_days_count ?? 0 }} Hadir</span>
              <span class="adm-pill adm-pill--warning">{{ $s->completed_quizzes_count ?? 0 }} Kuis</span>
              <span class="adm-pill adm-pill--info">{{ $s->game_sessions_count ?? 0 }} Game</span>
            </div>
          </td>
          <td style="color: var(--ink-soft);">
            {{ $s->last_login_at ? $s->last_login_at->translatedFormat('d M Y, H:i') : 'Belum pernah' }}
          </td>
          <td>
            @if ($s->is_active)
              <span class="adm-pill adm-pill--success">Aktif</span>
            @else
              <span class="adm-pill adm-pill--neutral">Nonaktif</span>
            @endif
          </td>
          <td style="text-align: right;">
            <div class="adm-actions">
              <a href="{{ route('admin.reports.index', ['student_id' => $s->id]) }}" class="adm-btn adm-btn--blue adm-btn--xs" title="Lihat Rapor Siswa">Rapor</a>
              <a href="{{ route('admin.students.card', $s) }}" target="_blank" class="adm-btn adm-btn--secondary adm-btn--xs" title="Cetak Kartu PIN">Kartu</a>
              <form action="{{ route('admin.students.reset-pin', $s) }}" method="POST" onsubmit="return confirm('Reset PIN untuk {{ $s->name }}?')">
                @csrf
                <button type="submit" class="adm-btn adm-btn--secondary adm-btn--xs">Reset PIN</button>
              </form>
              <a href="{{ route('admin.students.edit', $s) }}" class="adm-btn adm-btn--secondary adm-btn--xs">Ubah</a>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 36px; text-align: center; color: var(--ink-soft);">
            Tidak ada data siswa yang sesuai filter.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top: 20px;">
  {{ $students->links() }}
</div>
@endsection
