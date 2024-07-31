@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-start">
        {{-- Double Left Arrow Button --}}
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-arrow" style="text-decoration: none;">«</a>
        @else
            <span class="pagination-arrow disabled" style="text-decoration: none;">«</span>
        @endif

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-previous disabled">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous">Previous</a>
        @endif

        {{-- Page Number Links --}}
        <ul class="pagination-list" style="display: inline; padding-left: 5px; padding-right: 5px;">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li style="display: inline;"><span class="pagination-ellipsis">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li style="display: inline; padding-left: 5px; padding-right: 5px;"><span
                                    class="pagination-link is-current">{{ $page }}</span></li>
                        @else
                            <li style="display: inline; padding-left: 5px; padding-right: 5px;"><a
                                    href="{{ $url }}" class="pagination-link">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next">Next</a>
        @else
            <span class="pagination-next disabled">Next</span>
        @endif

        {{-- Double Right Arrow Button --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-arrow" style="text-decoration: none;">»</a>
        @else
            <span class="pagination-arrow disabled" style="text-decoration: none;">»</span>
        @endif
    </nav>

    <div>
        <p>Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </p>
    </div>
@endif
