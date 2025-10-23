<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
</head>

<body>
    <h1>{{ __('resources.place_all') }}</h1>
    <form action="{{ route('places.create') }}">
        <button type="submit">{{ __('resources.button_create') }}</button>
    </form>

    <h3><a id="create" href="{{ route('places.create') }}">{{ __('resources.button_create') }}</a></h3>
    <div id="display-list">
        @foreach ($places as $place)
            <div>
                <h3><a id="edit" href="{{ route('places.edit', urlencode($place->name)) }}">{{ $place->name }}</a></h3>
                <p>{{ $place->latitude }} {{ $place->longitude }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>