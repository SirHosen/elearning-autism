@extends('layouts.admin')

@section('title', 'Laporan Hasil Belajar Siswa')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem;">Laporan Hasil Belajar Siswa</h1>
        <p style="margin: var(--space-1) 0 0 0; color: var(--text-muted); font-size: 0.9375rem;">Pantau riwayat kuis, sesi permainan, bintang terkumpul, dan kehadiran.</p>
    </div>
    <div style="display: flex; gap: var(--space-3);">
        <a href="{{ route('admin.reports.export') }}" class="btn btn-secondary">
            Export CSV
        </a>
        @if($selectedStudent)
            <a href="{{ route('admin.reports.print', $selectedStudent) }}" target="_blank" class="btn btn-primary">
                Cetak Rapor
            </a>
        @endif
    </div>
</div>

{{-- Pemilih Siswa --}}
<div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
    <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; gap: var(--space-4); align-items: center; flex-wrap: wrap;">
        <label for="student_id" style="font-weight: 600; font-size: 0.9375rem;">Pilih Siswa:</label>
        <select name="student_id" id="student_id" class="form-control" style="max-width: 280px;" onchange="this.form.submit()">
            <option value="">-- Pilih Siswa untuk Melihat Detail --</option>
            @foreach($students as $s)
                <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->name }} ({{ $s->username }})
                </option>
            @endforeach
        </select>
        @if(request('student_id'))
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary" style="padding: var(--space-2) var(--space-3); font-size: 0.875rem;">Reset</a>
        @endif
    </form>
</div>

@if($selectedStudent)
    {{-- Ringkasan Statistik Siswa --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-4); margin-bottom: var(--space-6);">
        <div class="card" style="padding: var(--space-4); text-align: center; border-left: 5px solid var(--primary);">
            <div style="font-size: 0.875rem; color: var(--text-muted); font-weight: 600;">Kuis Diselesaikan</div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text); margin-top: var(--space-1);">{{ $summary['total_quizzes'] }}</div>
        </div>
        <div class="card" style="padding: var(--space-4); text-align: center; border-left: 5px solid var(--success);">
            <div style="font-size: 0.875rem; color: var(--text-muted); font-weight: 600;">Rata-rata Skor Kuis</div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-top: var(--space-1);">{{ $summary['avg_quiz_score'] }}%</div>
        </div>
        <div class="card" style="padding: var(--space-4); text-align: center; border-left: 5px solid #F59E0B;">
            <div style="font-size: 0.875rem; color: var(--text-muted); font-weight: 600;">Total Bintang</div>
            <div style="font-size: 2rem; font-weight: 700; color: #D97706; margin-top: var(--space-1);">{{ $summary['total_stars'] }}</div>
        </div>
        <div class="card" style="padding: var(--space-4); text-align: center; border-left: 5px solid #8B5CF6;">
            <div style="font-size: 0.875rem; color: var(--text-muted); font-weight: 600;">Hari Hadir</div>
            <div style="font-size: 2rem; font-weight: 700; color: #6D28D9; margin-top: var(--space-1);">{{ $summary['attendance_days'] }}</div>
        </div>
    </div>

    {{-- Bagian 1: Riwayat Kuis --}}
    <div class="card" style="margin-bottom: var(--space-6); padding: 0; overflow-x: auto;">
        <div style="padding: var(--space-4); border-bottom: 1px solid var(--border); font-weight: 700; font-size: 1.125rem;">
            Riwayat Pengerjaan Kuis
        </div>
        <table class="table" style="margin: 0;">
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
                        <td>
                            <strong>{{ $attempt->quiz?->title ?? 'Kuis' }}</strong>
                        </td>
                        <td>{{ $attempt->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <span class="badge" style="background: var(--surface-alt); font-weight: 700;">{{ $pct }}%</span>
                        </td>
                        <td>
                            <span class="badge badge-warning">{{ $stars }} Bintang</span>
                        </td>
                        <td>
                            {{ $attempt->correct_first_try }} dari {{ $attempt->total_questions }} benar
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-6); color: var(--text-muted);">
                            Siswa belum pernah mengerjakan kuis.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Bagian 2: Riwayat Permainan --}}
    <div class="card" style="padding: 0; overflow-x: auto;">
        <div style="padding: var(--space-4); border-bottom: 1px solid var(--border); font-weight: 700; font-size: 1.125rem;">
            Riwayat Sesi Permainan
        </div>
        <table class="table" style="margin: 0;">
            <thead>
                <tr>
                    <th>Permainan</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Ronde Selesai</th>
                    <th>Durasi</th>
                    <th>Bintang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gameSessions as $session)
                    <tr>
                        <td><strong>{{ $session->game?->title ?? 'Permainan' }}</strong></td>
                        <td>{{ $session->game?->category?->name ?? '-' }}</td>
                        <td>{{ $session->created_at->format('d M Y, H:i') }}</td>
                        <td>{{ $session->rounds_correct_first_try }} / {{ $session->rounds_total }}</td>
                        <td>{{ gmdate('i:s', $session->duration_seconds) }}</td>
                        <td><span class="badge badge-warning">{{ $session->stars_earned }} Bintang</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: var(--space-6); color: var(--text-muted);">
                            Siswa belum pernah memainkan permainan edukasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@else
    <div class="card" style="text-align: center; padding: var(--space-10); color: var(--text-muted);">
        Silakan pilih siswa pada menu di atas untuk menampilkan rincian hasil belajar.
    </div>
@endif
@endsection
