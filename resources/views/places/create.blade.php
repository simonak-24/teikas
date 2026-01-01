@extends('site')

@section('title', __('site.title_create'))

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.button_create') }}</h2>
    </div>
    
    <form action="{{ route('places.store') }}" method="POST">
        @csrf
        @method('POST')
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
            <td><input type="number" step="0.000001" id="latitude" name="latitude" value="{{ old('latitude', $place->latitude) }}">
            @if($errors->has('latitude'))<div class="validation-error"> {{ $errors->get('latitude')[0] }} </div>@endif</td>
        </tr>

        <tr>
            <td><b><label for="longitude">{{ __('resources.place_longitude') }}: </label></b></td>
            <td><input type="number" step="0.000001" id="longitude" name="longitude" value="{{ old('longitude', $place->longitude) }}">
            @if($errors->has('longitude'))<div class="validation-error"> {{ $errors->get('longitude')[0] }} </div>@endif</td>
        </tr>
        </table>
        <br>
        <button class="resource-button" type="submit">{{ __('site.button_save') }}</button>
    </form>
    <br>
@endsection