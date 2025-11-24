@extends('site')

@section('title', __('resources.title_edit'))

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('resources.title_edit') }}</h2>

        <form method="POST" action="{{ route('places.destroy', $place->id) }}">
            @csrf
            @method('DELETE')
            <button class="resource-button" type="submit">{{ __('resources.button_delete') }}</button>
        </form>
    </div>

    <form action="{{ route('places.update', $place->id) }}" method="POST">
        @csrf
        @method('PUT')
        <table>
        <colgroup>
            <col span="1" id="display-item-column" />
            <col span="1" id="display-item-value"/>
        </colgroup>
        <tr>
            <td><b><label for="name">{{ __('resources.place_name') }}: </label></b></td>
            <td><input type="text" id="name" name="name" value="{{ old('name', $place->name) }}"></td>
        </tr>

        <tr>
            <td><b><label for="latitude">{{ __('resources.place_latitude') }}: </label></b></td>
            <td><input type="number" step="0.000001" id="latitude" name="latitude" value="{{ old('latitude', $place->latitude) }}"></td>
        </tr>

        <tr>
            <td><b><label for="longitude">{{ __('resources.place_longitude') }}: </label></b></td>
            <td><input type="number" step="0.000001" id="longitude" name="longitude" value="{{ old('longitude', $place->longitude) }}"></td>
        </tr>

        <tr>
            <td><b><label for="external_id">{{ __('resources.external-link-humma') }}: </label></b></td>
            <td><input type="number" id="external_id" name="external_id" value="{{ old('external_id', $place->external_id) }}"></td>
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