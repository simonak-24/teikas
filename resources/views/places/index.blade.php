@extends('site')

@section('title', __('resources.place_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>  
@endsection

@section('content')
    <div id="heading">
        <h1>{{ __('resources.place_all') }}</h1>
        
        <button class="resource-button" name="format" value="CSV" type="submit" form="search-form">{{ __('site.button_csv') }}</button>
    </div>

    <div id="display-list">
        <table>
            <colgroup>
                <col span="1" id="place-index-name" />
                <col span="1" id="place-index-latitude"/>
                <col span="1" id="place-index-longitude"/>
            </colgroup>
            <tr>
                <th>{{ __('resources.place_name') }}</th>
                <th>{{ __('resources.place_latitude') }}</th>
                <th>{{ __('resources.place_longitude') }}</th>
            </tr>
            <tr>
                <form id="search-form" action="{{ route('places.index') }}" method="GET">
                    <td class="search-cell"><input type="text" id="search-name" name="name" onblur="submitForm()" value="{{ old('name', request()->input('name')) }}"></td>
                    <td></td>
                    <td></td>
                    <button id="search-button" type="submit"></button>
                </form>
            </tr>
            @foreach ($paginator as $place)
                <tr>
                    <td><a href="{{ route('places.show', $place->id) }}">{{ $place->name }}</a></td>
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
                    </tr>
            @endforeach
            @if($paginator->total() == 0)
                <tr><td colspan="8">{{ __('resources.none_multiple') }}</td></tr>
            @endif
        </table>

        <div id="pagination-section">
        @include('navigation.pagination')
        @if(Auth::check())
        <form action="{{ route('places.create') }}">
            <button class="resource-button" type="submit">{{ __('site.button_create') }}</button>
        </form>
        @endif
        </div>
    </div>
@endsection