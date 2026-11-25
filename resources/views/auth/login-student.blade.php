@extends('layouts.guest')

@section('title', 'Masuk Siswa | Sahabat Belajar')

@section('content')
<div class="student-wrap">
  <div class="login-nav-row">
    <a href="{{ route('login') }}" class="login-nav-pill">
      <span aria-hidden="true">&larr;</span>
      <span>Kembali</span>
    </a>
  </div>

  <div class="student-card">
    <form id="studentLoginForm" action="{{ route('login.student.submit') }}" method="POST" class="student-grid">
      @csrf

      {{-- Kolom Kiri: Ilustrasi Murid, Judul, Suara Petunjuk, & Nama Pengguna --}}
      <div class="sl-left">
        <div class="role-card--student">
          <div class="role-card__cover">
            <img src="/images/murid-cover.jpg" alt="Ilustrasi murid membawa buku" class="role-card__illus">
            <span class="role-card__tag">Murid</span>
          </div>
        </div>

        <div class="sl-left__content">
          <div class="sl-head-row">
            <div>
              <h1 class="sl-title">Masuk Siswa</h1>
              <p class="sl-desc">Tulis nama pengguna dan tekan PIN.</p>
            </div>
            <button type="button" id="btnVoiceGuide" class="sl-voice-btn" aria-pressed="false" title="Dengarkan panduan cara masuk">
              <span class="sl-voice-dot" aria-hidden="true"></span>
              <span id="voiceLabel">Dengar</span>
            </button>
          </div>

          @if ($errors->has('auth'))
            <div class="login-alert" role="alert" style="margin-bottom: 0;">
              <span class="login-alert__dot" aria-hidden="true">!</span>
              <span>{{ $errors->first('auth') }}</span>
            </div>
          @endif

          <div class="login-field" style="margin-top: auto;">
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
        </div>
      </div>

      {{-- Kolom Kanan: Kotak PIN Visual Interaktif & Keypad Besar --}}
      <div class="sl-right">
        <div>
          <div class="sl-pin-bar" style="margin-bottom: 10px;">
            <span class="login-label">PIN Kamu</span>
            <div class="sl-pin-actions">
              <button type="button" id="btnShowPin" class="sl-pin-toggle" aria-pressed="false">Lihat</button>
              <span id="pinCountLabel" class="login-count-tag">0 / 6</span>
            </div>
          </div>

          <input type="hidden" id="pinInput" name="pin" value="">

          <div id="pinIndicator" class="pin-slots" aria-label="Kotak indikator angka PIN">
            <span class="pin-box"></span>
            <span class="pin-box"></span>
            <span class="pin-box"></span>
            <span class="pin-box"></span>
            <span class="pin-box"></span>
            <span class="pin-box"></span>
          </div>
          @error('pin')
            <span class="login-error" style="display: block; margin-top: 6px;">{{ $message }}</span>
          @enderror
        </div>

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
          <button type="submit" class="keypad-btn keypad-btn--submit" id="btnSubmitStudent">Masuk</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let pin = '';
  let showDigits = false;
  let peekTimeout = null;
  let audioCtx = null;

  const maxPin = 6;
  const pinInput = document.getElementById('pinInput');
  const pinIndicator = document.getElementById('pinIndicator');
  const pinCountLabel = document.getElementById('pinCountLabel');
  const btnShowPin = document.getElementById('btnShowPin');
  const btnVoice = document.getElementById('btnVoiceGuide');
  const voiceLabel = document.getElementById('voiceLabel');

  function playSoftTap(freq) {
    try {
      const Ctx = window.AudioContext || window.webkitAudioContext;
      if (!Ctx) return;
      if (!audioCtx) audioCtx = new Ctx();
      if (audioCtx.state === 'suspended') audioCtx.resume();
      const osc = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      osc.type = 'sine';
      osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
      gain.gain.setValueAtTime(0.06, audioCtx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.08);
      osc.connect(gain);
      gain.connect(audioCtx.destination);
      osc.start();
      osc.stop(audioCtx.currentTime + 0.09);
    } catch (e) {}
  }

  function updateIndicator(justTypedIndex = -1) {
    pinInput.value = pin;
    pinCountLabel.textContent = pin.length + ' / ' + maxPin;

    const boxes = pinIndicator.querySelectorAll('.pin-box');
    boxes.forEach((box, index) => {
      const filled = index < pin.length;
      box.classList.toggle('is-filled', filled);
      box.classList.toggle('is-just-entered', filled && index === justTypedIndex && !showDigits);

      if (!filled) {
        box.textContent = '';
      } else if (showDigits || index === justTypedIndex) {
        box.textContent = pin[index];
      } else {
        box.textContent = '•';
      }
    });
  }

  function addDigit(digit, triggerBtn = null) {
    if (pin.length >= maxPin) return;
    pin += digit;
    const idx = pin.length - 1;
    updateIndicator(idx);
    playSoftTap(440 + idx * 40);

    if (triggerBtn) {
      triggerBtn.classList.add('is-pressed');
      setTimeout(() => triggerBtn.classList.remove('is-pressed'), 120);
    }

    if (!showDigits) {
      clearTimeout(peekTimeout);
      peekTimeout = setTimeout(() => updateIndicator(-1), 650);
    }
  }

  function removeDigit() {
    if (!pin.length) return;
    pin = pin.slice(0, -1);
    updateIndicator(-1);
    playSoftTap(320);
  }

  document.querySelectorAll('.keypad-btn[data-key]').forEach((btn) => {
    btn.addEventListener('click', () => addDigit(btn.dataset.key, btn));
  });

  document.getElementById('btnClear').addEventListener('click', removeDigit);

  btnShowPin.addEventListener('click', () => {
    showDigits = !showDigits;
    btnShowPin.classList.toggle('is-active', showDigits);
    btnShowPin.setAttribute('aria-pressed', showDigits ? 'true' : 'false');
    btnShowPin.textContent = showDigits ? 'Sembunyi' : 'Lihat';
    updateIndicator(-1);
  });

  if (btnVoice) {
    btnVoice.addEventListener('click', () => {
      if (!('speechSynthesis' in window)) return;
      if (btnVoice.classList.contains('is-speaking')) {
        window.speechSynthesis.cancel();
        btnVoice.classList.remove('is-speaking');
        btnVoice.setAttribute('aria-pressed', 'false');
        voiceLabel.textContent = 'Dengar';
        return;
      }

      window.speechSynthesis.cancel();
      const msg = new SpeechSynthesisUtterance('Halo! Tulis nama pengguna kamu, lalu tekan angka PIN ya.');
      msg.lang = 'id-ID';
      msg.rate = 0.85;
      msg.onstart = () => {
        btnVoice.classList.add('is-speaking');
        btnVoice.setAttribute('aria-pressed', 'true');
        voiceLabel.textContent = 'Stop';
      };
      msg.onend = msg.onerror = () => {
        btnVoice.classList.remove('is-speaking');
        btnVoice.setAttribute('aria-pressed', 'false');
        voiceLabel.textContent = 'Dengar';
      };
      window.speechSynthesis.speak(msg);
    });
  }

  window.addEventListener('keydown', (e) => {
    if (document.activeElement && document.activeElement.id === 'username') {
      return;
    }
    if (e.key >= '0' && e.key <= '9') {
      const btn = document.querySelector(`.keypad-btn[data-key="${e.key}"]`);
      addDigit(e.key, btn);
    } else if (e.key === 'Backspace') {
      e.preventDefault();
      removeDigit();
    } else if (e.key === 'Enter' && document.activeElement?.tagName !== 'BUTTON') {
      e.preventDefault();
      document.getElementById('studentLoginForm').requestSubmit();
    }
  });
</script>
@endpush
