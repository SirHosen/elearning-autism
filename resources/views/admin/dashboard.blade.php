@extends('layouts.admin')

@section('title', 'Dashboard | Panel Guru')

@section('content')
<div style="margin-bottom: 28px;">
  <p style="font-size: 13px; font-weight: 800; letter-spacing: 2px; color: var(--primary); margin-bottom: 4px;">PANEL PENGELOLAAN</p>
  <h2 style="font-size: 28px;">Selamat bekerja, {{ auth()->user()->name }}! 👋</h2>
  <p style="color: var(--ink-soft); font-size: 15px; margin-top: 4px;">Kelola materi dan pantau kemajuan belajar siswa SLB B-C Wijayakusumah.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px;">
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <small style="color: var(--ink-soft); font-weight: 700;">Total Siswa Aktif</small>
    <strong style="display: block; font-size: 32px; margin-top: 8px;">{{ \App\Models\User::where('role', 'siswa')->count() }}</strong>
  </div>
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <small style="color: var(--ink-soft); font-weight: 700;">Total Materi Pembelajaran</small>
    <strong style="display: block; font-size: 32px; margin-top: 8px;">{{ \App\Models\Material::count() }}</strong>
  </div>
  <div style="background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 20px;">
    <small style="color: var(--ink-soft); font-weight: 700;">Kategori Aktif</small>
    <strong style="display: block; font-size: 32px; margin-top: 8px;">{{ \App\Models\Category::where('is_active', true)->count() }}</strong>
  </div>
</div>
@endsection
