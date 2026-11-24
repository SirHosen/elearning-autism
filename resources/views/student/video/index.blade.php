@extends('layouts.student')

@section('title', 'Video Edukasi | EduAutis')
@section('page_title', 'Video Edukasi')

@section('content')
<div style="max-width: 960px; margin: 0 auto;">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
    <a href="{{ route('student.home') }}" class="btn btn-secondary" style="font-size: 0.9375rem; font-weight: 700; padding: 10px 20px; border-radius: 999px;">
      &larr; Kembali ke Beranda
    </a>
    <span class="badge" style="background: var(--tint-lavender); color: #6D28D9; font-size: 0.8125rem; padding: 6px 14px;">
      Video Pembelajaran
    </span>
  </div>

  @if($videos->isEmpty())
    <div class="card" style="text-align: center; padding: 48px 24px; max-width: 500px; margin: 0 auto;">
      <h2 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 8px;">Belum Ada Video</h2>
      <p style="color: var(--ink-soft); margin-bottom: 24px;">Bapak/Ibu guru akan menambahkan video pembelajaran di sini segera.</p>
      <a href="{{ route('student.home') }}" class="btn btn-primary" style="border-radius: 999px;">Kembali ke Menu Utama</a>
    </div>
  @else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
      @foreach($videos as $video)
        <a href="{{ route('student.videos.show', $video) }}" class="card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; overflow: hidden; padding: 0; border-radius: 16px; border-top: 4px solid #7C3AED; transition: transform 0.2s ease, box-shadow 0.2s ease;">
          <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: var(--surface-alt); overflow: hidden; display: grid; place-items: center;">
            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/images/icons/video-placeholder.svg'">
            <div style="position: absolute; width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.95); display: grid; place-items: center; box-shadow: var(--shadow-sm); font-weight: 800; color: #7C3AED; font-size: 1.125rem;">
              &blacktriangleright;
            </div>
          </div>
          <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              @if($video->category)
                <span class="badge badge-neutral" style="font-size: 0.75rem; margin-bottom: 8px;">
                  {{ $video->category->name }}
                </span>
              @endif
              <h3 style="margin: 0; font-size: 1.125rem; font-weight: 800; color: var(--text); line-height: 1.35;">
                {{ $video->title }}
              </h3>
            </div>
            <div style="margin-top: 16px; color: #7C3AED; font-weight: 800; font-size: 0.9375rem; display: flex; align-items: center; justify-content: space-between;">
              <span>Tonton Video</span>
              <span>&rarr;</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
