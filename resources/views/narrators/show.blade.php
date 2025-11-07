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
        <h2>{{ $narrator->fullname }}</h2>
            <p>{{ $narrator->fullname }}{{ $narrator->gender ? ',' : '' }} {{ $narrator->gender }}</p>
            <form action="{{ route('narrators.edit', $narrator->id) }}">
                <button type="submit">{{ __('resources.button_edit') }}</button>
            </form>
        </div>
    </div>
</body>
</html>