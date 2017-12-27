@extends('layouts.main')

@section('content')
    <h2>Echipe participante</h2>
    <div class="concurs">
    @foreach($data['teams'] as $team)
        <div class="team">
            <span class="team-id clanColor">[{{ $team['id']  }}]</span><strong>{{ $team['name']  }}</strong>
            <ul>
                @foreach($team['players'] as $player)
                    <li>{{ $player }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach
    </div>
    <h2>Grupe</h2>
    <div class="concurs">
        @foreach ($data['groups'] as $gId => $group)
            <span><strong>{{ str_replace('g', 'Grupa ', $gId)  }}</strong></span>
            <div class="group">
                <div class="row">
                    <ul class="group-teams col-md-3">
                    @foreach($group as $teamId)
                        <li>{{ $data['teams'][$teamId]['id'] }}</li>
                    @endforeach
                    </ul>
                    <div class="games col-md-4">
                        Meciuri:<br>
                        <ol>
                        @foreach ($data['matches']['qualify'] as $pairs)
                            <li><span>{{ $data['teams'][ $group[$data['matches']['pos'][$pairs[0]]] ]['id'] }}</span> v.
                                <span>{{ $data['teams'][ $group[$data['matches']['pos'][$pairs[1]]] ]['id'] }}</span></li>
                        @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <span style="clear: both; display: inline-block; width:100%; height:200px;">
@endsection
