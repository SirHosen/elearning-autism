@extends('layouts.guest')

@section('title', 'Masuk Siswa | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div class="auth-shell">

  {{-- Panel Kiri: Panduan Singkat untuk Siswa & Pendamping --}}
  <aside class="auth-aside">
    <div>
      <span class="auth-aside__eyebrow">Area Belajar Siswa</span>
      <h2 class="auth-aside__title">Siap Belajar Bersama Hari Ini</h2>
      <p class="auth-aside__lead">
        Gunakan kartu akun dari Bapak atau Ibu Guru untuk masuk ke ruang belajarmu dengan mudah.
      </p>
    </div>

    <div class="auth-preview-box">
      <div class="auth-preview-box__label">Langkah Masuk</div>
      <ul class="auth-preview-list">
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--blue" aria-hidden="true"></span>
            <span>Ketik nama pengguna kamu</span>
          </span>
          <span class="auth-preview-num">01</span>
        </li>
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--green" aria-hidden="true"></span>
            <span>Tekan 4–6 angka PIN rahasia</span>
          </span>
          <span class="auth-preview-num">02</span>
        </li>
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--amber" aria-hidden="true"></span>
            <span>Tekan tombol Masuk warna biru</span>
          </span>
          <span class="auth-preview-num">03</span>
        </li>
      </ul>
    </div>

    <div class="auth-aside__meta">
      <span>Dapat memakai keyboard angka</span>
      <span>SLB B-C Wijayakusumah</span>
    </div>
  </aside>

  {{-- Panel Kanan: Formulir Masuk Siswa --}}
  <section class="auth-panel" aria-labelledby="student-login-heading">
    <nav class="auth-tabs" aria-label="Navigasi Halaman Masuk">
      <a href="{{ route('login') }}" class="auth-tab">Pilih Peran</a>
      <a href="{{ route('login.student') }}" class="auth-tab is-active is-active--blue" aria-current="page">Masuk Siswa</a>
      <a href="{{ route('login.teacher') }}" class="auth-tab">Masuk Guru</a>
    </nav>

    <div class="auth-head">
      <h1 id="student-login-heading" class="auth-head__title">Masuk Siswa</h1>
      <p class="auth-head__desc">Masukkan nama pengguna dan 4–6 angka PIN kamu.</p>
    </div>

    @if ($errors->has('auth'))
      <div class="auth-alert" role="alert">
        <span class="auth-alert__dot" aria-hidden="true">!</span>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    <form id="studentLoginForm" action="{{ route('login.student.submit') }}" method="POST" class="auth-form">
      @csrf

      <div class="auth-field">
        <label for="username" class="auth-label">Nama Pengguna Kamu</label>
        <input
          type="text"
          id="username"
          name="username"
          value="{{ old('username') }}"
          required
          autofocus
          autocomplete="username"
          placeholder="Contoh: siswa_a"
          class="auth-input auth-input--student @error('username') is-invalid @enderror"
          @error('username') aria-describedby="username-error" @enderror
        >
        @error('username')
          <span id="username-error" class="auth-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="auth-field">
        <div class="auth-label-row">
          <span class="auth-label">PIN Rahasia Kamu</span>
          <span id="pinCountLabel" class="auth-hint-tag">0 / 6 angka</span>
        </div>
        <input type="hidden" id="pinInput" name="pin" value="">

        <div id="pinIndicator" class="pin-tray" aria-label="Indikator jumlah angka PIN yang dimasukkan">
          <span class="pin-slot"></span>
          <span class="pin-slot"></span>
          <span class="pin-slot"></span>
          <span class="pin-slot"></span>
          <span class="pin-slot pin-slot--optional"></span>
          <span class="pin-slot pin-slot--optional"></span>
        </div>
        @error('pin')
          <span class="auth-error">{{ $message }}</span>
        @enderror

        <div class="keypad" role="group" aria-label="Papan angka PIN">
          <button type="button" class="keypad-btn" data-key="1">1</button>
          <button type="button" class="keypad-btn" data-key="2">2</button>
          <button type="button" class="keypad-btn" data-key="3">3</button>
          <button type="button" class="keypad-btn" data-key="4">4</button>
          <button type="button" class="keypad-btn" data-key="5">5</button>
          <button type="button" class="keypad-btn" data-key="6">6</button>
          <button type="button" class="keypad-btn" data-key="7">7</button>
          <button type="button" class="keypad-btn" data-key="8">8</button>
          <button type="button" class="keypad-btn" data-key="9">9</button>
          <button type="button" class="keypad-btn keypad-btn--clear" id="btnClear" aria-label="Hapus satu angka PIN">Hapus</button>
          <button type="button" class="keypad-btn" data-key="0">0</button>
          <button type="submit" class="keypad-btn keypad-btn--submit">Masuk</button>
        </div>
      </div>
    </form>
  </section>

</div>
@endsection

@push('scripts')
<script>
  let pin = '';
  const maxPin = 6;
  const pinInput = document.getElementById('pinInput');
  const pinIndicator = document.getElementById('pinIndicator');
  const pinCountLabel = document.getElementById('pinCountLabel');

  function updateIndicator() {
    pinInput.value = pin;
    pinCountLabel.textContent = pin.length + ' / ' + maxPin + ' angka';

    const slots = pinIndicator.querySelectorAll('.pin-slot');
    slots.forEach((slot, index) => {
      slot.classList.toggle('is-filled', index < pin.length);
    });
  }

  document.querySelectorAll('.keypad-btn[data-key]').forEach((btn) => {
    btn.addEventListener('click', () => {
      if (pin.length < maxPin) {
        pin += btn.dataset.key;
        updateIndicator();
      }
    });
  });

  document.getElementById('btnClear').addEventListener('click', () => {
    pin = pin.slice(0, -1);
    updateIndicator();
  });

  window.addEventListener('keydown', (e) => {
    if (document.activeElement && document.activeElement.id === 'username') {
      return;
    }
    if (e.key >= '0' && e.key <= '9') {
      if (pin.length < maxPin) {
        pin += e.key;
        updateIndicator();
      }
    } else if (e.key === 'Backspace') {
      e.preventDefault();
      pin = pin.slice(0, -1);
      updateIndicator();
    } else if (e.key === 'Enter' && document.activeElement?.tagName !== 'BUTTON') {
      e.preventDefault();
      document.getElementById('studentLoginForm').requestSubmit();
    }
  });
</script>
@endpush
