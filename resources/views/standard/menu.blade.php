<div class="menu">
    <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ "homepage" == Route::currentRouteName() ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('homepage') }}">Info</a>
                </li>
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ $wotLogin }}{{ Session::get('wgAuth') }}/">Autentificare</a>
                </li>
                @endguest
                @auth
                    <li class="nav-item {{ "clanMembers" == Route::currentRouteName() ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('clanMembers') }}">Membri</a>
                    </li>
                    <li class="nav-item dropdown {{ in_array(Route::currentRouteName(), ['concurs', 'concurs-echipe', 'concurs-rezultate']) ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Concurs
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('concurs') }}">Regulament & premii</a>
                            <a class="dropdown-item" href="{{ route('concurs-echipe') }}">Echipe</a>
                            {{--<div class="dropdown-divider"></div>--}}
                            <a class="dropdown-item" href="{{ route('concurs-rezultate') }}">Rezultate</a>
                        </div>
                    </li>

                    <li class="nav-item {{ "profile" == Route::currentRouteName() ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('profile') }}">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Deconectare</a>
                    </li>

                @endauth
                {{--<li class="nav-item dropdown">--}}
                    {{--<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"--}}
                       {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--Dropdown--}}
                    {{--</a>--}}
                    {{--<div class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
                        {{--<a class="dropdown-item" href="#">Action</a>--}}
                        {{--<a class="dropdown-item" href="#">Another action</a>--}}
                        {{--<div class="dropdown-divider"></div>--}}
                        {{--<a class="dropdown-item" href="#">Something else here</a>--}}
                    {{--</div>--}}
                {{--</li>--}}
                {{--<li class="nav-item">--}}
                    {{--<a class="nav-link disabled" href="#">Disabled</a>--}}
                {{--</li>--}}
            </ul>
            @auth
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a target="_blank" href="https://worldoftanks.eu/en/community/accounts/{{ Auth::user()->wargaming_id }}-{{ Auth::user()->nickname }}">WoT :: {{ Auth::user()->nickname }}</a>
                    </li>
                </ul>
            @endauth
            {{--<form class="form-inline my-3 my-lg-0">--}}
            {{--<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">--}}
            {{--<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
            {{--</form>--}}
        </div>
    </nav>
</div>