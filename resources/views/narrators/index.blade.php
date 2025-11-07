<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
</head>

<body>
    <h1>{{ __('resources.narrator_all') }}</h1>
    <form action="{{ route('narrators.create') }}">
        <button type="submit">{{ __('resources.button_create') }}</button>
    </form>

    <div id="display-list">
        @foreach ($narrators as $narrator)
            <div>
                <h3><a id="show" href="{{ route('narrators.show', $narrator->id) }}">{{ $narrator->fullname }}</a></h3>
            </div>
        @endforeach
    </div>
</body>
</html>