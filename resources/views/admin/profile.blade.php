@extends('layouts.admin')

@section('title', 'Profil Guru | Panel Pengelolaan Guru')
@section('page_title', 'Profil Guru')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Pengaturan Akun Pendidik</p>
    <h1 class="adm-title">Profil &amp; Keamanan Akun Guru</h1>
    <p class="adm-subtitle">Kelola identitas akun guru dan perbarui kata sandi panel pengelolaan secara berkala.</p>
  </div>
</div>

<div class="adm-grid-aside">
  <div class="adm-form-card">
    <form action="{{ route('admin.profile.update') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="adm-field">
        <label for="name" class="adm-label">Nama Lengkap Pendidik *</label>
        <input type="text" id="name" name="name" class="adm-input" value="{{ old('name', $teacher->name) }}" required>
        @error('name')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div class="adm-field">
        <label for="email" class="adm-label">Alamat Email Masuk Panel *</label>
        <input type="email" id="email" name="email" class="adm-input" value="{{ old('email', $teacher->email) }}" required>
        @error('email')<span class="adm-error">{{ $message }}</span>@enderror
      </div>

      <div style="margin: 24px 0 16px; padding-top: 18px; border-top: 1px solid var(--line);">
        <h3 style="margin: 0 0 4px; font-size: 1rem; color: var(--ink);">Ubah Kata Sandi (Opsional)</h3>
        <p style="margin: 0 0 14px; font-size: 0.8125rem; color: var(--ink-soft);">Kosongkan ketiga kolom di bawah apabila Anda tidak ingin mengganti kata sandi.</p>

        <div class="adm-field">
          <label for="current_password" class="adm-label">Kata Sandi Saat Ini</label>
          <input type="password" id="current_password" name="current_password" class="adm-input" placeholder="Masukkan kata sandi saat ini">
          @error('current_password')<span class="adm-error">{{ $message }}</span>@enderror
        </div>

        <div class="adm-form-grid-2">
          <div class="adm-field">
            <label for="password" class="adm-label">Kata Sandi Baru</label>
            <input type="password" id="password" name="password" class="adm-input" placeholder="Minimal 6 karakter">
            @error('password')<span class="adm-error">{{ $message }}</span>@enderror
          </div>

          <div class="adm-field">
            <label for="password_confirmation" class="adm-label">Ulangi Kata Sandi Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="adm-input" placeholder="Ulangi kata sandi baru">
          </div>
        </div>
      </div>

      <div class="adm-form-footer" style="margin-top: 8px;">
        <a href="{{ route('admin.dashboard') }}" class="adm-btn adm-btn--secondary">Kembali ke Ringkasan</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Profil</button>
      </div>
    </form>
  </div>

  <div>
    <section class="adm-panel">
      <div class="adm-panel__head">
        <div>
          <h2 class="adm-panel__title">Ringkasan Wewenang Guru</h2>
          <p class="adm-panel__sub">Akses penuh kurikulum &amp; laporan sekolah</p>
        </div>
        <span class="adm-pill adm-pill--success">Admin Aktif</span>
      </div>
      <div class="adm-panel__body" style="display: flex; flex-direction: column; gap: 12px;">
        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
          <span style="color: var(--ink-soft);">Username</span>
          <code>{{ $teacher->username }}</code>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
          <span style="color: var(--ink-soft);">Satuan Pendidikan</span>
          <strong>SLB B-C Wijayakusumah</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
          <span style="color: var(--ink-soft);">Siswa Aktif Terkelola</span>
          <strong>{{ $summary['students'] }} Siswa</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
          <span style="color: var(--ink-soft);">Kategori &amp; Materi</span>
          <strong>{{ $summary['categories'] }} Kat. / {{ $summary['materials'] }} Materi</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
          <span style="color: var(--ink-soft);">Bank Kuis Aktif</span>
          <strong>{{ $summary['quizzes'] }} Kuis</strong>
        </div>
      </div>
    </section>
  </div>
</div>
@endsection
