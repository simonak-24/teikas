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

    <form action="{{ route('sources.store') }}" method="POST">
        @csrf
        @method('POST')

        <div>
            <label for="name">{{ __('resources.source_identifier') }}: </label>
            <input type="text" id="identifier" name="identifier" value="{{ $source->identifier }}">
        </div>

        <div>
            <label for="name">{{ __('resources.source_title') }}: </label>
            <input type="text" id="title" name="title" value="{{ $source->title }}">
        </div>

        <div>
            <label for="name">{{ __('resources.source_author') }}: </label>
            <input type="text" id="author" name="author" value="{{ $source->author }}">
        </div>

        <br>
        <button type="submit">{{ __('resources.button_save') }}</button>
    </form>
    <br>
    </div>
    @error('identifier')
        <div class="error">{{ $message }}</div>
    @enderror
</body>
</html>