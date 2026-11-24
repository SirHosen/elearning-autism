@extends('layouts.student')

@section('title', 'Kategori Kosong | EduAutis')
@section('page_title', $category->name)

@section('header_left')
  <a href="{{ route('student.learn.index') }}" class="btn btn-secondary" style="min-height: 48px; padding: 0 16px;">
    <span>←</span> <span>Kategori</span>
  </a>
@endsection

@section('content')
<div style="max-width: 560px; margin: 40px auto; background: var(--surface); border: var(--border) solid var(--line); border-radius: var(--radius); padding: 48px 32px; text-align: center;">
  <div style="font-size: 64px; margin-bottom: 16px;">🧩</div>
  <h2 style="font-size: 24px; margin-bottom: 8px;">Materi Belum Tersedia</h2>
  <p style="color: var(--ink-soft); font-size: var(--fs-base); margin-bottom: 24px;">
    {{ __('ui.empty_content') }}
  </p>
  <a href="{{ route('student.learn.index') }}" class="btn btn-student btn-primary">
    Lihat Kategori Lain <span>→</span>
  </a>
</div>
@endsection
