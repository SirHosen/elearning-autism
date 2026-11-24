@extends('layouts.guest')

@section('title', 'Masuk Siswa | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div style="width: 100%; max-width: 440px; background: var(--surface); border: var(--border) solid var(--line); border-radius: var(--radius); padding: 32px 24px; text-align: center;">
  <div style="font-size: 48px; margin-bottom: 8px;">🎒</div>
  <h1 style="font-size: var(--fs-title); margin-bottom: 6px;">Masuk Siswa</h1>
  <p style="color: var(--ink-soft); font-size: var(--fs-base); margin-bottom: 24px;">Ayo mulai belajar hari ini!</p>

  @if ($errors->has('auth'))
    <div class="feedback-bar feedback-hint" role="alert" style="margin-bottom: 20px;">
      <span>💡</span> <span>{{ $errors->first('auth') }}</span>
    </div>
  @endif

  <form id="studentLoginForm" action="{{ route('login.student.submit') }}" method="POST">
    @csrf
    <div style="margin-bottom: 20px; text-align: left;">
      <label for="username" style="display: block; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: var(--ink);">
        Nama Pengguna Kamu
      </label>
      <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
             placeholder="Ketik namamu di sini..."
             style="width: 100%; min-height: 56px; font-size: 20px; padding: 0 16px; border: var(--border) solid var(--line); border-radius: var(--radius-sm); outline: none;">
      @error('username')
        <small style="color: var(--danger); font-size: 14px; font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>
      @enderror
    </div>

    <div style="margin-bottom: 24px;">
      <span style="display: block; font-weight: 700; font-size: 16px; margin-bottom: 8px; color: var(--ink); text-align: left;">
        PIN Rahasia Kamu (4-6 angka)
      </span>
      <input type="hidden" id="pinInput" name="pin" value="">

      <!-- Indikator Titik PIN -->
      <div id="pinIndicator" style="display: flex; justify-content: center; gap: 12px; margin-bottom: 16px; min-height: 36px; align-items: center;" aria-label="Jumlah angka PIN">
        <span class="pin-dot" style="font-size: 28px; color: var(--line);">○</span>
        <span class="pin-dot" style="font-size: 28px; color: var(--line);">○</span>
        <span class="pin-dot" style="font-size: 28px; color: var(--line);">○</span>
        <span class="pin-dot" style="font-size: 28px; color: var(--line);">○</span>
      </div>
      @error('pin')
        <small style="color: var(--danger); font-size: 14px; font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>
      @enderror

      <!-- Numpad Layar Digital -->
      <div class="numpad">
        <button type="button" class="numpad-btn" data-key="1">1</button>
        <button type="button" class="numpad-btn" data-key="2">2</button>
        <button type="button" class="numpad-btn" data-key="3">3</button>
        <button type="button" class="numpad-btn" data-key="4">4</button>
        <button type="button" class="numpad-btn" data-key="5">5</button>
        <button type="button" class="numpad-btn" data-key="6">6</button>
        <button type="button" class="numpad-btn" data-key="7">7</button>
        <button type="button" class="numpad-btn" data-key="8">8</button>
        <button type="button" class="numpad-btn" data-key="9">9</button>
        <button type="button" class="numpad-btn" id="btnClear" style="font-size: 18px;">Hapus</button>
        <button type="button" class="numpad-btn" data-key="0">0</button>
        <button type="submit" class="numpad-btn" style="font-size: 18px; color: var(--primary); font-weight: 900;">Masuk</button>
      </div>
    </div>
  </form>

  <div style="border-top: 1px solid var(--line); padding-top: 16px; margin-top: 16px;">
    <a href="{{ route('login.teacher') }}" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 15px;">
      Saya Guru / Pengelola →
    </a>
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
        dots += '<span style="font-size: 28px; color: var(--primary);">●</span>';
      } else {
        dots += '<span style="font-size: 28px; color: var(--line);">○</span>';
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
