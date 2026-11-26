@props(['href', 'label'])
<a href="{{ $href }}" class="adm-btn adm-btn--secondary adm-btn--sm adm-back-link">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <line x1="19" y1="12" x2="5" y2="12"/>
    <polyline points="12 19 5 12 12 5"/>
  </svg>
  <span>{{ $label }}</span>
</a>
