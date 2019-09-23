
@if ($paginator->hasPages())
    <ul class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="tt-delivery-pagination"> < </span>
            </li>
        @else
            <li class="page-item">
                <a class="tt-delivery-pagination" id="tt-previous" data-datac="{{ $paginator->previousPageUrl() }}" rel="prev"> < </a>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="tt-delivery-pagination" id="tt-next" data-datac="{{ $paginator->nextPageUrl() }}" rel="next"> > </a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="tt-delivery-pagination"> > </span>
            </li>
        @endif
    </ul>
@endif