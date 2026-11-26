@extends('layouts.student')

@section('title', 'Video Edukasi | Sahabat Belajar')
@section('page_title', 'Video Edukasi')

@section('content')
<div class="st-page-shell">
  <div class="st-page-bar st-anim st-anim--1">
    <a href="{{ route('student.home') }}" class="st-back-pill">
      @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-back-pill__icon'])
      <span>Kembali ke Beranda</span>
    </a>

    <span class="st-topic-pill st-topic-pill--lavender">
      Video Pembelajaran
    </span>
  </div>

  <div class="st-intro-banner st-anim st-anim--2">
    <div class="st-intro-banner__text">
      <h2>Nonton Video Edukasi</h2>
      <p>Tekan salah satu video di bawah untuk memutar cerita atau lagu belajar.</p>
    </div>
    <div class="st-intro-banner__art" style="background: var(--tint-lavender);" aria-hidden="true">
      <svg viewBox="0 0 80 80" width="60" height="60" fill="none">
        <rect x="12" y="20" width="56" height="40" rx="10" fill="#5B21B6"/>
        <rect x="17" y="25" width="46" height="30" rx="6" fill="#FFFFFF"/>
        <polygon points="35,32 51,40 35,48" fill="#5B21B6"/>
      </svg>
    </div>
  </div>

  @if($videos->isEmpty())
    <div class="st-empty-card">
      <h2 style="font-size: 1.375rem; font-weight: 700; margin: 0 0 8px;">Belum Ada Video</h2>
      <p style="color: var(--ink-soft); margin: 0 0 24px;">Bapak/Ibu guru akan menambahkan video pembelajaran di sini segera.</p>
      <a href="{{ route('student.home') }}" class="btn btn-student btn-primary st-pill-cta">
        @include('student.partials.icon-arrow', ['dir' => 'left', 'size' => 18, 'class' => 'st-btn-arrow st-btn-arrow--left'])
        <span>Kembali ke Menu Utama</span>
      </a>
    </div>
  @else
    <div class="st-hub-grid">
      @foreach($videos as $video)
        <a href="{{ route('student.videos.show', $video) }}" class="st-hub-card st-hub-card--lavender">
          <div class="st-video-thumb-wrap">
            <img src="{{ $video->thumbnail_url }}"
                 alt="{{ $video->title }}"
                 class="st-video-thumb-img"
                 onerror="this.src='/images/icons/video-placeholder.svg'">
            <div class="st-video-play-orb" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="7 4 20 12 7 20"/>
              </svg>
            </div>
          </div>

          @if($video->category)
            <div style="margin-bottom: 10px;">
              <span class="st-topic-pill st-topic-pill--blue" style="min-height: 28px; padding: 3px 10px; font-size: 0.75rem;">
                {{ $video->category->name }}
              </span>
            </div>
          @endif

          <h3 class="st-hub-card__title">{{ $video->title }}</h3>

          <div class="st-hub-card__footer">
            <span class="st-hub-card__meta">Putar Manual</span>
            <span class="st-hub-card__cta">
              <span>Tonton Video</span>
              @include('student.partials.icon-arrow', ['size' => 15])
            </span>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
