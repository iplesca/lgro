@extends('layouts.main')

@section('content')
    <h1>{{ $data->nickname }}</h1>
    <small>ID: {{ $data->wargaming_id }}</small><br>
    <br>
    <table style="width:70%">
        <tr>
            <td>
                <div>
                    <strong>Tanchist din data de:</strong> {{ $data->wot_created_at }}<br>
                    <strong>Cont premium:</strong> {{ $data->wot_premium ? 'DA' : 'NU'  }}
                    @if ($data->wot_premium)
                        - <em>până pe {{ $data->wot_premium_expire }}</em>
                        <br>
                    @endif
                    <strong>Experiență free:</strong> {{ number_format($data->wot_free_xp, 0, ',', '.') }}<br>
                </div>
            </td>
            <td>
                <div>
                    <strong>Credite:</strong> {{ number_format($data->wot_credits, 0, ',', '.') }}<br>
                    <strong>Gold:</strong> {{ number_format($data->wot_gold, 0, ',', '.') }}<br>
                    <strong>Bond-uri:</strong> {{ number_format($data->wot_bonds, 0, ',', '.') }}<br>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <h2>Statistici</h2>
    <div class="stats">
        <input type="radio" name="stats" value="all" id="stats_all" checked><label for="stats_all">Globale</label>
        <input type="radio" name="stats" value="deta" id="stats_stronghold_skirmish"><label for="stats_stronghold_skirmish">Detașamente</label>
        <input type="radio" name="stats" value="clan" id="stats_clan"><label for="stats_clan">Clan</label>
        <input type="radio" name="stats" value="team" id="stats_team"><label for="stats_team">Team battle</label>
    </div>
    @foreach($stats as $group)
        <div class="tabel_stats" id="group_{{ $group['_type'] }}">
            <table style="width: 80%">
                <tr>
                    <td>
                        <h3>General</h3>
                        <strong>Kill-uri:</strong> {{ number_format($group['frags'], 0, ',', '.') }}<br>
                        <strong>Experiență:</strong> {{ number_format($group['xp'], 0, ',', '.') }}<br>
                        <strong>Experiență medie pe luptă:</strong> {{ number_format($group['battle_avg_xp'], 0, ',', '.') }}<br>
                        <strong>Tancuri spotate:</strong> {{ number_format($group['spotted'], 0, ',', '.') }}<br>
                        <strong>Eficiență armură:</strong> {{ $group['tanking_factor'] }}<br>

                        <h3>Lupte</h3>
                        <strong>Total:</strong> {{ number_format($group['battles'], 0, ',', '.') }}<br>
                        <strong>Câștigate:</strong> {{ number_format($group['wins'], 0, ',', '.') }}<br>
                        <strong>Remize:</strong> {{ number_format($group['draws'], 0, ',', '.') }}<br>
                        <strong>Pierdute:</strong> {{ number_format($group['losses'], 0, ',', '.') }}<br>
                        <strong>Supraviețuitor:</strong> {{ number_format($group['survived_battles'], 0, ',', '.') }}<br>
                    </td>
                    <td>
                        <h3>Damage</h3>
                        <strong>Total:</strong> {{ number_format($group['damage_dealt'], 0, ',', '.') }}<br>
                        <strong>Primit:</strong> {{ number_format($group['damage_received'], 0, ',', '.') }}<br>
                        @if (isset($group['avg_damage_blocked']))
                        <strong>Medie blocat:</strong> {{ number_format($group['avg_damage_blocked'], 0, ',', '.') }}<br>
                        @endif

                        @if (isset($group['avg_damage_assisted']))
                        <h3>Damage assist</h3>
                        <strong>Medie:</strong> {{ $group['avg_damage_assisted'] }}<br>
                        <strong>Medie spot:</strong> {{ number_format($group['avg_damage_assisted_radio'], 0, ',', '.') }}<br>
                        <strong>Medie șenile blocate:</strong> {{ number_format($group['avg_damage_assisted_track'], 0, ',', '.') }} <br>
                        @endif

                        <h3>Bază</h3>
                        <strong>Puncte captură:</strong> {{ number_format($group['capture_points'], 0, ',', '.') }}<br>
                        <strong>Puncte reset:</strong> {{ number_format($group['dropped_capture_points'], 0, ',', '.')}}<br>
                    </td>
                    <td>
                        <h3>Lovituri</h3>
                        <h5>primite</h5>
                        <strong>Total:</strong> {{ number_format($group['direct_hits_received'], 0, ',', '.') }}<br>
                        <strong>Cu penetrare:</strong> {{ number_format($group['piercings_received'], 0, ',', '.') }}<br>

                        <h5>trase</h5>
                        <strong>Total:</strong> {{ number_format($group['shots'], 0, ',', '.') }}<br>
                        <strong>Atinse:</strong> {{ number_format($group['hits'], 0, ',', '.') }}<br>
                        <strong>Cu penetrare:</strong> {{ number_format($group['piercings'], 0, ',', '.') }}<br>

                        <h3>Artilerie</h3>
                        <strong>Tancuri în șoc:</strong> {{ number_format($group['stun_number'], 0, ',', '.') }}<br>
                        <strong>Damage assist:</strong> {{ number_format($group['stun_assisted_damage'], 0, ',', '.') }}<br>
                    </td>
                </tr>
            </table>
            {{--[battles_on_stunning_vehicles] => 0--}}
            {{--[explosion_hits] => 0--}}
            {{--[avg_damage_assisted_radio] => 0--}}
            {{--[stun_assisted_damage] => 0--}}
            {{--[no_damage_direct_hits_received] => 0--}}
            {{--[explosion_hits_received] => 0--}}
        </div>
    @endforeach
    <br><br><br><br><br><br><br><br><br><br><br>
    <script>
        jQuery(document).ready(function () {
            $('input[name=stats]').on('click.isteam', function (e) {
                $('.tabel_stats').hide();
                $('#group_' + $(this).val()).show();
            });
            $('#group_all').show();
        });
    </script>
@endsection
