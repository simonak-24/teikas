@extends('site')

@if($exists)
@section('title', __('site.title_edit'))
@else
@section('title', __('site.title_create'))
@endif

@section('scripts')
    <script src="{{ asset('js/delete-popup.js') }}"></script>
@endsection

@section('content')
    <div id="heading">
        @if($exists)
        <h2><a class="return-link" href="{{ route('places.show', $place->id) }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.title_edit') }}</h2>
        <button class="resource-button" onclick="openDeletePopup()">{{ __('site.button_delete') }}</button>
        @else
        <h2><a class="return-link" href="{{ route('places.index') }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.button_create') }}</h2>
        @endif
    </div>

    @if($exists)
    <form action="{{ route('places.update', $place->id) }}" method="POST">
    @else
    <form action="{{ route('places.store') }}" method="POST">
    @endif
        @csrf
        @if($exists)
        @method('PUT')
        @else
        @method('POST')
        @endif

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

@if($exists)
@section('popup')
    <div id="resource-delete" class="delete-popup">
        <div class="heading">
            <h3>{{ __('site.delete_confirmation') }}</h3>
            <a class="popup-link" onclick="closeDeletePopup()">X</a>
        </div>
        <p>{{ __('site.delete_question') }}</p>
        @if($place->legends()->count() > 1)
        <p>{{ __('resources.place_delete-multiple', ['count' => $place->legends()->count()]) }}</p>
        @elseif($place->legends()->count() > 0)
        <p>{{ __('resources.place_delete-single') }}</p>
        @endif
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
@endif