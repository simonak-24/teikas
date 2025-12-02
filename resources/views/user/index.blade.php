@extends('site')

@section('title', __('resources.user_all'))

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>
    <script>
        var openPopupId = -1;

        function openPopup(id) {
            if (openPopupId > -1) {
                document.getElementById("user-" + openPopupId).style.display = "none";
            }
            document.getElementById("user-" + id).style.display = "block";
            openPopupId = id
        }

        function closePopup() {
            document.getElementById("user-" + openPopupId).style.display = "none";
            openPopupId = -1;
        }
    </script>
@endsection

@section('content')
    <div id="heading">
        <h2>{{ __('resources.user_all') }}</h2>
    </div>

    <div id="display-grid">
        @foreach ($users as $user)
            <div><a class="" onclick="openPopup('{{ $user->id }}')">{{ $user->name }}</a></div>
        @endforeach
    </div>

    <div id="user-create">
        <h3>{{ __('site.title_create') }}</h3>
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            @method('POST')
            <label for="name">{{ __('resources.user_name') }}: </label>
            <input type="text" id="name" name="name"  class="user-input" value="{{ old('name') }}">
            @if($errors->has('name'))
            <div class="validation-error"> {{ $errors->get('name')[0] }} </div>
            @else
            <br>
            @endif
            <br><br>
            <label for="password">{{ __('resources.user_password') }}: </label>
            <input type="password" id="password" name="password"  class="user-input">
            <br><br>
            <label for="password_confirmation">{{ __('resources.user_confirmation') }}: </label>
            <input type="password" id="password_confirmation" name="password_confirmation"  class="user-input">
            @if($errors->has('password'))
            <div class="validation-error"> {{ $errors->get('password')[0] }} </div>
            @else
            <br>
            @endif
            <br>
            <button class="resource-button" type="submit">{{ __('site.button_create') }}</button>
        </form>
    </div>
@endsection

@section('popup')
@foreach($users as $user)
    <div id="user-{{ $user->id }}" class="delete-popup">
            <div class="heading">
                <h3>{{ __('site.delete_confirmation') }}</h3>
                <a class="popup-link" onclick="closePopup()">X</a>
            </div>
            <p>{{ __('site.delete_question', ['resource' => 'user']) }}</p>
            <br>
            <div class="button-group">
                <form><button class="resource-button" onclick="closePopup()">{{ __('site.button_return') }}</button></form>
                <form method="POST" action="{{ route('user.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="resource-button" type="submit">{{ __('site.button_delete') }}</button>
                </form>
            </div>
        </div>
@endforeach
@endsection