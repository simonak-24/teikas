@extends('site')

@section('title', __('resources.collector_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>  
@endsection

@section('content')
    <div id="heading">
        <h1>{{ __('resources.collector_all') }}</h1>
        <button id="search-button" type="submit" form="search-form"></button>
        <button class="resource-button" name="format" value="CSV" type="submit" form="search-form">{{ __('site.button_csv') }}</button>
    </div>

    <div id="display-list">
        @if(request()->input('global_search') != "")<p>{{ __('site.search_fragment-1') }}<b>{{ request()->input('global_search') }}</b>{{ __('site.search_fragment-2') }}</p>@endif
        @include('collectors.table')
        <div id="pagination-section">
        @include('partials.pagination', ['paginator' => $collectors])
        @if(Auth::check())
        <form action="{{ route('collectors.create') }}">
            <button class="resource-button" type="submit">{{ __('site.button_create') }}</button>
        </form>
        @endif
        </div>
    </div>
@endsection