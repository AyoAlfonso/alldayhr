@if ($paginator->hasPages())
    <ul class="pagination" role="navigation" style="float: right;margin-bottom: 1%;margin-right: 5%;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="cd-pg-btn-lg disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">

           Prev </button>
        @else
         
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" >
               <button class="cd-pg-btn-lg" style="font-size: 12px; color: rgba(38,38,38,0.4);font-family: Ubuntu-l;">
                 Prev
                </button>
                </a>
              
        @endif

        <div class="cd-pg-btn-mg">
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                {{-- <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li> --}}
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="cd-pg-btn-sm" aria-current="page">
                        {{-- <span class="cd-pg-btn-sm"> --}}
                        {{ $page }}
                        {{-- </span> --}}
                        </button>
                    @else
                        {{-- <li class="page-item"> --}}
                        <a href="{{ $url }}">
                     
                        <span>   {{ $page }} </span>
                        </a>
                        {{-- </li> --}}
                    @endif
                @endforeach
            @endif
        @endforeach
        </div>
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())

             <a href="{{ $paginator->nextPageUrl() }}" rel="next">
             <button class="cd-pg-btn-lg" style="font-size: 12px; color: rgba(38,38,38,0.4);font-family: Ubuntu-l;">
             Next
            </button>
             </a>
         
        @else
            <button class="cd-pg-btn-lg disabled" aria-disabled="true" aria-label="@lang('pagination.next')">

           Next </button>
        @endif
    </ul>
@endif



