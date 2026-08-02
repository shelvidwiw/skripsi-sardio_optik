@if ($paginator->hasPages())
    <nav class="so-pagination__nav" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="so-page-btn so-page-btn--disabled">&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="so-page-btn">&lsaquo;</a>
        @endif

        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $window = 1;

            $pages = collect();
            for ($p = 1; $p <= $last; $p++) {
                if ($p === 1 || $p === $last || ($p >= $current - $window && $p <= $current + $window)) {
                    $pages->push($p);
                }
            }
        @endphp

        @php $previous = null; @endphp
        @foreach ($pages as $page)
            @if ($previous !== null && $page - $previous > 1)
                <span class="so-page-btn so-page-btn--dots">...</span>
            @endif

            @if ($page === $current)
                <span class="so-page-btn so-page-btn--active">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" class="so-page-btn">{{ $page }}</a>
            @endif

            @php $previous = $page; @endphp
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="so-page-btn">&rsaquo;</a>
        @else
            <span class="so-page-btn so-page-btn--disabled">&rsaquo;</span>
        @endif
    </nav>
@endif
