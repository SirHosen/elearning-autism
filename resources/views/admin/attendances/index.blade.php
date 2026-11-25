@extends('layouts.admin')

@section('title', 'Rekap Absensi Siswa | Panel Pengelolaan Guru')
@section('page_title', 'Rekap Absensi')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Monitoring Kehadiran Harian</p>
    <h1 class="adm-title">Rekap Absensi Siswa</h1>
    <p class="adm-subtitle">Pantau presensi mandiri siswa dan perbarui status beserta catatan keterangan guru.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.attendances.export', ['date' => $date]) }}" class="adm-btn adm-btn--primary">
      Unduh CSV ({{ $date }})
    </a>
  </div>
</div>

{{-- Strip Ringkasan Kehadiran Harian --}}
<div class="adm-kpi-grid" style="margin-bottom: 20px;">
  <div class="adm-kpi-card adm-kpi-card--green">
    <span class="adm-kpi-card__label">Hadir</span>
    <strong class="adm-kpi-card__val">{{ $statusSummary['hadir'] }}</strong>
    <span class="adm-kpi-card__sub">dari {{ $statusSummary['total'] }} siswa</span>
  </div>
  <div class="adm-kpi-card adm-kpi-card--blue">
    <span class="adm-kpi-card__label">Izin</span>
    <strong class="adm-kpi-card__val">{{ $statusSummary['izin'] }}</strong>
    <span class="adm-kpi-card__sub">Dengan keterangan orang tua/wali</span>
  </div>
  <div class="adm-kpi-card adm-kpi-card--amber">
    <span class="adm-kpi-card__label">Sakit</span>
    <strong class="adm-kpi-card__val">{{ $statusSummary['sakit'] }}</strong>
    <span class="adm-kpi-card__sub">Siswa sedang kurang sehat</span>
  </div>
  <div class="adm-kpi-card adm-kpi-card--purple">
    <span class="adm-kpi-card__label">Alpa / Belum Absen</span>
    <strong class="adm-kpi-card__val">{{ $statusSummary['alpa'] + $statusSummary['belum'] }}</strong>
    <span class="adm-kpi-card__sub">Alpa: {{ $statusSummary['alpa'] }} &bull; Belum: {{ $statusSummary['belum'] }}</span>
  </div>
</div>

{{-- Filter Tanggal & Kelas --}}
<form action="{{ route('admin.attendances.index') }}" method="GET" class="adm-filter-bar">
  <label for="date" style="font-weight: 700; font-size: 0.875rem;">Tanggal:</label>
  <input type="date" name="date" id="date" class="adm-input" value="{{ $date }}" onchange="this.form.submit()">

  <label for="class_name" style="font-weight: 700; font-size: 0.875rem;">Kelas:</label>
  <select name="class_name" id="class_name" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Kelas</option>
    @foreach($classes as $cls)
      <option value="{{ $cls }}" {{ request('class_name') === $cls ? 'selected' : '' }}>{{ $cls }}</option>
    @endforeach
  </select>

  <button type="submit" class="adm-btn adm-btn--secondary adm-btn--sm">Lihat Absensi</button>
  @if($date !== now()->format('Y-m-d') || request('class_name'))
    <a href="{{ route('admin.attendances.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">Hari Ini</a>
  @endif
</form>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead>
      <tr>
        <th style="width: 52px;">No</th>
        <th>Siswa</th>
        <th>Status</th>
        <th>Masuk / Pencatat</th>
        <th>Ubah Status &amp; Catatan</th>
        <th style="text-align: right;">Rapor</th>
      </tr>
    </thead>
    <tbody>
      @forelse($students as $index => $student)
        @php
          $att = $attendances->get($student->id);
          $status = $att?->status;
          $initials = strtoupper(substr($student->name, 0, 2));
        @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>
            <div style="display: flex; align-items: center; gap: 10px;">
              <span class="adm-avatar" aria-hidden="true">{{ $initials }}</span>
              <div>
                <strong style="color: var(--ink);">{{ $student->name }}</strong>
                <div style="font-size: 0.75rem; color: var(--ink-soft);">
                  {{ $student->class_name ?? 'Umum' }} &bull; <code>{{ $student->username }}</code>
                </div>
              </div>
            </div>
          </td>
          <td>
            @if($status === 'hadir')
              <span class="adm-pill adm-pill--success">Hadir</span>
            @elseif($status === 'sakit')
              <span class="adm-pill adm-pill--warning">Sakit</span>
            @elseif($status === 'izin')
              <span class="adm-pill adm-pill--info">Izin</span>
            @elseif($status === 'alpa')
              <span class="adm-pill adm-pill--danger">Alpa</span>
            @else
              <span class="adm-pill adm-pill--neutral">Belum Absen</span>
            @endif
          </td>
          <td>
            <div>{{ $att?->checked_in_at ? $att->checked_in_at->format('H:i') : '-' }}</div>
            @if($att)
              <span style="font-size: 0.75rem; color: var(--ink-soft);">
                Oleh {{ $att->source === 'siswa' ? 'Mandiri' : 'Guru' }}
              </span>
            @endif
          </td>
          <td>
            <form action="{{ route('admin.attendances.update', $student) }}" method="POST" style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
              @csrf
              <input type="hidden" name="date" value="{{ $date }}">
              <select name="status" class="adm-select" style="width: auto; min-height: 36px; font-size: 0.8125rem; padding: 4px 10px;">
                <option value="hadir" {{ $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ $status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpa" {{ $status === 'alpa' ? 'selected' : '' }}>Alpa</option>
              </select>
              <input type="text"
                     name="note"
                     value="{{ $att?->note }}"
                     placeholder="Catatan singkat..."
                     class="adm-input"
                     style="width: 170px; min-height: 36px; font-size: 0.8125rem; padding: 4px 10px;">
              <button type="submit" class="adm-btn adm-btn--primary adm-btn--xs">Simpan</button>
            </form>
          </td>
          <td style="text-align: right;">
            <a href="{{ route('admin.reports.index', ['student_id' => $student->id]) }}" class="adm-btn adm-btn--secondary adm-btn--xs">
              Rapor &rarr;
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align: center; padding: 36px; color: var(--ink-soft);">
            Belum ada data siswa terdaftar.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
