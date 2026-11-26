@extends('layouts.guest')

@section('title', 'Masuk | Sahabat Belajar SLB B-C Wijayakusumah')

@section('content')
<div class="role-wrap">
  <div class="role-header auth-anim auth-anim--1">
    <h1 class="role-header__title">Selamat Datang</h1>
    <p class="role-header__desc">Pilih masuk sebagai murid atau guru.</p>
  </div>

  <div class="role-grid">
    <a href="{{ route('login.student') }}" class="role-card role-card--student auth-anim auth-anim--2">
      <div class="role-card__cover">
        @include('auth.partials.role-art', ['role' => 'student'])
        <span class="role-card__tag">Murid</span>
      </div>
      <div class="role-card__body">
        <h2 class="role-card__title">Saya Siswa</h2>
        <p class="role-card__desc">Masuk dengan nama pengguna dan angka PIN.</p>
        <div class="role-card__btn">
          <span>Masuk Murid</span>
          <span class="role-card__arrow" aria-hidden="true">&rarr;</span>
        </div>
      </div>
    </a>

    <a href="{{ route('login.teacher') }}" class="role-card role-card--teacher auth-anim auth-anim--3">
      <div class="role-card__cover">
        @include('auth.partials.role-art', ['role' => 'teacher'])
        <span class="role-card__tag">Guru</span>
      </div>
      <div class="role-card__body">
        <h2 class="role-card__title">Saya Guru</h2>
        <p class="role-card__desc">Masuk dengan email dan kata sandi.</p>
        <div class="role-card__btn">
          <span>Masuk Guru</span>
          <span class="role-card__arrow" aria-hidden="true">&rarr;</span>
        </div>
      </div>
    </a>
  </div>
</div>
@endsection
