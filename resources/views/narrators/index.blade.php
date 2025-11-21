@extends('site')

@section('title', __('resources.narrator_all'))

@section('content')
    <div id="heading">
        <h1>{{ __('resources.narrator_all') }}</h1>

        @if(Auth::check())
        <form action="{{ route('narrators.create') }}">
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
                <th>{{ __('resources.narrator_count') }}</th>
                <th>{{ __('resources.external-link-garamantas') }}</th>
            </tr>
            @foreach ($narrators as $narrator)
            <tr>
                <td><a href="{{ route('narrators.show', $narrator->id) }}">{{ $narrator->fullname }}</a></td>
                <td class="center-cell">{{ $narrator->gender }}</td>
                <td class="center-cell">{{ count($narrator->legends) }}</td>
                <td class="center-cell"><a href="">{{ __('resources.external-link-open') }}</a></td>
            </tr>
            @endforeach
        </table>
        <div id="pagination-links">
            <div id="pagination-forward">
                <div class="pagination-button"><a href="{{ $narrators->url(1) }}"> << </a></div>
                <div class="pagination-button"><a href="{{ $narrators->previousPageUrl() }}"> < </a></div>
            </div>
            <div id="pagination-back">
                <div class="pagination-button"><a href="{{ $narrators->nextPageUrl() }}"> > </a></div>
                <div class="pagination-button"><a href="{{ $narrators->url($narrators->lastPage()) }}"> >> </a></div>
            </div>
        </div>
    </div>
@endsection