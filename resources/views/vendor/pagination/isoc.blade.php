{{-- Template "post-pagination". --}}
@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="mt-5">
        <ul class="post-pagination">
            @unless ($paginator->onFirstPage())
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="post-pagination__prev" aria-label="Previous page"><span class="post-pagination__icon"><i class="fas fa-chevron-left"></i></span></a></li>
            @endunless

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li @class(['active' => $page == $paginator->currentPage()])>
                            <a href="{{ $url }}" @if ($page == $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="post-pagination__next" aria-label="Next page"><span class="post-pagination__icon"><i class="fas fa-chevron-right"></i></span></a></li>
            @endif
        </ul>
    </nav>
@endif
