@if ($paginator->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination pagination-sm mg-b-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <a class="page-link page-link-icon" href="#">
                        <span class="fa fa-chevron-left lnr lnr-chevron-left"></span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link page-link-icon" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" >
                        <span class="fa fa-chevron-left lnr lnr-chevron-left"></span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true">
                        <a class="page-link" href="#">
                            <span>{{ $element }}</span>
                        </a>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">
                                    <span>{{ $page }}</span>
                                </a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link page-link-icon" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <span class="fa fa-chevron-right lnr lnr-chevron-right"></span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <a class="page-link page-link-icon" href="#">
                        <span class="fa fa-chevron-right lnr lnr-chevron-right"></span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif



        
            {{-- <li class="page-item disabled"><a class="page-link page-link-icon" href="#"><i
                        data-feather="chevron-left"></i></a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link page-link-icon" href="#"><i
                        data-feather="chevron-right"></i></a></li>
        </ul>
    </nav> --}}