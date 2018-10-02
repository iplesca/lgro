@extends(ISTEAM_TEMPLATE . '.layouts.main')

@section('content')
    <h1>Top 15</h1>
    <fieldset id="heavy">
        <legend for="heavy">Heavy</legend>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Ieri</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ht1 as $entry)
                <tr>
                    <td><a href="{{ route('profile', ['memberId' => $entry->id]) }}">{{$entry->nickname}}</a></td>
                    <td>{{$entry->avgWn8}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </fieldset>
@endsection
