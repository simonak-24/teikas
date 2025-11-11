<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.title_webpage') }}</title>
</head>
<body>
    <div id="edit">
    <h2>{{ __('resources.title_edit') }}</h2>

    <p><form method="POST" action="{{ route('sources.destroy', $source->id) }}">
            @csrf
            @method('DELETE')
            <button id="delete" type="submit">{{ __('resources.button_delete') }}</button>
    </form></p>
    <br>

    <form action="{{ route('sources.update', $source->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">{{ __('resources.source_identifier') }}: </label>
            <input type="text" id="identifier" name="identifier" value="{{ old('identifier', $source->identifier) }}">
        </div>

        <div>
            <label for="name">{{ __('resources.source_title') }}: </label>
            <input type="text" id="title" name="title" value="{{ old('title', $source->title) }}">
        </div>

        <div>
            <label for="name">{{ __('resources.source_author') }}: </label>
            <input type="text" id="author" name="author" value="{{ old('author', $source->author) }}">
        </div>

        <br>
        <button type="submit">{{ __('resources.button_save') }}</button>
        @error('identifier')
            <div class="error">{{ $message }}</div>
        @enderror
    </form>
    <br>
    </div>
</body>
</html>