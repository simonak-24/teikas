@extends('site')

@section('title', __('site.title_search'))

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/expand-search.js') }}"></script> 
@endsection

@section('content')
    @if($item != "")<p>{{ __('site.search_fragment-1') }}<b>{{ $item }}</b>{{ __('site.search_fragment-2') }}</p>@endif
    <h3>{{ __('resources.legend_all') }}</h3>
    @include('legends.table')
    <br>
    <a id="global-link-legends" class="search-link">{{ __('site.search_more') }}</a>
    <hr>
    <h3>{{ __('resources.collector_all') }}</h3>
    @include('collectors.table')
    <br>
    <a id="global-link-collectors" class="search-link" href="{{ route('collectors.index') }}">{{ __('site.search_more') }}</a>
    <hr>
    <h3>{{ __('resources.narrator_all') }}</h3>
    @include('narrators.table')
    <br>
    <a id="global-link-narrators" class="search-link" href="{{ route('narrators.index') }}">{{ __('site.search_more') }}</a>
    <hr>
    <h3>{{ __('resources.place_all') }}</h3>
    @include('places.table')
    <br>
    <a id="global-link-places" class="search-link" href="{{ route('places.index') }}">{{ __('site.search_more') }}</a>
    <hr>
    <h3>{{ __('resources.source_all') }}</h3>
    @include('sources.table')
    <br>
    <a id="global-link-sources" class="search-link" href="{{ route('sources.index') }}">{{ __('site.search_more') }}</a>

    <form id="global-submit">
        <input type="text" name="global_search" value="{{ $item }}">
        <input type="submit" id="global-submit-legends" formaction="{{ route('legends.index') }}">
        <input type="submit" id="global-submit-collectors" formaction="{{ route('collectors.index') }}">
        <input type="submit" id="global-submit-narrators" formaction="{{ route('narrators.index') }}">
        <input type="submit" id="global-submit-places" formaction="{{ route('places.index') }}">
        <input type="submit" id="global-submit-sources" formaction="{{ route('sources.index') }}">
    </form>
@endsection