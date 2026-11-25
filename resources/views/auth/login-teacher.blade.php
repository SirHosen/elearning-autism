@extends('layouts.guest')

@section('title', 'Masuk Guru | Sahabat Belajar')

@section('content')
<div class="login-wrap">
  <a href="{{ route('login') }}" class="login-back">
    <span aria-hidden="true">&larr;</span>
    <span>Kembali</span>
  </a>

  <div class="login-card">
    <div class="login-card__accent login-card__accent--green"></div>

    <div class="login-card__head">
      <span class="login-role-pill login-role-pill--green">Guru</span>
      <h1 class="login-card__title">Masuk Guru</h1>
      <p class="login-card__desc">Silakan masuk dengan email dan kata sandi Anda.</p>
    </div>

    @if ($errors->has('auth'))
      <div class="login-alert" role="alert">
        <span class="login-alert__dot" aria-hidden="true">!</span>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    <form action="{{ route('login.teacher.submit') }}" method="POST" class="login-form">
      @csrf

      <div class="login-field">
        <label for="email" class="login-label">Email</label>
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
        <label for="password" class="login-label">Kata Sandi</label>
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

      <button type="submit" class="login-submit login-submit--green">
        <span>Masuk</span>
        <span aria-hidden="true">&rarr;</span>
      </button>
    </form>
  </div>

  <p class="login-switch-alt">
    Bukan guru? <a href="{{ route('login.student') }}">Masuk sebagai murid</a>
  </p>
</div>
@endsection

@push('scripts')
<script>
  const passwordInput = document.getElementById('password');
  const toggleBtn = document.getElementById('togglePassword');

  if (passwordInput && toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const showing = passwordInput.type === 'text';
      passwordInput.type = showing ? 'password' : 'text';
      toggleBtn.textContent = showing ? 'Lihat' : 'Tutup';
      toggleBtn.setAttribute('aria-pressed', showing ? 'false' : 'true');
    });
  }
</script>
@endpush
