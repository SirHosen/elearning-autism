<div class="login-card-topbar">
  <a href="{{ route('login') }}" class="login-back-btn">
    <svg class="login-back-btn__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
    <span>Kembali</span>
  </a>

  <span class="login-role-badge login-role-badge--{{ $variant ?? 'student' }}">
    <span class="login-role-badge__dot" aria-hidden="true"></span>
    <span>{{ $badge ?? 'Murid' }}</span>
  </span>
</div>
