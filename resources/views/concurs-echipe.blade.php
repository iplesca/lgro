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
                    @if (! $showScores)
                    <ul class="group-teams col-md-3">
                    @foreach($group as $teamId)
                        <li>{{ $data['teams'][$teamId]['id'] }}</li>
                    @endforeach
                    </ul>
                    @else
                    <table class="clasament">
                        <thead>
                            <th>&nbsp;</th>
                            <th>P</th>
                            <th>V</th>
                            <th>I</th>
                            <th>R</th>
                        </thead>
                        <tbody>
                        @foreach($scoresGroup[$gId] as $teamId => $teamData)
                            <tr>
                                <td>{{ $data['teams'][$teamId]['id'] }}</td>
                                <td>{{ $teamData['points'] }}</td>
                                <td>{{ $teamData['victory'] }}</td>
                                <td>{{ $teamData['defeat'] }}</td>
                                <td>{{ $teamData['draw'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                    <div class="games col-md-4">
                        Meciuri:<br>
                        <ol>
                        @foreach ($data['matches']['qualify'] as $mId => $pairs)
                            <li data-match-id="{{$gId}}:{{$mId}}">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>{{ $data['teams'][ $group[$data['matches']['pos'][$pairs[0]]] ]['id'] }}</td>
                                        <td>v.</td>
                                        <td>{{ $data['teams'][ $group[$data['matches']['pos'][$pairs[1]]] ]['id'] }}</td>
                                        <td>
                                            @can('isCE', \App\User::class)
                                                <a href="#" class="match_action btn-xs btn-primary">[Save]</a>
                                            @endcan
                                            @cannot('isCE', \App\User::class)
                                                &nbsp;
                                            @endcannot
                                        </td>
                                    </tr>
                                    @cannot('isCE', \App\User::class)
                                    @if (isset($scores[$gId.':'.$mId]))
                                    <tr>
                                        <td>
                                            {{ !empty($scores[$gId.':'.$mId]['home']['slotOne']) ?
                                             $scores[$gId.':'.$mId]['home']['slotOne'] :
                                             '&nbsp;'}}
                                        </td>
                                        <td>tur</td>
                                        <td>
                                            {{ !empty($scores[$gId.':'.$mId]['home']['slotTwo']) ?
                                                 $scores[$gId.':'.$mId]['home']['slotTwo'] :
                                                 '&nbsp;'}}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            {{ !empty($scores[$gId.':'.$mId]['away']['slotOne']) ?
                                                 $scores[$gId.':'.$mId]['away']['slotOne'] :
                                                 '&nbsp;'}}
                                        </td>
                                        <td>retur</td>
                                        <td>
                                            {{ !empty($scores[$gId.':'.$mId]['away']['slotTwo']) ?
                                                 $scores[$gId.':'.$mId]['away']['slotTwo'] :
                                                 '&nbsp;'}}
                                        </td>
                                        <td></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>tur</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>retur</td>
                                        <td>&nbsp;</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                    @endcannot
                                    @can('isCE', \App\User::class)
                                    <form id="f{{$gId}}:{{$mId}}" method="post" action="{{ route('concurs-save')  }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="mId" value="{{$gId}}:{{$mId}}">
                                    @if (isset($scores[$gId.':'.$mId]))
                                    <tr>
                                        <td>
                                            <input type="radio" name="winner{{ $gId.':'.$mId }}-home" value="slotOne">
                                            <input type="text" size="2" name="slotOne[home]" value="{{ $scores[$gId.':'.$mId]['home']['slotOne'] }}">
                                        </td>
                                        <td>tur</td>
                                        <td>
                                            <input type="text" size="2" name="slotTwo[home]" value="{{ $scores[$gId.':'.$mId]['home']['slotTwo'] }}">
                                            <input type="radio" name="winner{{ $gId.':'.$mId }}-home" value="slotTwo">
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" name="winner{{ $gId.':'.$mId }}-away" value="slotOne">
                                            <input type="text" size="2" name="slotOne[away]" value="{{ $scores[$gId.':'.$mId]['away']['slotOne'] }}">
                                        </td>
                                        <td>retur</td>
                                        <td>
                                            <input type="text" size="2" name="slotTwo[away]" value="{{ $scores[$gId.':'.$mId]['away']['slotTwo'] }}">
                                            <input type="radio" name="winner{{ $gId.':'.$mId }}-away" value="slotTwo">
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>
                                            <input type="text" size="2" name="slotOne[home]" value="">
                                        </td>
                                        <td>tur</td>
                                        <td>
                                            <input type="text" size="2" name="slotTwo[home]" value="">
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" size="2" name="slotOne[away]" value="">
                                        </td>
                                        <td>retur</td>
                                        <td>
                                            <input type="text" size="2" name="slotTwo[away]" value="">
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @endif
                                    </form>
                                    @endcan
                                </table>
                                <span class="clear">&nbsp;</span>
                            </li>
                        @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <span style="clear: both; display: inline-block; width:100%; height:200px;">
    <script>
        $(document).ready(function () {
            $('.match_action').on('click.isteam', function () {
                var parent = $(this).parents('li');
                var form = $('form', parent);
                form.submit();
            });
        });
    </script>
@endsection
