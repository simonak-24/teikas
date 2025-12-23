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
    
    <form action="{{ route('narrators.update', $narrator->id) }}" method="POST">
        @csrf
        @method('PUT')
        <table>
        <colgroup>
            <col span="1" id="display-item-column" />
            <col span="1" id="display-item-value"/>
        </colgroup>
        <tr>
            <td><b><label for="name">{{ __('resources.person_fullname') }}: </label></b></td>
            <td><input type="text" id="fullname" name="fullname" value="{{ old('fullname', $narrator->fullname) }}">
            @if($errors->has('fullname'))<div class="validation-error"> {{ $errors->get('fullname')[0] }} </div>@endif</td>
        </tr>
        <tr>
            <td><b><label for="gender">{{ __('resources.person_gender') }}: </label></b></td>
            <td><select id="gender" name="gender">
                <option value="M" {{ old('gender', $narrator->gender) == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                <option value="F" {{ old('gender', $narrator->gender) == "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                <option value="?" {{ old('gender', $narrator->gender) == "?" ? 'selected' : '' }}>{{ __('resources.person_unknown') }}</option>
            </select>
            @if($errors->has('gender'))<div class="validation-error"> {{ $errors->get('gender')[0] }} </div>@endif</td>
        </tr>
        <tr>
            <td><b><label for="external_id">{{ __('site.external-link-humma') }}: </label></b></td>
            <td><input type="text" id="external_id" name="external_id" value="{{ old('external_id', $narrator->external_id) }}">
            @if($errors->has('external_id'))<div class="validation-error"> {{ $errors->get('external_id')[0] }} </div>@endif</td>
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
        <form id="delete-form" method="POST" action="{{ route('narrators.destroy', $narrator->id) }}">
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