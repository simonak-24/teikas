@extends('site')

@section('title', __('resources.collector_all'))

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
            @foreach ($collectors as $collector)
            <tr>
                @if ($collector->name != 'Nezināms')    <!-- Vēlāk jānoņem, lai nepastāvētu Nezināms. -->
                    <td><a href="{{ route('collectors.show', $collector->id) }}">{{ $collector->fullname }}</a></td>
                    <td class="center-cell">{{ $collector->gender }}</td>
                    <td class="center-cell">{{ count($collector->legends) }}</td>
                    <td class="center-cell"><a href="">{{ __('resources.external-link-open') }}</a></td>
                @endif
            </tr>
            @endforeach
        </table>
        <div id="pagination-links">
            <div id="pagination-forward">
                <div class="pagination-button"><a href="{{ $collectors->url(1) }}"> << </a></div>
                <div class="pagination-button"><a href="{{ $collectors->previousPageUrl() }}"> < </a></div>
            </div>
            <div id="pagination-back">
                <div class="pagination-button"><a href="{{ $collectors->nextPageUrl() }}"> > </a></div>
                <div class="pagination-button"><a href="{{ $collectors->url($collectors->lastPage()) }}"> >> </a></div>
            </div>
        </div>
    </div>
@endsection