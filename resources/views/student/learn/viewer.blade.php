@extends('layouts.student')

@section('title', $material->label . ' | Belajar ' . $category->name)
@section('page_title', $category->name)

@php
  $colorKey = $category->color_key ?? 'blue';
  $pillModifier = match ($colorKey) {
      'green' => 'green',
      'yellow' => 'yellow',
      'lavender' => 'lavender',
      'peach' => 'peach',
      default => 'blue',
  };
  $audioUrl = $material->audio_path ? '/storage/' . $material->audio_path : null;
  $wordLabel = $material->label;
  $sentenceLabel = $material->short_sentence;
@endphp

@section('header_left')
  <a href="{{ route('student.learn.index') }}" class="student-nav-btn" title="Kembali ke Daftar Kategori">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <line x1="19" y1="12" x2="5" y2="12"/>
      <polyline points="12 19 5 12 12 5"/>
    </svg>
    <span>Kategori</span>
  </a>
@endsection

@section('content')
<div class="learn-workspace">
  <!-- Bar Progres Kartu Materi -->
  <div class="learn-progress-header st-anim st-anim--1">
    <span class="st-topic-pill st-topic-pill--{{ $pillModifier }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
      </svg>
      <span>{{ $category->name }}</span>
    </span>

    <div class="learn-progress-track" aria-hidden="true">
      @for ($i = 1; $i <= $totalMaterials; $i++)
        <span class="learn-step-pill {{ $i === $currentIndex ? 'learn-step-pill--active' : ($i < $currentIndex ? 'learn-step-pill--done' : '') }}"></span>
      @endfor
    </div>

    <span class="learn-count-label">
      {{ $currentIndex }} dari {{ $totalMaterials }}
    </span>
  </div>

  <!-- Kartu Gambar Interaktif -->
  <section class="learn-flashcard st-anim st-anim--2" aria-label="Kartu belajar {{ $material->label }}">
    <div class="learn-picture-frame learn-picture-frame--{{ $pillModifier }}"
         id="materialImageBox"
         tabindex="0"
         role="button"
         aria-label="Gambar {{ $material->label }}. Tekan untuk mendengarkan suara."
         title="Ketuk gambar untuk mendengar suara">
      <span class="learn-tap-hint" aria-hidden="true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
        </svg>
        Ketuk Gambar
      </span>

      <img src="/storage/{{ $material->image_path }}" alt="{{ $material->label }}" class="learn-picture-img">
    </div>

    <h2 class="learn-word-heading">{{ $material->label }}</h2>

    <div>
      <button type="button" class="learn-speaker-btn" id="btnSpeaker" aria-pressed="false" title="Dengarkan pengucapan kata">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
          <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
          <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
        </svg>
        <span class="learn-sound-bars" aria-hidden="true">
          <span></span><span></span><span></span>
        </span>
        <span id="speakerText">{{ __('ui.hear') }}</span>
      </button>
    </div>

    @if ($category->slug === 'musik-nada')
      @php
        $scaleNotes = [
            ['key' => 'nada do', 'short' => 'Do', 'mod' => 'do'],
            ['key' => 'nada re', 'short' => 'Re', 'mod' => 're'],
            ['key' => 'nada mi', 'short' => 'Mi', 'mod' => 'mi'],
            ['key' => 'nada fa', 'short' => 'Fa', 'mod' => 'fa'],
            ['key' => 'nada sol', 'short' => 'Sol', 'mod' => 'sol'],
        ];
        $currentNoteLower = strtolower(trim($material->label));
      @endphp
      <div class="learn-scale-bar" aria-label="Tangga nada interaktif">
        <span class="learn-scale-title">Ketuk nada untuk mendengar tinggi bunyi:</span>
        <div class="learn-scale-keys">
          @foreach ($scaleNotes as $n)
            <button type="button"
                    class="learn-scale-key learn-scale-key--{{ $n['mod'] }} {{ $currentNoteLower === $n['key'] ? 'is-current' : '' }}"
                    data-note-key="{{ $n['key'] }}">
              {{ $n['short'] }}
            </button>
          @endforeach
        </div>
      </div>
    @endif

    @if ($material->short_sentence)
      <button type="button"
              class="learn-sentence-card"
              id="btnSentenceSpeak"
              title="Dengarkan kalimat pendamping">
        <p class="learn-sentence-card__text">{{ $material->short_sentence }}</p>
        <span class="learn-sentence-card__cta">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
          </svg>
          <span>Dengar Kalimat</span>
        </span>
      </button>
    @endif
  </section>

  <!-- Navigasi Langkah Besar (Sebelumnya & Berikutnya / Selesai) -->
  <nav class="learn-step-nav st-anim st-anim--3" aria-label="Navigasi kartu materi">
    @if ($prevMaterial)
      <a href="{{ route('student.learn.material', [$category->slug, $prevMaterial->id]) }}"
         class="btn btn-student btn-secondary learn-nav-btn learn-nav-btn--prev"
         id="btnPrev">
        @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 20, 'class' => 'st-btn-arrow st-btn-arrow--left'])
        <span>Sebelumnya</span>
      </a>
    @else
      <button type="button" class="btn btn-student btn-secondary learn-nav-btn learn-nav-btn--prev" disabled>
        @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 20, 'class' => 'st-btn-arrow st-btn-arrow--left'])
        <span>Sebelumnya</span>
      </button>
    @endif

    <span class="learn-nav-spacer" aria-hidden="true">
      Panah &larr; / &rarr;
    </span>

    @if ($nextMaterial)
      <a href="{{ route('student.learn.material', [$category->slug, $nextMaterial->id]) }}"
         class="btn btn-student btn-primary learn-nav-btn learn-nav-btn--next"
         id="btnNext">
        <span>Berikutnya</span>
        @include('student.partials.icon-arrow', ['size' => 20])
      </a>
    @else
      <a href="{{ route('student.learn.index') }}"
         class="btn btn-student btn-soft learn-nav-btn learn-nav-btn--finish"
         id="btnFinish">
        <svg class="st-btn-check" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>Selesai</span>
      </a>
    @endif
  </nav>
</div>
@endsection

@push('scripts')
<script type="module">
  const audioManager = window.audioManager;

  const audioUrl = @json($audioUrl);
  const textLabel = @json($wordLabel);
  const sentenceText = @json($sentenceLabel);
  const categorySlug = @json($category->slug);
  const wordLang = categorySlug === 'bahasa-inggris' ? 'en-US' : 'id-ID';
  const wordMusicKey = categorySlug === 'musik-nada' ? textLabel : null;

  const btnSpeaker = document.getElementById('btnSpeaker');
  const speakerText = document.getElementById('speakerText');
  const imageBox = document.getElementById('materialImageBox');
  const btnSentenceSpeak = document.getElementById('btnSentenceSpeak');

  function setPlayingState(active, statusText) {
    btnSpeaker.classList.toggle('is-playing', active);
    imageBox.classList.toggle('is-playing', active);
    btnSpeaker.setAttribute('aria-pressed', active ? 'true' : 'false');
    speakerText.textContent = statusText || @json(__('ui.hear'));
  }

  function playSound() {
    if (!audioManager) return;
    audioManager.play({
      audioUrl: audioUrl,
      text: textLabel,
      lang: wordLang,
      musicKey: wordMusicKey,
      onStart: (status) => {
        setPlayingState(true, status.text || @json(__('ui.hearing')));
      },
      onEnd: () => {
        setPlayingState(false, @json(__('ui.hear')));
      }
    });
  }

  document.querySelectorAll('.learn-scale-key[data-note-key]').forEach((keyBtn) => {
    keyBtn.addEventListener('click', () => {
      if (!audioManager) return;
      audioManager.stop();
      audioManager.playMusicCue(keyBtn.dataset.noteKey);
      keyBtn.classList.add('is-ringing');
      setTimeout(() => keyBtn.classList.remove('is-ringing'), 420);
    });
  });

  btnSpeaker.addEventListener('click', playSound);
  imageBox.addEventListener('click', playSound);
  imageBox.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      playSound();
    }
  });

  if (btnSentenceSpeak && sentenceText) {
    btnSentenceSpeak.addEventListener('click', () => {
      if (!audioManager) return;
      const sentencePayload = categorySlug === 'bahasa-inggris' && window.ArenaHelpers
        ? {
            text: sentenceText,
            segments: window.ArenaHelpers.buildBilingualSegments(sentenceText, textLabel),
            musicKey: false,
          }
        : {
            text: sentenceText,
            lang: 'id-ID',
            musicKey: false,
          };

      audioManager.play({
        ...sentencePayload,
        onStart: (status) => {
          btnSpeaker.classList.remove('is-playing');
          btnSpeaker.setAttribute('aria-pressed', 'false');
          imageBox.classList.add('is-playing');
          speakerText.textContent = status.text || @json(__('ui.hearing'));
        },
        onEnd: () => {
          setPlayingState(false, @json(__('ui.hear')));
        }
      });
    });
  }

  // Navigasi Keyboard: Panah Kiri & Panah Kanan
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
