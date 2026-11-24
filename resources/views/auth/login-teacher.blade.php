@extends('layouts.guest')

@section('title', 'Masuk Panel Guru | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div class="lt-container">

  {{-- Tombol Kembali --}}
  <div class="lt-back-wrapper">
    <a href="{{ route('login') }}" class="lt-back-link">
      <span class="lt-arrow">&larr;</span>
      <span>Pilih Peran Lain</span>
    </a>
  </div>

  {{-- Kartu Login Guru --}}
  <div class="lt-card">
    {{-- Aksen Hijau Emerald di Atas Kartu --}}
    <div class="lt-accent-bar"></div>

    {{-- Header Kartu --}}
    <div class="lt-header">
      <div class="lt-monogram">G</div>
      <h1 class="lt-title">Masuk Panel Guru</h1>
      <p class="lt-subtitle">Kelola materi, kuis, absensi, dan rapor siswa</p>
    </div>

    {{-- Pesan Error Autentikasi --}}
    @if ($errors->has('auth'))
      <div class="lt-alert" role="alert">
        <span class="lt-alert-badge">!</span>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    {{-- Formulir Login --}}
    <form action="{{ route('login.teacher.submit') }}" method="POST" class="lt-form">
      @csrf

      {{-- Input Email --}}
      <div class="lt-field">
        <label for="email" class="lt-label">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
               placeholder="guru@slb.test"
               class="lt-input @error('email') is-invalid @enderror">
        @error('email')
          <span class="lt-error-text">{{ $message }}</span>
        @enderror
      </div>

      {{-- Input Password --}}
      <div class="lt-field">
        <label for="password" class="lt-label">Kata Sandi</label>
        <input type="password" id="password" name="password" required
               placeholder="Masukkan kata sandi..."
               class="lt-input @error('password') is-invalid @enderror">
        @error('password')
          <span class="lt-error-text">{{ $message }}</span>
        @enderror
      </div>

      {{-- Checkbox Ingat Sesi --}}
      <div class="lt-remember-row">
        <label class="lt-checkbox-label">
          <input type="checkbox" id="remember" name="remember" class="lt-checkbox">
          <span>Ingat sesi di perangkat ini</span>
        </label>
      </div>

      {{-- Tombol Submit --}}
      <button type="submit" class="lt-submit-btn">
        <span>Masuk ke Panel Guru</span>
        <span class="lt-submit-arrow">&rarr;</span>
      </button>
    </form>

    {{-- Link Beralih ke Siswa --}}
    <div class="lt-footer">
      <a href="{{ route('login.student') }}" class="lt-switch-link">
        &larr; Beralih ke Masuk Siswa
      </a>
    </div>
  </div>
</div>

<style>
/* ══════════════ TAMPILAN MODERN MASUK GURU (ZERO EMOJI) ══════════════ */
.lt-container {
  width: 100%;
  max-width: 440px;
  margin: 0 auto;
}

/* Navigasi Kembali */
.lt-back-wrapper {
  margin-bottom: 18px;
}
.lt-back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--ink-soft, #4B5563);
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 600;
  transition: color 0.15s ease, transform 0.15s ease;
}
.lt-back-link:hover {
  color: var(--ink, #1F2933);
  transform: translateX(-2px);
}
.lt-arrow {
  font-size: 1.125rem;
  line-height: 1;
}

/* Kartu Utama */
.lt-card {
  position: relative;
  background: var(--surface, #FFFFFF);
  border: 1.5px solid var(--line, #D9D2C5);
  border-radius: 18px;
  padding: 36px 32px 30px;
  box-shadow: 0 4px 20px rgba(31, 41, 51, 0.06);
  overflow: hidden;
}

/* Garis Aksen Hijau Emerald di Atas */
.lt-accent-bar {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: var(--success, #246B4D);
}

/* Header Kartu */
.lt-header {
  text-align: center;
  margin-bottom: 28px;
}
.lt-monogram {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: var(--success-bg, #E3F2EA);
  border: 1.5px solid rgba(36, 107, 77, 0.25);
  color: var(--success, #246B4D);
  font-size: 1.625rem;
  font-weight: 800;
  line-height: 1;
  display: grid;
  place-items: center;
  margin: 0 auto 14px auto;
}
.lt-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--ink, #1F2933);
  margin: 0 0 6px 0;
  letter-spacing: -0.3px;
  line-height: 1.25;
}
.lt-subtitle {
  color: var(--ink-soft, #4B5563);
  font-size: 0.9375rem;
  margin: 0;
  line-height: 1.5;
}

/* Kotak Peringatan / Error */
.lt-alert {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 10px;
  background: var(--hint-bg, #FFF4DB);
  border: 1px solid var(--hint-line, #C99A2E);
  color: #92400E;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 22px;
  text-align: left;
}
.lt-alert-badge {
  flex-shrink: 0;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #D97706;
  color: #FFFFFF;
  font-size: 0.75rem;
  font-weight: 800;
  display: grid;
  place-items: center;
  margin-top: 1px;
}

/* Form Fields */
.lt-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
  text-align: left;
}
.lt-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.lt-label {
  font-size: 0.875rem;
  font-weight: 700;
  color: var(--ink, #1F2933);
  letter-spacing: -0.1px;
}
.lt-input {
  width: 100%;
  min-height: 48px;
  padding: 0 16px;
  font-size: 0.9375rem;
  color: var(--ink, #1F2933);
  background: var(--surface, #FFFFFF);
  border: 1.5px solid var(--line, #D9D2C5);
  border-radius: 10px;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
  box-sizing: border-box;
}
.lt-input:focus {
  outline: none;
  border-color: var(--success, #246B4D);
  box-shadow: 0 0 0 3px rgba(36, 107, 77, 0.18);
}
.lt-input.is-invalid {
  border-color: var(--danger, #B3261E);
}
.lt-error-text {
  font-size: 0.8125rem;
  font-weight: 700;
  color: var(--danger, #B3261E);
}

/* Remember Me Checkbox */
.lt-remember-row {
  margin-top: -4px;
}
.lt-checkbox-label {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  font-size: 0.875rem;
  color: var(--ink-soft, #4B5563);
  font-weight: 500;
  user-select: none;
}
.lt-checkbox {
  width: 18px;
  height: 18px;
  border-radius: 4px;
  accent-color: var(--success, #246B4D);
  cursor: pointer;
}

/* Tombol Submit */
.lt-submit-btn {
  width: 100%;
  min-height: 50px;
  padding: 0 20px;
  margin-top: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  border-radius: 10px;
  background: var(--success, #246B4D);
  color: #FFFFFF;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: background-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
  box-shadow: 0 2px 6px rgba(36, 107, 77, 0.2);
}
.lt-submit-btn:hover {
  background: #1C553D;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(36, 107, 77, 0.3);
}
.lt-submit-btn:active {
  transform: translateY(1px);
}
.lt-submit-arrow {
  font-size: 1.125rem;
  transition: transform 0.15s ease;
}
.lt-submit-btn:hover .lt-submit-arrow {
  transform: translateX(3px);
}

/* Link Bawah / Switch Role */
.lt-footer {
  margin-top: 24px;
  padding-top: 18px;
  border-top: 1px solid var(--line, #D9D2C5);
  text-align: center;
}
.lt-switch-link {
  color: var(--primary, #2F6F9F);
  text-decoration: none;
  font-weight: 700;
  font-size: 0.875rem;
  transition: color 0.15s ease;
}
.lt-switch-link:hover {
  color: var(--primary-strong, #25587F);
  text-decoration: underline;
}

@media (max-width: 480px) {
  .lt-card {
    padding: 28px 20px 24px;
    border-radius: 14px;
  }
}
</style>
@endsection
