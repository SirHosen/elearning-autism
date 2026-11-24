@extends('layouts.student')

@section('title', $video->title)

@section('content')
<div class="container" style="max-width: 900px; padding-top: var(--space-6); padding-bottom: var(--space-10);">
    {{-- Header & Tombol Kembali Besar --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
        <a href="{{ route('student.videos.index') }}" class="btn btn-secondary" style="font-size: 1.125rem; font-weight: 600; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
            <span>&larr;</span> Kembali
        </a>
        <div style="display: flex; align-items: center; gap: var(--space-3);">
            <button id="btn-repeat-video" type="button" class="btn btn-primary" style="font-size: 1rem; font-weight: 600; padding: var(--space-3) var(--space-5); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                </svg>
                <span>Ulangi Video</span>
            </button>
        </div>
    </div>

    {{-- Kartu Video Player --}}
    <div class="card" style="padding: var(--space-4); border-radius: var(--radius-lg); background: var(--surface);">
        <div style="width: 100%; border-radius: var(--radius-md); overflow: hidden; background: #000000;">
            @if($video->source_type === 'upload')
                <video id="edu-video-player" controls playsinline preload="metadata" style="width: 100%; max-height: 520px; display: block; border-radius: var(--radius-md);">
                    <source src="{{ $video->stream_url }}" type="video/mp4">
                    Browser Anda tidak mendukung pemutar video HTML5.
                </video>
            @else
                <div style="position: relative; width: 100%; aspect-ratio: 16/9;">
                    <iframe id="edu-video-iframe" src="{{ $video->youtube_embed_url }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            @endif
        </div>

        <div style="padding: var(--space-4) var(--space-2) var(--space-2) var(--space-2);">
            @if($video->category)
                <span class="badge" style="background: var(--surface-alt); font-size: 0.8125rem; margin-bottom: var(--space-2); display: inline-block;">
                    {{ $video->category->name }}
                </span>
            @endif
            <h1 style="margin: 0; font-size: 1.5rem; color: var(--text); line-height: 1.35;">{{ $video->title }}</h1>
            @if($video->description)
                <p style="margin: var(--space-3) 0 0 0; color: var(--text-muted); font-size: 1rem; line-height: 1.5;">
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
