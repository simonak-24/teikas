@extends('site')

@section('title', __('resources.title_home'))

@section('stylesheets')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        var openPopupId = -1;

        function openPopup(e) {
            if (openPopupId > -1) {
                document.getElementById("place-" + openPopupId).style.display = "none";
            }
            document.getElementById("place-" + e.target.options.id).style.display = "block";
            openPopupId = e.target.options.id;
        }

        function closePopup() {
            document.getElementById("place-" + openPopupId).style.display = "none";
            openPopupId = -1;
        }

        document.addEventListener('DOMContentLoaded', () => {
            var coordinates = <?=($coordinates)?>;
            var markers = new L.MarkerClusterGroup();
            var map = L.map("analysis-map").setView([56.880139, 24.606222], 7);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 16,
                minZoom: 6,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            for (key in coordinates) {
                var lat = coordinates[key][0];
                var lon = coordinates[key][1];
                if (!(lat == 0 && lon == 0)) {
                    markers.addLayer(L.marker([lat, lon], { id : key }).on('click', openPopup));
                }
            }
            map.addLayer(markers);
        });
    </script>
@endsection

@section('content')
    <br>
    <div id="analysis-map">
    </div>
@endsection

@section('popup')
    @foreach($places as $place)
        <div id="place-{{ $place->id }}" class="place-legends">
            <div class="heading">
                <h3>{{ $place->name }}</h3>
                <a class="popup-link" onclick="closePopup()">X</a>
            </div>
            <br>
            <div class="place-legend">
                <table>
                    <colgroup>
                        <col span="1" id="place-legend-identifier" />
                        <col span="1" id="place-legend-chapter"/>
                        <col span="1" id="place-legend-title"/>
                    </colgroup>
                    <tr>
                        <th>{{ __('resources.legend_identifier') }}</th>
                        <th>{{ __('resources.legend_chapter-lv') }}</th>
                        <th>{{ __('resources.legend_title-lv') }}</th>
                    </tr>
                    @foreach($place->legends as $legend)
                    <tr>
                        <td><a href="{{ route('legends.show', $legend->identifier) }}" target="_blank">{{ $legend->identifier }}</a></td>
                        <td>{{ $legend->chapter_lv }}</td>
                        <td><a href="{{ route('legends.show', $legend->identifier) }}" target="_blank">{{ $legend->title_lv }}</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endforeach
@endsection