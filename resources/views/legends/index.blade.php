@extends('site')

@section('title', __('resources.legend_all'))

@section('content')
    <div id="heading">
        <h1>{{ __('resources.legend_all') }}</h1>
        <button id="search-button" type="submit" form="search-form"></button>
        <button class="resource-button" name="format" value="CSV" type="submit" form="search-form">{{ __('site.button_csv') }}</button>
    </div>

    <div id="display-list">
        @include('partials.table')
        <div id="pagination-section">
        @include('partials.pagination')
        @if(Auth::check())
        <form action="{{ route('legends.create') }}">
            <button class="resource-button" type="submit">{{ __('site.button_create') }}</button>
        </form>
        @endif
        </div>
    </div>
@endsection