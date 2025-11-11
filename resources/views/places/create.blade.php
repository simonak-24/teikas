<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
</head>
<body>
    <div id="create">
    <h2>{{ __('resources.button_create') }}</h2>

    <form action="{{ route('places.store') }}" method="POST">
        @csrf
        @method('POST')

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
    </form>
    <br>
    </div>
    @error('name')
        <div class="error">{{ $message }}</div>
    @enderror
</body>
</html>