@if($paginator->lastPage() > 1)      
<div id="pagination-links">
    <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url(1) }}"> << </a></div>
    <div class="pagination-button"><a href="{{ $paginator->withQueryString()->previousPageUrl() }}"> < </a></div>
    @if($paginator->lastPage() <= 9)
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a></div>
        @endfor
    @else
        @if($paginator->currentPage() <= 5)
            @for ($i = 1; $i <= 9; $i++)
                <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a></div>
            @endfor
        @elseif($paginator->currentPage() + 5 > $paginator->lastPage())
            @for ($i = $paginator->lastPage() - 9; $i <= $paginator->lastPage(); $i++)
                <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a></div>
            @endfor
        @else
            @for ($i = $paginator->currentPage() - 4; $i <= $paginator->currentPage() + 4; $i++)
                <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a></div>
            @endfor
        @endif
    @endif
    <div class="pagination-button"><a href="{{ $paginator->withQueryString()->nextPageUrl() }}"> > </a></div>
    <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url($paginator->lastPage()) }}"> >> </a></div>
</div>
@endif