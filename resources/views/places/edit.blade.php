<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
</head>
<body>
    <div id="edit">
    <h2>{{ __('resources.title_edit') }}</h2>

    <p><form method="POST" action="{{ route('places.destroy', $place->id) }}">
            @csrf
            @method('DELETE')
            <button id="delete" type="submit">{{ __('resources.button_delete') }}</button>
    </form></p>
    <br>

    <form action="{{ route('places.update', $place->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">{{ __('resources.place_name') }}: </label>
            <input type="text" id="name" name="name" value="{{ old('name', $place->name) }}">
        </div>

        <div>
            <label for="latitude">{{ __('resources.place_latitude') }}: </label>
            <input type="number" step="0.000001" id="latitude" name="latitude" value="{{ old('latitude', $place->latitude) }}">
        </div>

        <div>
            <label for="longitude">{{ __('resources.place_longitude') }}: </label>
            <input type="number" step="0.000001" id="longitude" name="longitude" value="{{ old('longitude', $place->longitude) }}">
        </div>

        <br>
        <button type="submit">{{ __('resources.button_save') }}</button>
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror
    </form>
    <br>
    </div>
</body>
</html>