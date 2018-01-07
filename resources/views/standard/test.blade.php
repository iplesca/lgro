@extends(ISTEAM_TEMPLATE . '.layouts.main')

@section('content')
    @if (isset($existingMembers))
        existing members<br>
        <pre>{{ $existingMembers }}</pre>
    @endif
    @if (isset($members))
        members<br>
        <pre>{{ $members }}</pre>
    @endif
@endsection
