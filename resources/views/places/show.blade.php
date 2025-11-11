<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
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
    </div>
</body>
</html>