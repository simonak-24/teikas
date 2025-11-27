@extends('site')

@section('title', __('resources.title_contents'))

@section('content')
<div>
    <div class="heading">
        <h2>
            <a class="resource-link" href="{{ route('navigation.contents') }}">{{ __('resources.title_contents') }}</a>
            &nbsp;>&nbsp;&nbsp;{{ $legends->first()->chapter_lv }} / {{ $legends->first()->chapter_de }}
        </h2>
    </div>
    <div>
        <table>
            <colgroup>
                <col span="1" id="contents-table-identifier" />
                <col span="1" id="contents-table-title" />
                <col span="1" id="contents-chapter-text"/>
                <col span="1" id="contents-table-collector" />
                <col span="1" id="contents-table-narrator"/>
                <col span="1" id="contents-table-place" />
            </colgroup>
            <tr>
                <th>{{ __('resources.legend_identifier') }}</th>
                <th>{{ __('resources.legend_title-lv') }}</th>
                <th>{{ __('resources.legend_preview') }}</th>
                <th>{{ __('resources.legend_collector') }}</th>
                <th>{{ __('resources.legend_narrator') }}</th>
                <th>{{ __('resources.legend_place') }}</th>
            </tr>
            @foreach ($legends as $legend)
            <tr>
                <td><a href="{{ route('legends.show', $legend->identifier) }}">{{ $legend->identifier }}</a></td>
                <td><a href="{{ route('navigation.subchapter', [urlencode($legend->chapter_lv), urlencode($legend->title_lv)]) }}">{{ $legend->title_lv }}</a></td>
                <td>{{ Str::limit($legend->text_lv, 110) }}</td>
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
        @if($legends->lastPage() > 1)
        <div id="pagination-links">
            <div class="pagination-button"><a href="{{ $legends->url(1) }}"> << </a></div>
            <div class="pagination-button"><a href="{{ $legends->previousPageUrl() }}"> < </a></div>
            @if($legends->lastPage() <= 9)
                @for ($i = 1; $i <= $legends->lastPage(); $i++)
                <div class="pagination-button"><a href="{{ $legends->url($i) }}"> {{ $i }} </a></div>
                @endfor
            @else
                @if($legends->currentPage() <= 5)
                    @for ($i = 1; $i <= 9; $i++)
                    <div class="pagination-button"><a href="{{ $legends->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @elseif($legends->currentPage() + 5 > $legends->lastPage())
                    @for ($i = $legends->lastPage() - 9; $i <= $legends->lastPage(); $i++)
                    <div class="pagination-button"><a href="{{ $legends->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @else
                    @for ($i = $legends->currentPage() - 4; $i <= $legends->currentPage() + 4; $i++)
                    <div class="pagination-button"><a href="{{ $legends->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @endif
            @endif
            <div class="pagination-button"><a href="{{ $legends->nextPageUrl() }}"> > </a></div>
            <div class="pagination-button"><a href="{{ $legends->url($legends->lastPage()) }}"> >> </a></div>
        </div>
        @endif
    </div>
    <br>
</div>
@endsection