@extends('site')

@section('title', __('resources.legend_all'))

@section('content')
    <div id="heading">
        <h1>{{ __('resources.legend_all') }}</h1>
    
        <form action="{{ route('legends.create') }}">
            <button class="resource-button" type="submit">{{ __('resources.button_create') }}</button>
        </form>
    </div>

    <div id="display-list">
        <table>
            <colgroup>
                <col span="1" id="legend-index-identifier" />
                <col span="1" id="legend-index-volume"/>
                <col span="1" id="legend-index-chapter"/>
                <col span="1" id="legend-index-title"/>
                <col span="1" id="legend-index-collector"/>
                <col span="1" id="legend-index-narrator"/>
                <col span="1" id="legend-index-place"/>
            </colgroup>
            <tr>
                <th>{{ __('resources.legend_identifier') }}</th>
                <th>{{ __('resources.legend_volume') }}</th>
                <th>{{ __('resources.legend_chapter-lv') }}</th>
                <th>{{ __('resources.legend_title-lv') }}</th>
                <th>{{ __('resources.legend_collector') }}</th>
                <th>{{ __('resources.legend_narrator') }}</th>
                <th>{{ __('resources.legend_place') }}</th>
            </tr>
            @foreach ($legends as $legend)
            <tr>
                <td><a href="{{ route('legends.show', $legend->identifier) }}">{{ $legend->identifier }}</a></td>
                <td class="center-cell">{{ $legend->volume }}</td>
                <td>{{ $legend->chapter_lv}}</td>
                <td><a href="{{ route('legends.show', $legend->identifier) }}">{{ $legend->title_lv }}</a></td>
                @if(isset($legend->collector_id))
                    <td><a href="{{ route('collectors.show', $legend->collector_id) }}">{{ $legend->collector->fullname }}</a></td>
                @else
                    <td>{{ __('resources.person_unidentified') }}</td>
                @endif
                @if(isset($legend->narrator_id))
                    <td><a href="{{ route('narrators.show', $legend->collector_id)}}">{{ $legend->narrator->fullname }}</a></td>
                @else
                    <td>{{ __('resources.person_unidentified') }}</td>
                @endif
                @if(isset($legend->place_id))
                    <td><a href="{{ route('places.show', $legend->place_id)}}">{{ $legend->place->name }}</a></td>
                @else
                    <td>{{ __('resources.person_unidentified') }}</td>
                @endif
            </tr>
            @endforeach
        </table>
        <div id="pagination-links">
            <div id="pagination-forward">
                <div class="pagination-button"><a href="{{ $legends->url(1) }}"> << </a></div>
                <div class="pagination-button"><a href="{{ $legends->previousPageUrl() }}"> < </a></div>
            </div>
            <div id="pagination-back">
                <div class="pagination-button"><a href="{{ $legends->nextPageUrl() }}"> > </a></div>
                <div class="pagination-button"><a href="{{ $legends->url($legends->lastPage()) }}"> >> </a></div>
            </div>
        </div>
    </div>
@endsection