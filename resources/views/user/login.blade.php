<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ __('site.title_login') }} | {{ __('site.title_webpage') }}</title>
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
</head>

<body>
    <div id="login-frame">
        <div id="login-pane">
            <form id="return-form" action="{{ route('home') }}" method="GET"></form>
            <form id="login-form" action="{{ route('authenticate') }}" method="POST">
                @csrf
                @method('POST')
                <label for="name">{{ __('resources.user_name') }}: </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
                @if($errors->has('name'))
                <div class="validation-error"> {{ $errors->get('name')[0] }} </div>
                @else
                <br>
                @endif
                <br>
                <label for="password">{{ __('resources.user_password') }}: </label>
                <input type="password" id="password" name="password" value="{{ old('password') }}">
                @if($errors->has('password'))
                <div class="validation-error"> {{ $errors->get('password')[0] }} </div>
                @else
                <br>
                @endif
                <br>
            </form>
            <div id="login-buttons">
                <button id="back-button" class="user-button" type="submit" form="return-form">{{ __('site.button_back') }}</button>
                <button id="login-button" class="user-button" type="submit" form="login-form">{{ __('site.button_login') }}</button>
            </div>
        </div>
    </div>
</body>
</html>