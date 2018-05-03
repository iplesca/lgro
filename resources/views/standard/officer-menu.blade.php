<li class="nav-item dropdown {{ in_array(Route::currentRouteName(), ['profile', 'profileGarage', 'profileMessages']) ? 'active' : ''}}">
    <a class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="true">
        Ofiter
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        @can('access-recruitment')
            <a class="dropdown-item" href="{{ route('profileGarage', ['memberId' => Auth::user()->member_id]) }}">Recrutare</a>
        @endcan
        @can('access-strategy')
            <a class="dropdown-item" href="{{ route('profileGarage', ['memberId' => Auth::user()->member_id]) }}">Strategie</a>
        @endcan
        @can('access-command')
            <a class="dropdown-item" href="{{ route('profileGarage', ['memberId' => Auth::user()->member_id]) }}">Competitii</a>
        @endcan
    </div>
</li>
