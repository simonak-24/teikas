<table>
    <colgroup>
        <col span="1" id="source-index-identifier" />
        <col span="1" id="source-index-title"/>
        <col span="1" id="source-index-author"/>
        <col span="1" id="source-index-count"/>
    </colgroup>
    <tr>
        <th>{{ __('resources.source_identifier') }}</th>
        <th>{{ __('resources.source_title') }}</th>
        <th>{{ __('resources.source_author') }}</th>
        <th>{{ __('resources.source_count') }}</th>
    </tr>
    @if(!isset($search))
    <tr>
        <form id="search-form" action="{{ route('sources.index') }}" method="GET">
            <input type="hidden" name="global_search" value="{{ request()->input('global_search') ? request()->input('global_search') : '' }}">
            <td class="search-cell"><input type="text" id="search-sources-identifier" name="identifier" onblur="submitForm()" value="{{ old('identifier', request()->input('identifier')) }}"></td>
            <td class="search-cell"><input type="text" id="search-sources-title" name="title" onblur="submitForm()" value="{{ old('title', request()->input('title')) }}"></td>
            <td class="search-cell"><input type="text" id="search-sources-author" name="author" onblur="submitForm()" value="{{ old('author', request()->input('author')) }}"></td>
            <td class="search-cell"><select id="sort" name="sort" onchange="submitForm()">
                <option value="" {{ old('sort', request()->input('sort')) == "" ? 'selected' : '' }}>{{ __('site.sort_none') }}</option>
                <option value="desc"  {{ old('sort', request()->input('sort')) == "desc" ? 'selected' : '' }}>{{ __('site.sort_descending') }}</option>
                <option value="asc"  {{ old('sort', request()->input('sort')) == "asc" ? 'selected' : '' }}>{{ __('site.sort_ascending') }}</option>
            </select></td>
        </form>
    </tr>
    @endif
    @foreach ($sources as $source)
    <tr>
        <td><a href="{{ route('sources.show', $source->id) }}">{!! $source->identifier !!}</a></td>
        <td><a href="{{ route('sources.show', $source->id) }}">{!! $source->title !!}</a></td>
        <td>{!! $source->author !!}</td>
        <td class="center-cell">{{ count($source->legends) }}</td>
    </tr>
    @endforeach
     @if($sources->total() == 0)
        <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
    @endif
</table>