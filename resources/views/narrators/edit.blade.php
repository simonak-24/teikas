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

    <p><form method="POST" action="{{ route('narrators.destroy', $narrator->id) }}">
            @csrf
            @method('DELETE')
            <button id="delete" type="submit">{{ __('resources.button_delete') }}</button>
    </form></p>
    <br>

    <form action="{{ route('narrators.update', $narrator->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">{{ __('resources.person_fullname') }}: </label>
            <input type="text" id="fullname" name="fullname" value="{{ $narrator->fullname }}">
        </div>

        <div>
            <label for="gender">{{ __('resources.person_gender') }}: </label>
            <select id="gender" name="gender">
                <option value="M" {{ $narrator->gender == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                <option value="F" {{ $narrator->gender == "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                <option value="?" {{ $narrator->gender == "?" ? 'selected' : '' }}>{{ __('resources.person_unknown') }}</option>
            </select>
        </div>

        <br>
        <button type="submit">{{ __('resources.button_save') }}</button>
        @error('fullname')
            <div class="error">{{ $message }}</div>
        @enderror
    </form>
    <br>
    </div>
</body>
</html>