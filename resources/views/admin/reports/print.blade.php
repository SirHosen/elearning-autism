<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rapor Belajar - {{ $student->name }}</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <style>
    @page { size: A4; margin: 14mm; }
    body {
      font-family: 'Lexend', 'Segoe UI', sans-serif;
      color: #1F2933;
      margin: 0 auto;
      max-width: 820px;
      padding: 28px;
      background: #FFFFFF;
      font-size: 13.5px;
      line-height: 1.45;
    }
    .header {
      text-align: center;
      border-bottom: 3px double #143326;
      padding-bottom: 14px;
      margin-bottom: 22px;
    }
    .header h1 {
      margin: 0;
      font-size: 20px;
      color: #143326;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }
    .header h2 {
      margin: 4px 0;
      font-size: 15px;
      font-weight: 600;
      color: #2F6F9F;
    }
    .header p {
      margin: 0;
      font-size: 12px;
      color: #4B5563;
    }
    .student-info {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-bottom: 20px;
      background: #FBF8F3;
      padding: 14px 18px;
      border-radius: 10px;
      border: 1px solid #D9D2C5;
    }
    .student-info p { margin: 4px 0; }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 24px;
      text-align: center;
    }
    .stat-card {
      border: 1px solid #D9D2C5;
      border-top: 3px solid #143326;
      padding: 12px;
      border-radius: 8px;
    }
    .stat-card .val {
      font-size: 21px;
      font-weight: 700;
      margin-top: 4px;
      color: #143326;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }
    table, th, td {
      border: 1px solid #D9D2C5;
    }
    th {
      background: #F3EEE5;
      padding: 9px 10px;
      text-align: left;
      font-size: 11.5px;
      text-transform: uppercase;
      color: #1F2933;
    }
    td {
      padding: 8px 10px;
      font-size: 13px;
    }
    .signature-block {
      display: grid;
      grid-template-columns: 1fr 1fr;
      margin-top: 44px;
      text-align: center;
    }
    .signature-line {
      margin-top: 68px;
      border-bottom: 1px solid #1F2933;
      width: 210px;
      display: inline-block;
    }
    @media print {
      body { padding: 0; max-width: none; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>
  <div class="no-print" style="margin-bottom: 20px; text-align: right;">
    <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; font-weight: 700; background: #143326; color: #FFFFFF; border: 0; border-radius: 8px; cursor: pointer;">
      Cetak Dokumen Rapor
    </button>
  </div>

  <div class="header">
    <h1>SLB B-C WIJAYAKUSUMAH</h1>
    <h2>Laporan Hasil Belajar Siswa (Sahabat Belajar)</h2>
    <p>Sumedang &bull; Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</p>
  </div>

  <div class="student-info">
    <div>
      <p><strong>Nama Siswa:</strong> {{ $student->name }}</p>
      <p><strong>Nama Pengguna:</strong> {{ $student->username }}</p>
      <p><strong>Kelas:</strong> {{ $student->class_name ?? 'Umum' }}</p>
    </div>
    <div>
      <p><strong>Satuan Pendidikan:</strong> SLB B-C Wijayakusumah</p>
      <p><strong>Status Akun:</strong> {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}</p>
    </div>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div>Kuis Selesai</div>
      <div class="val">{{ $summary['total_quizzes'] }}</div>
    </div>
    <div class="stat-card">
      <div>Rata-rata Skor</div>
      <div class="val">{{ $summary['avg_quiz_score'] }}%</div>
    </div>
    <div class="stat-card">
      <div>Total Bintang</div>
      <div class="val">{{ $summary['total_stars'] }} Bintang</div>
    </div>
    <div class="stat-card">
      <div>Hari Hadir</div>
      <div class="val">{{ $summary['attendance_days'] }} Hari</div>
    </div>
  </div>

  <h3 style="margin-bottom: 8px;">1. Riwayat Kuis Terakhir</h3>
  <table>
    <thead>
      <tr>
        <th style="width: 34px;">No</th>
        <th>Judul Kuis</th>
        <th>Tanggal</th>
        <th>Jawaban Benar</th>
        <th>Skor</th>
        <th>Bintang</th>
      </tr>
    </thead>
    <tbody>
      @forelse($quizAttempts as $i => $q)
        @php
          $pct = $q->total_questions > 0 ? round(($q->correct_first_try / $q->total_questions) * 100) : 0;
        @endphp
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $q->quiz?->title ?? 'Kuis' }}</td>
          <td>{{ $q->created_at->format('d/m/Y H:i') }}</td>
          <td>{{ $q->correct_first_try }} dari {{ $q->total_questions }}</td>
          <td>{{ $pct }}%</td>
          <td>{{ $q->calculateStars() }} Bintang</td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align: center; color: #4B5563;">Belum ada data kuis.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <h3 style="margin-bottom: 8px;">2. Riwayat Permainan Terakhir</h3>
  <table>
    <thead>
      <tr>
        <th style="width: 34px;">No</th>
        <th>Permainan</th>
        <th>Tanggal</th>
        <th>Ronde</th>
        <th>Durasi</th>
        <th>Bintang</th>
      </tr>
    </thead>
    <tbody>
      @forelse($gameSessions as $i => $g)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $g->game?->title ?? 'Permainan' }}</td>
          <td>{{ $g->created_at->format('d/m/Y H:i') }}</td>
          <td>{{ $g->rounds_correct_first_try }} / {{ $g->rounds_total }}</td>
          <td>{{ gmdate('i:s', $g->duration_seconds) }}</td>
          <td>{{ $g->stars_earned }} Bintang</td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align: center; color: #4B5563;">Belum ada data permainan.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="signature-block">
    <div>
      <p>Mengetahui,<br>Orang Tua / Wali Siswa</p>
      <div class="signature-line"></div>
    </div>
    <div>
      <p>Sumedang, {{ now()->translatedFormat('d F Y') }}<br>Guru Pendamping,</p>
      <div class="signature-line"></div>
    </div>
  </div>
</body>
</html>
