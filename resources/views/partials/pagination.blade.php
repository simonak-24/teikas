<div id="pagination-links">
    @if($paginator->lastPage() > 1) 
    @if($paginator->currentPage() != 1)
        <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url(1) }}"> << </a></div>
        <div class="pagination-button"><a href="{{ $paginator->withQueryString()->previousPageUrl() }}"> < </a></div>
    @endif
    @if($paginator->lastPage() <= app('number_of_pages'))
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <div class="pagination-button {{ $paginator->currentPage() == $i ? 'pagination-selected' : '' }}"> @if($paginator->currentPage() != $i) <a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a> @else <span> {{ $i }} </span> @endif </div>
        @endfor
    @else
        @if($paginator->currentPage() <= (int)(app('number_of_pages') / 2) + 1)
            @for ($i = 1; $i <= app('number_of_pages'); $i++)
                <div class="pagination-button {{ $paginator->currentPage() == $i ? 'pagination-selected' : '' }}"> @if($paginator->currentPage() != $i) <a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a> @else <span> {{ $i }} </span> @endif </div>
            @endfor
        @elseif($paginator->currentPage() + (int)(app('number_of_pages') / 2) + 1 > $paginator->lastPage())
            @for ($i = $paginator->lastPage() - app('number_of_pages'); $i <= $paginator->lastPage(); $i++)
                <div class="pagination-button {{ $paginator->currentPage() == $i ? 'pagination-selected' : '' }}"> @if($paginator->currentPage() != $i) <a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a> @else <span> {{ $i }} </span> @endif </div>
            @endfor
        @else
            @for ($i = $paginator->currentPage() - (int)(app('number_of_pages') / 2); $i <= $paginator->currentPage() + (int)(app('number_of_pages') / 2); $i++)
                <div class="pagination-button {{ $paginator->currentPage() == $i ? 'pagination-selected' : '' }}"> @if($paginator->currentPage() != $i) <a href="{{ $paginator->withQueryString()->url($i) }}"> {{ $i }} </a> @else <span> {{ $i }} </span> @endif </div>
            @endfor
        @endif
    @endif
    @if($paginator->currentPage() != $paginator->lastPage())
        <div class="pagination-button"><a href="{{ $paginator->withQueryString()->nextPageUrl() }}"> > </a></div>
        <div class="pagination-button"><a href="{{ $paginator->withQueryString()->url($paginator->lastPage()) }}"> >> </a></div>
    @endif
    @endif
    @if($paginator->total() > 0)
    <div id="pagination-text">{{ __('site.pagination_count', ['current' => $paginator->firstItem().' - '.($paginator->firstItem() + $paginator->count() - 1), 'all' => $paginator->total() ]) }}</div>
    @endif
</div>