<table>
    <colgroup>
        <col span="1" id="person-index-fullname" />
        <col span="1" id="person-index-gender"/>
        <col span="1" id="person-index-count"/>
    </colgroup>
    <tr>
        <th>{{ __('resources.person_fullname') }}</th>
        <th>{{ __('resources.person_gender') }}</th>
        <th>{{ __('resources.collector_count') }}</th>
    </tr>
    @if(!isset($search))
    <tr>
        <form id="search-form" action="{{ route('collectors.index') }}" method="GET">
            <input type="hidden" name="global_search" value="{{ request()->input('global_search') ? request()->input('global_search') : '' }}">
            <td class="search-cell"><input type="fullname" id="search-fullname" name="fullname" onblur="submitForm()" value="{{ old('fullname', request()->input('fullname')) }}"></td>
            <td class="search-cell"><select id="gender" name="gender" onchange="submitForm()">
                <option value="" {{ old('gender', request()->input('gender')) == "" ? 'selected' : '' }}>{{ __('resources.person_all') }}</option>
                <option value="M"  {{ old('gender', request()->input('gender')) == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                <option value="F"  {{ old('gender', request()->input('gender')) == "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                <option value="?"  {{ old('gender', request()->input('gender')) == "?" ? 'selected' : '' }}>{{ __('resources.person_unknown') }}</option>
            </select></td>
            <td class="search-cell"><select id="sort" name="sort" onchange="submitForm()">
                <option value="" {{ old('sort', request()->input('sort')) == "" ? 'selected' : '' }}>{{ __('site.sort_none') }}</option>
                <option value="desc"  {{ old('sort', request()->input('sort')) == "desc" ? 'selected' : '' }}>{{ __('site.sort_descending') }}</option>
                <option value="asc"  {{ old('sort', request()->input('sort')) == "asc" ? 'selected' : '' }}>{{ __('site.sort_ascending') }}</option>
            </select></td>
        </form>
    </tr>
    @endif
    @foreach ($collectors as $collector)
    <tr>
        <td><a href="{{ route('collectors.show', $collector->id) }}">{!! $collector->fullname !!}</a></td>
        <td class="center-cell">{{ $collector->gender }}</td>
        <td class="center-cell">{{ count($collector->legends) }}</td>
    </tr>
    @endforeach
    @if($collectors->total() == 0)
        <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
    @endif
</table>