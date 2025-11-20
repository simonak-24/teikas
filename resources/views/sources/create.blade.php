@extends('site')

@section('title', __('resources.title_create'))

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('resources.button_create') }}</h2>
    </div>

    <form action="{{ route('sources.store') }}" method="POST">
        @csrf
        @method('POST')
        <table>
        <tr>
            <td><b><label for="identifier">{{ __('resources.source_identifier') }}: </label></b></td>
            <td><input type="text" id="identifier" name="identifier" value="{{ old('identifier', $source->identifier) }}"></td>
        </tr>

        <tr>
            <td><b><label for="title">{{ __('resources.source_title') }}: </label></b></td>
            <td><input type="text" id="title" name="title" value="{{ old('title', $source->title) }}"></td>
        </tr>

        <tr>
            <td><b><label for="author">{{ __('resources.source_author') }}: </label></b></td>
            <td><input type="text" id="author" name="author" value="{{ old('author', $source->author) }}"></td>
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