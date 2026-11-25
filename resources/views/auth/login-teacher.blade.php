@extends('layouts.guest')

@section('title', 'Masuk Guru | Sahabat Belajar')

@section('content')
<div class="teacher-wrap">
  <div class="login-nav-row">
    <a href="{{ route('login') }}" class="login-nav-pill">
      <span aria-hidden="true">&larr;</span>
      <span>Kembali</span>
    </a>
  </div>

  <div class="teacher-card">
    <div class="role-card--teacher">
      <div class="role-card__cover">
        <img src="/images/guru-cover.jpg" alt="Ilustrasi guru di kelas" class="role-card__illus">
        <span class="role-card__tag">Guru</span>
      </div>
    </div>

    <div class="teacher-body">
      <div class="teacher-head">
        <h1 class="teacher-title">Masuk Guru</h1>
        <p class="teacher-desc">Silakan masuk dengan email dan kata sandi Anda.</p>
      </div>

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
          <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="email"
            placeholder="guru@slb.test"
            class="login-input login-input--green @error('email') is-invalid @enderror"
            @error('email') aria-describedby="email-error" @enderror
          >
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
            <input
              type="password"
              id="password"
              name="password"
              required
              autocomplete="current-password"
              placeholder="Masukkan kata sandi"
              class="login-input login-input--green login-input--with-toggle @error('password') is-invalid @enderror"
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
          <span aria-hidden="true">&rarr;</span>
        </button>
      </form>
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
