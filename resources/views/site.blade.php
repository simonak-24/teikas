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
                <div id="main-links">
                    <a id="home-link" class="nav-link" href="{{ route('home') }}">{{ __('resources.title_webpage') }}</a>
                    <span>&nbsp;|&nbsp;</span>
                    <a id="contents-link" class="nav-link" href="{{ route('navigation.contents') }}">{{ __('resources.title_contents') }}</a>
                </div>
                <div id="resource-links">
                    @if(Auth::check())
                    <a class="nav-link" href="{{ route('user.index') }}">{{ __('resources.user_all') }}</a>
                    <form id="logout-link" action="{{ route('logout') }}" method="POST">
                        @csrf
                        @method('POST')
                        <button class="user-button" type="submit">Log Out</button>
                    </form>
                    @else
                        <form id="login-link" action="{{ route('login') }}" method="GET">
                        @csrf
                        <button class="user-button" type="submit">Log In</button>
                    </form>
                    @endif
                    <a class="nav-link" href="{{ route('legends.index') }}">{{ __('resources.legend_all') }}</a>&nbsp;|&nbsp;
                    <a class="nav-link" href="{{ route('collectors.index') }}">{{ __('resources.collector_all') }}</a> &nbsp;|&nbsp;
                    <a class="nav-link" href="{{ route('narrators.index') }}">{{ __('resources.narrator_all') }}</a> &nbsp;|&nbsp;
                    <a class="nav-link" href="{{ route('places.index') }}">{{ __('resources.place_all') }}</a>&nbsp;|&nbsp;
                    <a class="nav-link" href="{{ route('sources.index') }}">{{ __('resources.source_all') }}</a>
                </div>
            </nav>
        </header>
        <section id="content">@yield('content')</section>
        <footer></footer>
    </div>
    <div id="popup">
        @yield('popup')
    </div>
</body>
</html>