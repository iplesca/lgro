@extends(ISTEAM_TEMPLATE . '.layouts.main')

@section('content')
    Salut {{ Auth::user()->nickname }}.
@endsection
