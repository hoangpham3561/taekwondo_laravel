@if ($paginator->hasPages())
    <div class="col-12 col-md-5 mb-2 mb-md-0">
        <div class="dataTables_info text-muted" role="status" aria-live="polite">
            Trang <strong>{{ $paginator->currentPage() }}</strong> / <strong>{{ $paginator->lastPage() }}</strong>
        </div>
    </div>
    <div class="col-12 col-md-7">
        <div class="d-flex justify-content-md-end">
            <ul class="pagination pagination-sm flex-wrap mb-0 user-pagination">
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link px-3" href="{{ $paginator->onFirstPage() ? 'javascript:void(0)' : $paginator->url(1) }}" aria-label="First">
                        Đầu
                    </a>
                </li>
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginator->onFirstPage() ? 'javascript:void(0)' : $paginator->previousPageUrl() }}" aria-label="Previous">
                        ‹
                    </a>
                </li>

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                                @if ($page == $paginator->currentPage())
                                    <span class="page-link">{{ $page }}</span>
                                @else
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endforeach

                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : 'javascript:void(0)' }}" aria-label="Next">
                        ›
                    </a>
                </li>
                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link px-3" href="{{ $paginator->hasMorePages() ? $paginator->url($paginator->lastPage()) : 'javascript:void(0)' }}" aria-label="Last">
                        Cuối
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endif
