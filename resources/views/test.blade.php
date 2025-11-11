<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
</head>

<body>
    <h1>{{ __('resources.title_webpage') }}</h1>
    <div>
        <h3><a href="{{ route('legends.index') }}">{{ __('resources.legend_all') }}</a></h3>
        <h3><a href="{{ route('collectors.index') }}">{{ __('resources.collector_all') }}</a></h3>
        <h3><a href="{{ route('narrators.index') }}">{{ __('resources.narrator_all') }}</a></h3>
        <h3><a href="{{ route('places.index') }}">{{ __('resources.place_all') }}</a></h3>
        <h3><a href="{{ route('sources.index') }}">{{ __('resources.source_all') }}</a></h3>
    </div>
</body>
</html>