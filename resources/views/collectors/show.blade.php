@extends('site')

@section('title', $collector->fullname)

@section('content')
    <div id="heading">
        <h2>
            <a class="resource-link" href="{{ route('collectors.index').'?page='.$page }}">{{ __('resources.collector_all') }}</a>
            &nbsp;>&nbsp;&nbsp;{{ $collector->fullname }}
        </h2>

        @if(Auth::check())
        <form action="{{ route('collectors.edit', $collector->id) }}">
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
            <td>{{ $collector->fullname }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.person_gender') }}</th>
            <td>{{ $collector->gender }}</td>
        </tr>
        <tr>
            <th>{{ __('site.external-link-garamantas') }}</th>
            <td>@if(isset($collector->external_identifier))<a href="{{ 'https://garamantas.lv/lv/person/'.$collector->external_identifier }}" target="_blank">{{ __('site.external-link-open')  }}</a>@endif</td>
        </tr>
    </table>
    <br>
@endsection