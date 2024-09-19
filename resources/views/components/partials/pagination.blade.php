<ul class="pagination-list">
    <li class="pagination-item pagination-item-prev">
        <a href="{{ $paginator->previousPageUrl() }}">Назад</a>
    </li>
    
    @foreach ($paginator->links()->elements as $element)
        @if (is_string($element))
            <li class="pagination-item disabled"><span>{{ $element }}</span></li>
        @endif
        
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="pagination-item pagination-item-active"><a>{{ $page }}</a></li>
                @else
                    <li class="pagination-item"><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach
    
    <li class="pagination-item pagination-item-next">
        <a href="{{ $paginator->nextPageUrl() }}">Вперед</a>
    </li>
</ul>
