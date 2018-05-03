<div class="menu">
    <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                @guest
                <li class="nav-item {{ "homepage" == Route::currentRouteName() ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('homepage') }}">Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ $wotLogin }}{{ Session::get('wgAuth') }}/">Autentificare</a>
                </li>
                @endguest
                @auth
                    {{-- CLAN TAG --}}
                    <li class="nav-item dropdown {{ in_array(Route::currentRouteName(), ['homepage', 'profile', 'clanMembers']) ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="{{ route('homepage') }}" role="button"
                           aria-haspopup="true" aria-expanded="true">
                            {{ $clanData->tag }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('clanMembers') }}">Membri</a>
                            <a class="dropdown-item" href="{{ route('profile', ['memberId' => Auth::user()->member_id]) }}">Bine ai venit</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('profile', ['memberId' => Auth::user()->member_id]) }}">Regulament</a>
                        </div>
                    </li>

                    {{-- DASHBOARD --}}
                    <li class="nav-item dropdown {{ in_array(Route::currentRouteName(), ['profile', 'clanDashboard']) ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="{{ route('clanDashboard') }}" role="button" aria-haspopup="true" aria-expanded="true">
                            Dashboard
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile', ['memberId' => Auth::user()->member_id]) }}">Statistici</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown {{ in_array(Route::currentRouteName(), ['profile', 'profileGarage', 'profileMessages']) ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="{{ route('profile', ['memberId' => Auth::user()->member_id]) }}" role="button" aria-haspopup="true" aria-expanded="true">
                            Dosar personal
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profileGarage', ['memberId' => Auth::user()->member_id]) }}">Garaj</a>
                            <a class="dropdown-item" href="{{ route('profileMessages', ['memberId' => Auth::user()->member_id]) }}">Mesaje</a>
                        </div>
                    </li>
                    @if (Bouncer::is(\Illuminate\Support\Facades\Auth::user())->an('officer'))
                        @include ('piroman.officer-menu')
                    @endif

                    {{--<li class="nav-item {{ "profile" == Route::currentRouteName() ? 'active' : ''}}">--}}
                        {{--<a class="nav-link" href="{{ route('profile') }}">Recrutare</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item {{ "profile" == Route::currentRouteName() ? 'active' : ''}}">--}}
                        {{--<a class="nav-link" href="{{ route('profile') }}">Strategie</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item {{ "profile" == Route::currentRouteName() ? 'active' : ''}}">--}}
                        {{--<a class="nav-link" href="{{ route('profile') }}">Comandant</a>--}}
                    {{--</li>--}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Deconectare</a>
                    </li>
                @endauth
            </ul>
            @auth
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a target="_blank" href="https://worldoftanks.eu/en/community/accounts/{{ Auth::user()->wargaming_id }}-{{ Auth::user()->nickname }}">WoT :: {{ Auth::user()->nickname }}</a>
                    </li>
                </ul>
            @endauth
        </div>
    </nav>
</div>