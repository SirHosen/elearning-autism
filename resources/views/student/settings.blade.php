@extends('layouts.student')

@section('title', 'Pengaturan Tampilan & Suara | Sahabat Belajar')
@section('page_title', 'Pengaturan Kenyamanan')

@section('content')
<div class="st-page-shell st-page-shell--narrow">
  <div class="st-page-bar">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      <span>Kembali</span>
    </a>

    <span class="st-topic-pill st-topic-pill--blue">
      Simpan Otomatis
    </span>
  </div>

  <div class="st-settings-card">
    <div style="margin-bottom: 28px;">
      <h2 style="font-size: calc(1.5rem * var(--font-scale)); font-weight: 700; margin: 0 0 6px; color: var(--ink);">
        Pengaturan Kenyamanan Belajar
      </h2>
      <p style="color: var(--ink-soft); font-size: calc(1rem * var(--font-scale)); line-height: 1.5; margin: 0;">
        Atur suara, warna layar, ukuran huruf, dan animasi sesuai kenyamananmu.
      </p>
    </div>

    <form id="settingForm" action="{{ route('student.settings.update') }}" method="POST">
      @csrf
      @method('PUT')

      <!-- 1. PENGATURAN SUARA / VOLUME -->
      <div class="st-setting-block">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 12px;">
          <div class="st-setting-head" style="margin-bottom: 0;">
            <span class="st-setting-icon" style="background: var(--tint-blue); color: var(--primary-strong);" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
              </svg>
            </span>
            <label for="volumeRange" style="font-size: calc(1.125rem * var(--font-scale)); font-weight: 700; color: var(--ink);">
              Kekerasan Suara: <span id="volumeVal" style="color: var(--primary);">{{ $setting->volume }}%</span>
            </label>
          </div>
          <button type="button" class="btn btn-secondary" id="btnTestSound" style="min-height: 44px; padding: 0 18px; border-radius: 999px; font-size: 0.875rem;">
            Uji Suara
          </button>
        </div>

        <input type="range" id="volumeRange" name="volume" min="0" max="100" step="10" value="{{ $setting->volume }}"
               style="width: 100%; height: 18px; border-radius: 9px; cursor: pointer; accent-color: var(--primary);">
        <small style="color: var(--ink-soft); font-size: 0.875rem; display: block; margin-top: 8px;">
          Geser ke kiri untuk mengecilkan atau mematikan suara, geser ke kanan untuk mengeraskan.
        </small>
      </div>

      <!-- 2. PILIHAN WARNA / TEMA -->
      <div class="st-setting-block">
        <div class="st-setting-head">
          <span class="st-setting-icon" style="background: var(--tint-yellow); color: #78350F;" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="5"/>
              <line x1="12" y1="1" x2="12" y2="3"/>
              <line x1="12" y1="21" x2="12" y2="23"/>
              <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
              <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            </svg>
          </span>
          <label style="font-size: calc(1.125rem * var(--font-scale)); font-weight: 700; color: var(--ink);">
            Warna Tampilan Layar
          </label>
        </div>

        <div class="st-setting-grid-2">
          <label class="st-choice-tile theme-option {{ $setting->theme === 'calm' ? 'is-checked' : '' }}" style="display: flex; align-items: center; gap: 12px;">
            <input type="radio" name="theme" value="calm" {{ $setting->theme === 'calm' ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: var(--primary);">
            <span style="width: 34px; height: 34px; border-radius: 10px; background: #FBF8F3; border: 2px solid #D9D2C5; flex-shrink: 0;" aria-hidden="true"></span>
            <div>
              <strong style="display: block; font-size: 1rem;">Tema Tenang</strong>
              <small style="color: var(--ink-soft); font-size: 0.8125rem;">Latar krem lembut</small>
            </div>
          </label>

          <label class="st-choice-tile st-choice-tile--dark theme-option {{ $setting->theme === 'contrast' ? 'is-checked' : '' }}" style="display: flex; align-items: center; gap: 12px;">
            <input type="radio" name="theme" value="contrast" {{ $setting->theme === 'contrast' ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: #FFD400;">
            <span style="width: 34px; height: 34px; border-radius: 10px; background: #000000; border: 2px solid #FFD400; flex-shrink: 0;" aria-hidden="true"></span>
            <div>
              <strong style="display: block; font-size: 1rem; color: #FFFFFF;">Kontras Tinggi</strong>
              <small style="color: #E5E7EB; font-size: 0.8125rem;">Gelap &amp; kuning terang</small>
            </div>
          </label>
        </div>
      </div>

      <!-- 3. UKURAN HURUF -->
      <div class="st-setting-block">
        <div class="st-setting-head">
          <span class="st-setting-icon" style="background: var(--tint-green); color: var(--success);" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="4 7 4 4 20 4 20 7"/>
              <line x1="9" y1="20" x2="15" y2="20"/>
              <line x1="12" y1="4" x2="12" y2="20"/>
            </svg>
          </span>
          <label style="font-size: calc(1.125rem * var(--font-scale)); font-weight: 700; color: var(--ink);">
            Ukuran Huruf (Teks)
          </label>
        </div>

        <div class="st-setting-grid-3">
          <label class="st-choice-tile scale-option {{ $setting->font_scale === 'normal' ? 'is-checked' : '' }}" style="text-align: center;">
            <input type="radio" name="font_scale" value="normal" {{ $setting->font_scale === 'normal' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
            <div style="font-size: 1rem; font-weight: 700;">Normal</div>
            <small style="color: var(--ink-soft); font-size: 0.75rem;">100%</small>
          </label>
          <label class="st-choice-tile scale-option {{ $setting->font_scale === 'large' ? 'is-checked' : '' }}" style="text-align: center;">
            <input type="radio" name="font_scale" value="large" {{ $setting->font_scale === 'large' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
            <div style="font-size: 1.125rem; font-weight: 700;">Besar</div>
            <small style="color: var(--ink-soft); font-size: 0.75rem;">115%</small>
          </label>
          <label class="st-choice-tile scale-option {{ $setting->font_scale === 'xlarge' ? 'is-checked' : '' }}" style="text-align: center;">
            <input type="radio" name="font_scale" value="xlarge" {{ $setting->font_scale === 'xlarge' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
            <div style="font-size: 1.25rem; font-weight: 700;">Sangat Besar</div>
            <small style="color: var(--ink-soft); font-size: 0.75rem;">130%</small>
          </label>
        </div>
      </div>

      <!-- 4. GERAK & ANIMASI -->
      <div class="st-setting-block" style="margin-bottom: 24px;">
        <div class="st-setting-head">
          <span class="st-setting-icon" style="background: var(--tint-lavender); color: #4C1D95;" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="9"/>
              <path d="M9 12l2 2 4-4"/>
            </svg>
          </span>
          <label style="font-size: calc(1.125rem * var(--font-scale)); font-weight: 700; color: var(--ink);">
            Animasi Layar
          </label>
        </div>

        <div class="st-setting-grid-3">
          <label class="st-choice-tile motion-option {{ $setting->animation_level === 'off' ? 'is-checked' : '' }}" style="text-align: center;">
            <input type="radio" name="animation_level" value="off" {{ $setting->animation_level === 'off' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
            <div style="font-size: 0.9375rem; font-weight: 700;">Tanpa Gerak</div>
            <small style="color: var(--ink-soft); font-size: 0.75rem;">Tenang (0ms)</small>
          </label>
          <label class="st-choice-tile motion-option {{ $setting->animation_level === 'minimal' ? 'is-checked' : '' }}" style="text-align: center;">
            <input type="radio" name="animation_level" value="minimal" {{ $setting->animation_level === 'minimal' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
            <div style="font-size: 0.9375rem; font-weight: 700;">Minimal</div>
            <small style="color: var(--ink-soft); font-size: 0.75rem;">Halus (150ms)</small>
          </label>
          <label class="st-choice-tile motion-option {{ $setting->animation_level === 'normal' ? 'is-checked' : '' }}" style="text-align: center;">
            <input type="radio" name="animation_level" value="normal" {{ $setting->animation_level === 'normal' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
            <div style="font-size: 0.9375rem; font-weight: 700;">Normal</div>
            <small style="color: var(--ink-soft); font-size: 0.75rem;">Standar (220ms)</small>
          </label>
        </div>
      </div>

      <!-- Status & Tombol Kembali -->
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div id="saveStatus" style="font-weight: 700; font-size: 0.9375rem; color: var(--success); min-height: 24px;"></div>
        <a href="{{ route('student.home') }}" class="btn btn-student btn-primary" style="min-height: 56px; border-radius: 999px; padding: 0 28px;">
          Kembali ke Beranda
        </a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script type="module">
  import { audioManager } from '/js/core/audio-manager.js';

  const form = document.getElementById('settingForm');
  const volumeRange = document.getElementById('volumeRange');
  const volumeVal = document.getElementById('volumeVal');
  const btnTestSound = document.getElementById('btnTestSound');
  const saveStatus = document.getElementById('saveStatus');

  function syncCheckedTiles(groupName) {
    form.querySelectorAll(`input[name="${groupName}"]`).forEach((radio) => {
      const tile = radio.closest('.st-choice-tile');
      if (tile) tile.classList.toggle('is-checked', radio.checked);
    });
  }

  volumeRange.addEventListener('input', (e) => {
    const vol = parseInt(e.target.value, 10);
    volumeVal.textContent = vol + '%';
    audioManager.setVolume(vol);
  });

  btnTestSound.addEventListener('click', () => {
    const currentVol = parseInt(volumeRange.value, 10);
    audioManager.setVolume(currentVol);
    audioManager.play({
      text: 'Halo! Suara sudah diuji pada volume ' + currentVol + ' persen.',
      onStart: (status) => { btnTestSound.textContent = status.muted ? 'Suara dimatikan' : 'Sedang bersuara...'; },
      onEnd: () => { btnTestSound.textContent = 'Uji Suara'; }
    });
  });

  form.querySelectorAll('input').forEach(input => {
    input.addEventListener('change', async () => {
      if (input.name) syncCheckedTiles(input.name);
      saveStatus.textContent = 'Menyimpan perubahan...';
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

        if (res.ok) {
          saveStatus.textContent = 'Pengaturan berhasil disimpan otomatis.';
          setTimeout(() => { saveStatus.textContent = ''; }, 3000);
        } else {
          saveStatus.textContent = 'Gagal menyimpan pengaturan.';
        }
      } catch (err) {
        saveStatus.textContent = 'Koneksi terganggu.';
      }
    });
  });
</script>
@endpush
