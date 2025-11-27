@extends('site')

@section('title', $legend->title_lv)

@section('content')
    <div id="heading">
        <h2>
            <a class="resource-link" href="{{ route('legends.index').'?page='.$page }}">{{ __('resources.legend_all') }}</a>
            &nbsp;>&nbsp;&nbsp;<a class="resource-link" href="{{ route('navigation.chapter', urlencode($legend->chapter_lv)) }}">{{ $legend->chapter_lv }} / {{ $legend->chapter_de }}</a>
            &nbsp;>&nbsp;&nbsp;<a class="resource-link" href="{{ route('navigation.chapter', [urlencode($legend->chapter_lv), urlencode($legend->title_lv)]) }}">{{ $legend->title_lv }} / {{ $legend->title_de }}</a>
            &nbsp;>&nbsp;&nbsp;{{ $legend->identifier }}
        </h2>

        @if(Auth::check())
        <form action="{{ route('legends.edit', $legend->identifier) }}">
            <button class="resource-button" type="submit">{{ __('resources.button_edit') }}</button>
        </form>
        @endif
    </div>

    <table>
        <colgroup>
            <col span="1" id="display-item-column" />
            <col span="1" id="display-item-value"/>
        </colgroup>
        <tr>
            <th>{{ __('resources.legend_identifier') }}</th>
            <td>{{ $legend->identifier }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.legend_volume') }}</th>
            <td>{{ $legend->volume }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.legend_chapter-lv') }}</th>
            <td>{{ $legend->chapter_lv }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.legend_title-lv') }}</th>
            <td>{{ $legend->title_lv }} / {{ $legend->title_de }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.legend_metadata') }}</th>
            <td>{{ $legend->metadata }}</td>
        </tr>

        <tr>
            <th>{{ __('resources.legend_collector') }}</th>
            @if(isset($legend->collector_id))
                <td><a href="{{ route('collectors.show', $legend->collector_id)}}">{{ $legend->collector->fullname }}</a></td>
            @else
                <td>{{ __('resources.person_unidentified') }}</td>
            @endif
        </tr>
        <tr>
            <th>{{ __('resources.legend_narrator') }}</th>
            @if(isset($legend->narrator_id))
                <td><a href="{{ route('narrators.show', $legend->narrator_id)}}">{{ $legend->narrator->fullname }}</a></td>
            @else
                <td>{{ __('resources.person_unidentified') }}</td>
            @endif
        </tr>
        <tr>
            <th>{{ __('resources.legend_place') }}</th>
            @if(isset($legend->place_id))
                <td><a href="{{ route('places.show', $legend->place_id)}}">{{ $legend->place->name }}</a></td>
            @else
                <td>{{ __('resources.place_unidentified') }}</td>
            @endif
        </tr>
        <tr>
            <th>{{ __('resources.legend_sources') }}</th>
            <td>
                @foreach($legend->sources as $link)
                    <a href="{{ route('sources.show', $link->source_id)}}">{{ $link->source->title }}</a><br>
                @endforeach
            </td>
        </tr>      
    </table>
    <br>
    <table>
        <colgroup>
            <col span="1" id="legend-text-lv" />
            <col span="1" id="legend-text-de"/>
        </colgroup>
        <tr>
            <th>{{ __('resources.legend_text-lv') }}</th>
            <th>{{ __('resources.legend_text-de') }}</th>
        </tr>
        <tr>
            <td>{{ $legend->text_lv }}</td>
            <td>{{ $legend->text_de }}</td>
        </tr>
    </table>
    <br>
@endsection
