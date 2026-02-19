@extends('site')

@section('title', __('site.title_about'))

@section('content')
    <div id="information">
        <div id="about">
            @foreach(__('site.information_about') as $paragraph)
            @if($paragraph != '')
            <p>{{ $paragraph }}</p>
            @else
            <br>
            @endif
            @endforeach
            <img class="logo" src="{{ asset('images/logo_dhc.png') }}">
            <img class="logo" src="{{ asset('images/logo_vpp.jpg') }}">
        </div>
        <div id="contacts">
            <h3>{{ __('site.information_contacts-title') }}</h3>
            <p>{{ __('site.information_contacts-text') }}<a class="external-link" href="mailto:dhc@lu.lv" target="_blank">dhc@lu.lv</a>.</p>
        </div>
        <div id="dataset">
            <h3>{{ __('site.information_dataset-title') }}</h3>
            <p>{{ __('site.information_dataset-text') }}<a class="external-link" href="http://hdl.handle.net/20.500.12574/147" target="_blank">http://hdl.handle.net/20.500.12574/147</a>.</p>
        </div>
    </div>
@endsection