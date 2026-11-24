@extends('layouts.student')

@section('title', $material->label . ' | Belajar ' . $category->name)
@section('page_title', $category->name)

@section('header_left')
  <a href="{{ route('student.learn.index') }}" class="btn btn-secondary" style="min-height: 48px; padding: 0 16px; font-size: 16px;" title="Kembali ke Daftar Kategori">
    <span>←</span> <span>Kategori</span>
  </a>
@endsection

@section('content')
<div class="material-viewer">
  <!-- Indikator Langkah -->
  <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
    <span style="font-weight: 800; font-size: 16px; color: var(--primary); background: var(--tint-blue); padding: 4px 14px; border-radius: 20px;">
      {{ $category->name }}
    </span>
    <span style="font-weight: 800; font-size: 16px; color: var(--ink-soft);">
      {{ $currentIndex }} dari {{ $totalMaterials }}
    </span>
  </div>

  <!-- Kotak Gambar Besar (Tap Gambar = Memutar Suara) -->
  <div class="material-image-box" id="materialImageBox" tabindex="0" role="button" aria-label="Gambar {{ $material->label }}. Tekan untuk mendengarkan suara." title="Tekan gambar untuk mendengar suara">
    <img src="/storage/{{ $material->image_path }}" alt="{{ $material->label }}" class="material-image">
  </div>

  <!-- Nama Objek -->
  <div class="material-word">{{ $material->label }}</div>

  <!-- Tombol Dengar -->
  <div>
    <button type="button" class="btn-speaker" id="btnSpeaker" aria-pressed="false" title="Dengarkan pengucapan kata">
      <span id="speakerIcon">🔊</span>
      <span id="speakerText">{{ __('ui.hear') }}</span>
    </button>
  </div>

  <!-- Kalimat Pendek Pendamping (Jika Ada) -->
  @if ($material->short_sentence)
    <p style="font-size: var(--fs-label); color: var(--ink-soft); margin-top: 4px;">
      {{ $material->short_sentence }}
    </p>
  @endif

  <!-- StepNav (Sebelumnya & Berikutnya / Selesai) -->
  <div class="step-nav">
    @if ($prevMaterial)
      <a href="{{ route('student.learn.material', [$category->slug, $prevMaterial->id]) }}" class="btn btn-student btn-secondary" id="btnPrev">
        <span>←</span> <span>Sebelumnya</span>
      </a>
    @else
      <button class="btn btn-student btn-secondary" disabled>
        <span>←</span> <span>Sebelumnya</span>
      </button>
    @endif

    <!-- Titik Langkah (Progress Dots) -->
    <div style="display: flex; gap: 8px; align-items: center;" aria-hidden="true">
      @for ($i = 1; $i <= $totalMaterials; $i++)
        <span style="display: inline-block; width: {{ $i === $currentIndex ? '16px' : '10px' }}; height: 10px; border-radius: 5px; background: {{ $i === $currentIndex ? 'var(--primary)' : 'var(--line)' }}; transition: all 150ms ease;"></span>
      @endfor
    </div>

    @if ($nextMaterial)
      <a href="{{ route('student.learn.material', [$category->slug, $nextMaterial->id]) }}" class="btn btn-student btn-primary" id="btnNext">
        <span>Berikutnya</span> <span>→</span>
      </a>
    @else
      <a href="{{ route('student.learn.index') }}" class="btn btn-student btn-soft" id="btnFinish">
        <span>✓</span> <span>Selesai</span>
      </a>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script type="module">
  import { audioManager } from '/js/core/audio-manager.js';

  const audioUrl = @json($material->audio_path ? '/storage/' . $material->audio_path : null);
  const textLabel = @json($material->label);

  const btnSpeaker = document.getElementById('btnSpeaker');
  const speakerIcon = document.getElementById('speakerIcon');
  const speakerText = document.getElementById('speakerText');
  const imageBox = document.getElementById('materialImageBox');

  function playSound() {
    audioManager.play({
      audioUrl: audioUrl,
      text: textLabel,
      onStart: (status) => {
        btnSpeaker.classList.add('is-playing');
        btnSpeaker.setAttribute('aria-pressed', 'true');
        speakerText.textContent = status.text || '{{ __("ui.hearing") }}';
      },
      onEnd: () => {
        btnSpeaker.classList.remove('is-playing');
        btnSpeaker.setAttribute('aria-pressed', 'false');
        speakerText.textContent = '{{ __("ui.hear") }}';
      }
    });
  }

  btnSpeaker.addEventListener('click', playSound);
  imageBox.addEventListener('click', playSound);
  imageBox.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      playSound();
    }
  });

  // Navigasi Keyboard: Panah Kiri (←) & Panah Kanan (→)
  window.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
      const prevBtn = document.getElementById('btnPrev');
      if (prevBtn) prevBtn.click();
    } else if (e.key === 'ArrowRight') {
      const nextBtn = document.getElementById('btnNext') || document.getElementById('btnFinish');
      if (nextBtn) nextBtn.click();
    }
  });
</script>
@endpush
