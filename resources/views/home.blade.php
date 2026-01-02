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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
            var map = L.map("home-map").setView([56.880139, 24.606222], 7);
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
    <div id="home-map">
    </div>
    <form id="home-select" action="{{ route('home') }}" method="GET">
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
    </form>
    <div id="information">
        <p>{{ __('site.text_information_1') }}</p>
        <p>{{ __('site.text_information_2') }}</p>
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