@extends('layouts.student')

@section('title', 'Video Edukasi')

@section('content')
<div class="container" style="padding-top: var(--space-6); padding-bottom: var(--space-10);">
    {{-- Header & Navigasi Kembali --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
        <a href="{{ route('student.home') }}" class="btn btn-secondary" style="font-size: 1.125rem; font-weight: 600; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
            <span>&larr;</span> Kembali
        </a>
        <h1 style="margin: 0; font-size: 1.75rem; color: var(--text);">Video Edukasi</h1>
    </div>

    @if($videos->isEmpty())
        <div class="card" style="text-align: center; padding: var(--space-10); max-width: 500px; margin: 0 auto;">
            <div style="font-size: 3rem; margin-bottom: var(--space-2);">🎬</div>
            <h2 style="font-size: 1.25rem; margin-bottom: var(--space-2);">Belum Ada Video</h2>
            <p style="color: var(--text-muted); margin-bottom: var(--space-6);">Bapak/Ibu guru akan menambahkan video pembelajaran di sini segera.</p>
            <a href="{{ route('student.home') }}" class="btn btn-primary" style="border-radius: var(--radius-full);">Kembali ke Menu Utama</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: var(--space-6);">
            @foreach($videos as $video)
                <a href="{{ route('student.videos.show', $video) }}" class="card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; overflow: hidden; padding: 0; transition: transform var(--transition-base), box-shadow var(--transition-base); border-radius: var(--radius-lg);" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: var(--surface-alt); overflow: hidden;">
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='{{ asset('images/icons/video-placeholder.svg') }}'">
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.18);">
                            <div style="width: 54px; height: 54px; border-radius: 50%; background: rgba(255,255,255,0.92); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-md);">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="#1D4ED8">
                                    <polygon points="8,5 19,12 8,19"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div style="padding: var(--space-4); flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            @if($video->category)
                                <span class="badge" style="background: var(--surface-alt); font-size: 0.75rem; margin-bottom: var(--space-2); display: inline-block;">
                                    {{ $video->category->name }}
                                </span>
                            @endif
                            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--text); line-height: 1.35;">
                                {{ $video->title }}
                            </h3>
                        </div>
                        <div style="margin-top: var(--space-3); color: var(--primary); font-weight: 600; font-size: 0.9375rem; display: flex; align-items: center; gap: var(--space-1);">
                            <span>Tonton Video</span> &rarr;
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
