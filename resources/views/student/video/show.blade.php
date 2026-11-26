@extends('layouts.student')

@section('title', $video->title . ' | Video Edukasi')
@section('page_title', 'Video Edukasi')

@section('content')
<div class="st-page-shell" style="max-width: 900px;">
  {{-- Header & Tombol Kembali + Ulangi --}}
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.videos.index') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali</span>
    </a>

    <button id="btn-repeat-video" type="button" class="btn btn-student btn-primary st-repeat-btn">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <polyline points="1 4 1 10 7 10"/>
        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
      </svg>
      <span>Ulangi Video</span>
    </button>
  </div>

  {{-- Kartu Pemutar Video --}}
  <div class="st-cinema-card st-anim st-anim--2">
    <div class="st-cinema-screen">
      @if($video->source_type === 'upload')
        <video id="edu-video-player" controls playsinline preload="metadata" style="width: 100%; max-height: 520px; display: block;">
          <source src="{{ $video->stream_url }}" type="video/mp4">
          Browser Anda tidak mendukung pemutar video HTML5.
        </video>
      @else
        <div style="position: relative; width: 100%; aspect-ratio: 16/9;">
          <iframe id="edu-video-iframe"
                  src="{{ $video->youtube_embed_url }}"
                  title="{{ $video->title }}"
                  style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                  referrerpolicy="strict-origin-when-cross-origin"
                  allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                  allowfullscreen></iframe>
        </div>
      @endif
    </div>

    <div style="padding: 18px 8px 6px;">
      @if($video->category)
        <div style="margin-bottom: 10px;">
          <span class="st-topic-pill st-topic-pill--lavender" style="min-height: 30px; padding: 4px 12px; font-size: 0.8125rem;">
            {{ $video->category->name }}
          </span>
        </div>
      @endif

      <h2 style="margin: 0; font-size: calc(1.5rem * var(--font-scale)); font-weight: 700; color: var(--ink); line-height: 1.3;">
        {{ $video->title }}
      </h2>

      @if($video->description)
        <p style="margin: 10px 0 0; color: var(--ink-soft); font-size: calc(1rem * var(--font-scale)); line-height: 1.5;">
          {{ $video->description }}
        </p>
      @endif
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const repeatBtn = document.getElementById('btn-repeat-video');
    const videoEl = document.getElementById('edu-video-player');
    const iframeEl = document.getElementById('edu-video-iframe');

    if (repeatBtn) {
        repeatBtn.addEventListener('click', function() {
            if (videoEl) {
                videoEl.currentTime = 0;
                videoEl.play();
            } else if (iframeEl) {
                const currentSrc = iframeEl.src;
                iframeEl.src = currentSrc;
            }
        });
    }
});
</script>
@endsection
