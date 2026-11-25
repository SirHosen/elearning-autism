@extends('layouts.guest')

@section('title', 'Masuk Siswa | Sahabat Belajar')

@section('content')
<div class="login-wrap">
  <a href="{{ route('login') }}" class="login-back">
    <span aria-hidden="true">&larr;</span>
    <span>Kembali</span>
  </a>

  <div class="login-card">
    <div class="login-card__accent login-card__accent--blue"></div>

    <div class="login-card__head">
      <span class="login-role-pill login-role-pill--blue">Murid</span>
      <h1 class="login-card__title">Masuk Siswa</h1>
      <p class="login-card__desc">Isi nama pengguna dan angka PIN kamu.</p>
    </div>

    @if ($errors->has('auth'))
      <div class="login-alert" role="alert">
        <span class="login-alert__dot" aria-hidden="true">!</span>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    <form id="studentLoginForm" action="{{ route('login.student.submit') }}" method="POST" class="login-form">
      @csrf

      <div class="login-field">
        <label for="username" class="login-label">Nama Pengguna Kamu</label>
        <input
          type="text"
          id="username"
          name="username"
          value="{{ old('username') }}"
          required
          autofocus
          autocomplete="username"
          placeholder="Contoh: siswa_a"
          class="login-input login-input--student @error('username') is-invalid @enderror"
          @error('username') aria-describedby="username-error" @enderror
        >
        @error('username')
          <span id="username-error" class="login-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="login-field">
        <div class="login-label-row">
          <span class="login-label">PIN Kamu</span>
          <span id="pinCountLabel" class="login-count-tag">0 / 6</span>
        </div>
        <input type="hidden" id="pinInput" name="pin" value="">

        <div id="pinIndicator" class="pin-tray" aria-label="Jumlah angka PIN yang dimasukkan">
          <span class="pin-slot"></span>
          <span class="pin-slot"></span>
          <span class="pin-slot"></span>
          <span class="pin-slot"></span>
          <span class="pin-slot pin-slot--optional"></span>
          <span class="pin-slot pin-slot--optional"></span>
        </div>
        @error('pin')
          <span class="login-error">{{ $message }}</span>
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
  </div>

  <p class="login-switch-alt">
    Bukan murid? <a href="{{ route('login.teacher') }}">Masuk sebagai guru</a>
  </p>
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
    pinCountLabel.textContent = pin.length + ' / ' + maxPin;

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
