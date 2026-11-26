@extends('layouts.guest')

@section('title', 'Masuk Guru | Sahabat Belajar')

@section('content')
<div class="teacher-wrap">
  <div class="teacher-card auth-anim auth-anim--1">
    @include('auth.partials.login-topbar', ['variant' => 'teacher', 'badge' => 'Portal Guru'])

    <div class="teacher-grid">
      {{-- Kolom Kiri: Ilustrasi Vektor Guru, Sapaan & Catatan Panel --}}
      <div class="tl-left">
        <div class="role-card--teacher">
          <div class="role-card__cover">
            @include('auth.partials.role-art', ['role' => 'teacher'])
          </div>
        </div>

        <div class="tl-left__content">
          <div class="teacher-head">
            <h1 class="teacher-title">Masuk Guru</h1>
            <p class="teacher-desc">Kelola materi belajar, kuis interaktif, dan laporan perkembangan siswa.</p>
          </div>

          <div class="tl-note">
            <svg class="tl-note__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              <path d="m9 12 2 2 4-4"></path>
            </svg>
            <span>Gunakan akun guru terdaftar SLB B-C Wijayakusumah.</span>
          </div>
        </div>
      </div>

      {{-- Kolom Kanan: Panel Formulir Masuk Guru --}}
      <div class="tl-right">
        @if ($errors->has('auth'))
          <div class="login-alert" role="alert">
            <span class="login-alert__dot" aria-hidden="true">!</span>
            <span>{{ $errors->first('auth') }}</span>
          </div>
        @endif

        <form id="teacherLoginForm" action="{{ route('login.teacher.submit') }}" method="POST" class="login-form">
          @csrf

          <div class="login-field">
            <div class="login-label-row">
              <label for="email" class="login-label">Email</label>
              <span id="emailValidTag" class="login-valid-tag">Format sesuai</span>
            </div>
            <div class="login-input-wrap">
              <svg class="login-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="2" y="4" width="20" height="16" rx="3"></rect>
                <path d="m22 7-10 6L2 7"></path>
              </svg>
              <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="Masukkan email"
                class="login-input login-input--green login-input--with-icon @error('email') is-invalid @enderror"
                @error('email') aria-describedby="email-error" @enderror
              >
            </div>
            @error('email')
              <span id="email-error" class="login-error">{{ $message }}</span>
            @enderror
          </div>

          <div class="login-field">
            <div class="login-label-row">
              <label for="password" class="login-label">Kata Sandi</label>
              <span id="capsWarning" class="login-caps-badge">Caps Lock aktif</span>
            </div>
            <div class="login-input-wrap">
              <svg class="login-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan kata sandi"
                class="login-input login-input--green login-input--with-icon login-input--with-toggle @error('password') is-invalid @enderror"
                @error('password') aria-describedby="password-error" @enderror
              >
              <button type="button" id="togglePassword" class="login-pw-toggle" aria-pressed="false" aria-label="Tampilkan atau sembunyikan kata sandi">
                Lihat
              </button>
            </div>
            @error('password')
              <span id="password-error" class="login-error">{{ $message }}</span>
            @enderror
          </div>

          <div>
            <label for="remember" class="login-check-label">
              <input type="checkbox" id="remember" name="remember">
              <span>Ingat saya</span>
            </label>
          </div>

          <button type="submit" id="btnTeacherSubmit" class="login-submit login-submit--green">
            <span id="teacherSubmitLabel">Masuk</span>
            <svg class="login-submit__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const emailInput = document.getElementById('email');
  const emailValidTag = document.getElementById('emailValidTag');
  const passwordInput = document.getElementById('password');
  const toggleBtn = document.getElementById('togglePassword');
  const capsWarning = document.getElementById('capsWarning');
  const teacherForm = document.getElementById('teacherLoginForm');
  const submitBtn = document.getElementById('btnTeacherSubmit');
  const submitLabel = document.getElementById('teacherSubmitLabel');

  if (emailInput && emailValidTag) {
    const checkEmail = () => {
      const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim());
      emailValidTag.classList.toggle('is-visible', ok);
    };
    emailInput.addEventListener('input', checkEmail);
    checkEmail();
  }

  if (passwordInput && toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const showing = passwordInput.type === 'text';
      passwordInput.type = showing ? 'password' : 'text';
      toggleBtn.textContent = showing ? 'Lihat' : 'Tutup';
      toggleBtn.setAttribute('aria-pressed', showing ? 'false' : 'true');
    });

    const handleCaps = (e) => {
      if (capsWarning && typeof e.getModifierState === 'function') {
        capsWarning.classList.toggle('is-visible', e.getModifierState('CapsLock'));
      }
    };
    passwordInput.addEventListener('keydown', handleCaps);
    passwordInput.addEventListener('keyup', handleCaps);
    passwordInput.addEventListener('blur', () => capsWarning?.classList.remove('is-visible'));
  }

  if (teacherForm && submitBtn && submitLabel) {
    teacherForm.addEventListener('submit', () => {
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.8';
      submitLabel.textContent = 'Memeriksa...';
    });
  }
</script>
@endpush
