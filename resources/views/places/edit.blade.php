@extends('site')

@section('title', __('site.title_edit'))

@section('scripts')
    <script src="{{ asset('js/delete-popup.js') }}"></script>
@endsection

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ route('places.show', $place->id) }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.title_edit') }}</h2>
        <button class="resource-button" onclick="openDeletePopup()">{{ __('site.button_delete') }}</button>
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
            <td><input type="text" id="name" name="name" value="{{ old('name', $place->name) }}">
            @if($errors->has('name'))<div class="validation-error"> {{ $errors->get('name')[0] }} </div>@endif</td>
        </tr>

        <tr>
            <td><b><label for="latitude">{{ __('resources.place_latitude') }}: </label></b></td>
            <td><input type="text" id="latitude" name="latitude" value="{{ old('latitude', $place->latitude) }}">
            @if($errors->has('latitude'))<div class="validation-error"> {{ $errors->get('latitude')[0] }} </div>@endif</td>
        </tr>

        <tr>
            <td><b><label for="longitude">{{ __('resources.place_longitude') }}: </label></b></td>
            <td><input type="text" id="longitude" name="longitude" value="{{ old('longitude', $place->longitude) }}">
            @if($errors->has('longitude'))<div class="validation-error"> {{ $errors->get('longitude')[0] }} </div>@endif</td>
        </tr>
        </table>
        <br>
        <button class="resource-button" type="submit">{{ __('site.button_save') }}</button>
    </form>
    <br>
@endsection

@section('popup')
    <div id="resource-delete" class="delete-popup">
        <div class="heading">
            <h3>{{ __('site.delete_confirmation') }}</h3>
            <a class="popup-link" onclick="closeDeletePopup()">X</a>
        </div>
        <p>{{ __('site.delete_question') }}</p>
        <form id="delete-form" method="POST" action="{{ route('places.destroy', $place->id) }}">
            @csrf
            @method('DELETE')
        </form>
        <br>
        <div class="button-group">
            <button class="resource-button" onclick="closeDeletePopup()">{{ __('site.button_return') }}</button>
            <button form="delete-form" class="resource-button" type="submit">{{ __('site.button_delete') }}</button>
        </div>
    </div>
@endsection