@extends('layouts.guest')

@section('title', 'Masuk Guru | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div style="width: 100%; max-width: 440px; margin: 0 auto;">

  <!-- Tombol Kembali -->
  <div style="margin-bottom: 16px;">
    <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      <span>Pilih Peran Lain</span>
    </a>
  </div>

  <div class="card" style="background: var(--surface); border: 2px solid var(--border); border-radius: 20px; padding: 36px 30px; text-align: left; box-shadow: var(--shadow-sm);">

    <div style="text-align: center; margin-bottom: 24px;">
      <!-- SVG Icon Guru Modern (No Emoji) -->
      <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(5, 150, 105, 0.08); border: 1px solid rgba(5, 150, 105, 0.16); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #047857;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
      </div>

      <h1 style="font-size: 1.625rem; font-weight: 800; color: var(--text); margin: 0 0 6px 0; letter-spacing: -0.3px;">
        Masuk Panel Guru
      </h1>
      <p style="color: var(--text-muted); font-size: 0.9375rem; margin: 0;">
        Akses panel kelola materi, kuis, absensi, dan rapor siswa
      </p>
    </div>

    @if ($errors->has('auth'))
      <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 10px; background: #FEF3C7; border: 1px solid #F59E0B; color: #92400E; font-size: 0.875rem; font-weight: 600; text-align: left; display: flex; align-items: flex-start; gap: 10px;" role="alert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    <form action="{{ route('login.teacher.submit') }}" method="POST">
      @csrf

      <div style="margin-bottom: 18px;">
        <label for="email" style="display: block; font-weight: 700; font-size: 0.875rem; margin-bottom: 6px; color: var(--text);">
          Alamat Email
        </label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
               placeholder="guru@slb.test"
               class="form-control"
               style="width: 100%; min-height: 48px; font-size: 1rem; padding: 0 14px; border-radius: 10px;">
        @error('email')
          <small style="color: var(--danger); font-size: 0.8125rem; font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>
        @enderror
      </div>

      <div style="margin-bottom: 20px;">
        <label for="password" style="display: block; font-weight: 700; font-size: 0.875rem; margin-bottom: 6px; color: var(--text);">
          Kata Sandi
        </label>
        <input type="password" id="password" name="password" required
               placeholder="Masukkan kata sandi..."
               class="form-control"
               style="width: 100%; min-height: 48px; font-size: 1rem; padding: 0 14px; border-radius: 10px;">
        @error('password')
          <small style="color: var(--danger); font-size: 0.8125rem; font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>
        @enderror
      </div>

      <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
        <input type="checkbox" id="remember" name="remember" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary);">
        <label for="remember" style="font-size: 0.875rem; color: var(--text-muted); cursor: pointer;">Ingat sesi di perangkat ini</label>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; min-height: 50px; font-size: 1rem; font-weight: 700; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <span>Masuk ke Panel Guru</span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"></line>
          <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
      </button>
    </form>

    <div style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 24px; text-align: center;">
      <a href="{{ route('login.student') }}" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        <span>Beralih ke Masuk Siswa</span>
      </a>
    </div>
  </div>
</div>
@endsection
