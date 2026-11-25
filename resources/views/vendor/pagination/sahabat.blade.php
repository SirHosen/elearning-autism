@if ($paginator->hasPages())
  <nav class="adm-pagination" role="navigation" aria-label="Navigasi halaman">
    <div class="adm-pagination__summary">
      Menampilkan <strong>{{ $paginator->firstItem() ?? 0 }}</strong>&ndash;<strong>{{ $paginator->lastItem() ?? 0 }}</strong> dari <strong>{{ $paginator->total() }}</strong> data
    </div>

    <ul class="adm-pagination__list">
      {{-- Halaman Sebelumnya --}}
      @if ($paginator->onFirstPage())
        <li>
          <span class="adm-page-btn adm-page-btn--disabled" aria-disabled="true" aria-label="Halaman sebelumnya">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="15 18 9 12 15 6"/>
            </svg>
            <span>Sebelumnya</span>
          </span>
        </li>
      @else
        <li>
          <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="adm-page-btn" aria-label="Halaman sebelumnya">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="15 18 9 12 15 6"/>
            </svg>
            <span>Sebelumnya</span>
          </a>
        </li>
      @endif

      {{-- Nomor Halaman --}}
      @foreach ($elements as $element)
        @if (is_string($element))
          <li><span class="adm-page-dots" aria-disabled="true">{{ $element }}</span></li>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li>
                <span class="adm-page-btn adm-page-btn--active" aria-current="page">{{ $page }}</span>
              </li>
            @else
              <li>
                <a href="{{ $url }}" class="adm-page-btn" aria-label="Halaman {{ $page }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Halaman Berikutnya --}}
      @if ($paginator->hasMorePages())
        <li>
          <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="adm-page-btn" aria-label="Halaman berikutnya">
            <span>Berikutnya</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </a>
        </li>
      @else
        <li>
          <span class="adm-page-btn adm-page-btn--disabled" aria-disabled="true" aria-label="Halaman berikutnya">
            <span>Berikutnya</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </span>
        </li>
      @endif
    </ul>
  </nav>
@endif
