@extends('site')

@section('title', __('resources.legend_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>
@endsection

@section('content')
    <div id="heading">
        <h1>{{ __('resources.legend_all') }}</h1>
        <button id="search-button" type="submit" form="search-form"></button>
        <button class="resource-button" name="format" value="CSV" type="submit" form="search-form">{{ __('site.button_csv') }}</button>
    </div>

    <div id="display-list">
        <table>
            <colgroup>
                <col span="1" id="legend-index-identifier" />
                <col span="1" id="legend-index-volume"/>
                <col span="1" id="legend-index-chapter"/>
                <col span="1" id="legend-index-title"/>
                <col span="1" id="legend-index-text"/>
                <col span="1" id="legend-index-collector"/>
                <col span="1" id="legend-index-narrator"/>
                <col span="1" id="legend-index-place"/>
            </colgroup>
            <tr>
                <th>{{ __('resources.legend_identifier') }}</th>
                <th>{{ __('resources.legend_volume') }}</th>
                <th>{{ __('resources.legend_chapter-lv') }}</th>
                <th>{{ __('resources.legend_title-lv') }}</th>
                <th>{{ __('resources.legend_preview') }}</th>
                <th>{{ __('resources.legend_collector') }}</th>
                <th>{{ __('resources.legend_narrator') }}</th>
                <th>{{ __('resources.legend_place') }}</th>
            </tr>
            <tr>
                <form id="search-form" action="{{ route('legends.index') }}" method="GET">
                    <td class="search-cell"><input type="text" id="search-identifier" name="identifier" onblur="submitForm()" value="{{ old('identifier', request()->input('identifier')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-volume" name="volume" onblur="submitForm()" value="{{ old('volume', request()->input('volume')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-chapter" name="chapter" onblur="submitForm()" value="{{ old('chapter', request()->input('chapter')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-title" name="title" onblur="submitForm()" value="{{ old('title', request()->input('title')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-text" name="text" onblur="submitForm()" value="{{ old('text', request()->input('text')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-collector" name="collector" onblur="submitForm()" value="{{ old('collector', request()->input('collector')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-narrator" name="narrator" onblur="submitForm()" value="{{ old('narrator', request()->input('narrator')) }}"></td>
                    <td class="search-cell"><input type="text" id="search-place" name="place" onblur="submitForm()" value="{{ old('place', request()->input('place')) }}"></td>
                </form>
            </tr>
            @foreach ($paginator as $legend)
            <tr>
                <td><a href="{{ route('legends.show', $legend->identifier) }}">{{ $legend->identifier }}</a></td>
                <td class="center-cell">{{ $legend->volume }}</td>
                <td><a href="{{ route('navigation.chapter', urlencode($legend->chapter_lv)) }}">{{ $legend->chapter_lv}}</a></td>
                <td><a href="{{ route('navigation.subchapter', [urlencode($legend->chapter_lv), urlencode($legend->title_lv)]) }}">{{ $legend->title_lv }}</a></td>
                <td>{{ Str::limit($legend->text_lv, 100) }}</td>
                @if(isset($legend->collector_id))
                    <td><a href="{{ route('collectors.show', $legend->collector_id) }}">{{ $legend->collector->fullname }}</a></td>
                @else
                    <td>{{ __('resources.person_unidentified') }}</td>
                @endif
                @if(isset($legend->narrator_id))
                    <td><a href="{{ route('narrators.show', $legend->narrator_id)}}">{{ $legend->narrator->fullname }}</a></td>
                @else
                    <td>{{ __('resources.person_unidentified') }}</td>
                @endif
                @if(isset($legend->place_id))
                    <td><a href="{{ route('places.show', $legend->place_id)}}">{{ $legend->place->name }}</a></td>
                @else
                    <td>{{ __('resources.place_unidentified') }}</td>
                @endif
            </tr>
            @endforeach
            @if($paginator->total() == 0)
                <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
            @endif
        </table>

        <div id="pagination-section">
        @include('navigation.pagination')
        @if(Auth::check())
        <form action="{{ route('legends.create') }}">
            <button class="resource-button" type="submit">{{ __('site.button_create') }}</button>
        </form>
        @endif
        </div>
    </div>
@endsection