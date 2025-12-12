@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <div class="d-flex align-items-center justify-content-center">
            <ul class="pagination mb-0">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Prev</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prev</a>
                    </li>
                @endif

                {{-- First Page --}}
                @if ($paginator->currentPage() > 3)
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                    </li>
                    @if ($paginator->currentPage() > 4)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endif

                {{-- Page Numbers --}}
                @for ($i = max(1, $paginator->currentPage() - 2); $i <= min($paginator->lastPage(), $paginator->currentPage() + 2); $i++)
                    <li class="page-item {{ $i == $paginator->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Last Page --}}
                @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                    @if ($paginator->currentPage() < $paginator->lastPage() - 3)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Next</span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <style>
        .pagination .page-link {
            color: #696969;
            padding: 10.83px 15.83px;
            margin: 0 2px;
            border-radius: 10.67px;
            font-family: Inter;
            font-weight: 400;
            font-size: 17.33px;
            line-height: 100%;
            border: 1.33px solid #E1E0E0;
        }

        .pagination .page-item.active .page-link {
            border: 1.33px solid #37488E;
            background: #37488E14;
        }

        .pagination .page-item.disabled .page-link {
            background-color: transparent;
            border-color: #dee2e6;
            color: #6c757d;
        }

        .pagination .page-link:hover:not(.disabled) {
            border: 1.33px solid #37488E;
            background: #37488E14;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border: none;
            background: #FFFFFF;
        }
    </style>
@endif
