@extends('site')

@section('title', __('resources.title_edit'))

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('resources.title_edit') }}</h2>

        <form method="POST" action="{{ route('collectors.destroy', $collector->id) }}">
            @csrf
            @method('DELETE')
            <button class="resource-button" type="submit">{{ __('resources.button_delete') }}</button>
        </form>
    </div>
    
    <form action="{{ route('collectors.update', $collector->id) }}" method="POST">
        @csrf
        @method('PUT')
        <table>
        <colgroup>
            <col span="1" id="display-item-column" />
            <col span="1" id="display-item-value"/>
        </colgroup>
        <tr>
            <td><b><label for="fullname">{{ __('resources.person_fullname') }}: </label></b></td>
            <td><input type="text" id="fullname" name="fullname" value="{{ old('fullname', $collector->fullname) }}"></td>
        </tr>
        <tr>
            <td><b><label for="gender">{{ __('resources.person_gender') }}: </label>
            <td><select id="gender" name="gender">
                <option value="M" {{ old('gender', $collector->gender) == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                <option value="F" {{ old('gender', $collector->gender)== "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                <option value="?" {{ old('gender', $collector->gender) == "?" ? 'selected' : '' }}>{{ __('resources.person_unknown') }}</option>
            </select></td>
        </tr>
        <tr>
            <td><b><label for="external_id">{{ __('resources.external-link-humma') }}: </label></b></td>
            <td><input type="number" id="external_id" name="external_id" value="{{ old('external_id', $collector->external_id) }}"></td>
        </tr>
        </table>
        <br>
        <button class="resource-button" type="submit">{{ __('resources.button_save') }}</button>
    </form>
    <br>
    <div id="validation-errors">
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endsection