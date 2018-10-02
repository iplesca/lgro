<li class="nav-item dropdown {{ in_array(Route::currentRouteName(), ['officerRecruitment', 'officerCombat', 'officerClanWars', 'officerCommand']) ? 'active' : ''}}">
    <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="true">
        Ofiter
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        @can('access-recruitment')
            <a class="dropdown-item" href="{{ route('officerRecruitment') }}">Recrutare</a>
        @endcan
        @can('access-strategy')
            <a class="dropdown-item" href="{{ route('officerCombat') }}">Strategie</a>
        @endcan
        @can('access-clanwars')
            <a class="dropdown-item" href="{{ route('officerClanWars') }}">Clan Wars</a>
        @endcan
        @can('access-command')
            <a class="dropdown-item" href="{{ route('officerCommand') }}">Competitii</a>
        @endcan
    </div>
</li>
