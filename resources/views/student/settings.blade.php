@extends('layouts.student')

@section('title', 'Pengaturan Tampilan & Suara | Sahabat Belajar')
@section('page_title', 'Pengaturan Kenyamanan')

@section('content')
<div style="max-width: 680px; margin: 0 auto; background: var(--surface); border: var(--border) solid var(--line); border-radius: var(--radius); padding: 36px 32px; box-shadow: var(--shadow-sm);">
  <div style="margin-bottom: 28px;">
    <h2 style="font-size: 1.625rem; font-weight: 800; margin-bottom: 6px; letter-spacing: -0.3px;">
      Pengaturan Kenyamanan Belajar
    </h2>
    <p style="color: var(--ink-soft); font-size: 1rem; line-height: 1.5;">
      Perubahan akan otomatis disimpan dan langsung diterapkan ke layar kamu.
    </p>
  </div>

  <form id="settingForm" action="{{ route('student.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <!-- 1. PENGATURAN SUARA / VOLUME -->
    <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--line);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <label for="volumeRange" style="font-size: 1.125rem; font-weight: 800; color: var(--ink);">
          Kekerasan Suara: <span id="volumeVal" style="color: var(--primary);">{{ $setting->volume }}%</span>
        </label>
        <button type="button" class="btn btn-secondary" id="btnTestSound" style="min-height: 40px; padding: 0 16px; font-size: 14px;">
          Uji Suara
        </button>
      </div>
      <input type="range" id="volumeRange" name="volume" min="0" max="100" step="10" value="{{ $setting->volume }}"
             style="width: 100%; height: 16px; border-radius: 8px; cursor: pointer; accent-color: var(--primary);">
      <small style="color: var(--ink-soft); font-size: 14px; display: block; margin-top: 6px;">
        Geser ke kiri untuk memperkecil atau mematikan suara, geser ke kanan untuk memperkeras.
      </small>
    </div>

    <!-- 2. PILIHAN WARNA / TEMA -->
    <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--line);">
      <label style="display: block; font-size: 1.125rem; font-weight: 800; color: var(--ink); margin-bottom: 12px;">
        Warna Tampilan Layar
      </label>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <label style="border: var(--border) solid {{ $setting->theme === 'calm' ? 'var(--primary)' : 'var(--line)' }}; border-radius: var(--radius-sm); padding: 18px; cursor: pointer; background: var(--surface); display: flex; align-items: center; gap: 12px;" class="theme-option">
          <input type="radio" name="theme" value="calm" {{ $setting->theme === 'calm' ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: var(--primary);">
          <div>
            <strong style="display: block; font-size: 16px;">Tema Tenang</strong>
            <small style="color: var(--ink-soft); font-size: 13px;">Warna lembut ramah mata (Standar)</small>
          </div>
        </label>

        <label style="border: var(--border) solid {{ $setting->theme === 'contrast' ? 'var(--primary)' : 'var(--line)' }}; border-radius: var(--radius-sm); padding: 18px; cursor: pointer; background: #111; color: #fff; display: flex; align-items: center; gap: 12px;" class="theme-option">
          <input type="radio" name="theme" value="contrast" {{ $setting->theme === 'contrast' ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: #FFD400;">
          <div>
            <strong style="display: block; font-size: 16px; color: #fff;">Kontras Tinggi</strong>
            <small style="color: #ccc; font-size: 13px;">Latar hitam pekat & teks terang</small>
          </div>
        </label>
      </div>
    </div>

    <!-- 3. UKURAN HURUF -->
    <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--line);">
      <label style="display: block; font-size: 1.125rem; font-weight: 800; color: var(--ink); margin-bottom: 12px;">
        Ukuran Huruf (Teks)
      </label>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
        <label style="border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);" class="scale-option">
          <input type="radio" name="font_scale" value="normal" {{ $setting->font_scale === 'normal' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
          <div style="font-size: 15px; font-weight: 700;">Normal</div>
          <small style="color: var(--ink-soft); font-size: 12px;">100%</small>
        </label>
        <label style="border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);" class="scale-option">
          <input type="radio" name="font_scale" value="large" {{ $setting->font_scale === 'large' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
          <div style="font-size: 17px; font-weight: 700;">Besar</div>
          <small style="color: var(--ink-soft); font-size: 12px;">115%</small>
        </label>
        <label style="border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);" class="scale-option">
          <input type="radio" name="font_scale" value="xlarge" {{ $setting->font_scale === 'xlarge' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
          <div style="font-size: 19px; font-weight: 700;">Sangat Besar</div>
          <small style="color: var(--ink-soft); font-size: 12px;">130%</small>
        </label>
      </div>
    </div>

    <!-- 4. GERAK & ANIMASI -->
    <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--line);">
      <label style="display: block; font-size: 1.125rem; font-weight: 800; color: var(--ink); margin-bottom: 12px;">
        Animasi Layar
      </label>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
        <label style="border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);" class="motion-option">
          <input type="radio" name="animation_level" value="off" {{ $setting->animation_level === 'off' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
          <div style="font-size: 14px; font-weight: 700;">Tanpa Gerak</div>
          <small style="color: var(--ink-soft); font-size: 12px;">0ms (Tenang)</small>
        </label>
        <label style="border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);" class="motion-option">
          <input type="radio" name="animation_level" value="minimal" {{ $setting->animation_level === 'minimal' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
          <div style="font-size: 14px; font-weight: 700;">Minimal</div>
          <small style="color: var(--ink-soft); font-size: 12px;">150ms</small>
        </label>
        <label style="border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);" class="motion-option">
          <input type="radio" name="animation_level" value="normal" {{ $setting->animation_level === 'normal' ? 'checked' : '' }} style="margin-bottom: 6px; accent-color: var(--primary);">
          <div style="font-size: 14px; font-weight: 700;">Normal</div>
          <small style="color: var(--ink-soft); font-size: 12px;">220ms</small>
        </label>
      </div>
    </div>

    <!-- Status Penyimpanan & Tombol Selesai -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div id="saveStatus" style="font-weight: 700; font-size: 14px; color: var(--success); min-height: 24px;"></div>
      <a href="{{ route('student.home') }}" class="btn btn-student btn-primary" style="font-size: 16px; min-height: 52px; padding: 0 28px;">
        Kembali ke Beranda
      </a>
    </div>
  </form>
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

  volumeRange.addEventListener('input', (e) => {
    volumeVal.textContent = e.target.value + '%';
  });

  btnTestSound.addEventListener('click', () => {
    const currentVol = parseInt(volumeRange.value, 10);
    audioManager.play({
      text: 'Halo! Suara sudah diuji pada volume ' + currentVol + ' persen.',
      volume: currentVol / 100,
      onStart: () => { btnTestSound.textContent = 'Sedang bersuara...'; },
      onEnd: () => { btnTestSound.textContent = 'Uji Suara'; }
    });
  });

  // Simpan otomatis setiap perubahan form
  const inputs = form.querySelectorAll('input');
  inputs.forEach(input => {
    input.addEventListener('change', async () => {
      saveStatus.textContent = 'Menyimpan perubahan...';
      const formData = new FormData(form);

      // Terapkan langsung ke <html> agar instan
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
