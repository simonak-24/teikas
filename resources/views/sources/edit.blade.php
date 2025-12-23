@extends('site')

@section('title', __('site.title_edit'))

@section('scripts')
    <script src="{{ asset('js/delete-popup.js') }}"></script>
@endsection

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.title_edit') }}</h2>
        <button class="resource-button" onclick="openDeletePopup()">{{ __('site.button_delete') }}</button>
    </div>

    <form action="{{ route('sources.update', $source->id) }}" method="POST">
        @csrf
        @method('PUT')
        <table>
        <tr>
            <td><b><label for="identifier">{{ __('resources.source_identifier') }}: </label></b></td>
            <td><input type="text" id="identifier" name="identifier" value="{{ old('identifier', $source->identifier) }}">
            @if($errors->has('identifier'))<div class="validation-error"> {{ $errors->get('identifier')[0] }} </div>@endif</td>
        </tr>

        <tr>
            <td><b><label for="title">{{ __('resources.source_title') }}: </label></b></td>
            <td><input type="text" id="title" name="title" value="{{ old('title', $source->title) }}">
            @if($errors->has('title'))<div class="validation-error"> {{ $errors->get('title')[0] }} </div>@endif</td>
        </tr>

        <tr>
            <td><b><label for="author">{{ __('resources.source_author') }}: </label></b></td>
            <td><input type="text" id="author" name="author" value="{{ old('author', $source->author) }}">
            @if($errors->has('author'))<div class="validation-error"> {{ $errors->get('author')[0] }} </div>@endif</td>
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
        <form id="delete-form" method="POST" action="{{ route('sources.destroy', $source->id) }}">
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