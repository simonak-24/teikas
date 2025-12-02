@extends('site')

@section('title', __('site.title_create'))

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('site.button_create') }}</h2>
    </div>
    
    <form action="{{ route('narrators.store') }}" method="POST">
        @csrf
        @method('POST')
        <table>
            <colgroup>
                <col span="1" id="display-item-column" />
                <col span="1" id="display-item-value"/>
            </colgroup>
            <tr>
                <td><b><label for="fullname">{{ __('resources.person_fullname') }}: </label></b></td>
                <td><input type="text" id="fullname" name="fullname" value="{{ old('fullname', $narrator->fullname) }}">
                @if($errors->has('fullname'))<div class="validation-error"> {{ $errors->get('fullname')[0] }} </div>@endif</td>
            </tr>
            <tr>
                <td><b><label for="gender">{{ __('resources.person_gender') }}: </label></b></td>
                <td><select id="gender" name="gender">
                    <option value="M" {{ old('gender', $narrator->gender) == "M" ? 'selected' : '' }}>{{ __('resources.person_man') }}</option>
                    <option value="F" {{ old('gender', $narrator->gender) == "F" ? 'selected' : '' }}>{{ __('resources.person_woman') }}</option>
                    <option value="?" {{ old('gender', $narrator->gender) == "?" ? 'selected' : ''  }}>{{ __('resources.person_unknown') }}</option>
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