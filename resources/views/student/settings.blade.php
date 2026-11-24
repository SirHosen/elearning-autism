@extends('layouts.student')

@section('title', 'Pengaturan Tampilan & Suara | EduAutis')
@section('page_title', 'Pengaturan Kenyamanan')

@section('content')
<div style="max-width: 680px; margin: 0 auto; background: var(--surface); border: var(--border) solid var(--line); border-radius: var(--radius); padding: 36px 28px;">
  <div style="margin-bottom: 28px;">
    <h2 style="font-size: 26px; margin-bottom: 6px;">Atur Sesuai Kenyamananmu ✨</h2>
    <p style="color: var(--ink-soft); font-size: var(--fs-base);">
      Perubahan akan otomatis disimpan dan langsung diterapkan ke layar.
    </p>
  </div>

  <form id="settingForm" action="{{ route('student.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <!-- 1. PENGATURAN SUARA / VOLUME -->
    <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--line);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <label for="volumeRange" style="font-size: var(--fs-label); font-weight: 800; color: var(--ink);">
          🔊 Kekerasan Suara: <span id="volumeVal">{{ $setting->volume }}%</span>
        </label>
        <button type="button" class="btn btn-secondary" id="btnTestSound" style="min-height: 44px; padding: 0 16px; font-size: 14px;">
          🔊 Uji Suara
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
      <label style="display: block; font-size: var(--fs-label); font-weight: 800; color: var(--ink); margin-bottom: 12px;">
        🎨 Warna Tampilan Layar
      </label>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <label style="border: var(--border) solid {{ $setting->theme === 'calm' ? 'var(--primary)' : 'var(--line)' }}; border-radius: var(--radius-sm); padding: 18px; cursor: pointer; background: var(--surface); display: flex; align-items: center; gap: 12px;" class="theme-option">
          <input type="radio" name="theme" value="calm" {{ $setting->theme === 'calm' ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: var(--primary);">
          <div>
            <strong style="display: block; font-size: 18px;">Tema Tenang</strong>
            <small style="color: var(--ink-soft); font-size: 13px;">Warna lembut ramah mata (Standar)</small>
          </div>
        </label>

        <label style="border: var(--border) solid {{ $setting->theme === 'contrast' ? 'var(--primary)' : 'var(--line)' }}; border-radius: var(--radius-sm); padding: 18px; cursor: pointer; background: #111; color: #fff; display: flex; align-items: center; gap: 12px;" class="theme-option">
          <input type="radio" name="theme" value="contrast" {{ $setting->theme === 'contrast' ? 'checked' : '' }} style="width: 22px; height: 22px; accent-color: #FFD400;">
          <div>
            <strong style="display: block; font-size: 18px; color: #fff;">Kontras Tinggi</strong>
            <small style="color: #ccc; font-size: 13px;">Latar hitam pekat & teks terang</small>
          </div>
        </label>
      </div>
    </div>

    <!-- 3. UKURAN HURUF -->
    <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--line);">
      <label style="display: block; font-size: var(--fs-label); font-weight: 800; color: var(--ink); margin-bottom: 12px;">
        🔤 Ukuran Huruf (Teks)
      </label>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
        <label style="border: var(--border) solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);">
          <input type="radio" name="font_scale" value="normal" {{ $setting->font_scale === 'normal' ? 'checked' : '' }} style="accent-color: var(--primary);">
          <span style="display: block; font-size: 16px; font-weight: 700; margin-top: 4px;">Biasa (100%)</span>
        </label>
        <label style="border: var(--border) solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);">
          <input type="radio" name="font_scale" value="large" {{ $setting->font_scale === 'large' ? 'checked' : '' }} style="accent-color: var(--primary);">
          <span style="display: block; font-size: 18px; font-weight: 700; margin-top: 4px;">Besar (115%)</span>
        </label>
        <label style="border: var(--border) solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);">
          <input type="radio" name="font_scale" value="xlarge" {{ $setting->font_scale === 'xlarge' ? 'checked' : '' }} style="accent-color: var(--primary);">
          <span style="display: block; font-size: 20px; font-weight: 700; margin-top: 4px;">Ekstra (130%)</span>
        </label>
      </div>
    </div>

    <!-- 4. GERAK & ANIMASI -->
    <div style="margin-bottom: 32px;">
      <label style="display: block; font-size: var(--fs-label); font-weight: 800; color: var(--ink); margin-bottom: 12px;">
        ⚡ Gerak & Animasi Layar
      </label>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
        <label style="border: var(--border) solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);">
          <input type="radio" name="animation_level" value="off" {{ $setting->animation_level === 'off' ? 'checked' : '' }} style="accent-color: var(--primary);">
          <span style="display: block; font-size: 15px; font-weight: 700; margin-top: 4px;">Mati (Diam)</span>
        </label>
        <label style="border: var(--border) solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);">
          <input type="radio" name="animation_level" value="minimal" {{ $setting->animation_level === 'minimal' ? 'checked' : '' }} style="accent-color: var(--primary);">
          <span style="display: block; font-size: 15px; font-weight: 700; margin-top: 4px;">Sedikit (Halus)</span>
        </label>
        <label style="border: var(--border) solid var(--line); border-radius: var(--radius-sm); padding: 14px; text-align: center; cursor: pointer; background: var(--surface);">
          <input type="radio" name="animation_level" value="normal" {{ $setting->animation_level === 'normal' ? 'checked' : '' }} style="accent-color: var(--primary);">
          <span style="display: block; font-size: 15px; font-weight: 700; margin-top: 4px;">Biasa</span>
        </label>
      </div>
    </div>

    <!-- Status Notifikasi Auto-Save -->
    <div id="saveStatus" style="font-size: 14px; font-weight: 700; color: var(--success); min-height: 24px; text-align: center; margin-bottom: 16px;"></div>

    <div style="text-align: center;">
      <a href="{{ route('student.home') }}" class="btn btn-student btn-primary">
        Kembali ke Beranda ⌂
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

  volumeRange.addEventListener('input', () => {
    volumeVal.textContent = volumeRange.value + '%';
    audioManager.setVolume(Number(volumeRange.value));
    autoSave();
  });

  btnTestSound.addEventListener('click', () => {
    audioManager.play({
      text: 'Halo! Ini suara contoh untuk belajar.',
      onStart: () => { btnTestSound.textContent = '🔊 Sedang bersuara...'; },
      onEnd: () => { btnTestSound.textContent = '🔊 Uji Suara'; }
    });
  });

  document.querySelectorAll('input[name="theme"]').forEach(radio => {
    radio.addEventListener('change', () => {
      document.documentElement.setAttribute('data-theme', radio.value);
      autoSave();
    });
  });

  document.querySelectorAll('input[name="font_scale"]').forEach(radio => {
    radio.addEventListener('change', () => {
      document.documentElement.setAttribute('data-font-scale', radio.value);
      autoSave();
    });
  });

  document.querySelectorAll('input[name="animation_level"]').forEach(radio => {
    radio.addEventListener('change', () => {
      document.documentElement.setAttribute('data-motion', radio.value);
      autoSave();
    });
  });

  let saveTimer = null;
  function autoSave() {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(async () => {
      saveStatus.textContent = 'Menyimpan preferensi...';
      const formData = new FormData(form);
      const payload = {
        volume: Number(formData.get('volume')),
        theme: formData.get('theme'),
        font_scale: formData.get('font_scale'),
        animation_level: formData.get('animation_level'),
      };

      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch('{{ route("student.settings.update") }}', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(payload)
        });

        if (res.ok) {
          saveStatus.textContent = '✓ Pengaturan berhasil disimpan otomatis.';
          setTimeout(() => { saveStatus.textContent = ''; }, 2500);
        }
      } catch (err) {
        saveStatus.textContent = 'Gagal menyimpan. Coba lagi.';
      }
    }, 300);
  }
</script>
@endpush
