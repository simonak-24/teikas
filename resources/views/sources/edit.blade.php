@extends('site')

@section('title', __('site.title_edit'))

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.title_edit') }}</h2>

        <form method="POST" action="{{ route('sources.destroy', $source->id) }}">
            @csrf
            @method('DELETE')
            <button class="resource-button" type="submit">{{ __('site.button_delete') }}</button>
        </form>
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