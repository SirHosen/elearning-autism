@extends('layouts.guest')

@section('title', 'Masuk Siswa | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div class="ls-container">

  {{-- Back --}}
  <a href="{{ route('login') }}" class="ls-back">
    <span class="ls-back__arrow">&larr;</span>
    <span>Pilih Peran Lain</span>
  </a>

  <div class="ls-card">
    {{-- Accent --}}
    <div class="ls-accent ls-accent--blue"></div>

    {{-- Header --}}
    <div class="ls-header">
      <div class="ls-monogram ls-monogram--blue">S</div>
      <h1 class="ls-title">Masuk Siswa</h1>
      <p class="ls-desc">Masukkan nama pengguna dan 4 digit PIN kamu</p>
    </div>

    {{-- Error --}}
    @if ($errors->has('auth'))
      <div class="ls-alert" role="alert">
        <span class="ls-alert__badge">!</span>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    <form id="studentLoginForm" action="{{ route('login.student.submit') }}" method="POST">
      @csrf

      {{-- Username --}}
      <div class="ls-field">
        <label for="username" class="ls-label">Nama Pengguna Kamu</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
               placeholder="Contoh: siswa_a"
               class="form-control ls-input">
        @error('username')
          <small class="ls-error">{{ $message }}</small>
        @enderror
      </div>

      {{-- PIN --}}
      <div class="ls-field">
        <span class="ls-label">PIN Rahasia Kamu (4-6 angka)</span>
        <input type="hidden" id="pinInput" name="pin" value="">

        <div id="pinIndicator" class="ls-pin-dots" aria-label="Jumlah angka PIN">
          <span class="ls-dot"></span>
          <span class="ls-dot"></span>
          <span class="ls-dot"></span>
          <span class="ls-dot"></span>
        </div>
        @error('pin')
          <small class="ls-error">{{ $message }}</small>
        @enderror

        {{-- Numpad --}}
        <div class="ls-numpad">
          <button type="button" class="numpad-btn" data-key="1">1</button>
          <button type="button" class="numpad-btn" data-key="2">2</button>
          <button type="button" class="numpad-btn" data-key="3">3</button>
          <button type="button" class="numpad-btn" data-key="4">4</button>
          <button type="button" class="numpad-btn" data-key="5">5</button>
          <button type="button" class="numpad-btn" data-key="6">6</button>
          <button type="button" class="numpad-btn" data-key="7">7</button>
          <button type="button" class="numpad-btn" data-key="8">8</button>
          <button type="button" class="numpad-btn" data-key="9">9</button>
          <button type="button" class="numpad-btn ls-numpad__clear" id="btnClear">Hapus</button>
          <button type="button" class="numpad-btn" data-key="0">0</button>
          <button type="submit" class="numpad-btn ls-numpad__submit">Masuk</button>
        </div>
      </div>
    </form>

    {{-- Switch role --}}
    <div class="ls-switch">
      <a href="{{ route('login.teacher') }}" class="ls-switch__link">
        Saya Guru / Pendamping &rarr;
      </a>
    </div>
  </div>
</div>

<style>
/* ══════════════ LOGIN STUDENT ══════════════ */
.ls-container { width: 100%; max-width: 460px; margin: 0 auto; }

/* Back link */
.ls-back {
  display: inline-flex; align-items: center; gap: 8px;
  color: var(--text-muted); text-decoration: none;
  font-size: 0.875rem; font-weight: 600;
  margin-bottom: 16px;
  transition: color 0.15s;
}
.ls-back:hover { color: var(--text); }
.ls-back__arrow { font-size: 1.125rem; }

/* Card */
.ls-card {
  position: relative;
  background: var(--surface);
  border: 1.5px solid var(--line);
  border-radius: 16px;
  padding: 36px 28px;
  overflow: hidden;
}

/* Accent bar */
.ls-accent {
  position: absolute; top: 0; left: 0; right: 0; height: 4px;
}
.ls-accent--blue { background: var(--primary); }
.ls-accent--green { background: var(--success); }

/* Header */
.ls-header { text-align: center; margin-bottom: 28px; }

.ls-monogram {
  width: 56px; height: 56px;
  border-radius: 14px;
  display: grid; place-items: center;
  margin: 0 auto 14px;
  font-size: 1.5rem; font-weight: 800;
  line-height: 1;
}
.ls-monogram--blue {
  background: var(--tint-blue);
  color: var(--primary);
  border: 1.5px solid rgba(47,111,159,0.18);
}
.ls-monogram--green {
  background: var(--success-bg);
  color: var(--success);
  border: 1.5px solid rgba(36,107,77,0.18);
}

.ls-title {
  font-size: 1.5rem; font-weight: 800;
  color: var(--text); margin: 0 0 6px;
  letter-spacing: -0.3px;
}
.ls-desc {
  color: var(--text-muted);
  font-size: 0.9375rem; margin: 0;
}

/* Alert */
.ls-alert {
  display: flex; align-items: flex-start; gap: 10px;
  padding: 12px 16px; border-radius: 10px;
  background: var(--hint-bg); border: 1px solid var(--hint-line);
  color: #92400E; font-size: 0.875rem; font-weight: 600;
  margin-bottom: 24px;
}
.ls-alert__badge {
  flex-shrink: 0;
  width: 22px; height: 22px;
  border-radius: 50%;
  background: #F59E0B; color: #fff;
  font-size: 0.75rem; font-weight: 800;
  display: grid; place-items: center;
  margin-top: 1px;
}

/* Fields */
.ls-field { margin-bottom: 20px; }
.ls-label {
  display: block; font-weight: 700;
  font-size: 0.875rem; margin-bottom: 8px;
  color: var(--text);
}
.ls-input {
  width: 100%; min-height: 52px;
  font-size: 1.125rem; padding: 0 16px;
  border-radius: 10px;
}
.ls-error {
  color: var(--danger); font-size: 0.8125rem;
  font-weight: 700; display: block; margin-top: 6px;
}

/* PIN dots */
.ls-pin-dots {
  display: flex; justify-content: center; gap: 14px;
  margin-bottom: 18px; min-height: 42px; align-items: center;
  background: var(--surface-alt); padding: 10px;
  border-radius: 12px; border: 1px solid var(--line);
}
.ls-dot {
  width: 14px; height: 14px; border-radius: 50%;
  border: 2px solid var(--line); background: transparent;
}
.ls-dot.is-filled {
  background: var(--primary); border-color: var(--primary);
}

/* Numpad */
.ls-numpad {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
}
.ls-numpad__clear {
  font-size: 0.9375rem !important; font-weight: 600 !important;
  color: var(--danger) !important;
}
.ls-numpad__submit {
  font-size: 1rem !important; font-weight: 800 !important;
  color: var(--on-primary) !important;
  background: var(--primary) !important;
  border-color: var(--primary) !important;
}

/* Switch link */
.ls-switch {
  border-top: 1px solid var(--line);
  padding-top: 18px; margin-top: 24px;
  text-align: center;
}
.ls-switch__link {
  color: var(--primary); text-decoration: none;
  font-weight: 700; font-size: 0.875rem;
}
.ls-switch__link:hover { text-decoration: underline; }
</style>
@endsection

@push('scripts')
<script>
  let pin = '';
  const maxPin = 6;
  const pinInput = document.getElementById('pinInput');
  const pinIndicator = document.getElementById('pinIndicator');

  function updateIndicator() {
    pinInput.value = pin;
    const totalDots = Math.max(4, pin.length);
    let dots = '';
    for (let i = 0; i < totalDots; i++) {
      dots += '<span class="ls-dot' + (i < pin.length ? ' is-filled' : '') + '"></span>';
    }
    pinIndicator.innerHTML = dots;
  }

  document.querySelectorAll('.numpad-btn[data-key]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (pin.length < maxPin) { pin += btn.dataset.key; updateIndicator(); }
    });
  });

  document.getElementById('btnClear').addEventListener('click', () => {
    pin = pin.slice(0, -1); updateIndicator();
  });

  window.addEventListener('keydown', (e) => {
    if (document.activeElement.id === 'username') return;
    if (e.key >= '0' && e.key <= '9') {
      if (pin.length < maxPin) { pin += e.key; updateIndicator(); }
    } else if (e.key === 'Backspace') {
      pin = pin.slice(0, -1); updateIndicator();
    }
  });
</script>
@endpush
