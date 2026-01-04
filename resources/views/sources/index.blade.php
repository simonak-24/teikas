@extends('site')

@section('title', __('resources.source_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>  
@endsection

@section('content')
    <div id="heading">
        <h1>{{ __('resources.source_all') }}</h1>
        <button id="search-button" type="submit" form="search-form"></button>
        <button class="resource-button" name="format" value="CSV" type="submit" form="search-form">{{ __('site.button_csv') }}</button>
    </div>

    <div id="display-list">
        <table>
            <colgroup>
                <col span="1" id="source-index-identifier" />
                <col span="1" id="source-index-title"/>
                <col span="1" id="source-index-author"/>
            </colgroup>
            <tr>
                <th>{{ __('resources.source_identifier') }}</th>
                <th>{{ __('resources.source_title') }}</th>
                <th>{{ __('resources.source_author') }}</th>
            </tr>
            <tr>
                <form id="search-form" action="{{ route('sources.index') }}" method="GET">
                    <td class="search-cell"><input type="text" id="search-sources-identifier" name="identifier" onblur="submitForm()" value="{{ old('identifier', request()->input('identifier')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-sources-title" name="title" onblur="submitForm()" value="{{ old('title', request()->input('title')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-sources-author" name="author" onblur="submitForm()" value="{{ old('author', request()->input('author')) }}"></td>
                </form>
            </tr>
            @foreach ($paginator as $source)
            <tr>
                <td><a href="{{ route('sources.show', $source->id) }}">{{ $source->identifier }}</a></td>
                <td><a href="{{ route('sources.show', $source->id) }}">{{ $source->title }}</a></td>
                <td>{{ $source->author }}</td>
            </tr>
            @endforeach
            @if($paginator->total() == 0)
                <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
            @endif
        </table>

        <div id="pagination-section">
        @include('navigation.pagination')
        @if(Auth::check())
        <form action="{{ route('sources.create') }}">
            <button class="resource-button" type="submit">{{ __('site.button_create') }}</button>
        </form>
        @endif
        </div>
    </div>
@endsection