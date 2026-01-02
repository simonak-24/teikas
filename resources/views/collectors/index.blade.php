@extends('site')

@section('title', __('resources.collector_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>  
@endsection

@section('content')
    <div id="heading">
        <h1>{{ __('resources.collector_all') }}</h1>

        <button class="resource-button" name="format" value="CSV" type="submit" form="search-form">{{ __('site.button_csv') }}</button>
    </div>

    <div id="display-list">
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
            <tr>
                <form id="search-form" action="{{ route('collectors.index') }}" method="GET">
                    <td class="search-cell"><input type="fullname" id="search-fullname" name="fullname" onblur="submitForm()" value="{{ old('fullname', request()->input('fullname')) }}"></td>
                    <td class="search-cell"><select id="gender" name="gender" onchange="submitForm()">
                        <option value="" {{ old('gender', request()->input('gender')) == "" ? 'selected' : '' }}>{{ __('resources.person_all') }}</option>
                        <option value="M"  {{ old('gender', request()->input('gender')) == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                        <option value="F"  {{ old('gender', request()->input('gender')) == "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                        <option value="?"  {{ old('gender', request()->input('gender')) == "?" ? 'selected' : '' }}>{{ __('resources.person_unknown') }}</option>
                    </select></td>
                    <td class="search-cell"><select id="sort" name="sort" onchange="submitForm()">
                        <option value="" {{ old('sort', request()->input('sort')) == "" ? 'selected' : '' }}>{{ __('site.sort_none') }}</option>
                        <option value="asc"  {{ old('sort', request()->input('sort')) == "asc" ? 'selected' : '' }}>{{ __('site.sort_ascending') }}</option>
                        <option value="desc"  {{ old('sort', request()->input('sort')) == "desc" ? 'selected' : '' }}>{{ __('site.sort_descending') }}</option>
                    </select></td>
                    <button id="search-button" type="submit"></button>
                </form>
            </tr>
            @foreach ($paginator as $collector)
            <tr>
                <td><a href="{{ route('collectors.show', $collector->id) }}">{{ $collector->fullname }}</a></td>
                <td class="center-cell">{{ $collector->gender }}</td>
                <td class="center-cell">{{ count($collector->legends) }}</td>
            </tr>
            @endforeach
            @if($paginator->total() == 0)
                <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
            @endif
        </table>

        <div id="pagination-section">
        @include('navigation.pagination')
        @if(Auth::check())
        <form action="{{ route('collectors.create') }}">
            <button class="resource-button" type="submit">{{ __('site.button_create') }}</button>
        </form>
        @endif
        </div>
    </div>
@endsection