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
<div class="adm-kpi-grid adm-kpi-grid--5">
  <div class="adm-kpi-card adm-kpi-card--blue">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Siswa Aktif</span>
      <a href="{{ route('admin.students.index') }}" class="adm-pill adm-pill--info">Kelola</a>
    </div>
    <strong class="adm-kpi-card__val">{{ $totalStudents }}</strong>
    <span class="adm-kpi-card__sub">Tersebar di {{ max(1, $classCount) }} kelompok kelas</span>
  </div>

  <div class="adm-kpi-card adm-kpi-card--green">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Kehadiran</span>
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
      <span class="adm-kpi-card__label">Bank Konten</span>
      <a href="{{ route('admin.categories.index') }}" class="adm-pill adm-pill--neutral">{{ $categoriesCount }} Kategori</a>
    </div>
    <strong class="adm-kpi-card__val">{{ $contentTotals['materials'] }} Materi</strong>
    <span class="adm-kpi-card__sub">
      {{ $contentTotals['videos'] }} Video &bull; {{ $contentTotals['games'] }} Game &bull; {{ $contentTotals['quizzes'] }} Kuis
    </span>
  </div>

  <div class="adm-kpi-card adm-kpi-card--purple">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Evaluasi 7H</span>
      <a href="{{ route('admin.reports.index') }}" class="adm-pill adm-pill--purple">Rapor</a>
    </div>
    <strong class="adm-kpi-card__val">{{ $quizzesLast7Days + $gamesLast7Days }}</strong>
    <span class="adm-kpi-card__sub">
      {{ $quizzesLast7Days }} kuis selesai &bull; {{ $gamesLast7Days }} game
    </span>
  </div>

  <div class="adm-kpi-card adm-kpi-card--amber">
    <div class="adm-kpi-card__head">
      <span class="adm-kpi-card__label">Skor Kuis 7H</span>
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
      <div class="adm-chart-legend">
        <span class="adm-chart-legend__item adm-chart-legend__item--hadir">
          <span class="adm-chart-legend__swatch adm-chart-legend__swatch--hadir"></span> Hadir
        </span>
        <span class="adm-chart-legend__item adm-chart-legend__item--activity">
          <span class="adm-chart-legend__swatch adm-chart-legend__swatch--activity"></span> Kuis &amp; Game
        </span>
      </div>
    </div>
    <div class="adm-panel__body adm-panel__body--chart">
      <svg viewBox="0 0 640 220" width="100%" height="220" role="img" aria-label="Grafik tren kehadiran dan aktivitas 7 hari">
        @php
          $chartBottom = 172;
          $chartTop = 24;
          $chartHeight = $chartBottom - $chartTop;
          $scaleTop = max(1, $maxChartVal);
          $scaleMid = max(1, (int) ceil($scaleTop / 2));
        @endphp
        @for($g = 0; $g <= 2; $g++)
          @php
            $y = $chartTop + ($chartHeight / 2) * $g;
            $tickLabel = $g === 0 ? $scaleTop : ($g === 1 ? $scaleMid : 0);
          @endphp
          <text x="26" y="{{ $y + 4 }}" text-anchor="end" font-size="10.5" font-weight="600" fill="#6B7280">{{ $tickLabel }}</text>
          <line x1="36" y1="{{ $y }}" x2="622" y2="{{ $y }}" stroke="{{ $g === 2 ? '#D9D2C5' : '#EDE8DD' }}" stroke-dasharray="{{ $g === 2 ? 'none' : '4 4' }}" stroke-width="1"/>
        @endfor

        @foreach($weeklyTrend as $idx => $day)
          @php
            $slotX = 56 + $idx * 80;
            $hAtt = $maxChartVal > 0 ? round(($day['attended'] / $maxChartVal) * $chartHeight) : 0;
            $hAct = $maxChartVal > 0 ? round(($day['activities'] / $maxChartVal) * $chartHeight) : 0;
            $barAtt = $day['attended'] > 0 ? max(10, $hAtt) : 4;
            $barAct = $day['activities'] > 0 ? max(10, $hAct) : 4;
          @endphp
          <rect x="{{ $slotX }}" y="{{ $chartBottom - $barAtt }}" width="22" height="{{ $barAtt }}" rx="{{ $day['attended'] > 0 ? 6 : 2 }}" fill="{{ $day['attended'] > 0 ? '#246B4D' : '#E5E7EB' }}">
            <title>{{ $day['label'] }} ({{ $day['date_short'] }}): {{ $day['attended'] }} Hadir</title>
          </rect>
          <rect x="{{ $slotX + 26 }}" y="{{ $chartBottom - $barAct }}" width="22" height="{{ $barAct }}" rx="{{ $day['activities'] > 0 ? 6 : 2 }}" fill="{{ $day['activities'] > 0 ? '#2F6F9F' : '#E5E7EB' }}">
            <title>{{ $day['label'] }} ({{ $day['date_short'] }}): {{ $day['activities'] }} Kuis &amp; Game</title>
          </rect>
          @if($day['attended'] > 0)
            <text x="{{ $slotX + 11 }}" y="{{ $chartBottom - $barAtt - 6 }}" text-anchor="middle" font-size="11" font-weight="700" fill="#17523A">{{ $day['attended'] }}</text>
          @endif
          @if($day['activities'] > 0)
            <text x="{{ $slotX + 37 }}" y="{{ $chartBottom - $barAct - 6 }}" text-anchor="middle" font-size="11" font-weight="700" fill="#1A4B70">{{ $day['activities'] }}</text>
          @endif
          <text x="{{ $slotX + 24 }}" y="193" text-anchor="middle" font-size="12" font-weight="700" fill="#1F2933">{{ $day['label'] }}</text>
          <text x="{{ $slotX + 24 }}" y="209" text-anchor="middle" font-size="10" fill="#4B5563">{{ $day['date_short'] }}</text>
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
    <div class="adm-panel__body adm-panel__body--grow">
      @if ($unattendedStudents->isEmpty())
        <div class="feedback-bar feedback-success adm-flash-bar">
          <span>Seluruh siswa aktif sudah tercatat presensinya hari ini.</span>
        </div>
      @else
        <div class="adm-stack-sm">
          @foreach ($unattendedStudents->take(5) as $s)
            <div class="adm-quick-item">
              <div>
                <div class="adm-quick-item__name">{{ $s->name }}</div>
                <div class="adm-quick-item__meta">{{ $s->class_name ?? 'Umum' }} &bull; <code>{{ $s->username }}</code></div>
              </div>
              <form action="{{ route('admin.attendances.update', $s) }}" method="POST" class="adm-quick-form">
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

      <div class="adm-panel__foot-note">
        <span>Total tercatat hari ini: <strong>{{ $todayAttendedCount }} / {{ $totalStudents }}</strong></span>
        <a href="{{ route('admin.attendances.export', ['date' => now()->toDateString()]) }}" class="adm-link-chip">Unduh CSV</a>
      </div>
    </div>
  </section>
</div>

{{-- 3. Matriks Kesiapan Kurikulum Lintas Modul --}}
<section class="adm-panel adm-panel-spaced">
  <div class="adm-panel__head">
    <div>
      <h2 class="adm-panel__title">Matriks Kesiapan Kurikulum per Kategori</h2>
      <p class="adm-panel__sub">Keterhubungan setiap kategori ke modul Materi, Video, Permainan, dan Kuis siswa</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="adm-btn adm-btn--secondary adm-btn--sm">+ Kategori Baru</a>
  </div>

  <div class="adm-table-scroll">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Kategori</th>
          <th>Materi Visual/Audio</th>
          <th>Video Edukasi</th>
          <th>Permainan</th>
          <th>Kuis Bergambar</th>
          <th>Kesiapan Generator Kuis</th>
          <th class="adm-td-actions">Tindak Cepat</th>
        </tr>
      </thead>
      <tbody>
        @forelse($curriculumMatrix as $cat)
          @php
            $quizReady = $cat->active_materials_count >= 3;
          @endphp
          <tr>
            <td>
              <div class="adm-entity-cell">
                <span class="adm-swatch adm-swatch--{{ $cat->color_key ?? 'blue' }}"></span>
                <div>
                  <strong class="adm-entity-name">{{ $cat->name }}</strong>
                  <div class="adm-entity-sub"><code>/belajar/{{ $cat->slug }}</code></div>
                </div>
              </div>
            </td>
            <td>
              <a href="{{ route('admin.materials.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->materials_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot adm-dot--blue"></span>
                {{ $cat->materials_count }} Materi ({{ $cat->active_materials_count }} Aktif)
              </a>
            </td>
            <td>
              <a href="{{ route('admin.videos.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->videos_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot adm-dot--purple"></span>
                {{ $cat->videos_count }} Video
              </a>
            </td>
            <td>
              <a href="{{ route('admin.games.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->games_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot adm-dot--green"></span>
                {{ $cat->games_count }} Game
              </a>
            </td>
            <td>
              <a href="{{ route('admin.quizzes.index', ['category_id' => $cat->id]) }}"
                 class="adm-link-chip {{ $cat->quizzes_count === 0 ? 'adm-link-chip--zero' : '' }}">
                <span class="adm-dot adm-dot--amber"></span>
                {{ $cat->quizzes_count }} Kuis
              </a>
            </td>
            <td>
              @if($quizReady)
                <span class="adm-pill adm-pill--success"><span class="adm-dot"></span>Siap (&ge;3 Materi)</span>
              @else
                <span class="adm-pill adm-pill--neutral">Kurang {{ 3 - $cat->active_materials_count }} Materi</span>
              @endif
            </td>
            <td class="adm-td-actions">
              <div class="adm-actions">
                <a href="{{ route('admin.materials.create', ['category_id' => $cat->id]) }}" class="adm-btn adm-btn--secondary adm-btn--xs">+ Materi</a>
                <a href="{{ route('admin.categories.edit', $cat) }}" class="adm-btn adm-btn--secondary adm-btn--xs">Ubah</a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="adm-table-empty">
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
    <div class="adm-table-scroll">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Siswa</th>
            <th>Modul</th>
            <th>Capaian</th>
            <th>Bintang</th>
            <th class="adm-th-right">Detail</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentEvaluations as $eval)
            @php
              $avatar = strtoupper(substr($eval['student_name'], 0, 2));
            @endphp
            <tr>
              <td>
                <div class="adm-entity-cell">
                  <span class="adm-avatar" aria-hidden="true">{{ $avatar }}</span>
                  <div>
                    <strong>{{ $eval['student_name'] }}</strong>
                    <div class="adm-entity-sub">{{ $eval['student_class'] }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="adm-pill {{ $eval['type'] === 'kuis' ? 'adm-pill--warning' : 'adm-pill--success' }}">
                  {{ ucfirst($eval['type']) }}
                </span>
                <div class="adm-module-title">{{ $eval['title'] }}</div>
              </td>
              <td>{{ $eval['score_label'] }}</td>
              <td><span class="adm-pill adm-pill--warning">{{ $eval['stars'] }} Bintang</span></td>
              <td class="adm-td-right">
                <a href="{{ route('admin.reports.index', ['student_id' => $eval['student_id']]) }}" class="adm-btn adm-btn--secondary adm-btn--xs">
                  Rapor &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="adm-table-empty">
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
    <div class="adm-panel__body adm-panel__body--grow adm-stack-sm">
      <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary adm-shortcut-btn">
        <span>Kelola &amp; Generator Kuis</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <a href="{{ route('admin.games.create') }}" class="adm-btn adm-btn--secondary adm-shortcut-btn">
        <span>Buat Permainan Edukasi</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <a href="{{ route('admin.videos.create') }}" class="adm-btn adm-btn--secondary adm-shortcut-btn">
        <span>Tambah Video Pembelajaran</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <a href="{{ route('admin.attendances.export', ['date' => now()->toDateString()]) }}" class="adm-btn adm-btn--secondary adm-shortcut-btn">
        <span>Unduh CSV Absensi Hari Ini</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      </a>
      <a href="{{ route('admin.reports.export') }}" class="adm-btn adm-btn--primary adm-shortcut-btn">
        <span>Unduh Rekap Nilai Siswa (CSV)</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      </a>
    </div>
  </section>
</div>
@endsection
