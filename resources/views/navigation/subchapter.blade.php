@extends('site')

@section('title', $paginator->first()->title_lv.' / '.$paginator->first()->title_de)

@section('content')
<div>
    <div class="heading">
        <h2>
            <a class="resource-link" href="{{ route('navigation.contents') }}">{{ __('site.title_contents') }}</a>
            &nbsp;>&nbsp;&nbsp;<a class="resource-link" href="{{ route('navigation.chapter', urlencode($paginator->first()->chapter_lv)) }}">{{ $paginator->first()->chapter_lv }} / {{ $paginator->first()->chapter_de }}</a>
            &nbsp;>&nbsp;&nbsp;{{ $paginator->first()->title_lv }} / {{ $paginator->first()->title_de }}
    </div>
    <div>
        <table>
            <colgroup>
                <col span="1" id="contents-table-identifier" />
                <col span="1" id="contents-subchapter-text"/>
                <col span="1" id="contents-table-collector" />
                <col span="1" id="contents-table-narrator"/>
                <col span="1" id="contents-table-place" />
            </colgroup>
            <tr>
                <th>{{ __('resources.legend_identifier') }}</th>
                <th>{{ __('resources.legend_preview') }}</th>
                <th>{{ __('resources.legend_collector') }}</th>
                <th>{{ __('resources.legend_narrator') }}</th>
                <th>{{ __('resources.legend_place') }}</th>
            </tr>
            @foreach ($paginator as $legend)
            <tr>
                <td><a href="{{ route('legends.show', $legend->identifier) }}">{{ $legend->identifier }}</a></td>
                <td>{{ Str::limit($legend->text_lv, 190) }}</td>
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
                    <td>{{ __('resources.person_unidentified') }}</td>
                @endif
            </tr>
            @endforeach
        </table>
        @include('navigation.pagination')
    </div>
    <br>
</div>
@endsection