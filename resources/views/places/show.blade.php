<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script> 
        document.addEventListener('DOMContentLoaded', () => {
            var lat = <?=($place->latitude)?>;
            var lon = <?=($place->longitude)?>;
            if (!(lat == 0 && lon == 0)) {
                var map = L.map("map").setView([lat, lon], 13);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                var marker = L.marker([lat, lon]).addTo(map);
            }
        });
    </script>
</head>
<body>
    <div>
        <div>
        <h2>{{ $place->name }}</h2>
            <p>{{ $place->latitude }}, {{ $place->longitude }}</p>
            <form action="{{ route('places.edit', $place->id) }}">
                <button type="submit">{{ __('resources.button_edit') }}</button>
            </form>
        </div>

        <div><a href="https://www.google.com/maps/place/{{ $place->latitude }},{{ $place->longitude }}" target="_blank">{{ __('resources.place_external-map') }}</a></div>
        <div id="map" style="height:40vh">
        </div>
    </div>
</body>
</html>