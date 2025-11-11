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

    <form action="{{ route('narrators.store') }}" method="POST">
        @csrf
        @method('POST')

        <div>
            <label for="name">{{ __('resources.person_fullname') }}: </label>
            <input type="text" id="fullname" name="fullname" value="{{ old('fullname', $narrator->fullname) }}">
        </div>

        <div>
            <label for="gender">{{ __('resources.person_gender') }}: </label>
            <select id="gender" name="gender">
                <option value="M" {{ old('gender', $narrator->gender) == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                <option value="F" {{ old('gender', $narrator->gender) == "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                <option value="?" {{ old('gender', $narrator->gender) == "?" ? 'selected' : ''  }}>{{ __('resources.person_unknown') }}</option>
            </select>
        </div>

        <br>
        <button type="submit">{{ __('resources.button_save') }}</button>
    </form>
    <br>
    </div>
    @error('name')
        <div class="error">{{ $message }}</div>
    @enderror
</body>
</html>