@extends('site')

@section('title', __('resources.place_all'))

@section('content')
    <div id="heading">
        <h1>{{ __('resources.place_all') }}</h1>
        
        @if(Auth::check())
        <form action="{{ route('places.create') }}">
            <button class="resource-button" type="submit">{{ __('resources.button_create') }}</button>
        </form>
        @endif
    </div>

    <div id="display-list">
        <table>
            <colgroup>
                <col span="1" id="place-index-name" />
                <col span="1" id="place-index-latitude"/>
                <col span="1" id="place-index-longitude"/>
                <col span="1" id="place-index-link"/>
            </colgroup>
            <tr>
                <th>{{ __('resources.place_name') }}</th>
                <th>{{ __('resources.place_latitude') }}</th>
                <th>{{ __('resources.place_longitude') }}</th>
                <th>{{ __('resources.external-link-garamantas') }}</th>
            </tr>
            @foreach ($places as $place)
                <tr>
                    <td><a href="{{ route('places.show', $place->id) }}">{{ $place->name }}</a></td>
                    @if($place->latitude != 0)
                    <td class="center-cell">{{ $place->latitude }}</td>
                    @else
                    <td  class="center-cell">null</td>
                    @endif
                    @if($place->latitude != 0)
                    <td class="center-cell">{{ $place->longitude }}</td>
                    @else
                    <td  class="center-cell">null</td>
                    @endif
                    <td class="center-cell"><a href="">{{ __('resources.external-link-open') }}</a></td>
                    </tr>
            @endforeach
        </table>
        <div id="pagination-links">
            <div class="pagination-button"><a href="{{ $places->url(1) }}"> << </a></div>
            <div class="pagination-button"><a href="{{ $places->previousPageUrl() }}"> < </a></div>
            @if($places->lastPage() <= 9)
                @for ($i = 1; $i <= $places->lastPage(); $i++)
                <div class="pagination-button"><a href="{{ $places->url($i) }}"> {{ $i }} </a></div>
                @endfor
            @else
                @if($places->currentPage() <= 5)
                    @for ($i = 1; $i <= 9; $i++)
                    <div class="pagination-button"><a href="{{ $places->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @elseif($places->currentPage() + 5 > $places->lastPage())
                    @for ($i = $places->lastPage() - 9; $i <= $places->lastPage(); $i++)
                    <div class="pagination-button"><a href="{{ $places->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @else
                    @for ($i = $places->currentPage() - 4; $i <= $places->currentPage() + 4; $i++)
                    <div class="pagination-button"><a href="{{ $places->url($i) }}"> {{ $i }} </a></div>
                    @endfor
                @endif
            @endif
            <div class="pagination-button"><a href="{{ $places->nextPageUrl() }}"> > </a></div>
            <div class="pagination-button"><a href="{{ $places->url($places->lastPage()) }}"> >> </a></div>
        </div>
    </div>
@endsection