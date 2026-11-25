@extends('layouts.student')

@section('title', 'Kategori Kosong | Sahabat Belajar')
@section('page_title', $category->name)

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
<div class="st-empty-card">
  <div style="width: 140px; height: 140px; margin: 0 auto 20px;" aria-hidden="true">
    <svg viewBox="0 0 140 140" width="140" height="140" fill="none">
      <circle cx="70" cy="70" r="60" fill="#DCEBF7"/>
      <rect x="34" y="42" width="72" height="58" rx="14" fill="#FFFFFF" stroke="#2F6F9F" stroke-width="4"/>
      <path d="M54 64 Q60 68 64 64 M76 64 Q82 68 86 64" stroke="#2F6F9F" stroke-width="3.5" stroke-linecap="round"/>
      <path d="M62 78 Q70 84 78 78" stroke="#2F6F9F" stroke-width="3.5" stroke-linecap="round"/>
      <rect x="46" y="34" width="48" height="10" rx="5" fill="#FBBF24"/>
    </svg>
  </div>

  <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--ink); margin: 0 0 10px;">
    Materi Belum Tersedia
  </h2>
  <p style="color: var(--ink-soft); font-size: calc(1rem * var(--font-scale)); margin: 0 0 24px; line-height: 1.5;">
    {{ __('ui.empty_content') }}
  </p>
  <a href="{{ route('student.learn.index') }}" class="btn btn-student btn-primary" style="border-radius: 999px;">
    Lihat Kategori Lain &rarr;
  </a>
</div>
@endsection
