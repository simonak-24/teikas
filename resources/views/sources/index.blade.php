@extends('site')

@section('title', __('resources.source_all'))

@section('content')
    <div id="heading">
        <h1>{{ __('resources.source_all') }}</h1>

        <form action="{{ route('sources.create') }}">
            <button class="resource-button" type="submit">{{ __('resources.button_create') }}</button>
        </form>
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
            @foreach ($sources as $source)
            <tr>
                <td><a href="{{ route('sources.show', $source->id) }}">{{ $source->identifier }}</a></td>
                <td><a href="{{ route('sources.show', $source->id) }}">{{ $source->title }}</a></td>
                <td>{{ $source->author }}</td>
            </tr>
            @endforeach
        </table>
        <div id="pagination-links">
            <div id="pagination-forward">
                <div class="pagination-button"><a href="{{ $sources->url(1) }}"> << </a></div>
                <div class="pagination-button"><a href="{{ $sources->previousPageUrl() }}"> < </a></div>
            </div>
            <div id="pagination-back">
                <div class="pagination-button"><a href="{{ $sources->nextPageUrl() }}"> > </a></div>
                <div class="pagination-button"><a href="{{ $sources->url($sources->lastPage()) }}"> >> </a></div>
            </div>
        </div>
    </div>
@endsection