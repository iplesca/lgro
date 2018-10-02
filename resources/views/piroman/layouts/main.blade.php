<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $clanData->tag }} {{ $clanData->name }}</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="{{ asset('css/piroman.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

</head>
<body>
    <div id="main" class="container">
        @if (false != $clanData)
            <div class="header">
                <img src="{{ asset('/images/focd-header.jpg') }}">
                <div class="logo fly">
                    <a href="#" title="[{{ $clanData->tag }}] {{ $clanData->name }}">
                        <span style="color:{{ $clanData->color }}" class="clanShadow">[{{ $clanData->tag }}]</span> {{ $clanData->name }}
                    </a>
                </div>
                {{--<div class="logo">--}}

                    {{--<a href="#" title="[{{ $clanData->tag }}] {{ $clanData->name }}">--}}
                        {{--<img src="{{ $clanData->emblem195  }}">--}}
                        {{--<span style="color:{{ $clanData->color }}" class="clanShadow">[{{ $clanData->tag }}]</span> {{ $clanData->name }}--}}
                    {{--</a>--}}
                {{--</div>--}}
            </div>
        @else
            <div class="header">
                <div class="logo">
                    <a href="#" title="IsTeam">
                        <img src="{{ asset('/images/WorldOfTanks-logo.png') }}">
                        IsTeam HQ
                    </a>
                </div>
                {{--<div class="header_bg">--}}
                {{--<a href="https://eu.wargaming.net/clans/wot/500033466" title="Pagina WoT">--}}
                {{--<img src="/images/WorldOfTanks-logo.png">--}}
                {{--</a>--}}
                {{--</div>--}}
            </div>
        @endif
        @include(ISTEAM_TEMPLATE . '.menu')
        <div class="content">
            @yield('content')
        </div>
    </div>
    @include('standard.alerts')
</body>
</html>
