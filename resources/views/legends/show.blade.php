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
        <h2>{{ $legend->title_lv }}</h2>
            <p>[{{ $legend->identifier }}] {{ $legend->metadata }}</p>
            <p>{{ $legend->text_lv }}</p>
            <form action="{{ route('legends.edit', $legend->identifier) }}">
                <button type="submit">{{ __('resources.button_edit') }}</button>
            </form>
            @foreach ($legend->sources as $link)
            <div>
                <p>{{ $link->source->title }}</p>
            </div>
        @endforeach
        </div>
    </div>
</body>
</html>