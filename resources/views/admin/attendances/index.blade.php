@extends('layouts.admin')

@section('title', 'Rekap Absensi Siswa')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem;">Rekap Absensi Siswa</h1>
        <p style="margin: var(--space-1) 0 0 0; color: var(--text-muted); font-size: 0.9375rem;">Pantau kehadiran mandiri siswa dan perbarui status kehadiran secara manual.</p>
    </div>
    <div>
        <a href="{{ route('admin.attendances.export', ['date' => $date]) }}" class="btn btn-secondary">
            📥 Export CSV Absensi
        </a>
    </div>
</div>

{{-- Filter Tanggal --}}
<div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
    <form action="{{ route('admin.attendances.index') }}" method="GET" style="display: flex; gap: var(--space-4); align-items: center; flex-wrap: wrap;">
        <label for="date" style="font-weight: 600; font-size: 0.9375rem;">Pilih Tanggal:</label>
        <input type="date" name="date" id="date" class="form-control" style="max-width: 200px;" value="{{ $date }}" onchange="this.form.submit()">
        <button type="submit" class="btn btn-primary" style="padding: var(--space-2) var(--space-4); font-size: 0.875rem;">Lihat Absensi</button>
        @if($date !== now()->format('Y-m-d'))
            <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary" style="padding: var(--space-2) var(--space-4); font-size: 0.875rem;">Hari Ini</a>
        @endif
    </form>
</div>

<div class="card" style="padding: 0; overflow-x: auto;">
    <table class="table" style="margin: 0;">
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>Nama Siswa</th>
                <th>Status Kehadiran</th>
                <th>Waktu Masuk</th>
                <th>Pencatat</th>
                <th>Catatan</th>
                <th style="text-align: right; width: 220px;">Ubah Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                @php
                    $att = $attendances->get($student->id);
                    $status = $att ? $att->status : null;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: 600; color: var(--text);">{{ $student->name }}</div>
                        <div style="font-size: 0.8125rem; color: var(--text-muted);">{{ $student->username }}</div>
                    </td>
                    <td>
                        @if($status === 'hadir')
                            <span class="badge" style="background: var(--success-bg, #E3F2EA); color: var(--success, #246B4D); font-weight: 600;">Hadir</span>
                        @elseif($status === 'sakit')
                            <span class="badge" style="background: #FEF3C7; color: #92400E; font-weight: 600;">Sakit</span>
                        @elseif($status === 'izin')
                            <span class="badge" style="background: #E0E7FF; color: #3730A3; font-weight: 600;">Izin</span>
                        @elseif($status === 'alpa')
                            <span class="badge" style="background: #FEE2E2; color: #991B1B; font-weight: 600;">Alpa</span>
                        @else
                            <span class="badge" style="background: #F3F4F6; color: #6B7280;">Belum Absen</span>
                        @endif
                    </td>
                    <td>
                        {{ $att?->checked_in_at ? $att->checked_in_at->format('H:i') : '-' }}
                    </td>
                    <td>
                        @if($att)
                            <span style="font-size: 0.875rem;">
                                {{ $att->source === 'siswa' ? '📱 Mandiri' : '👨‍🏫 Guru' }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <span style="font-size: 0.875rem; color: var(--text-muted);">{{ $att?->note ?: '-' }}</span>
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('admin.attendances.update', $student) }}" method="POST" style="display: flex; gap: var(--space-2); justify-content: flex-end; align-items: center;">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}">
                            <select name="status" class="form-control" style="font-size: 0.8125rem; padding: var(--space-1) var(--space-2); max-width: 110px;" onchange="this.form.submit()">
                                <option value="hadir" {{ $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="izin" {{ $status === 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="sakit" {{ $status === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="alpa" {{ $status === 'alpa' ? 'selected' : '' }}>Alpa</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: var(--space-8); color: var(--text-muted);">
                        Belum ada data siswa terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
