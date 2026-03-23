@use('App\Models\{Collector, Narrator, Place, Source}')

<table>
    <colgroup>
        <col span="1" id="legend-index-identifier" />
        <col span="1" id="legend-index-volume"/>
        <col span="1" id="legend-index-chapter"/>
        <col span="1" id="legend-index-title"/>
        <col span="1" id="legend-index-text"/>
        @if(!($item instanceof App\Models\Collector))<col span="1" id="legend-index-collector"/>@endif
        @if(!($item instanceof App\Models\Narrator))<col span="1" id="legend-index-narrator"/>@endif
        @if(!($item instanceof App\Models\Place))<col span="1" id="legend-index-place"/>@endif
    </colgroup>
    <tr>
        <th>{{ __('resources.legend_identifier') }}</th>
        <th>{{ __('resources.legend_volume') }}</th>
        <th>{{ __('resources.legend_chapter-lv') }}</th>
        <th>{{ __('resources.legend_title-lv') }}</th>
        <th>{{ __('resources.legend_preview') }}</th>
        @if(!($item instanceof App\Models\Collector))<th><div class="sort-cell"><div>{{ __('resources.legend_collector') }}</div><div><a id="cl" class="sort-arrow" href="#" onclick="submitSort('cl')">{{ isset($sort['collector']) ? urldecode($sort['collector']) : "⭥" }}</a></div></div></th>@endif
        @if(!($item instanceof App\Models\Narrator))<th><div class="sort-cell"><div>{{ __('resources.legend_narrator') }}</div><div><a id="nr" class="sort-arrow"  href="#" onclick="submitSort('nr')">{{ isset($sort['narrator']) ? urldecode($sort['narrator']) : "⭥" }}</a></div></div></th>@endif
        @if(!($item instanceof App\Models\Place))<th><div class="sort-cell"><div>{{ __('resources.legend_place') }}</div><div><a id="pl" class="sort-arrow"  href="#" onclick="submitSort('pl')">{{ isset($sort['place']) ? urldecode($sort['place']) : "⭥" }}</a></div></div></th>@endif
    </tr>
    <tr>
        @if($item instanceof App\Models\Collector)<form id="search-form" action="{{ route('collectors.show', $collector->id) }}" method="GET">
        @elseif($item instanceof App\Models\Narrator)<form id="search-form" action="{{ route('narrators.show', $narrator->id) }}" method="GET">
        @elseif($item instanceof App\Models\Place)<form id="search-form" action="{{ route('places.show', $place->id) }}" method="GET">
        @elseif($item instanceof App\Models\Source)<form id="search-form" action="{{ route('sources.show', $source->id) }}" method="GET">
        @else<form id="search-form" action="{{ route('legends.index') }}" method="GET">@endif
            <td class="search-cell"><input type="text" id="search-identifier" name="identifier" onblur="submitForm()" value="{{ old('identifier', request()->input('identifier')) }}"></td>
            <td class="search-cell"><input type="text" id="search-volume" name="volume" onblur="submitForm()" value="{{ old('volume', request()->input('volume')) }}"></td>
            <td class="search-cell"><input type="text" id="search-chapter" name="chapter" onblur="submitForm()" value="{{ old('chapter', request()->input('chapter')) }}"></td>
            <td class="search-cell"><input type="text" id="search-title" name="title" onblur="submitForm()" value="{{ old('title', request()->input('title')) }}"></td>
            <td class="search-cell"><input type="text" id="search-text" name="text" onblur="submitForm()" value="{{ old('text', request()->input('text')) }}"></td>
            @if(!($item instanceof App\Models\Collector))<td class="search-cell"><input type="text" id="search-collector" name="collector" onblur="submitForm()" value="{{ old('collector', request()->input('collector')) }}"></td>@endif
            @if(!($item instanceof App\Models\Narrator))<td class="search-cell"><input type="text" id="search-narrator" name="narrator" onblur="submitForm()" value="{{ old('narrator', request()->input('narrator')) }}"></td>@endif
            @if(!($item instanceof App\Models\Place))<td class="search-cell"><input type="text" id="search-place" name="place" onblur="submitForm()" value="{{ old('place', request()->input('place')) }}"></td>@endif

            <input type="hidden" id="collector_sort" name="collector_sort" value="{{ isset($sort['collector']) ? $sort['collector'] : urlencode('⭥') }}">
            <input type="hidden" id="narrator_sort" name="narrator_sort" value="{{ isset($sort['narrator']) ? $sort['narrator'] : urlencode('⭥') }}">
            <input type="hidden" id="places_sort" name="place_sort" value="{{ isset($sort['place']) ? $sort['place'] : urlencode('⭥') }}">
            <input type="hidden" id="sort" name="sort">

            <button id="search-button" type="submit"></button>
        </form>
    </tr>
    @foreach ($paginator as $legend)
        <tr>
            <td><a href="{{ route('legends.show', $legend->identifier) }}">{{ $legend->identifier }}</a></td>
            <td class="center-cell">{{ $legend->volume }}</td>
            <td><a href="{{ route('navigation.chapter', urlencode($legend->chapter_lv)) }}">{{ $legend->chapter_lv}}</a></td>
            <td><a href="{{ route('navigation.subchapter', [urlencode($legend->chapter_lv), urlencode($legend->title_lv)]) }}">{{ $legend->title_lv }}</a></td>
            <td>{!! $legend->text !!}</td>
            @if(!($item instanceof App\Models\Collector))
            @if(isset($legend->collector_id))
                <td><a href="{{ route('collectors.show', $legend->collector_id) }}">{{ $legend->collector->fullname }}</a></td>
            @else
                <td>{{ __('resources.person_unidentified') }}</td>
            @endif
            @endif
            @if(!($item instanceof App\Models\Narrator))
            @if(isset($legend->narrator_id))
                <td><a href="{{ route('narrators.show', $legend->narrator_id)}}">{{ $legend->narrator->fullname }}</a></td>
            @else
                <td>{{ __('resources.person_unidentified') }}</td>
            @endif
            @endif
            @if(!($item instanceof App\Models\Place))
            @if(isset($legend->place_id))
                <td><a href="{{ route('places.show', $legend->place_id)}}">{{ $legend->place->name }}</a></td>
            @else
                <td>{{ __('resources.place_unidentified') }}</td>
            @endif
            @endif
        </tr>
    @endforeach
    @if($paginator->count() == 0)
        <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
    @endif
</table>