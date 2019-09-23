@if ($paginator->hasPages())
    <ul class="pagination" role="navigation">
        @if ($paginator->onFirstPage())
         
             <button class="btn disabled">Show previous jobs</button>
        @else
         
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" >
               
            <button class="btn">Show previous jobs</button>
                </a>
              
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
               @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button aria-current="page">
                        {{ $page }}
                        </button>
                        
                    @else
                        
                        <a href="{{ $url }}">
                        <span style="color: #3f36be;font-weight: bold;">   {{ $page }} </span>
                        </a>
                    
                    @endif
                @endforeach
            @endif
        @endforeach
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())

             <a href="{{ $paginator->nextPageUrl() }}" rel="next">
                <button class="btn">Show more jobs</button>
             </a>
         
        @else
             <button class="btn disabled">Show more jobs</button>
        @endif
    </ul>
@endif



