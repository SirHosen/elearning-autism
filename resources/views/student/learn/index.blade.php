@extends('layouts.student')

@section('title', 'Pilih Kategori Belajar | EduAutis')
@section('page_title', 'Pilih Kategori Belajar')

@section('content')
<div style="text-align: center; margin-bottom: 32px;">
  <p style="font-size: 13px; font-weight: 800; letter-spacing: 2px; color: var(--primary); margin-bottom: 6px;">MATERI PEMBELAJARAN</p>
  <h2 style="font-size: var(--fs-title); margin-bottom: 8px;">Mau belajar apa hari ini? 🎈</h2>
  <p style="color: var(--ink-soft); font-size: var(--fs-base);">Pilih kategori gambar dan dengarkan suaranya.</p>
</div>

<div class="tile-grid">
  @forelse ($categories as $cat)
    <a href="{{ route('student.learn.category', $cat->slug) }}" class="tile tile-{{ $cat->color_key }}">
      <span class="tile-icon">
        @if ($cat->slug === 'hewan') 🐾
        @elseif ($cat->slug === 'warna') 🎨
        @elseif ($cat->slug === 'benda') 🧸
        @elseif ($cat->slug === 'huruf') 🔤
        @elseif ($cat->slug === 'angka') 🔢
        @else 🌟
        @endif
      </span>
      <span class="tile-label">{{ $cat->name }}</span>
      <small style="color: var(--ink-soft); font-size: 14px; margin-top: 6px;">
        {{ $cat->materials_count }} materi tersedia
      </small>
    </a>
  @empty
    <div style="grid-column: 1/-1; background: var(--surface); padding: 48px; border: var(--border) solid var(--line); border-radius: var(--radius); text-align: center;">
      <p style="font-size: var(--fs-label); color: var(--ink-soft);">{{ __('ui.empty_content') }}</p>
    </div>
  @endforelse
</div>
@endsection
