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

    <div id="display-list">
        @foreach ($places as $place)
            <div>
                <h3><a id="show" href="{{ route('places.show', $place->id) }}">{{ $place->name }}</a></h3>
            </div>
        @endforeach
    </div>
</body>
</html>