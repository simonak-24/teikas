@extends('site')

@section('title', __('site.title_home'))

@section('stylesheets')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/leaflet-heatmap-layer/dist/leaflet-heatmap-layer.umd.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const DEFAULT_LATITUDE = 57.39098;
        const DEFAULT_LONGITUDE = 23.793624;

        var openPopupId = -1;
        var map;
        var baseLayer = null;
        var markers = null;
        var heatLayer = null;
        var heatRadius = 30;

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

        function setMap() {
            map = L.map("map-visual").setView([56.880139, 24.606222], 7);
            document.getElementById("radio-arcgis").checked = true;
            setBase("arcgis");
        }

        function setBase(type) {    // jāsaliek https://stackoverflow.com/questions/28543752/multiple-radio-button-groups-in-one-form, lai radio pogas strādātu.
            if (baseLayer != null) {
                map.removeLayer(baseLayer);
                baseLayer = null;
            }
            if (type == "arcgis") {
                baseLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
	                maxZoom: 16,
                    minZoom: 6,
                    attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'
                }).addTo(map);
                document.getElementById("radio-openstreetmap").checked = false;
            } else {
                baseLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 16,
                    minZoom: 6,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                document.getElementById("radio-arcgis").checked = false;
            }
        }

        function toggleMarkers () {
            if (heatLayer != null) {
                map.removeLayer(heatLayer);
                heatLayer = null;
            }
            var coordinates = <?=($coordinates)?>;
            markers = new L.MarkerClusterGroup();

            for (key in coordinates) {
                var lat = coordinates[key][0];
                var lon = coordinates[key][1];
                if (!(lat == 0 && lon == 0)) {
                    markers.addLayer(L.marker([lat, lon], { id : key }).on('click', openPopup));
                } else {
                    if (!(<?=($exclude_unknown)?>)) {
                        markers.addLayer(L.marker([DEFAULT_LATITUDE, DEFAULT_LONGITUDE], { id : key }).on('click', openPopup))
                    }
                }
            }
            map.addLayer(markers);

            document.getElementById("map-heatmap").style.display = "block";
            document.getElementById("map-markers").style.display = "none";
            document.getElementById("map-slider").style.display = "none";
        }

        function toggleHeatmap() {
            if (markers != null) {
                markers.clearLayers();
                markers= null;
            }
            if (heatLayer != null) {
                map.removeLayer(heatLayer);
                heatLayer = null;
            }
            var coordinates = <?=($coordinates)?>;
            var heat = new Map();
            var heatMarkers = [];

            var maxCount = 0;
            for (key in coordinates) {
                var lat = coordinates[key][0];
                var lon = coordinates[key][1];
                var count = coordinates[key][2];
                if (!(<?=($exclude_unknown)?>) && (lat == 0 && lon == 0)) {
                    lat = DEFAULT_LATITUDE;
                    lon = DEFAULT_LONGITUDE;
                }
                if (!(lat == 0 && lon == 0)) {
                    var heatNumber = lat * 1000000 + lon;
                    var heatKey = heatNumber.toString();
                    if (heat.has(heatKey)) {
                        var mark = heat.get(heatKey);
                        mark[2] = mark[2] + count;
                        heat.set(heatKey, mark);
                    } else {
                        heat.set(heatKey, [lat, lon, count]);
                    }
                    if (count > maxCount) {
                        maxCount = count;
                    }
                }
            }
            for (const [key, value] of heat) {
                var intensity = value[2] / maxCount;
                heatMarkers.push([parseFloat(value[0]), parseFloat(value[1]), intensity]);  // SMILTENE LMAO
            }
            heatLayer = L.heatLayer(heatMarkers, { radius: heatRadius, blur: 20 }).addTo(map);

            document.getElementById("map-markers").style.display = "block";
            document.getElementById("map-heatmap").style.display = "none";
            document.getElementById("map-slider").style.display = "flex";
        }

        function setHeatRadius() {
            heatRadius = document.getElementById("heat-slider").value;
            toggleHeatmap();
        }

        function toggleBaseList() {
            // var baseButton = document.getElementById("map-base-button");
            var baseList = document.getElementById("map-base-list");
            if (baseList.style.display == "flex") {
                // baseButton.style.display = "none";
                baseList.style.display = "none";
            }
            else {
                // baseButton.style.display = "block";
                baseList.style.display = "flex";
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setMap();
            toggleMarkers();
            document.getElementById("map-markers").addEventListener("click", () => {
                toggleMarkers();
            });
            document.getElementById("map-heatmap").addEventListener("click", () =>  {
                toggleHeatmap();
            });
            document.getElementById("map-slider").addEventListener("input", () =>  {
                setHeatRadius();
            });

            document.getElementById("map-base-button").addEventListener("click", () =>  {
                toggleBaseList();
                document.getElementById("radio-openstreetmap").addEventListener("change", () =>  {
                    setBase("openstreetmap");
                });
                document.getElementById("radio-arcgis").addEventListener("change", () =>  {
                    setBase("arcgis");
                });
            });
            document.getElementById("map-base-list").addEventListener("mouseleave", () =>  {
                toggleBaseList();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            $('.select2-titles').select2();
            var selected = <?=json_encode(old('titles', ($titles_selected)))?>;
            var selected_data = [];
            for (var key in selected) {
                var obj = selected[key];
                selected_data.push(obj);
            }
            $(".select2-titles").val(selected_data);
            $(".select2-titles").trigger('change');
        });
    </script>
@endsection

@section('content')
    <br>
    <div id="map-all">
        <div id="map-visual"></div>
        <div id="map-controls">
            <button id="map-markers" class="map-item map-button"></button>
            <button id="map-heatmap" class="map-item map-button"></button>
            <div id="map-slider" class="map-item map-button">
                <input type="range" min="10" max="70" value="30" id="heat-slider" class="slider"">
            </div>
            <div id="map-base">
                <div id="map-base-list" class="map-item map-list">
                    <div class="map-base-item"><input type="radio" id="radio-arcgis" class="radio-button" value="arcgis" /><label for="radio-arcgis" class="radio-label">ArcGIS WorldTopoMap</label></div>
                    <div class="map-base-item"><input type="radio" id="radio-openstreetmap" class="radio-button" value="openstreetmap" /><label for="radio-openstreemap" class="radio-label">OpenStreetMap</label></div>
                </div>
                <button id="map-base-button" class="map-item map-button"></button>
            </div>
        </div>
    </div>
    <br>
    <h3>{{ __('site.map_filter') }}</h3>
    <form id="home-select" action="{{ route('home') }}" method="GET">
        <div id="home-select-titles">
        <select id="titles" name="titles[]" class="select2-titles" multiple>
            @foreach ($chapters_titles as $chapter => $titles)
                <optgroup label="{{ $chapter }}">
                    @foreach($titles as $title)
                        <option value="{{ $title[0] }}">
                            {{ $title[0] }} / {{ $title[1] }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <button class="resource-button" type="submit">{{ __('site.button_filter') }}</button>
        </div>
        <div id="home-select-checkboxes">
        <input type="checkbox" id="exclude_unknown" name="exclude_unknown" @if($exclude_unknown == 1) checked @endif/>
        <label for="include_unknown">Neiekļaut nezināmās vietas</label>
        </div>
    </form>
    <p>{{ __('site.map_information') }}</p>
    <br>
    <div id="information">
        @foreach(__('site.information_home') as $paragraph)
        <p>{{ $paragraph }}</p>
        @endforeach
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
                        <col span="1" id="place-legend-text"/>
                    </colgroup>
                    <tr>
                        <th>{{ __('resources.legend_identifier') }}</th>
                        <th>{{ __('resources.legend_chapter-lv') }}</th>
                        <th>{{ __('resources.legend_title-lv') }}</th>
                        <th>{{ __('resources.legend_preview') }}</th>
                    </tr>
                    @foreach($place->legends as $legend)
                    <tr>
                        <td><a href="{{ route('legends.show', $legend->identifier) }}" target="_blank">{{ $legend->identifier }}</a></td>
                        <td><a href="{{ route('navigation.chapter', $legend->chapter_lv) }}" target="_blank">{{ $legend->chapter_lv }} / {{ $legend->chapter_de }}</a></td>
                        <td><a href="{{ route('navigation.subchapter', [$legend->chapter_lv, $legend->title_lv]) }}" target="_blank">{{ $legend->title_lv }} / {{ $legend->title_de }}</a></td>
                        <td>{{ Str::limit($legend->text_lv, 120) }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endforeach
@endsection