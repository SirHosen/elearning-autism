@extends('layouts.student')

@section('title', 'Pengaturan Tampilan & Suara | Sahabat Belajar')
@section('page_title', 'Pengaturan Kenyamanan')

@section('content')
<div class="st-page-shell st-page-shell--narrow">
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali ke Beranda</span>
    </a>

    <span class="st-topic-pill st-topic-pill--blue">
      Simpan Otomatis
    </span>
  </div>

  <div class="st-settings-card st-anim st-anim--2">
    <div class="st-settings-intro">
      <div class="st-settings-intro__badge" aria-hidden="true">
        <svg viewBox="0 0 48 48" width="44" height="44" fill="none">
          <rect x="4" y="4" width="40" height="40" rx="14" fill="#DCEBF7"/>
          <path d="M24 14v3m0 14v3M14 24h3m14 0h3m-2.9-7.1l-2.1 2.1m-10 10l-2.1 2.1m14.2 0l-2.1-2.1m-10-10l-2.1-2.1" stroke="#2F6F9F" stroke-width="2.5" stroke-linecap="round"/>
          <circle cx="24" cy="24" r="5" fill="#FFFFFF" stroke="#1A4B70" stroke-width="2.5"/>
        </svg>
      </div>
      <div>
        <h2 class="st-settings-intro__title">Pengaturan Kenyamanan Belajar</h2>
        <p class="st-settings-intro__desc">
          Atur suara, warna layar, ukuran huruf, dan animasi sesuai kenyamananmu.
        </p>
      </div>
    </div>

    <form id="settingForm" action="{{ route('student.settings.update') }}" method="POST">
      @csrf
      @method('PUT')

      <!-- 1. PENGATURAN SUARA / VOLUME -->
      <section class="st-setting-block" aria-labelledby="settingSoundTitle">
        <div class="st-setting-bar">
          <div class="st-setting-head st-setting-head--tight">
            <span class="st-setting-icon st-setting-icon--blue" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="currentColor" fill-opacity="0.16"/>
                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                <path d="M18.8 5.2a9.5 9.5 0 0 1 0 13.6"/>
              </svg>
            </span>
            <label for="volumeRange" id="settingSoundTitle" class="st-setting-label">
              Kekerasan Suara
              <span id="volumeVal" class="st-vol-badge">{{ $setting->volume }}%</span>
            </label>
          </div>
          <button type="button" class="btn btn-secondary st-btn-test-sound" id="btnTestSound">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
              <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
            </svg>
            <span id="btnTestSoundLabel">Uji Suara</span>
          </button>
        </div>

        <div class="st-slider-wrap">
          <input type="range" id="volumeRange" name="volume" min="0" max="100" step="10" value="{{ $setting->volume }}" class="st-volume-slider">
        </div>
        <small class="st-setting-hint">
          Geser ke kiri untuk mengecilkan atau mematikan suara, geser ke kanan untuk mengeraskan.
        </small>
      </section>

      <!-- 2. PILIHAN WARNA / TEMA -->
      <section class="st-setting-block" aria-labelledby="settingThemeTitle">
        <div class="st-setting-head">
          <span class="st-setting-icon st-setting-icon--yellow" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="5" fill="currentColor" fill-opacity="0.18"/>
              <line x1="12" y1="2" x2="12" y2="4"/>
              <line x1="12" y1="20" x2="12" y2="22"/>
              <line x1="4.93" y1="4.93" x2="6.34" y2="6.34"/>
              <line x1="17.66" y1="17.66" x2="19.07" y2="19.07"/>
              <line x1="2" y1="12" x2="4" y2="12"/>
              <line x1="20" y1="12" x2="22" y2="12"/>
              <line x1="4.93" y1="19.07" x2="6.34" y2="17.66"/>
              <line x1="17.66" y1="6.34" x2="19.07" y2="4.93"/>
            </svg>
          </span>
          <label id="settingThemeTitle" class="st-setting-label">Warna Tampilan Layar</label>
        </div>

        <div class="st-setting-grid-2">
          <label class="st-choice-tile st-choice-tile--theme theme-option {{ $setting->theme === 'calm' ? 'is-checked' : '' }}">
            <input type="radio" name="theme" value="calm" {{ $setting->theme === 'calm' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-swatch-preview st-swatch-preview--calm" aria-hidden="true">
              <span class="st-swatch-mini-bar st-swatch-mini-bar--calm"></span>
            </span>
            <div class="st-choice-body">
              <strong class="st-choice-title">Tema Tenang</strong>
              <small class="st-choice-sub">Latar krem lembut</small>
            </div>
            <span class="st-choice-check" aria-hidden="true">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
          </label>

          <label class="st-choice-tile st-choice-tile--theme st-choice-tile--dark theme-option {{ $setting->theme === 'contrast' ? 'is-checked' : '' }}">
            <input type="radio" name="theme" value="contrast" {{ $setting->theme === 'contrast' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-swatch-preview st-swatch-preview--contrast" aria-hidden="true">
              <span class="st-swatch-mini-bar st-swatch-mini-bar--contrast"></span>
            </span>
            <div class="st-choice-body">
              <strong class="st-choice-title">Kontras Tinggi</strong>
              <small class="st-choice-sub">Gelap &amp; kuning terang</small>
            </div>
            <span class="st-choice-check" aria-hidden="true">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
          </label>
        </div>
      </section>

      <!-- 3. UKURAN HURUF -->
      <section class="st-setting-block" aria-labelledby="settingFontTitle">
        <div class="st-setting-head">
          <span class="st-setting-icon st-setting-icon--green" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 20L9 6l5 14"/>
              <path d="M5.8 15h6.4"/>
              <path d="M16 20v-9"/>
              <path d="M13.5 11h5"/>
            </svg>
          </span>
          <label id="settingFontTitle" class="st-setting-label">Ukuran Huruf (Teks)</label>
        </div>

        <div class="st-setting-grid-3">
          <label class="st-choice-tile st-choice-tile--centered scale-option {{ $setting->font_scale === 'normal' ? 'is-checked' : '' }}">
            <input type="radio" name="font_scale" value="normal" {{ $setting->font_scale === 'normal' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-choice-check st-choice-check--corner" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span class="st-choice-glyph st-choice-glyph--normal" aria-hidden="true">Aa</span>
            <div class="st-scale-label--normal">Normal</div>
            <small class="st-choice-meta">100%</small>
          </label>
          <label class="st-choice-tile st-choice-tile--centered scale-option {{ $setting->font_scale === 'large' ? 'is-checked' : '' }}">
            <input type="radio" name="font_scale" value="large" {{ $setting->font_scale === 'large' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-choice-check st-choice-check--corner" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span class="st-choice-glyph st-choice-glyph--large" aria-hidden="true">Aa</span>
            <div class="st-scale-label--large">Besar</div>
            <small class="st-choice-meta">115%</small>
          </label>
          <label class="st-choice-tile st-choice-tile--centered scale-option {{ $setting->font_scale === 'xlarge' ? 'is-checked' : '' }}">
            <input type="radio" name="font_scale" value="xlarge" {{ $setting->font_scale === 'xlarge' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-choice-check st-choice-check--corner" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span class="st-choice-glyph st-choice-glyph--xlarge" aria-hidden="true">Aa</span>
            <div class="st-scale-label--xlarge">Sangat Besar</div>
            <small class="st-choice-meta">130%</small>
          </label>
        </div>
      </section>

      <!-- 4. GERAK & ANIMASI -->
      <section class="st-setting-block st-setting-block--last" aria-labelledby="settingMotionTitle">
        <div class="st-setting-head">
          <span class="st-setting-icon st-setting-icon--lavender" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 3l1.9 4.6L18.5 9.5l-4.6 1.9L12 16l-1.9-4.6L5.5 9.5l4.6-1.9L12 3z" fill="currentColor" fill-opacity="0.16"/>
              <path d="M5 18.5c2.2-1 4.4-1 6.6 0s4.4 1 7.4 0"/>
            </svg>
          </span>
          <label id="settingMotionTitle" class="st-setting-label">Animasi Layar</label>
        </div>

        <div class="st-setting-grid-3">
          <label class="st-choice-tile st-choice-tile--centered motion-option {{ $setting->animation_level === 'off' ? 'is-checked' : '' }}">
            <input type="radio" name="animation_level" value="off" {{ $setting->animation_level === 'off' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-choice-check st-choice-check--corner" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span class="st-motion-badge" aria-hidden="true">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="12" r="4" fill="currentColor" fill-opacity="0.2"/></svg>
            </span>
            <div class="st-motion-label">Tanpa Gerak</div>
            <small class="st-choice-meta">Tenang (0ms)</small>
          </label>
          <label class="st-choice-tile st-choice-tile--centered motion-option {{ $setting->animation_level === 'minimal' ? 'is-checked' : '' }}">
            <input type="radio" name="animation_level" value="minimal" {{ $setting->animation_level === 'minimal' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-choice-check st-choice-check--corner" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span class="st-motion-badge" aria-hidden="true">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M5 14c3-4 6-4 9 0"/><circle cx="17" cy="12" r="2.2" fill="currentColor"/></svg>
            </span>
            <div class="st-motion-label">Minimal</div>
            <small class="st-choice-meta">Halus (150ms)</small>
          </label>
          <label class="st-choice-tile st-choice-tile--centered motion-option {{ $setting->animation_level === 'normal' ? 'is-checked' : '' }}">
            <input type="radio" name="animation_level" value="normal" {{ $setting->animation_level === 'normal' ? 'checked' : '' }} class="st-choice-input">
            <span class="st-choice-check st-choice-check--corner" aria-hidden="true">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <span class="st-motion-badge" aria-hidden="true">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M3 14c2.5-5 5-5 7.5 0s5-5 7.5 0"/><circle cx="20" cy="12" r="2" fill="currentColor"/></svg>
            </span>
            <div class="st-motion-label">Normal</div>
            <small class="st-choice-meta">Standar (220ms)</small>
          </label>
        </div>
      </section>

      <!-- Status & Tombol Kembali (Posisi Stabil / Tidak Berpindah) -->
      <div class="st-settings-footer">
        <div class="st-save-status-slot" aria-live="polite">
          <span id="saveStatus" class="st-save-status"></span>
        </div>
        <a href="{{ route('student.home') }}" class="btn btn-student btn-primary st-pill-cta">
          @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-btn-arrow st-btn-arrow--left'])
          <span>Kembali ke Beranda</span>
        </a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script type="module">
  const audioManager = window.audioManager;

  const form = document.getElementById('settingForm');
  const volumeRange = document.getElementById('volumeRange');
  const volumeVal = document.getElementById('volumeVal');
  const btnTestSound = document.getElementById('btnTestSound');
  const btnTestSoundLabel = document.getElementById('btnTestSoundLabel');
  const saveStatus = document.getElementById('saveStatus');
  let saveSeq = 0;
  let saveStatusTimer = null;

  function syncCheckedTiles(groupName) {
    form.querySelectorAll(`input[name="${groupName}"]`).forEach((radio) => {
      const tile = radio.closest('.st-choice-tile');
      if (tile) tile.classList.toggle('is-checked', radio.checked);
    });
  }

  volumeRange.addEventListener('input', (e) => {
    const vol = parseInt(e.target.value, 10);
    volumeVal.textContent = vol + '%';
    if (audioManager) audioManager.setVolume(vol);
  });

  btnTestSound.addEventListener('click', () => {
    if (!audioManager) return;
    const currentVol = parseInt(volumeRange.value, 10);
    audioManager.setVolume(currentVol);
    audioManager.play({
      text: 'Halo! Suara sudah diuji pada volume ' + currentVol + ' persen.',
      onStart: (status) => {
        btnTestSound.classList.add('is-playing');
        if (btnTestSoundLabel) btnTestSoundLabel.textContent = status.muted ? 'Suara dimatikan' : 'Sedang bersuara...';
      },
      onEnd: () => {
        btnTestSound.classList.remove('is-playing');
        if (btnTestSoundLabel) btnTestSoundLabel.textContent = 'Uji Suara';
      }
    });
  });

  function setStatus(text, state) {
    saveStatus.textContent = text;
    saveStatus.className = 'st-save-status' + (text ? ' is-visible' : '') + (state ? ' st-save-status--' + state : '');
  }

  form.querySelectorAll('input').forEach(input => {
    input.addEventListener('change', async () => {
      if (input.name) syncCheckedTiles(input.name);
      const seq = ++saveSeq;
      clearTimeout(saveStatusTimer);
      setStatus('Menyimpan perubahan...', 'pending');
      const formData = new FormData(form);

      const theme = formData.get('theme');
      const fontScale = formData.get('font_scale');
      const motion = formData.get('animation_level');

      if (theme) document.documentElement.setAttribute('data-theme', theme);
      if (fontScale) document.documentElement.setAttribute('data-font-scale', fontScale);
      if (motion) document.documentElement.setAttribute('data-motion', motion);

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          body: formData
        });

        if (seq !== saveSeq) return;

        if (res.ok) {
          setStatus('Pengaturan berhasil disimpan otomatis.', 'success');
          saveStatusTimer = setTimeout(() => {
            if (seq === saveSeq) setStatus('');
          }, 3000);
        } else {
          setStatus('Gagal menyimpan pengaturan.', 'error');
        }
      } catch (err) {
        if (seq !== saveSeq) return;
        setStatus('Koneksi terganggu.', 'error');
      }
    });
  });
</script>
@endpush
