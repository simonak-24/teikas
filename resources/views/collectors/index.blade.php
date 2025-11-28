@extends('site')

@section('title', __('resources.collector_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>  
@endsection

@section('content')
    <div id="heading">
        <h1>{{ __('resources.collector_all') }}</h1>

        @if(Auth::check())
        <form action="{{ route('collectors.create') }}">
            <button class="resource-button" type="submit">{{ __('resources.button_create') }}</button>
        </form>
        @endif
    </div>

    <div id="display-list">
        <table>
            <colgroup>
                <col span="1" id="person-index-fullname" />
                <col span="1" id="person-index-gender"/>
                <col span="1" id="person-index-count"/>
                <col span="1" id="person-index-link"/>
            </colgroup>
            <tr>
                <th>{{ __('resources.person_fullname') }}</th>
                <th>{{ __('resources.person_gender') }}</th>
                <th>{{ __('resources.collector_count') }}</th>
                <th>{{ __('resources.external-link-garamantas') }}</th>
            </tr>
            <tr>
                <form action="{{ route('collectors.index') }}" method="GET">
                    <td class="search-cell"><input type="fullname" id="search-fullname" name="fullname" onblur="submitForm()" value="{{ old('fullname', request()->input('fullname')) }}"></td>
                    <td class="search-cell"><select id="gender" name="gender" onchange="submitForm()">
                        <option value="" {{ old('gender', request()->input('gender')) == "" ? 'selected' : '' }}>{{ __('resources.person_all') }}</option>
                        <option value="M"  {{ old('gender', request()->input('gender')) == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                        <option value="F"  {{ old('gender', request()->input('gender')) == "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                        <option value="?"  {{ old('gender', request()->input('gender')) == "?" ? 'selected' : '' }}>{{ __('resources.person_unknown') }}</option>
                    </select></td>
                    <td class="search-cell"><select id="sort" name="sort" onchange="submitForm()">
                        <option value="" {{ old('sort', request()->input('sort')) == "" ? 'selected' : '' }}>{{ __('resources.sort_none') }}</option>
                        <option value="asc"  {{ old('sort', request()->input('sort')) == "asc" ? 'selected' : '' }}>{{ __('resources.sort_ascending') }}</option>
                        <option value="desc"  {{ old('sort', request()->input('sort')) == "desc" ? 'selected' : '' }}>{{ __('resources.sort_descending') }}</option>
                    </select></td>
                    <td class="search-cell"></td>
                    <button id="search-button" type="submit"></button>
                </form>
            </tr>
            @foreach ($collectors as $collector)
            <tr>
                @if ($collector->fullname != 'Nezināms')    <!-- Vēlāk jānoņem, lai nepastāvētu Nezināms. -->
                    <td><a href="{{ route('collectors.show', $collector->id) }}">{{ $collector->fullname }}</a></td>
                    <td class="center-cell">{{ $collector->gender }}</td>
                    <td class="center-cell">{{ count($collector->legends) }}</td>
                    <td class="center-cell"><a href="">{{ __('resources.external-link-open') }}</a></td>
                @endif
            </tr>
            @endforeach
        </table>
        @if($collectors->lastPage() > 1)
        <div id="pagination-links">
            <div class="pagination-button"><a href="{{ $collectors->withQueryString()->url(1) }}"> << </a></div>
            <div class="pagination-button"><a href="{{ $collectors->withQueryString()->previousPageUrl() }}"> < </a></div>
            @if($collectors->lastPage() <= 9)
                @for ($i = 1; $i <= $collectors->lastPage(); $i++)
                <div class="pagination-button"><a href="{{ $collectors->withQueryString()->url($i) }}"> {{ $i }} </a></div>
                @endfor
            @else
                @if($collectors->currentPage() <= 5)
                    @for ($i = 1; $i <= 9; $i++)
                    <div class="pagination-button"><a href="{{ $collectors->withQueryString()->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @elseif($collectors->currentPage() + 5 > $collectors->lastPage())
                    @for ($i = $collectors->lastPage() - 9; $i <= $collectors->lastPage(); $i++)
                    <div class="pagination-button"><a href="{{ $collectors->withQueryString()->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @else
                    @for ($i = $collectors->currentPage() - 4; $i <= $collectors->currentPage() + 4; $i++)
                    <div class="pagination-button"><a href="{{ $collectors->withQueryString()->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @endif
            @endif
            <div class="pagination-button"><a href="{{ $collectors->withQueryString()->nextPageUrl() }}"> > </a></div>
            <div class="pagination-button"><a href="{{ $collectors->withQueryString()->url($collectors->lastPage()) }}"> >> </a></div>
        </div>
        @endif
    </div>
@endsection