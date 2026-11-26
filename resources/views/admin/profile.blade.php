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

      <div class="adm-form-section">
        <h3 class="adm-form-section__title">Ubah Kata Sandi (Opsional)</h3>
        <p class="adm-form-section__sub">Kosongkan ketiga kolom di bawah apabila Anda tidak ingin mengganti kata sandi.</p>

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

      <div class="adm-form-footer adm-form-footer--tight">
        <a href="{{ route('admin.dashboard') }}" class="adm-btn adm-btn--secondary">Kembali ke Ringkasan</a>
        <button type="submit" class="adm-btn adm-btn--primary">Simpan Profil</button>
      </div>
    </form>
  </div>

  <section class="adm-panel">
    <div class="adm-panel__head">
      <div>
        <h2 class="adm-panel__title">Ringkasan Wewenang Guru</h2>
        <p class="adm-panel__sub">Akses penuh kurikulum &amp; laporan sekolah</p>
      </div>
      <span class="adm-pill adm-pill--success"><span class="adm-dot"></span>Admin Aktif</span>
    </div>
    <div class="adm-panel__body adm-panel__body--grow adm-kv-list">
      <div class="adm-kv-row">
        <span class="adm-kv-row__key">Username</span>
        <code>{{ $teacher->username }}</code>
      </div>
      <div class="adm-kv-row">
        <span class="adm-kv-row__key">Satuan Pendidikan</span>
        <strong>SLB B-C Wijayakusumah</strong>
      </div>
      <div class="adm-kv-row">
        <span class="adm-kv-row__key">Siswa Aktif Terkelola</span>
        <strong>{{ $summary['students'] }} Siswa</strong>
      </div>
      <div class="adm-kv-row">
        <span class="adm-kv-row__key">Kategori &amp; Materi</span>
        <strong>{{ $summary['categories'] }} Kat. / {{ $summary['materials'] }} Materi</strong>
      </div>
      <div class="adm-kv-row">
        <span class="adm-kv-row__key">Bank Kuis Aktif</span>
        <strong>{{ $summary['quizzes'] }} Kuis</strong>
      </div>

      <div class="adm-panel__foot-note">
        <span>Disarankan mengganti kata sandi secara berkala untuk keamanan data siswa.</span>
      </div>
    </div>
  </section>
</div>
@endsection
