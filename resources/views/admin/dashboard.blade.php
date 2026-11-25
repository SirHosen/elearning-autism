@extends('layouts.admin')

@section('title', 'Ringkasan Panel Guru | Sahabat Belajar')
@section('page_title', 'Ringkasan')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Operasional Kelas &bull; {{ $todayFormatted }}</p>
    <h1 class="adm-title">Selamat Bekerja, {{ auth()->user()->name }}</h1>
    <p class="adm-subtitle">Pusat kendali terpadu untuk kehadiran harian, kesiapan materi lintas modul, dan evaluasi hasil belajar siswa.</p>
  </div>

  <div class="adm-head-actions">
    <a href="{{ route('admin.attendances.index') }}" class="adm-btn adm-btn--secondary">Rekap Absensi</a>
    <a href="{{ route('admin.materials.create') }}" class="adm-btn adm-btn--primary">+ Tambah Materi</a>
  </div>
</div>

{{-- 1. KPI Strip Terintegrasi --}}
<div class="adm-kpi-grid">
  <div class="adm-kpi-card adm-kpi-card--blue">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Siswa Aktif</span>
      <a href="{{ route('admin.students.index') }}" class="adm-pill adm-pill--info">Kelola &rarr;</a>
    </div>
    <strong class="adm-kpi-card__val">{{ $totalStudents }}</strong>
    <span class="adm-kpi-card__sub">Tersebar di {{ max(1, $classCount) }} kelompok kelas</span>
  </div>

  <div class="adm-kpi-card adm-kpi-card--green">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Kehadiran Hari Ini</span>
      <span class="adm-pill adm-pill--success">{{ $attendanceRate }}%</span>
    </div>
    <strong class="adm-kpi-card__val">{{ $todayAttendedCount }} / {{ $totalStudents }}</strong>
    <div class="adm-meter" aria-hidden="true">
      <div class="adm-meter__fill" style="width: {{ $attendanceRate }}%;"></div>
    </div>
    <span class="adm-kpi-card__sub">
      Izin: {{ $todayBreakdown['izin'] }} &bull; Sakit: {{ $todayBreakdown['sakit'] }} &bull; Belum: {{ $todayBreakdown['belum'] }}
    </span>
  </div>

  <div class="adm-kpi-card adm-kpi-card--forest">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Bank Konten Aktif</span>
      <a href="{{ route('admin.categories.index') }}" class="adm-pill adm-pill--neutral">{{ $categoriesCount }} Kategori</a>
    </div>
    <strong class="adm-kpi-card__val">{{ $contentTotals['materials'] }} Materi</strong>
    <span class="adm-kpi-card__sub">
      {{ $contentTotals['videos'] }} Video &bull; {{ $contentTotals['games'] }} Game &bull; {{ $contentTotals['quizzes'] }} Kuis
    </span>
  </div>

  <div class="adm-kpi-card adm-kpi-card--purple">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Evaluasi 7 Hari</span>
      <a href="{{ route('admin.reports.index') }}" class="adm-pill adm-pill--purple">Rapor &rarr;</a>
    </div>
    <strong class="adm-kpi-card__val">{{ $quizzesLast7Days + $gamesLast7Days }}</strong>
    <span class="adm-kpi-card__sub">
      {{ $quizzesLast7Days }} kuis selesai &bull; {{ $gamesLast7Days }} sesi permainan
    </span>
  </div>

  <div class="adm-kpi-card adm-kpi-card--amber">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Skor Kuis 7 Hari</span>
      <span class="adm-pill adm-pill--warning">Rata-rata</span>
    </div>
    <strong class="adm-kpi-card__val">{{ $avgQuizScore7Days }}%</strong>
    <span class="adm-kpi-card__sub">Akurasi percobaan pertama siswa</span>
  </div>
</div>

{{-- 2. Tren 7 Hari + Quick Check-In Absensi --}}
<div class="adm-grid-aside">
  <section class="adm-panel">
    <div class="adm-panel__head">
      <div>
        <h2 class="adm-panel__title">Tren Kehadiran &amp; Aktivitas Belajar (7 Hari Terakhir)</h2>
        <p class="adm-panel__sub">Perbandingan jumlah siswa hadir dan sesi latihan interaktif per hari</p>
      </div>
      <div style="display: flex; gap: 12px; font-size: 0.75rem; font-weight: 700;">
        <span style="display: inline-flex; align-items: center; gap: 5px; color: var(--adm-forest);">
          <span style="width: 10px; height: 10px; border-radius: 3px; background: #246B4D; display: inline-block;"></span> Hadir
        </span>
        <span style="display: inline-flex; align-items: center; gap: 5px; color: var(--primary-strong);">
          <span style="width: 10px; height: 10px; border-radius: 3px; background: #2F6F9F; display: inline-block;"></span> Kuis &amp; Game
        </span>
      </div>
    </div>
    <div class="adm-panel__body">
      <svg viewBox="0 0 640 210" width="100%" height="210" role="img" aria-label="Grafik tren kehadiran dan aktivitas 7 hari">
        @php
          $chartBottom = 165;
          $chartTop = 20;
          $chartHeight = $chartBottom - $chartTop;
        @endphp
        @for($g = 0; $g <= 3; $g++)
          @php $y = $chartTop + ($chartHeight / 3) * $g; @endphp
          <line x1="28" y1="{{ $y }}" x2="620" y2="{{ $y }}" stroke="#E8E2D6" stroke-dasharray="4 4" stroke-width="1"/>
        @endfor

        @foreach($weeklyTrend as $idx => $day)
          @php
            $slotX = 48 + $idx * 84;
            $hAtt = $maxChartVal > 0 ? round(($day['attended'] / $maxChartVal) * $chartHeight) : 0;
            $hAct = $maxChartVal > 0 ? round(($day['activities'] / $maxChartVal) * $chartHeight) : 0;
            $barAtt = max($day['attended'] > 0 ? 8 : 3, $hAtt);
            $barAct = max($day['activities'] > 0 ? 8 : 3, $hAct);
          @endphp
          <rect x="{{ $slotX }}" y="{{ $chartBottom - $barAtt }}" width="20" height="{{ $barAtt }}" rx="5" fill="{{ $day['attended'] > 0 ? '#246B4D' : '#E5E7EB' }}">
            <title>{{ $day['label'] }} ({{ $day['date_short'] }}): {{ $day['attended'] }} Hadir</title>
          </rect>
          <rect x="{{ $slotX + 24 }}" y="{{ $chartBottom - $barAct }}" width="20" height="{{ $barAct }}" rx="5" fill="{{ $day['activities'] > 0 ? '#2F6F9F' : '#E5E7EB' }}">
            <title>{{ $day['label'] }} ({{ $day['date_short'] }}): {{ $day['activities'] }} Kuis &amp; Game</title>
          </rect>
          @if($day['attended'] > 0)
            <text x="{{ $slotX + 10 }}" y="{{ $chartBottom - $barAtt - 5 }}" text-anchor="middle" font-size="11" font-weight="700" fill="#17523A">{{ $day['attended'] }}</text>
          @endif
          @if($day['activities'] > 0)
            <text x="{{ $slotX + 34 }}" y="{{ $chartBottom - $barAct - 5 }}" text-anchor="middle" font-size="11" font-weight="700" fill="#1A4B70">{{ $day['activities'] }}</text>
          @endif
          <text x="{{ $slotX + 22 }}" y="186" text-anchor="middle" font-size="12" font-weight="700" fill="#1F2933">{{ $day['label'] }}</text>
          <text x="{{ $slotX + 22 }}" y="201" text-anchor="middle" font-size="10" fill="#4B5563">{{ $day['date_short'] }}</text>
        @endforeach
      </svg>
    </div>
  </section>

  <section class="adm-panel">
    <div class="adm-panel__head">
      <div>
        <h2 class="adm-panel__title">Belum Presensi Hari Ini</h2>
        <p class="adm-panel__sub">Catat kehadiran siswa langsung dari ringkasan</p>
      </div>
      <a href="{{ route('admin.attendances.index') }}" class="adm-btn adm-btn--secondary adm-btn--xs">Semua &rarr;</a>
    </div>
    <div class="adm-panel__body">
      @if ($unattendedStudents->isEmpty())
        <div class="feedback-bar feedback-success" style="margin: 0; font-size: 0.9375rem;">
          <span>Seluruh siswa aktif sudah tercatat presensinya hari ini.</span>
        </div>
      @else
        <div style="display: flex; flex-direction: column; gap: 10px;">
          @foreach ($unattendedStudents->take(5) as $s)
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 10px 12px; background: var(--surface-alt); border-radius: 10px; flex-wrap: wrap;">
              <div>
                <div style="font-weight: 700; font-size: 0.9375rem; color: var(--ink);">{{ $s->name }}</div>
                <div style="font-size: 0.75rem; color: var(--ink-soft);">{{ $s->class_name ?? 'Umum' }} &bull; <code>{{ $s->username }}</code></div>
              </div>
              <form action="{{ route('admin.attendances.update', $s) }}" method="POST" style="display: inline-flex; gap: 6px;">
                @csrf
                <input type="hidden" name="date" value="{{ now()->toDateString() }}">
                <button type="submit" name="status" value="hadir" class="adm-btn adm-btn--primary adm-btn--xs" title="Tandai Hadir">Hadir</button>
                <button type="submit" name="status" value="izin" class="adm-btn adm-btn--secondary adm-btn--xs" title="Tandai Izin">Izin</button>
                <button type="submit" name="status" value="sakit" class="adm-btn adm-btn--secondary adm-btn--xs" title="Tandai Sakit">Sakit</button>
              </form>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>
</div>

{{-- 3. Matriks Kesiapan Kurikulum Lintas Modul --}}
<section class="adm-panel" style="margin-bottom: 24px;">
  <div class="adm-panel__head">
    <div>
      <h2 class="adm-panel__title">Matriks Kesiapan Kurikulum per Kategori</h2>
      <p class="adm-panel__sub">Keterhubungan setiap kategori ke modul Materi, Video, Permainan, dan Kuis siswa</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="adm-btn adm-btn--secondary adm-btn--sm">+ Kategori Baru</a>
  </div>

  <div style="overflow-x: auto;">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Kategori</th>
          <th>Materi Visual/Audio</th>
          <th>Video Edukasi</th>
          <th>Permainan</th>
          <th>Kuis Bergambar</th>
          <th>Kesiapan Generator Kuis</th>
          <th style="text-align: right;">Tindak Cepat</th>
        </tr>
      </thead>
      <tbody>
        @forelse($curriculumMatrix as $cat)
          @php
            $quizReady = $cat->active_materials_count >= 3;
          @endphp
          <tr>
            <td>
              <div style="display: flex; align-items: center; gap: 10px;">
                <span class="tile-{{ $cat->color_key }}" style="width: 18px; height: 18px; border-radius: 6px; border: 1px solid var(--line); display: inline-block; flex-shrink: 0;"></span>
                <div>
                  <strong style="color: var(--ink);">{{ $cat->name }}</strong>
                  <div style="font-size: 0.75rem; color: var(--ink-soft);"><code>/belajar/{{ $cat->slug }}</code></div>
                </div>
              </div>
            </td>
            <td>
              <a href="{{ route('admin.materials.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--info">
                {{ $cat->materials_count }} Materi ({{ $cat->active_materials_count }} Aktif)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.videos.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--purple">
                {{ $cat->videos_count }} Video
              </a>
            </td>
            <td>
              <a href="{{ route('admin.games.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--success">
                {{ $cat->games_count }} Permainan
              </a>
            </td>
            <td>
              <a href="{{ route('admin.quizzes.index', ['category_id' => $cat->id]) }}" class="adm-pill adm-pill--warning">
                {{ $cat->quizzes_count }} Kuis
              </a>
            </td>
            <td>
              @if($quizReady)
                <span class="adm-pill adm-pill--success">Siap Otomatis (&ge;3 Materi)</span>
              @else
                <span class="adm-pill adm-pill--warning">Butuh {{ 3 - $cat->active_materials_count }} Materi Lagi</span>
              @endif
            </td>
            <td style="text-align: right;">
              <div class="adm-actions">
                <a href="{{ route('admin.materials.create', ['category_id' => $cat->id]) }}" class="adm-btn adm-btn--secondary adm-btn--xs">+ Materi</a>
                <a href="{{ route('admin.categories.edit', $cat) }}" class="adm-btn adm-btn--secondary adm-btn--xs">Ubah</a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 28px; color: var(--ink-soft);">
              Belum ada kategori terkonfigurasi.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

{{-- 4. Aliran Evaluasi Terbaru + Pusat Pintasan Ekspor --}}
<div class="adm-grid-aside">
  <section class="adm-panel">
    <div class="adm-panel__head">
      <div>
        <h2 class="adm-panel__title">Evaluasi Kuis &amp; Permainan Terbaru</h2>
        <p class="adm-panel__sub">Klik Rapor untuk membuka analisis perkembangan tiap siswa</p>
      </div>
      <a href="{{ route('admin.reports.index') }}" class="adm-btn adm-btn--secondary adm-btn--xs">Lihat Semua Rapor &rarr;</a>
    </div>
    <div style="overflow-x: auto;">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Siswa</th>
            <th>Modul</th>
            <th>Capaian</th>
            <th>Bintang</th>
            <th style="text-align: right;">Detail</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentEvaluations as $eval)
            @php
              $avatar = strtoupper(substr($eval['student_name'], 0, 2));
            @endphp
            <tr>
              <td>
                <div style="display: flex; align-items: center; gap: 10px;">
                  <span class="adm-avatar" aria-hidden="true">{{ $avatar }}</span>
                  <div>
                    <strong>{{ $eval['student_name'] }}</strong>
                    <div style="font-size: 0.75rem; color: var(--ink-soft);">{{ $eval['student_class'] }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="adm-pill {{ $eval['type'] === 'kuis' ? 'adm-pill--warning' : 'adm-pill--success' }}" style="margin-bottom: 3px;">
                  {{ ucfirst($eval['type']) }}
                </span>
                <div style="font-size: 0.8125rem; font-weight: 600;">{{ $eval['title'] }}</div>
              </td>
              <td>{{ $eval['score_label'] }}</td>
              <td><span class="adm-pill adm-pill--warning">{{ $eval['stars'] }} Bintang</span></td>
              <td style="text-align: right;">
                <a href="{{ route('admin.reports.index', ['student_id' => $eval['student_id']]) }}" class="adm-btn adm-btn--secondary adm-btn--xs">
                  Rapor &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; padding: 28px; color: var(--ink-soft);">
                Belum ada riwayat pengerjaan kuis maupun permainan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <section class="adm-panel">
    <div class="adm-panel__head">
      <div>
        <h2 class="adm-panel__title">Pintasan Kerja Guru</h2>
        <p class="adm-panel__sub">Akses instan penyusunan konten &amp; laporan</p>
      </div>
    </div>
    <div class="adm-panel__body" style="display: flex; flex-direction: column; gap: 10px;">
      <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary" style="justify-content: space-between;">
        <span>Kelola &amp; Generator Kuis</span>
        <span>&rarr;</span>
      </a>
      <a href="{{ route('admin.games.create') }}" class="adm-btn adm-btn--secondary" style="justify-content: space-between;">
        <span>Buat Permainan Edukasi</span>
        <span>&rarr;</span>
      </a>
      <a href="{{ route('admin.videos.create') }}" class="adm-btn adm-btn--secondary" style="justify-content: space-between;">
        <span>Tambah Video Pembelajaran</span>
        <span>&rarr;</span>
      </a>
      <a href="{{ route('admin.attendances.export', ['date' => now()->toDateString()]) }}" class="adm-btn adm-btn--secondary" style="justify-content: space-between;">
        <span>Unduh CSV Absensi Hari Ini</span>
        <span>&darr;</span>
      </a>
      <a href="{{ route('admin.reports.export') }}" class="adm-btn adm-btn--primary" style="justify-content: space-between;">
        <span>Unduh Rekap Nilai Siswa (CSV)</span>
        <span>&darr;</span>
      </a>
    </div>
  </section>
</div>
@endsection
