@extends('site')

@section('title', $source->identifier)

@section('content')
    <div id="heading">
        <h2>
            <a class="resource-link" href="{{ route('sources.index').'?page='.$page }}">{{ __('resources.source_all') }}</a>
            &nbsp;>&nbsp;&nbsp;{{ $source->identifier }}
        </h2>

        @if(Auth::check())
        <form action="{{ route('sources.edit', $source->id) }}">
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
            <th>{{ __('resources.source_identifier') }}</th>
            <td>{{ $source->identifier }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.source_title') }}</th>
            <td>{{ $source->title }}</td>
        </tr>
        <tr>
            <th>{{ __('resources.source_author') }}</th>
            <td>{{ $source->author }}</td>
        </tr>
    </table>
    <br>
@endsection