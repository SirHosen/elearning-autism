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
  <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama materi (mis. Kucing, Bola)..." class="adm-input" style="flex: 1; min-width: 220px;">
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

      <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px;">
        <div>
          <h3 style="margin: 0; font-size: 1.0625rem; font-weight: 700; color: var(--ink);">{{ $m->label }}</h3>
          <span style="font-size: 0.75rem; font-weight: 700; color: var(--primary-strong);">{{ $m->category->name }}</span>
        </div>
        @if ($m->is_active)
          <span class="adm-pill adm-pill--success">Aktif</span>
        @else
          <span class="adm-pill adm-pill--neutral">Nonaktif</span>
        @endif
      </div>

      <p style="margin: 0; font-size: 0.8125rem; color: var(--ink-soft); flex: 1; line-height: 1.4;">
        {{ $m->short_sentence ?? 'Belum ada kalimat pendamping.' }}
      </p>

      <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
        @if ($m->audio_path)
          <span class="adm-pill adm-pill--success">Rekaman Guru</span>
        @else
          <span class="adm-pill adm-pill--info">Suara Otomatis</span>
        @endif

        <button type="button"
                class="adm-btn adm-btn--secondary adm-btn--xs adm-preview-audio-btn"
                data-label="{{ $m->label }}"
                data-audio="{{ $m->audio_path ? '/storage/' . $m->audio_path : '' }}"
                style="min-width: 34px; padding: 0 8px;"
                aria-label="Putar suara {{ $m->label }}"
                title="Uji suara pelafalan">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
          </svg>
        </button>
      </div>

      <div style="display: flex; gap: 8px; padding-top: 10px; border-top: 1px solid var(--line);">
        <a href="{{ route('admin.materials.edit', $m) }}" class="adm-btn adm-btn--secondary adm-btn--sm" style="flex: 1;">Ubah</a>
        <form action="{{ route('admin.materials.destroy', $m) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus materi {{ $m->label }}?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm" style="width: 100%;">Hapus</button>
        </form>
      </div>
    </article>
  @empty
    <div style="grid-column: 1 / -1; background: var(--surface); padding: 48px; border: 1px dashed var(--line); border-radius: var(--adm-radius); text-align: center; color: var(--ink-soft);">
      Materi tidak ditemukan. Silakan tambahkan materi baru atau ubah filter pencarian.
    </div>
  @endforelse
</div>

<div style="margin-top: 24px;">
  {{ $materials->links() }}
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let activeAudio = null;
    const allBtns = document.querySelectorAll('.adm-preview-audio-btn');

    function resetPlayingState() {
      allBtns.forEach(function(b) {
        b.classList.remove('is-playing');
        b.setAttribute('aria-pressed', 'false');
      });
    }

    allBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        const audioUrl = btn.dataset.audio;
        const labelText = btn.dataset.label;

        if (activeAudio) {
          activeAudio.pause();
          activeAudio = null;
        }
        if ('speechSynthesis' in window) {
          window.speechSynthesis.cancel();
        }
        resetPlayingState();

        if (audioUrl) {
          activeAudio = new Audio(audioUrl);
          btn.classList.add('is-playing');
          btn.setAttribute('aria-pressed', 'true');
          activeAudio.onended = activeAudio.onerror = resetPlayingState;
          activeAudio.play().catch(resetPlayingState);
        } else if ('speechSynthesis' in window && labelText) {
          const u = new SpeechSynthesisUtterance(labelText);
          u.lang = 'id-ID';
          u.rate = 0.85;
          btn.classList.add('is-playing');
          btn.setAttribute('aria-pressed', 'true');
          u.onend = u.onerror = resetPlayingState;
          window.speechSynthesis.speak(u);
        }
      });
    });
  });
</script>
@endpush
