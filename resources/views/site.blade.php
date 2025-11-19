<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Metadata -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') | {{ __('resources.title_webpage') }}</title>
    <!-- Stylesheets -->
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    @yield('stylesheets')
    <!-- Scripts -->
    @yield('scripts')
</head>

<body>
    <div id="site-container">
        <header>
            <nav id="nav-bar">
                <a id="home-link" class="nav-link" href="{{ route('home') }}">{{ __('resources.title_webpage') }}</a>
                <div id="resource-links">
                    <a class="nav-link" href="{{ route('legends.index') }}">{{ __('resources.legend_all') }}</a> | 
                    <a class="nav-link" href="{{ route('collectors.index') }}">{{ __('resources.collector_all') }}</a> | 
                    <a class="nav-link" href="{{ route('narrators.index') }}">{{ __('resources.narrator_all') }}</a> | 
                    <a class="nav-link" href="{{ route('places.index') }}">{{ __('resources.place_all') }}</a> | 
                    <a class="nav-link" href="{{ route('sources.index') }}">{{ __('resources.source_all') }}</a>
                </div>
            </nav>
        </header>
        <section id="content">@yield('content')</section>
        <footer></footer>
    </div>
</body>
</html>