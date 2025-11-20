@extends('site')

@section('title', $place->name)

@section('stylesheets')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script> 
        document.addEventListener('DOMContentLoaded', () => {
            var lat = <?=($place->latitude)?>;
            var lon = <?=($place->longitude)?>;
            if (!(lat == 0 && lon == 0)) {
                var map = L.map("resource-map").setView([lat, lon], 10);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                var marker = L.marker([lat, lon]).addTo(map);
            }
        });
    </script>
@endsection

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ route('places.index').'?page='.$page }}">&nbsp;<&nbsp;</a>&nbsp;{{ $place->name }}</h2>

        <form action="{{ route('places.edit', $place->id) }}">
            <button class="resource-button" type="submit">{{ __('resources.button_edit') }}</button>
        </form>
    </div>
    
    <table>
        <colgroup>
            <col span="1" id="display-item-column" />
            <col span="1" id="display-item-value"/>
        </colgroup>
        <tr>
            <th>{{ __('resources.place_name') }}</th>
            <td>{{ $place->name }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.place_latitude') }}</th>
            <td>{{ $place->latitude }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.place_longitude') }}</th>
            <td>{{ $place->longitude }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.place_map') }}</th>
            <td><div id="resource-map"></div></td>
        </tr>
        <tr>
            <th>{{ __('resources.external-link-map') }}</th>
            <td><div><a href="https://www.google.com/maps/place/{{ $place->latitude }},{{ $place->longitude }}" target="_blank">{{ __('resources.external-link-open') }}</a></div></td>
        </tr>
    </table>
    <br>
@endsection