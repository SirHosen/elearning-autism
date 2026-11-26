@extends('layouts.admin')

@section('title', 'Materi Pembelajaran | Panel Pengelolaan Guru')
@section('page_title', 'Materi Belajar')

@section('content')
<div class="adm-page-head">
  <div>
    <p class="adm-eyebrow">Bank Kartu Visual &amp; Audio</p>
    <h1 class="adm-title">Materi Belajar Visual &amp; Audio</h1>
    <p class="adm-subtitle">Daftar objek pembelajaran yang digunakan langsung pada modul Belajar, Permainan, dan Kuis Bergambar.</p>
  </div>
  <div class="adm-head-actions">
    <a href="{{ route('admin.quizzes.index') }}" class="adm-btn adm-btn--secondary">Generator Kuis &rarr;</a>
    <a href="{{ route('admin.materials.create', ['category_id' => request('category_id')]) }}" class="adm-btn adm-btn--primary">+ Tambah Materi</a>
  </div>
</div>

{{-- Filter & Pencarian --}}
<form action="{{ route('admin.materials.index') }}" method="GET" class="adm-filter-bar">
  <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama materi (mis. Kucing, Bola)..." class="adm-input adm-filter-grow">
  <select name="category_id" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Kategori</option>
    @foreach ($categories as $c)
      <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
        {{ $c->name }} ({{ $c->materials_count }})
      </option>
    @endforeach
  </select>
  <select name="audio_type" class="adm-select" onchange="this.form.submit()">
    <option value="">Semua Sumber Suara</option>
    <option value="recorded" {{ request('audio_type') === 'recorded' ? 'selected' : '' }}>Rekaman Guru</option>
    <option value="tts" {{ request('audio_type') === 'tts' ? 'selected' : '' }}>Suara Otomatis (TTS)</option>
  </select>
  <button type="submit" class="adm-btn adm-btn--secondary adm-btn--sm">Filter</button>
  @if (request()->hasAny(['q', 'category_id', 'audio_type']))
    <a href="{{ route('admin.materials.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">Reset</a>
  @endif
</form>

<div class="adm-media-grid">
  @forelse ($materials as $m)
    <article class="adm-media-card">
      <div class="adm-media-card__thumb">
        <img src="/storage/{{ $m->image_path }}" alt="{{ $m->label }}">
      </div>

      <div class="adm-media-card__head">
        <div>
          <h3 class="adm-media-card__title">{{ $m->label }}</h3>
          <span class="adm-media-card__cat">{{ $m->category->name }}</span>
        </div>
        @if ($m->is_active)
          <span class="adm-pill adm-pill--success">Aktif</span>
        @else
          <span class="adm-pill adm-pill--neutral">Nonaktif</span>
        @endif
      </div>

      <p class="adm-media-card__desc">
        {{ $m->short_sentence ?? 'Belum ada kalimat pendamping.' }}
      </p>

      <div class="adm-media-card__meta">
        @if ($m->audio_path)
          <span class="adm-pill adm-pill--success">Rekaman Guru</span>
        @else
          <span class="adm-pill adm-pill--info">Suara Otomatis</span>
        @endif

        <button type="button"
                class="adm-btn adm-btn--secondary adm-btn--xs adm-icon-btn adm-preview-audio-btn"
                data-label="{{ $m->label }}"
                data-lang="{{ $m->category?->slug === 'bahasa-inggris' ? 'en-US' : 'id-ID' }}"
                data-audio="{{ $m->audio_path ? '/storage/' . $m->audio_path : '' }}"
                aria-label="Putar suara {{ $m->label }}"
                title="Uji suara pelafalan">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
          </svg>
        </button>
      </div>

      <div class="adm-media-card__foot">
        <a href="{{ route('admin.materials.edit', $m) }}" class="adm-btn adm-btn--secondary adm-btn--sm adm-btn-flex">Ubah</a>
        <form action="{{ route('admin.materials.destroy', $m) }}" method="POST" onsubmit="return confirm('Hapus materi {{ $m->label }}?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm adm-btn-block">Hapus</button>
        </form>
      </div>
    </article>
  @empty
    <div class="adm-empty-grid">
      Materi tidak ditemukan. Silakan tambahkan materi baru atau ubah filter pencarian.
    </div>
  @endforelse
</div>

<div class="adm-pagination-wrap">
  {{ $materials->links() }}
</div>
@endsection

@push('scripts')
<script type="module" src="/js/core/audio-manager.js?v={{ @filemtime(public_path('js/core/audio-manager.js')) ?: '2' }}"></script>
<script type="module">
  document.addEventListener('DOMContentLoaded', function() {
    const allBtns = document.querySelectorAll('.adm-preview-audio-btn');
    const resetPlayingState = () => {
      allBtns.forEach((b) => {
        b.classList.remove('is-playing');
        b.setAttribute('aria-pressed', 'false');
      });
    };

    allBtns.forEach((btn) => {
      btn.addEventListener('click', () => {
        const mgr = window.audioManager;
        if (!mgr) return;

        const wasPlaying = btn.classList.contains('is-playing');
        mgr.stop();
        resetPlayingState();
        if (wasPlaying) return;

        mgr.play({
          audioUrl: btn.dataset.audio || null,
          text: btn.dataset.label || '',
          lang: btn.dataset.lang || 'id-ID',
          onStart: () => {
            btn.classList.add('is-playing');
            btn.setAttribute('aria-pressed', 'true');
          },
          onEnd: resetPlayingState,
        });
      });
    });
  });
</script>
@endpush
