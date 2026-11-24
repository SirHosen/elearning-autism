@extends('layouts.student')

@section('title', 'Pengaturan | EduAutis')
@section('page_title', 'Pengaturan Tampilan & Suara')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: var(--surface); border: var(--border) solid var(--line); border-radius: var(--radius); padding: 32px;">
  <h2 style="font-size: 24px; margin-bottom: 24px;">Atur Kenyamananmu</h2>
  <p style="color: var(--ink-soft); font-size: var(--fs-base); margin-bottom: 24px;">
    Kamu bisa menyesuaikan suara, ukuran huruf, dan warna agar nyaman dipakai.
  </p>
  <a href="{{ route('student.home') }}" class="btn btn-student btn-primary">Kembali ke Beranda ⌂</a>
</div>
@endsection
