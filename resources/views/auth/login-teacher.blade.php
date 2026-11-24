@extends('layouts.guest')

@section('title', 'Masuk Guru | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div class="ls-container">

  {{-- Back --}}
  <a href="{{ route('login') }}" class="ls-back">
    <span class="ls-back__arrow">&larr;</span>
    <span>Pilih Peran Lain</span>
  </a>

  <div class="ls-card">
    {{-- Accent --}}
    <div class="ls-accent ls-accent--green"></div>

    {{-- Header --}}
    <div class="ls-header">
      <div class="ls-monogram ls-monogram--green">G</div>
      <h1 class="ls-title">Masuk Panel Guru</h1>
      <p class="ls-desc">Kelola materi, kuis, absensi, dan rapor siswa</p>
    </div>

    {{-- Error --}}
    @if ($errors->has('auth'))
      <div class="ls-alert" role="alert">
        <span class="ls-alert__badge">!</span>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    <form action="{{ route('login.teacher.submit') }}" method="POST">
      @csrf

      {{-- Email --}}
      <div class="ls-field">
        <label for="email" class="ls-label">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
               placeholder="guru@slb.test"
               class="form-control ls-input" style="font-size: 1rem;">
        @error('email')
          <small class="ls-error">{{ $message }}</small>
        @enderror
      </div>

      {{-- Password --}}
      <div class="ls-field">
        <label for="password" class="ls-label">Kata Sandi</label>
        <input type="password" id="password" name="password" required
               placeholder="Masukkan kata sandi..."
               class="form-control ls-input" style="font-size: 1rem;">
        @error('password')
          <small class="ls-error">{{ $message }}</small>
        @enderror
      </div>

      {{-- Remember --}}
      <div class="lt-remember">
        <input type="checkbox" id="remember" name="remember" class="lt-checkbox">
        <label for="remember" class="lt-remember__label">Ingat sesi di perangkat ini</label>
      </div>

      {{-- Submit --}}
      <button type="submit" class="btn btn-primary lt-submit">
        Masuk ke Panel Guru &rarr;
      </button>
    </form>

    {{-- Switch role --}}
    <div class="ls-switch">
      <a href="{{ route('login.student') }}" class="ls-switch__link">
        &larr; Beralih ke Masuk Siswa
      </a>
    </div>
  </div>
</div>

<style>
/* Shared styles from login-student are inherited via @push('styles') or inline above */
/* Only teacher-specific extras here */

.lt-remember {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 24px;
}
.lt-checkbox {
  width: 18px; height: 18px; cursor: pointer;
  accent-color: var(--success);
  border-radius: 4px;
}
.lt-remember__label {
  font-size: 0.875rem; color: var(--text-muted);
  cursor: pointer;
}

.lt-submit {
  width: 100%; min-height: 50px;
  font-size: 1rem; font-weight: 700;
  border-radius: 10px;
  background: var(--success) !important;
  border-color: var(--success) !important;
}
.lt-submit:hover {
  background: #1e5a3f !important;
}
</style>
@endsection
