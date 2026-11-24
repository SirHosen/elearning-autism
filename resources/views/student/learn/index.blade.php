@extends('layouts.student')

@section('title', 'Pilih Kategori Belajar | EduAutis')
@section('page_title', 'Pilih Kategori Belajar')

@section('content')
<div style="text-align: center; margin-bottom: 36px;">
  <p style="font-size: 0.75rem; font-weight: 800; letter-spacing: 1.5px; color: var(--primary); text-transform: uppercase; margin-bottom: 6px;">
    Materi Pembelajaran
  </p>
  <h2 style="font-size: clamp(1.625rem, 3.5vw, 2.125rem); font-weight: 800; margin-bottom: 8px; letter-spacing: -0.4px;">
    Mau belajar apa hari ini?
  </h2>
  <p style="color: var(--ink-soft); font-size: 1.0625rem; max-width: 500px; margin: 0 auto;">
    Pilih kategori gambar dan dengarkan suaranya.
  </p>
</div>

<div class="tile-grid">
  @forelse ($categories as $cat)
    <a href="{{ route('student.learn.category', $cat->slug) }}" class="tile tile-{{ $cat->color_key ?? 'blue' }}">
      <div class="tile-monogram">
        @if ($cat->slug === 'hewan') H
        @elseif ($cat->slug === 'warna') W
        @elseif ($cat->slug === 'benda') B
        @elseif ($cat->slug === 'huruf') Aa
        @elseif ($cat->slug === 'angka') 123
        @else {{ strtoupper(substr($cat->name, 0, 1)) }}
        @endif
      </div>
      <span class="tile-label">{{ $cat->name }}</span>
      <small style="color: var(--ink-soft); font-size: 0.875rem; margin-top: 4px; font-weight: 600;">
        {{ $cat->materials_count }} materi tersedia
      </small>
    </a>
  @empty
    <div style="grid-column: 1/-1; background: var(--surface); padding: 48px; border: 1px solid var(--line); border-radius: var(--radius); text-align: center;">
      <p style="font-size: var(--fs-label); color: var(--ink-soft);">{{ __('ui.empty_content') }}</p>
    </div>
  @endforelse
</div>
@endsection
