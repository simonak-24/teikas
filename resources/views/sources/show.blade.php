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
        <h2>{{ $source->identifier }}</h2>
            <p>{{ $source->title }} ({{ $source->author }})</p>
            <form action="{{ route('sources.edit', $source->id) }}">
                <button type="submit">{{ __('resources.button_edit') }}</button>
            </form>
        </div>
    </div>
</body>
</html>