@extends('layouts.guest')

@section('title', 'Masuk Siswa | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div style="width: 100%; max-width: 460px; margin: 0 auto;">

  <!-- Tombol Kembali ke Pilihan Peran -->
  <div style="margin-bottom: 16px;">
    <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      <span>Pilih Peran Lain</span>
    </a>
  </div>

  <div class="card" style="background: var(--surface); border: 2px solid var(--border); border-radius: 20px; padding: 36px 28px; text-align: center; box-shadow: var(--shadow-sm);">

    <!-- SVG Icon Siswa Modern (No Emoji) -->
    <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(37, 99, 235, 0.08); border: 1px solid rgba(37, 99, 235, 0.16); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #1D4ED8;">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
        <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
      </svg>
    </div>

    <h1 style="font-size: 1.625rem; font-weight: 800; color: var(--text); margin: 0 0 6px 0; letter-spacing: -0.3px;">
      Masuk Siswa
    </h1>
    <p style="color: var(--text-muted); font-size: 0.9375rem; margin: 0 0 24px 0;">
      Masukkan nama pengguna dan 4 digit PIN kamu
    </p>

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

    <form id="studentLoginForm" action="{{ route('login.student.submit') }}" method="POST">
      @csrf

      <div style="margin-bottom: 20px; text-align: left;">
        <label for="username" style="display: block; font-weight: 700; font-size: 0.875rem; margin-bottom: 8px; color: var(--text);">
          Nama Pengguna Kamu
        </label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
               placeholder="Contoh: siswa_a"
               class="form-control"
               style="width: 100%; min-height: 52px; font-size: 1.125rem; padding: 0 16px; border-radius: 10px;">
        @error('username')
          <small style="color: var(--danger); font-size: 0.8125rem; font-weight: 700; display: block; margin-top: 6px;">{{ $message }}</small>
        @enderror
      </div>

      <div style="margin-bottom: 24px;">
        <span style="display: block; font-weight: 700; font-size: 0.875rem; margin-bottom: 10px; color: var(--text); text-align: left;">
          PIN Rahasia Kamu (4-6 angka)
        </span>
        <input type="hidden" id="pinInput" name="pin" value="">

        <!-- Indikator Titik PIN Modern -->
        <div id="pinIndicator" style="display: flex; justify-content: center; gap: 14px; margin-bottom: 18px; min-height: 40px; align-items: center; background: var(--surface-alt); padding: 8px; border-radius: 12px; border: 1px solid var(--border);" aria-label="Jumlah angka PIN">
          <span class="pin-dot" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid var(--border); background: transparent; display: inline-block;"></span>
          <span class="pin-dot" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid var(--border); background: transparent; display: inline-block;"></span>
          <span class="pin-dot" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid var(--border); background: transparent; display: inline-block;"></span>
          <span class="pin-dot" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid var(--border); background: transparent; display: inline-block;"></span>
        </div>
        @error('pin')
          <small style="color: var(--danger); font-size: 0.8125rem; font-weight: 700; display: block; margin-top: 6px;">{{ $message }}</small>
        @enderror

        <!-- Numpad Layar Digital Modern -->
        <div class="numpad" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
          <button type="button" class="numpad-btn" data-key="1">1</button>
          <button type="button" class="numpad-btn" data-key="2">2</button>
          <button type="button" class="numpad-btn" data-key="3">3</button>
          <button type="button" class="numpad-btn" data-key="4">4</button>
          <button type="button" class="numpad-btn" data-key="5">5</button>
          <button type="button" class="numpad-btn" data-key="6">6</button>
          <button type="button" class="numpad-btn" data-key="7">7</button>
          <button type="button" class="numpad-btn" data-key="8">8</button>
          <button type="button" class="numpad-btn" data-key="9">9</button>
          <button type="button" class="numpad-btn" id="btnClear" style="font-size: 0.9375rem; font-weight: 600; color: var(--danger);">Hapus</button>
          <button type="button" class="numpad-btn" data-key="0">0</button>
          <button type="submit" class="numpad-btn" style="font-size: 1rem; color: #FFFFFF; background: var(--primary, #1D4ED8); font-weight: 800; border-color: var(--primary);">Masuk</button>
        </div>
      </div>
    </form>

    <div style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 20px;">
      <a href="{{ route('login.teacher') }}" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 6px;">
        <span>Saya Guru / Pendamping</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let pin = '';
  const maxPin = 6;
  const pinInput = document.getElementById('pinInput');
  const pinIndicator = document.getElementById('pinIndicator');

  function updateIndicator() {
    pinInput.value = pin;
    let dots = '';
    const totalDots = Math.max(4, pin.length);
    for (let i = 0; i < totalDots; i++) {
      if (i < pin.length) {
        dots += '<span class="pin-dot" style="width: 14px; height: 14px; border-radius: 50%; background: #1D4ED8; border: 2px solid #1D4ED8; display: inline-block;"></span>';
      } else {
        dots += '<span class="pin-dot" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid var(--border); background: transparent; display: inline-block;"></span>';
      }
    }
    pinIndicator.innerHTML = dots;
  }

  document.querySelectorAll('.numpad-btn[data-key]').forEach(btn => {
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
    if (document.activeElement.id === 'username') return;
    if (e.key >= '0' && e.key <= '9') {
      if (pin.length < maxPin) {
        pin += e.key;
        updateIndicator();
      }
    } else if (e.key === 'Backspace') {
      pin = pin.slice(0, -1);
      updateIndicator();
    }
  });
</script>
@endpush
