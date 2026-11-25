@extends('layouts.guest')

@section('title', 'Masuk Panel Guru | EduAutis SLB B-C Wijayakusumah')

@section('content')
<div class="auth-shell">

  {{-- Panel Kiri: Identitas Ruang Kerja Guru --}}
  <aside class="auth-aside auth-aside--pine">
    <div>
      <span class="auth-aside__eyebrow">Ruang Kerja Pendidik</span>
      <h2 class="auth-aside__title">Kelola Pembelajaran Secara Terstruktur</h2>
      <p class="auth-aside__lead">
        Pusat kendali guru dan administrator untuk menyusun materi ajar, memantau kehadiran, dan mengevaluasi capaian belajar siswa.
      </p>
    </div>

    <div class="auth-preview-box">
      <div class="auth-preview-box__label">Modul Manajemen Panel</div>
      <ul class="auth-preview-list">
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--green" aria-hidden="true"></span>
            <span>Materi Foto, Audio &amp; Video</span>
          </span>
          <span class="auth-preview-num">Aktif</span>
        </li>
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--amber" aria-hidden="true"></span>
            <span>Generator Kuis &amp; Kartu PIN</span>
          </span>
          <span class="auth-preview-num">Aktif</span>
        </li>
        <li class="auth-preview-item">
          <span class="auth-preview-item__left">
            <span class="auth-swatch auth-swatch--blue" aria-hidden="true"></span>
            <span>Rekap Absensi &amp; Cetak Rapor</span>
          </span>
          <span class="auth-preview-num">CSV / Cetak</span>
        </li>
      </ul>
    </div>

    <div class="auth-aside__meta">
      <span>Akses Terproteksi Role Admin</span>
      <span>SLB B-C Wijayakusumah</span>
    </div>
  </aside>

  {{-- Panel Kanan: Formulir Masuk Guru --}}
  <section class="auth-panel" aria-labelledby="teacher-login-heading">
    <nav class="auth-tabs" aria-label="Navigasi Halaman Masuk">
      <a href="{{ route('login') }}" class="auth-tab">Pilih Peran</a>
      <a href="{{ route('login.student') }}" class="auth-tab">Masuk Siswa</a>
      <a href="{{ route('login.teacher') }}" class="auth-tab is-active is-active--green" aria-current="page">Masuk Guru</a>
    </nav>

    <div class="auth-head">
      <h1 id="teacher-login-heading" class="auth-head__title">Masuk Panel Guru</h1>
      <p class="auth-head__desc">Gunakan alamat email dan kata sandi akun pendidik Anda.</p>
    </div>

    @if ($errors->has('auth'))
      <div class="auth-alert" role="alert">
        <span class="auth-alert__dot" aria-hidden="true">!</span>
        <span>{{ $errors->first('auth') }}</span>
      </div>
    @endif

    <form action="{{ route('login.teacher.submit') }}" method="POST" class="auth-form">
      @csrf

      <div class="auth-field">
        <label for="email" class="auth-label">Alamat Email</label>
        <input
          type="email"
          id="email"
          name="email"
          value="{{ old('email') }}"
          required
          autofocus
          autocomplete="email"
          placeholder="guru@slb.test"
          class="auth-input auth-input--green @error('email') is-invalid @enderror"
          @error('email') aria-describedby="email-error" @enderror
        >
        @error('email')
          <span id="email-error" class="auth-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="auth-field">
        <label for="password" class="auth-label">Kata Sandi</label>
        <div class="auth-input-wrap">
          <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="current-password"
            placeholder="Masukkan kata sandi"
            class="auth-input auth-input--green auth-input--has-action @error('password') is-invalid @enderror"
            @error('password') aria-describedby="password-error" @enderror
          >
          <button type="button" id="togglePassword" class="auth-pw-toggle" aria-pressed="false" aria-label="Tampilkan atau sembunyikan kata sandi">
            Lihat
          </button>
        </div>
        @error('password')
          <span id="password-error" class="auth-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="auth-check-row">
        <label for="remember" class="auth-check-label">
          <input type="checkbox" id="remember" name="remember">
          <span>Ingat sesi di perangkat ini</span>
        </label>
        <span class="auth-meta-note">Sesi terenkripsi</span>
      </div>

      <button type="submit" class="auth-submit auth-submit--green">
        <span>Masuk ke Panel Guru</span>
        <span aria-hidden="true">&rarr;</span>
      </button>
    </form>

    <div class="auth-helper-note">
      Bantuan akun: Apabila lupa kata sandi, silakan hubungi operator sekolah untuk pembaruan kredensial admin.
    </div>
  </section>

</div>
@endsection

@push('scripts')
<script>
  const passwordInput = document.getElementById('password');
  const toggleBtn = document.getElementById('togglePassword');

  if (passwordInput && toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const showing = passwordInput.type === 'text';
      passwordInput.type = showing ? 'password' : 'text';
      toggleBtn.textContent = showing ? 'Lihat' : 'Tutup';
      toggleBtn.setAttribute('aria-pressed', showing ? 'false' : 'true');
    });
  }
</script>
@endpush
