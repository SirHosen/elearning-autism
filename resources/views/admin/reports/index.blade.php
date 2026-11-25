@extends('layouts.admin')

@section('title', 'Laporan Hasil Belajar Siswa | Panel Pengelolaan Guru')
@section('page_title', 'Hasil Belajar')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Evaluasi &amp; Rapor Sekolah</p>
    <h1 class="adm-title">Laporan Hasil Belajar Siswa</h1>
    <p class="adm-subtitle">Analisis capaian kuis, sesi permainan edukasi, perolehan bintang, dan kehadiran per siswa.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.reports.export') }}" class="adm-btn adm-btn--secondary">Export Rekap CSV</a>
    @if($selectedStudent)
      <a href="{{ route('admin.reports.print', $selectedStudent) }}" target="_blank" class="adm-btn adm-btn--primary">Cetak Rapor</a>
    @endif
  </div>
</div>

{{-- Pemilih Siswa --}}
<form action="{{ route('admin.reports.index') }}" method="GET" class="adm-filter-bar">
  <label for="student_id" style="font-weight: 700; font-size: 0.875rem;">Pilih Siswa:</label>
  <select name="student_id" id="student_id" class="adm-select" style="min-width: 280px;" onchange="this.form.submit()">
    <option value="">-- Ringkasan Seluruh Kelas --</option>
    @foreach($students as $s)
      <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>
        {{ $s->name }} ({{ $s->class_name ?? $s->username }})
      </option>
    @endforeach
  </select>
  @if(request('student_id'))
    <a href="{{ route('admin.reports.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">Lihat Semua Siswa</a>
  @endif
</form>

@if($selectedStudent)
  {{-- Identitas Siswa Terpilih --}}
  <section class="adm-panel" style="margin-bottom: 20px;">
    <div class="adm-panel__body" style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
      <div style="display: flex; align-items: center; gap: 14px;">
        <span class="adm-avatar" style="width: 48px; height: 48px; font-size: 1rem;">
          {{ strtoupper(substr($selectedStudent->name, 0, 2)) }}
        </span>
        <div>
          <h2 style="margin: 0; font-size: 1.25rem; color: var(--ink);">{{ $selectedStudent->name }}</h2>
          <p style="margin: 3px 0 0; font-size: 0.875rem; color: var(--ink-soft);">
            {{ $selectedStudent->class_name ?? 'Kelas Umum' }} &bull; Username: <code>{{ $selectedStudent->username }}</code>
          </p>
        </div>
      </div>

      <div class="adm-actions">
        <a href="{{ route('admin.students.card', $selectedStudent) }}" target="_blank" class="adm-btn adm-btn--secondary adm-btn--sm">Kartu PIN</a>
        <a href="{{ route('admin.students.edit', $selectedStudent) }}" class="adm-btn adm-btn--secondary adm-btn--sm">Ubah Akun</a>
        <a href="{{ route('admin.reports.print', $selectedStudent) }}" target="_blank" class="adm-btn adm-btn--primary adm-btn--sm">Cetak Rapor</a>
      </div>
    </div>
  </section>

  {{-- Ringkasan Statistik Siswa --}}
  <div class="adm-kpi-grid" style="margin-bottom: 24px;">
    <div class="adm-kpi-card adm-kpi-card--blue">
      <span class="adm-kpi-card__label">Kuis Diselesaikan</span>
      <strong class="adm-kpi-card__val">{{ $summary['total_quizzes'] }}</strong>
      <span class="adm-kpi-card__sub">Total percobaan kuis selesai</span>
    </div>
    <div class="adm-kpi-card adm-kpi-card--green">
      <span class="adm-kpi-card__label">Rata-rata Skor Kuis</span>
      <strong class="adm-kpi-card__val">{{ $summary['avg_quiz_score'] }}%</strong>
      <span class="adm-kpi-card__sub">Jawaban tepat percobaan pertama</span>
    </div>
    <div class="adm-kpi-card adm-kpi-card--forest">
      <span class="adm-kpi-card__label">Sesi Permainan</span>
      <strong class="adm-kpi-card__val">{{ $summary['total_games'] }}</strong>
      <span class="adm-kpi-card__sub">Aktivitas game edukasi</span>
    </div>
    <div class="adm-kpi-card adm-kpi-card--amber">
      <span class="adm-kpi-card__label">Total Bintang</span>
      <strong class="adm-kpi-card__val">{{ $summary['total_stars'] }}</strong>
      <span class="adm-kpi-card__sub">Akumulasi kuis &amp; permainan</span>
    </div>
    <div class="adm-kpi-card adm-kpi-card--purple">
      <span class="adm-kpi-card__label">Hari Hadir</span>
      <strong class="adm-kpi-card__val">{{ $summary['attendance_days'] }}</strong>
      <span class="adm-kpi-card__sub">Kehadiran tercatat di kelas</span>
    </div>
  </div>

  {{-- Grafik Perkembangan Skor Kuis (jika ada) --}}
  @if($quizAttempts->isNotEmpty())
    @php
      $chartItems = $quizAttempts->take(8)->reverse()->values();
    @endphp
    <section class="adm-panel" style="margin-bottom: 24px;">
      <div class="adm-panel__head">
        <div>
          <h2 class="adm-panel__title">Perkembangan Skor Kuis Terakhir</h2>
          <p class="adm-panel__sub">Persentase ketepatan jawaban per pengerjaan kuis</p>
        </div>
      </div>
      <div class="adm-panel__body">
        <svg viewBox="0 0 640 175" width="100%" height="175" role="img" aria-label="Grafik perkembangan skor kuis siswa">
          <line x1="24" y1="20" x2="616" y2="20" stroke="#E8E2D6" stroke-dasharray="4 4"/>
          <line x1="24" y1="80" x2="616" y2="80" stroke="#E8E2D6" stroke-dasharray="4 4"/>
          <line x1="24" y1="140" x2="616" y2="140" stroke="#D9D2C5"/>
          @foreach($chartItems as $idx => $attempt)
            @php
              $pct = $attempt->total_questions > 0 ? round(($attempt->correct_first_try / $attempt->total_questions) * 100) : 0;
              $barH = max(6, round(($pct / 100) * 120));
              $x = 40 + $idx * 72;
            @endphp
            <rect x="{{ $x }}" y="{{ 140 - $barH }}" width="34" height="{{ $barH }}" rx="6" fill="#246B4D"/>
            <text x="{{ $x + 17 }}" y="{{ 140 - $barH - 6 }}" text-anchor="middle" font-size="11" font-weight="700" fill="#1F2933">{{ $pct }}%</text>
            <text x="{{ $x + 17 }}" y="160" text-anchor="middle" font-size="11" fill="#4B5563">#{{ $idx + 1 }}</text>
          @endforeach
        </svg>
      </div>
    </section>
  @endif

  {{-- Riwayat Kuis & Permainan --}}
  <section class="adm-panel" style="margin-bottom: 24px;">
    <div class="adm-panel__head">
      <h2 class="adm-panel__title">Riwayat Pengerjaan Kuis</h2>
    </div>
    <div style="overflow-x: auto;">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Judul Kuis</th>
            <th>Waktu Pengerjaan</th>
            <th>Skor</th>
            <th>Bintang</th>
            <th>Rincian Jawaban</th>
          </tr>
        </thead>
        <tbody>
          @forelse($quizAttempts as $attempt)
            @php
              $stars = $attempt->calculateStars();
              $pct = $attempt->total_questions > 0 ? round(($attempt->correct_first_try / $attempt->total_questions) * 100) : 0;
            @endphp
            <tr>
              <td><strong>{{ $attempt->quiz?->title ?? 'Kuis' }}</strong></td>
              <td>{{ $attempt->created_at->format('d M Y, H:i') }}</td>
              <td><span class="adm-pill adm-pill--info">{{ $pct }}%</span></td>
              <td><span class="adm-pill adm-pill--warning">{{ $stars }} Bintang</span></td>
              <td>{{ $attempt->correct_first_try }} dari {{ $attempt->total_questions }} benar</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; padding: 28px; color: var(--ink-soft);">
                Siswa belum pernah mengerjakan kuis.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <div class="adm-grid-2">
    <section class="adm-panel">
      <div class="adm-panel__head">
        <h2 class="adm-panel__title">Riwayat Sesi Permainan</h2>
      </div>
      <div style="overflow-x: auto;">
        <table class="adm-table">
          <thead>
            <tr>
              <th>Permainan</th>
              <th>Ronde</th>
              <th>Durasi</th>
              <th>Bintang</th>
            </tr>
          </thead>
          <tbody>
            @forelse($gameSessions as $session)
              <tr>
                <td>
                  <strong>{{ $session->game?->title ?? 'Permainan' }}</strong>
                  <div style="font-size: 0.75rem; color: var(--ink-soft);">
                    {{ $session->game?->category?->name ?? '-' }} &bull; {{ $session->created_at->format('d M Y') }}
                  </div>
                </td>
                <td>{{ $session->rounds_correct_first_try }} / {{ $session->rounds_total }}</td>
                <td>{{ gmdate('i:s', $session->duration_seconds) }}</td>
                <td><span class="adm-pill adm-pill--warning">{{ $session->stars_earned }} Bintang</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; padding: 28px; color: var(--ink-soft);">
                  Belum pernah memainkan permainan edukasi.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    <section class="adm-panel">
      <div class="adm-panel__head">
        <h2 class="adm-panel__title">Catatan Presensi Terakhir</h2>
        <a href="{{ route('admin.attendances.index') }}" class="adm-btn adm-btn--secondary adm-btn--xs">Kelola Absensi</a>
      </div>
      <div style="overflow-x: auto;">
        <table class="adm-table">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Pencatat &amp; Catatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentAttendances as $att)
              <tr>
                <td>{{ \Illuminate\Support\Carbon::parse($att->attendance_date)->format('d M Y') }}</td>
                <td>
                  <span class="adm-pill {{ $att->status === 'hadir' ? 'adm-pill--success' : ($att->status === 'alpa' ? 'adm-pill--danger' : 'adm-pill--info') }}">
                    {{ ucfirst($att->status) }}
                  </span>
                </td>
                <td style="font-size: 0.8125rem; color: var(--ink-soft);">
                  {{ $att->source === 'siswa' ? 'Mandiri' : 'Guru' }}
                  @if($att->note) &bull; {{ $att->note }} @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" style="text-align: center; padding: 28px; color: var(--ink-soft);">
                  Belum ada catatan kehadiran.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
@else
  {{-- Gambaran Umum Seluruh Kelas --}}
  <section class="adm-panel">
    <div class="adm-panel__head">
      <div>
        <h2 class="adm-panel__title">Rekap Evaluasi Seluruh Siswa</h2>
        <p class="adm-panel__sub">Klik Buka Rapor untuk melihat detail grafik, jawaban kuis, permainan, dan riwayat absensi</p>
      </div>
    </div>
    <div style="overflow-x: auto;">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Hari Hadir</th>
            <th>Kuis Selesai</th>
            <th>Rata-rata Kuis</th>
            <th>Sesi Permainan</th>
            <th>Total Bintang</th>
            <th class="adm-td-actions">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($classOverview as $row)
            @php
              $s = $row['student'];
              $sum = $row['summary'];
            @endphp
            <tr>
              <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                  <span class="adm-avatar" aria-hidden="true">{{ strtoupper(substr($s->name, 0, 2)) }}</span>
                  <div>
                    <strong>{{ $s->name }}</strong>
                    <div style="font-size: 0.75rem; color: var(--ink-soft);"><code>{{ $s->username }}</code></div>
                  </div>
                </div>
              </td>
              <td>{{ $s->class_name ?? '-' }}</td>
              <td><span class="adm-pill adm-pill--success">{{ $sum['attendance_days'] }} Hari</span></td>
              <td>{{ $sum['total_quizzes'] }}</td>
              <td><strong>{{ $sum['avg_quiz_score'] }}%</strong></td>
              <td>{{ $sum['total_games'] }}</td>
              <td><span class="adm-pill adm-pill--warning">{{ $sum['total_stars'] }} Bintang</span></td>
              <td class="adm-td-actions">
                <div class="adm-actions">
                  <a href="{{ route('admin.reports.index', ['student_id' => $s->id]) }}" class="adm-btn adm-btn--blue adm-btn--xs">Buka Rapor &rarr;</a>
                  <a href="{{ route('admin.reports.print', $s) }}" target="_blank" class="adm-btn adm-btn--secondary adm-btn--xs">Cetak</a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align: center; padding: 36px; color: var(--ink-soft);">
                Belum ada siswa terdaftar.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endif
@endsection
