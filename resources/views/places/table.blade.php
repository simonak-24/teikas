<table>
    <colgroup>
        <col span="1" id="place-index-name" />
        <col span="1" id="place-index-latitude"/>
        <col span="1" id="place-index-longitude"/>
        <col span="1" id="place-index-count"/>
    </colgroup>
    <tr>
        <th>{{ __('resources.place_name') }}</th>
        <th>{{ __('resources.place_latitude') }}</th>
        <th>{{ __('resources.place_longitude') }}</th>
        <th>{{ __('resources.place_count') }}</th>
    </tr>
    @if(!isset($search))
    <tr>
        <form id="search-form" action="{{ route('places.index') }}" method="GET">
            <input type="hidden" name="global_search" value="{{ request()->input('global_search') ? request()->input('global_search') : '' }}">
            <td class="search-cell"><input type="text" id="search-name" name="name" onblur="submitForm()" value="{{ old('name', request()->input('name')) }}"></td>
            <td></td>
            <td></td>
            <td class="search-cell"><select id="sort" name="sort" onchange="submitForm()">
                <option value="" {{ old('sort', request()->input('sort')) == "" ? 'selected' : '' }}>{{ __('site.sort_none') }}</option>
                <option value="desc"  {{ old('sort', request()->input('sort')) == "desc" ? 'selected' : '' }}>{{ __('site.sort_descending') }}</option>
                <option value="asc"  {{ old('sort', request()->input('sort')) == "asc" ? 'selected' : '' }}>{{ __('site.sort_ascending') }}</option>
            </select></td>
        </form>
    </tr>
    @endif
    @foreach ($places as $place)
        <tr>
            <td><a href="{{ route('places.show', $place->id) }}">{!! $place->name !!}</a></td>
            @if($place->latitude != 0)
            <td class="center-cell">{{ $place->latitude }}</td>
            @else
            <td  class="center-cell"></td>
            @endif
            @if($place->latitude != 0)
            <td class="center-cell">{{ $place->longitude }}</td>
            @else
            <td  class="center-cell"></td>
            @endif
            <td class="center-cell">{{ count($place->legends) }}</td>
        </tr>
    @endforeach
    @if($places->total() == 0)
        <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
    @endif
</table>