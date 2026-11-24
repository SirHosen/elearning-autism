@extends('layouts.guest')

@section('title', 'Masuk Guru | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div style="width: 100%; max-width: 440px; background: var(--surface); border: var(--border) solid var(--line); border-radius: var(--radius); padding: 36px 28px; text-align: left;">
  <div style="text-align: center; margin-bottom: 24px;">
    <div style="font-size: 48px; margin-bottom: 8px;">🧑‍🏫</div>
    <h1 style="font-size: 26px; margin-bottom: 6px;">Masuk Panel Guru</h1>
    <p style="color: var(--ink-soft); font-size: 15px;">Kelola materi, video, kuis, dan data siswa.</p>
  </div>

  @if ($errors->has('auth'))
    <div class="feedback-bar feedback-hint" role="alert" style="margin-bottom: 20px;">
      <span>⚠</span> <span>{{ $errors->first('auth') }}</span>
    </div>
  @endif

  <form action="{{ route('login.teacher.submit') }}" method="POST">
    @csrf
    <div style="margin-bottom: 18px;">
      <label for="email" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px; color: var(--ink);">
        Alamat Email
      </label>
      <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
             placeholder="nama@slb.test"
             style="width: 100%; min-height: 48px; font-size: 16px; padding: 0 14px; border: 1px solid var(--line); border-radius: var(--radius-sm); outline: none;">
      @error('email')
        <small style="color: var(--danger); font-size: 13px; font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>
      @enderror
    </div>

    <div style="margin-bottom: 20px;">
      <label for="password" style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px; color: var(--ink);">
        Kata Sandi
      </label>
      <input type="password" id="password" name="password" required
             placeholder="Masukkan kata sandi..."
             style="width: 100%; min-height: 48px; font-size: 16px; padding: 0 14px; border: 1px solid var(--line); border-radius: var(--radius-sm); outline: none;">
      @error('password')
        <small style="color: var(--danger); font-size: 13px; font-weight: 700; display: block; margin-top: 4px;">{{ $message }}</small>
      @enderror
    </div>

    <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
      <input type="checkbox" id="remember" name="remember" style="width: 18px; height: 18px; cursor: pointer;">
      <label for="remember" style="font-size: 14px; color: var(--ink-soft); cursor: pointer;">Ingat saya di perangkat ini</label>
    </div>

    <button type="submit" class="btn btn-primary" style="width: 100%; min-height: 52px; font-size: 16px;">
      Masuk ke Panel Guru <span>→</span>
    </button>
  </form>

  <div style="border-top: 1px solid var(--line); padding-top: 16px; margin-top: 24px; text-align: center;">
    <a href="{{ route('login.student') }}" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 14px;">
      ← Kembali ke Masuk Siswa
    </a>
  </div>
</div>
@endsection
