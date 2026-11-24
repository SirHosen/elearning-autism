@extends('layouts.admin')

@section('title', 'Data Siswa | Panel Guru')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px; flex-wrap: wrap;">
  <div>
    <h2 style="font-size: 24px; margin-bottom: 4px;">Data Akun Siswa</h2>
    <p style="color: var(--ink-soft); font-size: 14px;">Kelola akun masuk siswa dan cetak kartu PIN digital.</p>
  </div>
  <a href="{{ route('admin.students.create') }}" class="btn btn-primary" style="min-height: 44px; padding: 0 18px; font-size: 15px;">
    + Tambah Siswa Baru
  </a>
</div>

<!-- Banner Flash PIN Baru (Tampil Sekali) -->
@if (session('generated_pin'))
  <div style="background: var(--tint-yellow); border: 2px solid var(--hint-line); border-radius: var(--radius-sm); padding: 20px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <p style="font-size: 13px; font-weight: 800; color: #8A6508; letter-spacing: 1px; margin-bottom: 4px;">KARTU PIN DIGITAL SISWA (HANYA TAMPIL SEKALI)</p>
        <h3 style="font-size: 20px; margin-bottom: 6px;">{{ session('student_name') }} (Username: <code>{{ session('student_username') }}</code>)</h3>
        <p style="font-size: 16px; margin: 0;">PIN Masuk: <strong style="font-size: 24px; letter-spacing: 4px; color: var(--primary);">{{ session('generated_pin') }}</strong></p>
      </div>
      <div>
        <a href="{{ route('admin.students.card', session('student_id')) }}" target="_blank" class="btn btn-primary" style="min-height: 42px; padding: 0 16px;">
          🖨 Cetak Kartu PIN
        </a>
      </div>
    </div>
  </div>
@endif

<!-- Pencarian Siswa -->
<div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
  <form action="{{ route('admin.students.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap;">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama siswa, username, atau kelas..."
           style="flex: 1; min-width: 240px; min-height: 42px; padding: 0 12px; border: 1px solid var(--line); border-radius: var(--radius-sm);">
    <button type="submit" class="btn btn-secondary" style="min-height: 42px; padding: 0 16px;">Cari</button>
    @if (request('q'))
      <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" style="min-height: 42px; padding: 0 12px;">Reset</a>
    @endif
  </form>
</div>

<div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); overflow-x: auto;">
  <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
    <thead>
      <tr style="background: var(--surface-alt); border-bottom: 1px solid var(--line); color: var(--ink-soft);">
        <th style="padding: 14px 16px;">Nama Siswa</th>
        <th style="padding: 14px 16px;">Username</th>
        <th style="padding: 14px 16px;">Kelas</th>
        <th style="padding: 14px 16px;">Status</th>
        <th style="padding: 14px 16px;">Terakhir Masuk</th>
        <th style="padding: 14px 16px; text-align: right;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($students as $s)
        <tr style="border-bottom: 1px solid var(--line);">
          <td style="padding: 14px 16px; font-weight: 700;">{{ $s->name }}</td>
          <td style="padding: 14px 16px;"><code>{{ $s->username }}</code></td>
          <td style="padding: 14px 16px;">{{ $s->class_name ?? '-' }}</td>
          <td style="padding: 14px 16px;">
            @if ($s->is_active)
              <span style="background: var(--success-bg); color: var(--success); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700;">Aktif</span>
            @else
              <span style="background: #eee; color: #777; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700;">Nonaktif</span>
            @endif
          </td>
          <td style="padding: 14px 16px; color: var(--ink-soft);">
            {{ $s->last_login_at ? $s->last_login_at->translatedFormat('d M Y H:i') : 'Belum pernah' }}
          </td>
          <td style="padding: 14px 16px; text-align: right;">
            <div style="display: inline-flex; gap: 8px;">
              <a href="{{ route('admin.students.card', $s) }}" target="_blank" class="btn btn-secondary" style="min-height: 36px; padding: 0 10px; font-size: 13px;" title="Cetak Kartu PIN">
                🖨 Kartu
              </a>
              <form action="{{ route('admin.students.reset-pin', $s) }}" method="POST" onsubmit="return confirm('Reset PIN untuk {{ $s->name }}?')">
                @csrf
                <button type="submit" class="btn btn-secondary" style="min-height: 36px; padding: 0 10px; font-size: 13px;">Reset PIN</button>
              </form>
              <a href="{{ route('admin.students.edit', $s) }}" class="btn btn-secondary" style="min-height: 36px; padding: 0 10px; font-size: 13px;">Ubah</a>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding: 32px; text-align: center; color: var(--ink-soft);">Tidak ada data siswa.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top: 24px;">
  {{ $students->links() }}
</div>
@endsection
