<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Metadata -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') | {{ __('site.title_webpage') }}</title>
    <!-- Stylesheets -->
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    @yield('stylesheets')
    <!-- Scripts -->
    <script>
        function closeError() {
            document.getElementById("error-message").style.display = "none";
        }
    </script>
    @yield('scripts')
</head>

<body>
    <div id="site-container">
        <header>
            <nav id="nav-bar">
                <div id="main-links">
                    <a id="home-link" class="nav-link" href="{{ route('home') }}">{{ __('site.title_webpage') }}</a>
                    <span class="divider">&nbsp;|&nbsp;</span>
                    <a id="contents-link" class="nav-link" href="{{ route('navigation.contents') }}">{{ __('site.title_contents') }}</a>
                </div>
                <div id="resource-links">
                    @if(Auth::check())
                    <a class="nav-link" href="{{ route('user.index') }}">{{ __('resources.user_all') }}</a>
                    <form id="logout-link" action="{{ route('logout') }}" method="POST">
                        @csrf
                        @method('POST')
                        <button class="user-button" type="submit">{{ __('site.button_logout') }}</button>
                    </form>
                    @else
                        <form id="login-link" action="{{ route('login') }}" method="GET">
                        @csrf
                        <button class="user-button" type="submit">{{ __('site.button_login') }}</button>
                    </form>
                    @endif
                    <a class="nav-link" href="{{ route('legends.index') }}">{{ __('resources.legend_all') }}</a><span class="divider">&nbsp;|&nbsp;</span>
                    <a class="nav-link" href="{{ route('collectors.index') }}">{{ __('resources.collector_all') }}</a><span class="divider">&nbsp;|&nbsp;</span>
                    <a class="nav-link" href="{{ route('narrators.index') }}">{{ __('resources.narrator_all') }}</a><span class="divider">&nbsp;|&nbsp;</span>
                    <a class="nav-link" href="{{ route('places.index') }}">{{ __('resources.place_all') }}</a><span class="divider">&nbsp;|&nbsp;</span>
                    <a class="nav-link" href="{{ route('sources.index') }}">{{ __('resources.source_all') }}</a><span class="divider">&nbsp;&nbsp;||</span>
                    <div id="lan-dropdown">
                        &nbsp;&nbsp;<span id="dropdown-link" class="nav-link" href="">{{ Request::segment(1) }}</span>&nbsp;&nbsp;
                        <div id="lan-content">
                            @if (Request::segment(1) == 'lv')
                            <a class="lan-link" href="{{ route('language.set', 'en') }}">EN</a>
                            @else
                            <a class="lan-link" href="{{ route('language.set', 'lv') }}">LV</a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <section id="content">@yield('content')</section>
        <footer></footer>
    </div>
    <div id="popup">
        @yield('popup')
    </div>
    @if(Session::has('not-found'))
    <div id="error-message">
        <a class="popup-link" onclick="closeError()">X</a>
        <div>{{ Session::get('not-found') }}<div>
    </div>
    @endif
</body>
</html>