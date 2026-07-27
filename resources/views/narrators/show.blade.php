@extends('site')

@section('title', $narrator->fullname)

@section('scripts')
    <script src="{{ asset('js/hidden-submit.js') }}"></script>
@endsection

@section('content')
    <div id="heading">
        <h2>
            <a class="resource-link" href="{{ route('narrators.index').'?page='.$page }}">{{ __('resources.narrator_all') }}</a>
            &nbsp;>&nbsp;&nbsp;{{ $narrator->fullname }}
        </h2>

        @if(Auth::check())
        <form action="{{ route('narrators.edit', $narrator->id) }}">
            <button class="resource-button" type="submit">{{ __('site.button_edit') }}</button>
        </form>
        @endif
    </div>

    <table>
        <colgroup>
            <col span="1" id="display-item-column" />
            <col span="1" id="display-item-value"/>
        </colgroup>
        <tr>
            <th>{{ __('resources.person_fullname') }}</th>
            <td>{{ $narrator->fullname }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.person_gender') }}</th>
            <td>@if($narrator->gender == 'M')
                {{ __('resources.person_man') }}
                @elseif($narrator->gender == 'F')
                {{ __('resources.person_woman') }}
                @else
                {{ __('resources.person_unknown') }}
                @endif</td>
        </tr>
        <tr>
            <th>{{ __('site.external-link-garamantas') }}</th>
            <td>@if(isset($narrator->external_identifier))<a href="{{ 'https://garamantas.lv/lv/person/'.$narrator->external_identifier }}" target="_blank">{{ __('site.external-link-open')  }}</a>@endif</td>
        </tr>
        <tr>
            <th>{{ __('resources.narrator_count') }}</th>
            @if($narrator->legends()->count() > 0)
            <td><a href="{{ route('legends.index').'?narrator='.urlencode($narrator->fullname) }}">{{ $narrator->legends()->count() }}</a></td>
            @else
            <td>0</td>
            @endif
        </tr>
    </table>
    <br>

    <h3>{{ __('resources.narrator_legends') }}</h3>
    @include('legends.table')
    <div id="pagination-section">
        @include('partials.pagination')
    </div>
@endsection