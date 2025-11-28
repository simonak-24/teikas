@extends('site')

@section('title', __('resources.source_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>  
@endsection

@section('content')
    <div id="heading">
        <h1>{{ __('resources.source_all') }}</h1>

        @if(Auth::check())
        <form action="{{ route('sources.create') }}">
            <button class="resource-button" type="submit">{{ __('resources.button_create') }}</button>
        </form>
        @endif
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
                <form action="{{ route('sources.index') }}" method="GET">
                    <td class="search-cell"><input type="text" id="search-sources-identifier" name="identifier" onblur="submitForm()" value="{{ old('identifier', request()->input('identifier')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-sources-title" name="title" onblur="submitForm()" value="{{ old('title', request()->input('title')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-sources-author" name="author" onblur="submitForm()" value="{{ old('author', request()->input('author')) }}"></td>
                    <button id="search-button" type="submit"></button>
                </form>
            </tr>
            @foreach ($sources as $source)
            <tr>
                <td><a href="{{ route('sources.show', $source->id) }}">{{ $source->identifier }}</a></td>
                <td><a href="{{ route('sources.show', $source->id) }}">{{ $source->title }}</a></td>
                <td>{{ $source->author }}</td>
            </tr>
            @endforeach
        </table>
        @if($sources->lastPage() > 1)
        <div id="pagination-links">
            <div class="pagination-button"><a href="{{ $sources->withQueryString()->url(1) }}"> << </a></div>
            <div class="pagination-button"><a href="{{ $sources->withQueryString()->previousPageUrl() }}"> < </a></div>
            @for ($i = 1; $i <= $sources->lastPage(); $i++)
                <div class="pagination-button"><a href="{{ $sources->withQueryString()->url($i) }}"> {{ $i }} </a></div>
            @endfor
            <div class="pagination-button"><a href="{{ $sources->withQueryString()->nextPageUrl() }}"> > </a></div>
            <div class="pagination-button"><a href="{{ $sources->withQueryString()->url($sources->lastPage()) }}"> >> </a></div>
        </div>
        @endif
    </div>
@endsection