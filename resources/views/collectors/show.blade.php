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
        <h2>{{ $collector->fullname }}</h2>
            <p>{{ $collector->fullname }}{{ $collector->gender ? ',' : '' }} {{ $collector->gender }}</p>
            <form action="{{ route('collectors.edit', $collector->id) }}">
                <button type="submit">{{ __('resources.button_edit') }}</button>
            </form>
        </div>
    </div>
</body>
</html>