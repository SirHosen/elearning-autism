<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Belajar - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1F2937;
            margin: 0;
            padding: 30px;
            background: #FFFFFF;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 4px 0;
            font-size: 15px;
            font-weight: 500;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #4B5563;
        }
        .student-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
            background: #F9FAFB;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
        }
        .student-info p {
            margin: 4px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 25px;
            text-align: center;
        }
        .stat-card {
            border: 1px solid #D1D5DB;
            padding: 12px;
            border-radius: 6px;
        }
        .stat-card .val {
            font-size: 22px;
            font-weight: 700;
            margin-top: 4px;
            color: #1D4ED8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table, th, td {
            border: 1px solid #D1D5DB;
        }
        th {
            background: #F3F4F6;
            padding: 8px 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }
        td {
            padding: 8px 10px;
            font-size: 13px;
        }
        .signature-block {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            margin-top: 70px;
            border-bottom: 1px solid #000;
            width: 200px;
            display: inline-block;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; font-weight: 600; background: #1D4ED8; color: #fff; border: 0; border-radius: 6px; cursor: pointer;">
            Cetak Dokumen Rapor
        </button>
    </div>

    <div class="header">
        <h1>SLB B-C WIJAYAKUSUMAH</h1>
        <h2>Laporan Hasil Belajar Siswa (Sahabat Belajar)</h2>
        <p>Jl. Wijayakusumah, Sumedang &bull; Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="student-info">
        <div>
            <p><strong>Nama Siswa:</strong> {{ $student->name }}</p>
            <p><strong>Nama Pengguna (Username):</strong> {{ $student->username }}</p>
        </div>
        <div>
            <p><strong>Satuan Pendidikan:</strong> SLB B-C Wijayakusumah</p>
            <p><strong>Status Belajar:</strong> Aktif</p>
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
                <th style="width: 30px;">No</th>
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
                    <td colspan="6" style="text-align: center; color: #6B7280;">Belum ada data kuis.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="margin-bottom: 8px;">2. Riwayat Permainan Terakhir</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
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
                    <td colspan="6" style="text-align: center; color: #6B7280;">Belum ada data permainan.</td>
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
