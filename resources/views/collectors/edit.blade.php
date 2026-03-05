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
        <h2><a class="return-link" href="{{ route('collectors.show', $collector->id) }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.title_edit') }}</h2>
        @if($collector->id != 1)
        <button class="resource-button" onclick="openDeletePopup()">{{ __('site.button_delete') }}</button>
        @endif
        @else
        <h2><a class="return-link" href="{{ route('collectors.index') }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.button_create') }}</h2>
        @endif
    </div>
    
    @if($exists)
    <form action="{{ route('collectors.update', $collector->id) }}" method="POST">
    @else
    <form action="{{ route('collectors.store') }}" method="POST">
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
            <td><b><label for="fullname">{{ __('resources.person_fullname') }}: </label></b></td>
            <td><input type="text" id="fullname" name="fullname" value="{{ old('fullname', $collector->fullname) }}">
            @if($errors->has('fullname'))<div class="validation-error"> {{ $errors->get('fullname')[0] }} </div>@endif</td>
        </tr>
        <tr>
            <td><b><label for="gender">{{ __('resources.person_gender') }}: </label>
            <td><select id="gender" name="gender">
                <option value="M" {{ old('gender', $collector->gender) == 'M' ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                <option value="F" {{ old('gender', $collector->gender) == 'F' ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                <option value="?" {{ old('gender', $collector->gender) == '?' ? 'selected' : '' }}>{{ __('resources.person_unknown') }}</option>
            </select>
            @if($errors->has('gender'))<div class="validation-error"> {{ $errors->get('gender')[0] }} </div>@endif</td>
        </tr>
        <tr>
            <td><b><label for="external_id">{{ __('site.external-link-humma') }}: </label></b></td>
            <td><input type="text" id="external_id" name="external_id" value="{{ old('external_id', $collector->external_identifier) }}">
            @if($errors->has('external_id'))<div class="validation-error"> {{ $errors->get('external_id')[0] }} </div>@endif</td>
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
        @if($collector->legends()->count() > 1)
        <p>{{ __('resources.collector_delete-multiple', ['count' => $collector->legends()->count()]) }}</p>
        @elseif($collector->legends()->count() > 0)
        <p>{{ __('resources.collector_delete-single') }}</p>
        @endif
        <form id="delete-form" method="POST" action="{{ route('collectors.destroy', $collector->id) }}">
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